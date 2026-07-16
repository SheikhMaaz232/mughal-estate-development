<?php

namespace App\Services;

use App\Models\Party;
use App\Models\Product;
use App\Models\Project;
use App\Models\StockLedger;
use App\Models\AccountLedger;
use App\Models\BookingReturn;
use App\Models\DetailAccount;
use App\Models\GeneralJournal;
use Illuminate\Support\Facades\App;
use App\Models\BookingNomineeDetail;
use App\Models\BookingPaymentShedule;

class BookingReturnApplicationService
{
    public function getAll($perPage = 10)
    {
        return BookingReturn::with('mainHead', 'controlHead', 'subHead', 'subSubHead')->latest()->paginate($perPage);
    }

    public function getById($id)
    {
        return BookingReturn::findOrFail($id);
    }

    public function store($data)
    {
        return BookingReturn::create($data);
    }

    public function update($id, array $data)
    {
        $bookingReturn = BookingReturn::findOrFail($id);
        $bookingReturn->update($data);
        return $bookingReturn;
    }

    public function createLedgerEntry($bookingReturn): void
    {

        $booking = $bookingReturn->bookingApplication;

        $date = now()->toDateString();
        $documentNo = 'B-R-' . $bookingReturn->id;

        $customerAccount = $booking->detail_account_id;
        $partyId = $booking->party_id;
        $projectId = $booking->project_id;

        $productNameEN = $booking->product->name_en ?? '';
        $productNameUR = $booking->product->name_ur ?? '';

        $projectNameEN = $booking->project->name_en ?? '';
        $projectNameUR = $booking->project->name_ur ?? '';

        $partyNameEN = $booking->party->name_en ?? '';
        $partyNameUR = $booking->party->name_ur ?? '';
        $product = DetailAccount::where('project_id', $bookingReturn->bookingApplication->project_id)->where('name_en', $productNameEN)->value('id');
        $totalAmount = $booking->total_amount;

        $totalCredit = AccountLedger::where('detail_account_id', $bookingReturn->bookingApplication->detail_account_id)
            ->sum('credit');

        $totalDebit = AccountLedger::where('detail_account_id', $bookingReturn->bookingApplication->detail_account_id)
            ->sum('debit');

        $cancellationCharges =
            ($totalAmount * $bookingReturn->percentage_value) / 100;
        $discountValue = AccountLedger::where('detail_account_id', $bookingReturn->bookingApplication->detail_account_id)->where('document_number', 'B-A' . '-' . $bookingReturn->bookingApplication->id)->where('transaction_type', 'feeses_discount')->sum('credit');


        /*
    |--------------------------------------------------------------------------
    | STEP 1: Reverse Original Sale
    |--------------------------------------------------------------------------
    */

        // Debit Product Account
        AccountLedger::create([
            'date' => $date,
            'project_id' => $projectId,
            'invoice_id' => $bookingReturn->id,
            'party_id' => null,
            'detail_account_id' => $product,
            'document_number' => $documentNo,
            'description_en' => "Booking Cancellation - {$productNameEN} from {$partyNameEN}",
            'description_ur' => "{$partyNameUR} سے {$productNameUR} کی بکنگ کینسلیشن",
            'debit' => $totalAmount,
            'credit' => 0,
        ]);

        // Credit Customer
        AccountLedger::create([
            'date' => $date,
            'project_id' => $projectId,
            'invoice_id' => $bookingReturn->id,
            'party_id' => $partyId,
            'detail_account_id' => $customerAccount,
            'document_number' => $documentNo,
            'description_en' => "Sale reversal of {$productNameEN} of {$partyNameEN}",
            'description_ur' => "{$partyNameUR}  کے {$productNameEN} کی واپسی",
            'debit' => 0,
            'credit' => $totalAmount,
        ]);

        /*
    |--------------------------------------------------------------------------
    | STEP 2: Cancellation Charges
    |--------------------------------------------------------------------------
    */

        if ($cancellationCharges > 0) {

            // Debit Customer
            AccountLedger::create([
                'date' => $date,
                'project_id' => $projectId,
                'invoice_id' => $bookingReturn->id,
                'party_id' => $partyId,
                'detail_account_id' => $customerAccount,
                'document_number' => $documentNo,
                'description_en' => "Cancellation charges for {$partyNameEN}",
                'description_ur' => "{$partyNameUR} پر کینسلیشن چارجز",
                'debit' => $cancellationCharges,
                'credit' => 0,
                'is_fee_entry' => 0,
                'transaction_type' => 'cancellation_charges',
            ]);

            // Credit Cancellation Income
            AccountLedger::create([
                'date' => $date,
                'project_id' => $projectId,
                'invoice_id' => $bookingReturn->id,
                'party_id' => null,
                'detail_account_id' => $bookingReturn->cancellation_charges_account_id,
                'document_number' => $documentNo,
                'description_en' => "Cancellation income of {$productNameEN} From {$partyNameEN} ",
                'description_ur' => "{$partyNameUR} سے {$productNameUR} کی کینسلیشن کی آمدنی",
                'debit' => 0,
                'credit' => $cancellationCharges,
                'is_fee_entry' => 0,
                'transaction_type' => null,
            ]);

            // Credit Cancellation Income
            AccountLedger::create([
                'date' => $date,
                'project_id' => $projectId,
                'invoice_id' => $bookingReturn->id,
                'party_id' => null,
                'detail_account_id' => $bookingReturn->cash_bank_account,
                'document_number' => $documentNo,
                'description_en' => "Pay Cancellation Charges of {$productNameEN} For {$partyNameEN} ",
                'description_ur' => "{$partyNameUR} کے لیے {$productNameUR} کی کینسلیشن چاجز کی ادئیگی",
                'debit' => 0,
                'credit' => $cancellationCharges,
                'is_fee_entry' => 0,
                'transaction_type' => null,
            ]);
        }

        /*
    |--------------------------------------------------------------------------
    | STEP 3: Paid Amount
    |--------------------------------------------------------------------------
    */

        $balanceAmount = $totalCredit - $discountValue;
        $difference = $balanceAmount - $cancellationCharges;

        /*
    |--------------------------------------------------------------------------
    | STEP 4: Close Customer Account
    |--------------------------------------------------------------------------
    */

        if ($difference > 0) {

            // Debit Customer
            AccountLedger::create([
                'date' => $date,
                'project_id' => $projectId,
                'invoice_id' => $bookingReturn->id,
                'party_id' => $partyId,
                'detail_account_id' => $customerAccount,
                'document_number' => $documentNo,
                'description_en' => "Payable Amount of {$partyNameEN} of {$productNameUR} shift to liability Account.",
                'description_ur' => "{$partyNameUR} کی {$productNameUR} کی قابلِ ادائیگی رقم واجبات اکاؤنٹ میں منتقل کر دی گئی۔",
                'debit' => $difference,
                'credit' => 0,
            ]);

            // Credit Liability
            AccountLedger::create([
                'date' => $date,
                'project_id' => $projectId,
                'invoice_id' => $bookingReturn->id,
                'party_id' => null,
                'detail_account_id' => $bookingReturn->detail_account_id,
                'document_number' => $documentNo,
                'description_en' => $bookingReturn->bookingApplication->detailAccount->name_en,
                'description_ur' => $bookingReturn->bookingApplication->detailAccount->name_ur,
                'debit' => 0,
                'credit' => $difference,
            ]);
        } elseif ($difference < 0) {

            $receivableAmount = abs($difference);

            // Credit Customer
            AccountLedger::create([
                'date' => $date,
                'project_id' => $projectId,
                'invoice_id' => $bookingReturn->id,
                'party_id' => $partyId,
                'detail_account_id' => $customerAccount,
                'document_number' => $documentNo,
                'description_en' => "Receivable from {$partyNameEN}",
                'description_ur' => "{$partyNameUR} سے وصولی",
                'debit' => 0,
                'credit' => $receivableAmount,
            ]);

            // Debit Receivable Account
            AccountLedger::create([
                'date' => $date,
                'project_id' => $projectId,
                'invoice_id' => $bookingReturn->id,
                'party_id' => null,
                'detail_account_id' => $bookingReturn->receivable_detail_account_id,
                'document_number' => $documentNo,
                'description_en' => "Receivable Cancellation Charges From {$partyNameEN} in term of {$productNameEN}  ",
                'description_ur' => "{$partyNameUR} سے {$productNameUR} کی کینسلیشن چاجز کی وصولی",
                'debit' => $receivableAmount,
                'credit' => 0,
            ]);
        }
    }


    // public function createLedgerEntry(BookingReturn $booking): void
    // {
    //     $dealerMainParty = DetailAccount::where('id', $booking->dealer_id)->value('party_id');
    //     AccountLedger::create([
    //         'project_id'       => $booking->project_id,
    //         'party_id'         => $dealerMainParty,
    //         'detail_account_id' => $booking->dealer_id,
    //         'credit'           => $booking->commission,
    //         'debit'           => '0',
    //         'narration'        => "Commission Entry",
    //         'date'             => $booking->date,
    //     ]);

    //     AccountLedger::create([
    //         'project_id'       => $booking->project_id,
    //         'party_id'         => $dealerMainParty,
    //         'detail_account_id' => $booking->dealer_id,
    //         'credit'           => $booking->commission,
    //         'debit'           => '0',
    //         'narration'        => "Commission Entry",
    //         'date'             => $booking->date,
    //     ]);
    // }

    // public function createLedgerEntry($bookingReturn, $cancellationsCharges): void
    // {
    //     $productNameEN = Product::where('id', $bookingReturn->bookingApplication->product_id)->value('name_en');
    //     $productNameUR = Product::where('id', $bookingReturn->bookingApplication->product_id)->value('name_ur');
    //     $projectNameEN = Project::where('id', $bookingReturn->bookingApplication->project_id)->value('name_en');
    //     $projectNameUR = Project::where('id', $bookingReturn->bookingApplication->project_id)->value('name_ur');
    //     $partyNameEN = Party::where('id', $bookingReturn->bookingApplication->party_id)->value('name_en');
    //     $partyNameUR = Party::where('id', $bookingReturn->bookingApplication->party_id)->value('name_ur');
    //     $product = DetailAccount::where('project_id', $bookingReturn->bookingApplication->project_id)->where('name_en', $productNameEN)->value('id');

    //     $debitData = [
    //         'date' => now()->toDateString(),
    //         'project_id' => $bookingReturn->bookingApplication->project_id,
    //         'invoice_id' => $bookingReturn->id,
    //         'party_id' => null,
    //         'detail_account_id' => $product,
    //         'description_en' => "Sale Return of {$productNameEN} in {$projectNameEN} by {$partyNameEN}",
    //         'description_ur' => "{$partyNameUR} کی جانب سے {$projectNameUR} کے {$productNameUR} کی ریٹرن",
    //         'document_number' => 'B-R' . '-' . $bookingReturn->id,
    //         'debit' => $bookingReturn->bookingApplication->total_amount,
    //         'credit' => 0,
    //     ];

    //     if (!empty($debitData)) {
    //         AccountLedger::create($debitData);
    //     }

    //     $generalJournalDebitData = [
    //         'date' => now()->toDateString(),
    //         'project_id' => $bookingReturn->bookingApplication->project_id,
    //         'invoice_id' => $bookingReturn->id,
    //         'party_id' => null,
    //         'detail_account_id' => $product,
    //         'description_en' => "Sale of {$productNameEN} in {$projectNameEN} to {$partyNameEN}",
    //         'description_ur' => "{$partyNameUR} کو {$projectNameUR} کے {$productNameUR} کی فروخت",
    //         'document_number' => 'B-R' . '-' . $bookingReturn->id,
    //         'debit' => $bookingReturn->bookingApplication->total_amount,
    //         'credit' => 0,
    //     ];
    //     if (!empty($generalJournalDebitData)) {
    //         GeneralJournal::create($generalJournalDebitData);
    //     }

    //     $creditData = [
    //         'date' => now()->toDateString(),
    //         'project_id' => $bookingReturn->bookingApplication->project_id,
    //         'invoice_id' => $bookingReturn->id,
    //         'party_id' => $bookingReturn->bookingApplication->party_id,
    //         'detail_account_id' => $bookingReturn->bookingApplication->detail_account_id,
    //         'description_en' => "Sale Return of {$productNameEN} in {$projectNameEN} by {$partyNameEN}",
    //         'description_ur' => "{$partyNameUR} کی جانب سے {$projectNameUR} کے {$productNameUR} کی ریٹرن",
    //         'document_number' => 'B-R' . '-' . $bookingReturn->id,
    //         'debit' => 0,
    //         'credit' => $bookingReturn->bookingApplication->total_amount,
    //     ];

    //     if (!empty($creditData)) {
    //         AccountLedger::create($creditData);
    //     }

    //     $generalJournalCreditData = [
    //         'date' => now()->toDateString(),
    //         'project_id' => $bookingReturn->bookingApplication->project_id,
    //         'invoice_id' => $bookingReturn->id,
    //         'party_id' => $bookingReturn->bookingApplication->party_id,
    //         'detail_account_id' => $bookingReturn->bookingApplication->detail_account_id,
    //         'description_en' => "Sale of {$productNameEN} in {$projectNameEN} to {$partyNameEN}",
    //         'description_ur' => "{$partyNameUR} کو {$projectNameUR} کے {$productNameUR} کی فروخت",
    //         'document_number' => 'B-R' . '-' . $bookingReturn->id,
    //         'debit' => 0,
    //         'credit' => $bookingReturn->bookingApplication->total_amount,
    //     ];
    //     if (!empty($generalJournalCreditData)) {
    //         GeneralJournal::create($generalJournalCreditData);
    //     }


    //     $cancellationChargesDebitData = [
    //         'date' => now()->toDateString(),
    //         'project_id' => $bookingReturn->bookingApplication->project_id,
    //         'invoice_id' => $bookingReturn->id,
    //         'party_id' => $bookingReturn->bookingApplication->party_id,
    //         'detail_account_id' => $bookingReturn->bookingApplication->detail_account_id,
    //         'description_en' => "Cancellation charges for {$productNameEN} in {$projectNameEN} ({$partyNameEN})",
    //         'description_ur' => "{$partyNameUR} کی جانب سے {$projectNameUR} کے {$productNameUR} پر کینسلیشن چارجز",
    //         'document_number' => 'B-R' . '-' . $bookingReturn->id,
    //         'debit' => $cancellationsCharges,
    //         'credit' => 0,
    //     ];

    //     if (!empty($cancellationChargesDebitData)) {
    //         AccountLedger::create($cancellationChargesDebitData);
    //     }

    //     $cancellationChargesGeneralJournalDebitData = [
    //         'date' => now()->toDateString(),
    //         'project_id' => $bookingReturn->bookingApplication->project_id,
    //         'invoice_id' => $bookingReturn->id,
    //         'party_id' => $bookingReturn->bookingApplication->party_id,
    //         'detail_account_id' => $bookingReturn->bookingApplication->detail_account_id,
    //         'description_en' => "Cancellation charges for {$productNameEN} in {$projectNameEN} ({$partyNameEN})",
    //         'description_ur' => "{$partyNameUR} کی جانب سے {$projectNameUR} کے {$productNameUR} پر کینسلیشن چارجز",
    //         'document_number' => 'B-R' . '-' . $bookingReturn->id,
    //         'debit' => $cancellationsCharges,
    //         'credit' => 0,
    //     ];

    //     if (!empty($cancellationChargesGeneralJournalDebitData)) {
    //         GeneralJournal::create($cancellationChargesGeneralJournalDebitData);
    //     }

    //     $cancellationChargesCreditData = [
    //         'date' => now()->toDateString(),
    //         'project_id' => $bookingReturn->bookingApplication->project_id,
    //         'invoice_id' => $bookingReturn->id,
    //         'party_id' => null,
    //         'detail_account_id' => $bookingReturn->cash_bank_account,
    //         'description_en' => "Cancellation charges for {$productNameEN} in {$projectNameEN} ({$partyNameEN})",
    //         'description_ur' => "{$partyNameUR} کی جانب سے {$projectNameUR} کے {$productNameUR} پر کینسلیشن چارجز",
    //         'document_number' => 'B-R' . '-' . $bookingReturn->id,
    //         'debit' => $cancellationsCharges,
    //         'credit' => 0,
    //     ];

    //     if (!empty($cancellationChargesCreditData)) {
    //         AccountLedger::create($cancellationChargesCreditData);
    //     }

    //     $cancellationChargesGeneralJournalCreditData = [
    //         'date' => now()->toDateString(),
    //         'project_id' => $bookingReturn->bookingApplication->project_id,
    //         'invoice_id' => $bookingReturn->id,
    //         'party_id' => null,
    //         'detail_account_id' => $bookingReturn->cash_bank_account,
    //         'description_en' => "Cancellation charges for {$productNameEN} in {$projectNameEN} ({$partyNameEN})",
    //         'description_ur' => "{$partyNameUR} کی جانب سے {$projectNameUR} کے {$productNameUR} پر کینسلیشن چارجز",
    //         'document_number' => 'B-R' . '-' . $bookingReturn->id,
    //         'debit' => $cancellationsCharges,
    //         'credit' => 0,
    //     ];

    //     if (!empty($cancellationChargesGeneralJournalCreditData)) {
    //         GeneralJournal::create($cancellationChargesGeneralJournalCreditData);
    //     }

    //     $totalCredit = AccountLedger::where('detail_account_id', $bookingReturn->bookingApplication->detail_account_id)
    //         ->sum('credit');

    //     $totalDebit = AccountLedger::where('detail_account_id', $bookingReturn->bookingApplication->detail_account_id)
    //         ->sum('debit');
    //     $remainingAmount = $totalDebit - $totalCredit;

    //     if ($remainingAmount > 0) {
    //         // Debit is more → need CREDIT
    //         $debitAmount = 0;
    //         $creditAmount = $remainingAmount;
    //     } else {
    //         // Credit is more → need DEBIT
    //         $debitAmount = abs($remainingAmount);
    //         $creditAmount = 0;
    //     }
    //     if ($remainingAmount != 0) {
    //         $remainingDebitData = [
    //             'date' => now()->toDateString(),
    //             'project_id' => $bookingReturn->project_id,
    //             'invoice_id' => $bookingReturn->id,
    //             'party_id' => $bookingReturn->bookingApplication->party_id,
    //             'detail_account_id' => $bookingReturn->bookingApplication->detail_account_id,
    //             'description_en' => "Sale Return of {$productNameEN} in {$projectNameEN} by {$partyNameEN}",
    //             'description_ur' => "{$partyNameUR} کی جانب سے {$projectNameUR} کے {$productNameUR} کی ریٹرن",
    //             'document_number' => 'B-R' . '-' . $bookingReturn->id,
    //             'debit' => $debitAmount,
    //             'credit' => $creditAmount,
    //         ];

    //         if (!empty($remainingDebitData)) {
    //             AccountLedger::create($remainingDebitData);
    //         }

    //         $generalJournalRemainingDebitData = [
    //             'date' => now()->toDateString(),
    //             'project_id' => $bookingReturn->project_id,
    //             'invoice_id' => $bookingReturn->id,
    //             'party_id' => $bookingReturn->bookingApplication->party_id,
    //             'detail_account_id' => $bookingReturn->bookingApplication->detail_account_id,
    //             'description_en' => "Sale of {$productNameEN} in {$projectNameEN} to {$partyNameEN}",
    //             'description_ur' => "{$partyNameUR} کو {$projectNameUR} کے {$productNameUR} کی فروخت",
    //             'document_number' => 'B-R' . '-' . $bookingReturn->id,
    //             'debit' => $debitAmount,
    //             'credit' => $creditAmount,
    //         ];
    //         if (!empty($generalJournalRemainingDebitData)) {
    //             GeneralJournal::create($generalJournalRemainingDebitData);
    //         }

    //         $remainingCreditData = [
    //             'date' => now()->toDateString(),
    //             'project_id' => $bookingReturn->project_id,
    //             'invoice_id' => $bookingReturn->id,
    //             'party_id' => null,
    //             'detail_account_id' => $bookingReturn->detail_account_id,
    //             'description_en' => "Sale Return of {$productNameEN} in {$projectNameEN} by {$partyNameEN}",
    //             'description_ur' => "{$partyNameUR} کی جانب سے {$projectNameUR} کے {$productNameUR} کی ریٹرن",
    //             'document_number' => 'B-R' . '-' . $bookingReturn->id,
    //             'debit' => $debitAmount,
    //             'credit' => $creditAmount,
    //         ];

    //         if (!empty($remainingCreditData)) {
    //             AccountLedger::create($remainingCreditData);
    //         }

    //         $generalJournalRemainingCreditData = [
    //             'date' => now()->toDateString(),
    //             'project_id' => $bookingReturn->bookingApplication->project_id,
    //             'invoice_id' => $bookingReturn->id,
    //             'party_id' => $bookingReturn->bookingApplication->party_id,
    //             'detail_account_id' => $bookingReturn->bookingApplication->detail_account_id,
    //             'description_en' => "Sale Return of {$productNameEN} in {$projectNameEN} by {$partyNameEN}",
    //             'description_ur' => "{$partyNameUR} کی جانب سے {$projectNameUR} کے {$productNameUR} کی ریٹرن",
    //             'document_number' => 'B-R' . '-' . $bookingReturn->id,
    //             'debit' => $debitAmount,
    //             'credit' => $creditAmount,
    //         ];
    //         if (!empty($generalJournalRemainingCreditData)) {
    //             GeneralJournal::create($generalJournalRemainingCreditData);
    //         }


    //     }







    //     $totalCancellationChargesCreditData = [
    //         'date' => now()->toDateString(),
    //         'project_id' => $bookingReturn->bookingApplication->project_id,
    //         'invoice_id' => $bookingReturn->id,
    //         'party_id' => $bookingReturn->bookingApplication->party_id,
    //         'detail_account_id' => $bookingReturn->bookingApplication->detail_account_id,
    //         'description_en' => "Cancellation charges for {$productNameEN} in {$projectNameEN} ({$partyNameEN})",
    //         'description_ur' => "{$partyNameUR} کی جانب سے {$projectNameUR} کے {$productNameUR} پر کینسلیشن چارجز",
    //         'document_number' => 'B-R' . '-' . $bookingReturn->id,
    //         'debit' => $cancellationsCharges,
    //         'credit' => 0,
    //     ];

    //     if (!empty($totalCancellationChargesCreditData)) {
    //         AccountLedger::create($totalCancellationChargesCreditData);
    //     }

    //     $totalCancellationChargesGeneralJournalCreditData = [
    //         'date' => now()->toDateString(),
    //         'project_id' => $bookingReturn->bookingApplication->project_id,
    //         'invoice_id' => $bookingReturn->id,
    //         'party_id' => $bookingReturn->bookingApplication->party_id,
    //         'detail_account_id' => $bookingReturn->bookingApplication->detail_account_id,
    //         'description_en' => "Cancellation charges for {$productNameEN} in {$projectNameEN} ({$partyNameEN})",
    //         'description_ur' => "{$partyNameUR} کی جانب سے {$projectNameUR} کے {$productNameUR} پر کینسلیشن چارجز",
    //         'document_number' => 'B-R' . '-' . $bookingReturn->id,
    //         'debit' => $cancellationsCharges,
    //         'credit' => 0,
    //     ];

    //     if (!empty($totalCancellationChargesGeneralJournalCreditData)) {
    //         GeneralJournal::create($totalCancellationChargesGeneralJournalCreditData);
    //     }

    //     $totalCancellationChargesDebitData = [
    //         'date' => now()->toDateString(),
    //         'project_id' => $bookingReturn->bookingApplication->project_id,
    //         'invoice_id' => $bookingReturn->id,
    //         'party_id' => null,
    //         'detail_account_id' => $bookingReturn->cash_bank_account,
    //         'description_en' => "Cancellation charges for {$productNameEN} in {$projectNameEN} ({$partyNameEN})",
    //         'description_ur' => "{$partyNameUR} کی جانب سے {$projectNameUR} کے {$productNameUR} پر کینسلیشن چارجز",
    //         'document_number' => 'B-R' . '-' . $bookingReturn->id,
    //         'debit' => $cancellationsCharges,
    //         'credit' => 0,
    //     ];

    //     if (!empty($totalCancellationChargesDebitData)) {
    //         AccountLedger::create($totalCancellationChargesDebitData);
    //     }

    //     $totalCancellationChargesGeneralJournalDebitData = [
    //         'date' => now()->toDateString(),
    //         'project_id' => $bookingReturn->bookingApplication->project_id,
    //         'invoice_id' => $bookingReturn->id,
    //         'party_id' => null,
    //         'detail_account_id' => $bookingReturn->cash_bank_account,
    //         'description_en' => "Cancellation charges for {$productNameEN} in {$projectNameEN} ({$partyNameEN})",
    //         'description_ur' => "{$partyNameUR} کی جانب سے {$projectNameUR} کے {$productNameUR} پر کینسلیشن چارجز",
    //         'document_number' => 'B-R' . '-' . $bookingReturn->id,
    //         'debit' => $cancellationsCharges,
    //         'credit' => 0,
    //     ];

    //     if (!empty($totalCancellationChargesGeneralJournalDebitData)) {
    //         GeneralJournal::create($totalCancellationChargesGeneralJournalDebitData);
    //     }














    //     $stockLedgerData = [

    //         'date' => now()->toDateString(),
    //         'project_id' => $bookingReturn->bookingApplication->project_id,
    //         'product_id' => $bookingReturn->bookingApplication->product_id,
    //         'invoice_id' => $bookingReturn->id,
    //         'party_title_en' => $partyNameEN,
    //         'party_title_ur' => $partyNameUR,
    //         'description_en' => "Sale Return of {$productNameEN} in {$projectNameEN} From {$partyNameEN}",
    //         'description_ur' => "{$partyNameUR} سے {$projectNameUR} کے {$productNameUR} کی ریٹرن",
    //         'document_number' => 'B-R' . '-' . $bookingReturn->id,
    //         'stock_in_quantity' => 1,
    //         'stock_out_quantity' => 0,
    //     ];
    //     if (!empty($stockLedgerData)) {
    //         StockLedger::create($stockLedgerData);
    //     }
    // }
}
