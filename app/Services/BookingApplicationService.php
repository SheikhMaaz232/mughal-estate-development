<?php

namespace App\Services;

use App\Models\Product;
use App\Models\AccountLedger;
use App\Models\DetailAccount;
use App\Models\BookingApplication;
use Illuminate\Support\Facades\App;
use App\Models\BookingNomineeDetail;
use App\Models\BookingPaymentShedule;
use App\Models\GeneralJournal;
use App\Models\Party;
use App\Models\Project;
use App\Models\ScheduleType;
use App\Models\StockLedger;

class BookingApplicationService
{
    public function getAll($perPage = 10)
    {
        return BookingApplication::with('mainHead', 'controlHead', 'subHead', 'subSubHead')->latest()->paginate($perPage);
    }

    public function getById($id)
    {
        return BookingApplication::findOrFail($id);
    }

    public function create(array $data)
    {
        return BookingApplication::create($data);
    }

    public function update($id, array $data)
    {
        $bookingApplication = BookingApplication::findOrFail($id);
        $bookingApplication->update($data);
        return $bookingApplication;
    }

    public function createLedgerEntry($booking, $bookingPayment): void
    {
        $dealerMainParty = DetailAccount::where('id', $booking->dealer_id)->value('party_id');
        $productNameEN = Product::where('id', $booking->product_id)->value('name_en');
        $productNameUR = Product::where('id', $booking->product_id)->value('name_ur');
        $projectNameEN = Project::where('id', $booking->project_id)->value('name_en');
        $projectNameUR = Project::where('id', $booking->project_id)->value('name_ur');
        $partyNameEN = Party::where('id', $booking->party_id)->value('name_en');
        $partyNameUR = Party::where('id', $booking->party_id)->value('name_ur');
        $product = DetailAccount::where('project_id', $booking->project_id)->where('name_en', $productNameEN)->value('id');

        $creditData = [
            'date' => $booking->date,
            'project_id' => $booking->project_id,
            'invoice_id' => $booking->id,
            'party_id' => null,
            'detail_account_id' => $product,
            'is_fee_entry' => 0,
            'transaction_type' => null,
            'description_en' => "Sale of {$productNameEN} in {$projectNameEN} to {$partyNameEN}",
            'description_ur' => "{$partyNameUR} کو {$projectNameUR} کے {$productNameUR} کی فروخت",
            'document_number' => 'B-A' . '-' . $booking->id,
            'debit' => 0,
            'credit' => $booking->total_amount,
        ];

        if (!empty($creditData)) {
            AccountLedger::create($creditData);
        }

        $generalJournalCreditData = [
            'date' => $booking->date,
            'project_id' => $booking->project_id,
            'invoice_id' => $booking->id,
            'party_id' => null,
            'detail_account_id' => $product,
            'is_fee_entry' => 0,
            'transaction_type' => null,
            'description_en' => "Sale of {$productNameEN} in {$projectNameEN} to {$partyNameEN}",
            'description_ur' => "{$partyNameUR} کو {$projectNameUR} کے {$productNameUR} کی فروخت",
            'document_number' => 'B-A' . '-' . $booking->id,
            'debit' => 0,
            'credit' => $booking->total_amount,
        ];
        if (!empty($generalJournalCreditData)) {
            GeneralJournal::create($generalJournalCreditData);
        }

        if ($bookingPayment && $bookingPayment->isNotEmpty()) {
            foreach ($bookingPayment as $payment) {
                $paymentTypeNameEN = ScheduleType::where('id', $payment->schedule_type_id)->value('title_en');
                $paymentTypeNameUR = ScheduleType::where('id', $payment->schedule_type_id)->value('title_ur');

                $debitData = [
                    'date' => $payment->due_date,
                    'project_id' => $booking->project_id,
                    'invoice_id' => $booking->id,
                    'party_id' => $booking->party_id,
                    'is_fee_entry' => 0,
                    'transaction_type' => 'booking_payment',
                    'detail_account_id' => $booking->detail_account_id,
                    'description_en' => $paymentTypeNameEN . ' Payment on Sale Of ' . $productNameEN . ' of ' . $projectNameEN,
                    'description_ur' => $projectNameUR . ' کے ' . $productNameUR . ' پر فروخت کی ' . $paymentTypeNameUR . ' رقم ',
                    'document_number' => 'B-A' . '-' . $booking->id,
                    'debit' => $payment->calculated_total_amount ?? 0,
                    'credit' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $generalJournalDebitData = $debitData;
                AccountLedger::insert($debitData);
                GeneralJournal::insert($generalJournalDebitData);
            }
        }

        $stockLedgerData = [

            'date' => $booking->date,
            'project_id' => $booking->project_id,
            'product_id' => $booking->product_id,
            'invoice_id' => $booking->id,
            'party_title_en' => $partyNameEN,
            'party_title_ur' => $partyNameUR,
            'description_en' => "Sale of {$productNameEN} in {$projectNameEN} to {$partyNameEN}",
            'description_ur' => "{$partyNameUR} کو {$projectNameUR} کے {$productNameUR} کی فروخت",
            'document_number' => 'B-A' . '-' . $booking->id,
            'stock_in_quantity' => 0,
            'stock_out_quantity' => 1,
        ];
        if (!empty($stockLedgerData)) {
            StockLedger::create($stockLedgerData);
        }

        $commissionCreditData = [
            'date' => $booking->date,
            'project_id' => $booking->project_id,
            'invoice_id' => $booking->id,
            'is_fee_entry' => 0,
            'transaction_type' => null,
            'party_id' => $dealerMainParty ?? null,
            'detail_account_id' => $booking->dealer_id,
            'description_en' => 'Commission On Sale Of' . $productNameEN . 'of' . $projectNameEN,
            'description_ur' => $projectNameUR . ' کے ' . $productNameUR . ' کی فروخت پر کمیشن',
            'document_number' => 'B-A' . '-' . $booking->id,
            'debit' => 0,
            'credit' => $booking->commission,
        ];
        if (!empty($commissionCreditData)) {
            AccountLedger::create($commissionCreditData);
            GeneralJournal::create($commissionCreditData);
        }

        $commissionDebitData = [
            'date' => $booking->date,
            'project_id' => $booking->project_id,
            'invoice_id' => $booking->id,
            'is_fee_entry' => 0,
            'transaction_type' => null,
            'party_id' => null,
            'detail_account_id' => $product,
            'description_en' => 'Commission On Sale Of ' . $productNameEN . ' of ' . $projectNameEN,
            'description_ur' => $projectNameUR . ' کے ' . $productNameUR . ' کی فروخت پر کمیشن',
            'document_number' => 'B-A' . '-' . $booking->id,
            'debit' => $booking->commission,
            'credit' => 0,
        ];
        if (!empty($commissionDebitData)) {
            AccountLedger::create($commissionDebitData);
        }



        $generalJournalsCommissionDebitData = [
            'date' => $booking->date,
            'project_id' => $booking->project_id,
            'invoice_id' => $booking->id,
            'is_fee_entry' => 0,
            'transaction_type' => null,
            'party_id' => null,
            'detail_account_id' => $product,
            'description_en' => 'Commission On Sale Of ' . $productNameEN . ' of ' . $projectNameEN,
            'description_ur' => $projectNameUR . ' کے ' . $productNameUR . ' کی فروخت پر کمیشن',
            'document_number' => 'B-A' . '-' . $booking->id,
            'debit' => $booking->commission,
            'credit' => 0,
        ];
        if (!empty($generalJournalsCommissionDebitData)) {
            GeneralJournal::create($generalJournalsCommissionDebitData);
        }

        $receivableCommissionCreditData = [
            'date' => $booking->date,
            'project_id' => $booking->project_id,
            'invoice_id' => $booking->id,
            'is_fee_entry' => 0,
            'transaction_type' => null,
            'party_id' => null,
            'detail_account_id' => $product,
            'description_en' => 'Commission Received On Sale Of ' . $productNameEN . ' of ' . $projectNameEN,
            'description_ur' => $projectNameUR . ' کے ' . $productNameUR . ' کی فروخت پر کمیشن وصولی',
            'document_number' => 'B-A' . '-' . $booking->id,
            'debit' => 0,
            'credit' => $booking->commission,
        ];
        if (!empty($receivableCommissionCreditData)) {
            AccountLedger::create($receivableCommissionCreditData);
        }

        $generalJournalsReceivableCommissionCreditData = [
            'date' => $booking->date,
            'project_id' => $booking->project_id,
            'invoice_id' => $booking->id,
            'is_fee_entry' => 0,
            'transaction_type' => null,
            'party_id' => null,
            'detail_account_id' => $product,
            'description_en' => 'Commission Received On Sale Of ' . $productNameEN . ' of ' . $projectNameEN,
            'description_ur' => $projectNameUR . ' کے ' . $productNameUR . ' کی فروخت پر کمیشن وصولی',
            'document_number' => 'B-A' . '-' . $booking->id,
            'debit' => 0,
            'credit' => $booking->commission,
        ];
        if (!empty($generalJournalsReceivableCommissionCreditData)) {
            GeneralJournal::create($generalJournalsReceivableCommissionCreditData);
        }

        $receivableCommissionDebitData = [
            'date' => $booking->date,
            'project_id' => $booking->project_id,
            'invoice_id' => $booking->id,
            'is_fee_entry' => 0,
            'transaction_type' => null,
            'party_id' => null,
            'detail_account_id' => $booking->receivable_dealer_id,
            'description_en' => 'Commission On Sale Of ' . $productNameEN . ' of ' . $projectNameEN,
            'description_ur' => $projectNameUR . ' کے ' . $productNameUR . ' کی فروخت پر کمیشن',
            'document_number' => 'B-A' . '-' . $booking->id,
            'debit' => $booking->commission,
            'credit' => 0,
        ];
        if (!empty($receivableCommissionDebitData)) {
            AccountLedger::create($receivableCommissionDebitData);
        }

        $generalJournalsReceivableCommissionDebitData = [
            'date' => $booking->date,
            'project_id' => $booking->project_id,
            'invoice_id' => $booking->id,
            'is_fee_entry' => 0,
            'transaction_type' => null,
            'party_id' => null,
            'detail_account_id' => $booking->receivable_dealer_id,
            'description_en' => 'Commission On Sale Of ' . $productNameEN . ' of ' . $projectNameEN,
            'description_ur' => $projectNameUR . ' کے ' . $productNameUR . ' کی فروخت پر کمیشن',
            'document_number' => 'B-A' . '-' . $booking->id,
            'debit' => $booking->commission,
            'credit' => 0,
        ];
        if (!empty($generalJournalsReceivableCommissionDebitData)) {
            GeneralJournal::create($generalJournalsReceivableCommissionDebitData);
        }

        // Possession Fees
        if ($booking->possession_fees > 0) {
            $possessionFeesEntries = [
                [
                    'date' => $booking->date,
                    'project_id' => $booking->project_id,
                    'invoice_id' => $booking->id,
                    'party_id' => $booking->party_id,
                    'detail_account_id' => $booking->detail_account_id,
                    'description_en' => "Possession Fees of {$productNameEN} in {$projectNameEN} for {$partyNameEN}",
                    'description_ur' => "{$partyNameUR} کے لیے {$projectNameUR} میں {$productNameUR} کی فیس قبضہ",
                    'document_number' => 'B-A' . '-' . $booking->id,
                    'debit' => $booking->possession_fees,
                    'credit' => 0,
                    'is_fee_entry' => 1,
                    'transaction_type' => 'possession_fees',
                ],
                [
                    'date' => $booking->date,
                    'project_id' => $booking->project_id,
                    'invoice_id' => $booking->id,
                    'party_id' => null,
                    'detail_account_id' => $product,
                    'description_en' => "Possession Fees of {$productNameEN} in {$projectNameEN} for {$partyNameEN}",
                    'description_ur' => "{$partyNameUR} کے لیے {$projectNameUR} میں {$productNameUR} کی فیس قبضہ",
                    'document_number' => 'B-A' . '-' . $booking->id,
                    'debit' => 0,
                    'credit' => $booking->possession_fees,
                    'is_fee_entry' => 0,
                    'transaction_type' => null,
                ],
                [
                    'date' => $booking->date,
                    'project_id' => $booking->project_id,
                    'invoice_id' => $booking->id,
                    'party_id' => null,
                    'detail_account_id' => $product,
                    'description_en' => "Possession Fees of {$productNameEN} in {$projectNameEN} for {$partyNameEN}",
                    'description_ur' => "{$partyNameUR} کے لیے {$projectNameUR} میں {$productNameUR} کی فیس قبضہ",
                    'document_number' => 'B-A' . '-' . $booking->id,
                    'debit' => $booking->possession_fees,
                    'credit' => 0,
                    'is_fee_entry' => 0,
                    'transaction_type' => null,
                ],
                [
                    'date' => $booking->date,
                    'project_id' => $booking->project_id,
                    'invoice_id' => $booking->id,
                    'party_id' => null,
                    'detail_account_id' => $booking->possession_receivable_account,
                    'description_en' => "Possession Fees of {$productNameEN} in {$projectNameEN} for {$partyNameEN}",
                    'description_ur' => "{$partyNameUR} کے لیے {$projectNameUR} میں {$productNameUR} کی فیس قبضہ",
                    'document_number' => 'B-A' . '-' . $booking->id,
                    'debit' => 0,
                    'credit' => $booking->possession_fees,
                    'is_fee_entry' => 0,
                    'transaction_type' => null,
                ],
            ];

            AccountLedger::insert($possessionFeesEntries);
            GeneralJournal::insert($possessionFeesEntries);
        }

        //  Proceeding Fees
        if ($booking->proceeding_fees > 0) {
            $proceedingFeesEntries = [
                [
                    'date' => $booking->date,
                    'project_id' => $booking->project_id,
                    'invoice_id' => $booking->id,
                    'party_id' => $booking->party_id,
                    'detail_account_id' => $booking->detail_account_id,
                    'description_en' => "Proceeding Fees of {$productNameEN} in {$projectNameEN} for {$partyNameEN}",
                    'description_ur' => "{$partyNameUR} کے لیے {$projectNameUR} میں {$productNameUR} کی پروسیڈنگ فیس",
                    'document_number' => 'B-A' . '-' . $booking->id,
                    'debit' => $booking->proceeding_fees,
                    'credit' => 0,
                    'is_fee_entry' => 1,
                    'transaction_type' => 'proceeding_fees',
                ],
                [
                    'date' => $booking->date,
                    'project_id' => $booking->project_id,
                    'invoice_id' => $booking->id,
                    'party_id' => null,
                    'detail_account_id' => $product,
                    'description_en' => "Proceeding Fees of {$productNameEN} in {$projectNameEN} for {$partyNameEN}",
                    'description_ur' => "{$partyNameUR} کے لیے {$projectNameUR} میں {$productNameUR} کی پروسیڈنگ فیس",
                    'document_number' => 'B-A' . '-' . $booking->id,
                    'debit' => 0,
                    'credit' => $booking->proceeding_fees,
                    'is_fee_entry' => 0,
                    'transaction_type' => null,
                ],
                [
                    'date' => $booking->date,
                    'project_id' => $booking->project_id,
                    'invoice_id' => $booking->id,
                    'party_id' => null,
                    'detail_account_id' => $product,
                    'description_en' => "Proceeding Fees of {$productNameEN} in {$projectNameEN} for {$partyNameEN}",
                    'description_ur' => "{$partyNameUR} کے لیے {$projectNameUR} میں {$productNameUR} کی پروسیڈنگ فیس",
                    'document_number' => 'B-A' . '-' . $booking->id,
                    'debit' => $booking->proceeding_fees,
                    'credit' => 0,
                    'is_fee_entry' => 0,
                    'transaction_type' => null,
                ],
                [
                    'date' => $booking->date,
                    'project_id' => $booking->project_id,
                    'invoice_id' => $booking->id,
                    'party_id' => null,
                    'detail_account_id' => $booking->proceeding_receivable_account,
                    'description_en' => "Proceeding Fees of {$productNameEN} in {$projectNameEN} for {$partyNameEN}",
                    'description_ur' => "{$partyNameUR} کے لیے {$projectNameUR} میں {$productNameUR} کی پروسیڈنگ فیس",
                    'document_number' => 'B-A' . '-' . $booking->id,
                    'debit' => 0,
                    'credit' => $booking->proceeding_fees,
                    'is_fee_entry' => 0,
                    'transaction_type' => null,
                ],
            ];

            AccountLedger::insert($proceedingFeesEntries);
            GeneralJournal::insert($proceedingFeesEntries);
        }

        //Development Charges
        if ($booking->development_charges > 0) {
            $developmentChargesEntries = [
                [
                    'date' => $booking->date,
                    'project_id' => $booking->project_id,
                    'invoice_id' => $booking->id,
                    'party_id' => $booking->party_id,
                    'detail_account_id' => $booking->detail_account_id,
                    'description_en' => "Development Charges of {$productNameEN} in {$projectNameEN} for {$partyNameEN}",
                    'description_ur' => "{$partyNameUR} کے لیے {$projectNameUR} میں {$productNameUR} کے ترقیاتی چارجز",
                    'document_number' => 'B-A' . '-' . $booking->id,
                    'debit' => $booking->development_charges,
                    'credit' => 0,
                    'is_fee_entry' => 1,
                    'transaction_type' => 'development_charges',
                ],
                [
                    'date' => $booking->date,
                    'project_id' => $booking->project_id,
                    'invoice_id' => $booking->id,
                    'party_id' => null,
                    'detail_account_id' => $product,
                    'description_en' => "Development Charges of {$productNameEN} in {$projectNameEN} for {$partyNameEN}",
                    'description_ur' => "{$partyNameUR} کے لیے {$projectNameUR} میں {$productNameUR} کے ترقیاتی چارجز",
                    'document_number' => 'B-A' . '-' . $booking->id,
                    'debit' => 0,
                    'credit' => $booking->development_charges,
                    'is_fee_entry' => 0,
                    'transaction_type' => null,
                ],
                [
                    'date' => $booking->date,
                    'project_id' => $booking->project_id,
                    'invoice_id' => $booking->id,
                    'party_id' => null,
                    'detail_account_id' => $product,
                    'description_en' => "Development Charges of {$productNameEN} in {$projectNameEN} for {$partyNameEN}",
                    'description_ur' => "{$partyNameUR} کے لیے {$projectNameUR} میں {$productNameUR} کے ترقیاتی چارجز",
                    'document_number' => 'B-A' . '-' . $booking->id,
                    'debit' => $booking->development_charges,
                    'credit' => 0,
                    'is_fee_entry' => 0,
                    'transaction_type' => null,
                ],
                [
                    'date' => $booking->date,
                    'project_id' => $booking->project_id,
                    'invoice_id' => $booking->id,
                    'party_id' => null,
                    'detail_account_id' => $booking->development_receivable_id,
                    'description_en' => "Development Charges of {$productNameEN} in {$projectNameEN} for {$partyNameEN}",
                    'description_ur' => "{$partyNameUR} کے لیے {$projectNameUR} میں {$productNameUR} کے ترقیاتی چارجز",
                    'document_number' => 'B-A' . '-' . $booking->id,
                    'debit' => 0,
                    'credit' => $booking->development_charges,
                    'is_fee_entry' => 0,
                    'transaction_type' => null,
                ],
            ];

            AccountLedger::insert($developmentChargesEntries);
            GeneralJournal::insert($developmentChargesEntries);
        }

        // GST
        if ($booking->gst > 0) {
            $gstEntries = [
                [
                    'date' => $booking->date,
                    'project_id' => $booking->project_id,
                    'invoice_id' => $booking->id,
                    'party_id' => $booking->party_id,
                    'detail_account_id' => $booking->detail_account_id,
                    'description_en' => "GST of {$productNameEN} in {$projectNameEN} for {$partyNameEN}",
                    'description_ur' => "{$partyNameUR} کے لیے {$projectNameUR} میں {$productNameUR} کا جی ایس ٹی",
                    'document_number' => 'B-A' . '-' . $booking->id,
                    'debit' => $booking->gst,
                    'credit' => 0,
                    'is_fee_entry' => 1,
                    'transaction_type' => 'gst',
                ],
                [
                    'date' => $booking->date,
                    'project_id' => $booking->project_id,
                    'invoice_id' => $booking->id,
                    'party_id' => null,
                    'detail_account_id' => $product,
                    'description_en' => "GST of {$productNameEN} in {$projectNameEN} for {$partyNameEN}",
                    'description_ur' => "{$partyNameUR} کے لیے {$projectNameUR} میں {$productNameUR} کا جی ایس ٹی",
                    'document_number' => 'B-A' . '-' . $booking->id,
                    'debit' => 0,
                    'credit' => $booking->gst,
                    'is_fee_entry' => 0,
                    'transaction_type' => null,
                ],
                [
                    'date' => $booking->date,
                    'project_id' => $booking->project_id,
                    'invoice_id' => $booking->id,
                    'party_id' => null,
                    'detail_account_id' => $product,
                    'description_en' => "GST of {$productNameEN} in {$projectNameEN} for {$partyNameEN}",
                    'description_ur' => "{$partyNameUR} کے لیے {$projectNameUR} میں {$productNameUR} کا جی ایس ٹی",
                    'document_number' => 'B-A' . '-' . $booking->id,
                    'debit' => $booking->gst,
                    'credit' => 0,
                    'is_fee_entry' => 0,
                    'transaction_type' => null,
                ],
                [
                    'date' => $booking->date,
                    'project_id' => $booking->project_id,
                    'invoice_id' => $booking->id,
                    'party_id' => null,
                    'detail_account_id' => $booking->gst_receivable_account_id,
                    'description_en' => "GST of {$productNameEN} in {$projectNameEN} for {$partyNameEN}",
                    'description_ur' => "{$partyNameUR} کے لیے {$projectNameUR} میں {$productNameUR} کا جی ایس ٹی",
                    'document_number' => 'B-A' . '-' . $booking->id,
                    'debit' => 0,
                    'credit' => $booking->gst,
                    'is_fee_entry' => 0,
                    'transaction_type' => null,
                ],
            ];

            AccountLedger::insert($gstEntries);
            GeneralJournal::insert($gstEntries);
        }

        // 7E Chalan
        // if ($booking->sevenE_chalan > 0) {
        //     $sevenEChalanEntries = [
        //         [
        //             'date' => $booking->date,
        //             'project_id' => $booking->project_id,
        //             'invoice_id' => $booking->id,
        //             'party_id' => $booking->party_id,
        //             'detail_account_id' => $booking->detail_account_id,
        //             'description_en' => "7E Chalan of {$productNameEN} in {$projectNameEN} for {$partyNameEN}",
        //             'description_ur' => "{$partyNameUR} کے لیے {$projectNameUR} میں {$productNameUR} کا 7E چالان",
        //             'document_number' => 'B-A' . '-' . $booking->id,
        //             'debit' => $booking->sevenE_chalan,
        //             'credit' => 0,
        //             'is_fee_entry' => 1,
        //         ],
        //         [
        //             'date' => $booking->date,
        //             'project_id' => $booking->project_id,
        //             'invoice_id' => $booking->id,
        //             'party_id' => null,
        //             'detail_account_id' => $product,
        //             'description_en' => "7E Chalan of {$productNameEN} in {$projectNameEN} for {$partyNameEN}",
        //             'description_ur' => "{$partyNameUR} کے لیے {$projectNameUR} میں {$productNameUR} کا 7E چالان",
        //             'document_number' => 'B-A' . '-' . $booking->id,
        //             'debit' => 0,
        //             'credit' => $booking->sevenE_chalan,
        //             'is_fee_entry' => 0,
        //         ],
        //         [
        //             'date' => $booking->date,
        //             'project_id' => $booking->project_id,
        //             'invoice_id' => $booking->id,
        //             'party_id' => null,
        //             'detail_account_id' => $product,
        //             'description_en' => "7E Chalan of {$productNameEN} in {$projectNameEN} for {$partyNameEN}",
        //             'description_ur' => "{$partyNameUR} کے لیے {$projectNameUR} میں {$productNameUR} کا 7E چالان",
        //             'document_number' => 'B-A' . '-' . $booking->id,
        //             'debit' => $booking->sevenE_chalan,
        //             'credit' => 0,
        //             'is_fee_entry' => 0,
        //         ],
        //         [
        //             'date' => $booking->date,
        //             'project_id' => $booking->project_id,
        //             'invoice_id' => $booking->id,
        //             'party_id' => null,
        //             'detail_account_id' => $booking->sevenE_chalan_receivable_account,
        //             'description_en' => "7E Chalan of {$productNameEN} in {$projectNameEN} for {$partyNameEN}",
        //             'description_ur' => "{$partyNameUR} کے لیے {$projectNameUR} میں {$productNameUR} کا 7E چالان",
        //             'document_number' => 'B-A' . '-' . $booking->id,
        //             'debit' => 0,
        //             'credit' => $booking->sevenE_chalan,
        //             'is_fee_entry' => 0,
        //         ],
        //     ];

        //     AccountLedger::insert($sevenEChalanEntries);
        //     GeneralJournal::insert($sevenEChalanEntries);
        // }

        if ($booking->discount_amount > 0) {

            $discountCreditData = [
                'date' => $booking->date,
                'project_id' => $booking->project_id,
                'invoice_id' => $booking->id,
                'party_id' => $booking->party_id,
                'detail_account_id' => $booking->detail_account_id,
                'is_fee_entry' => 0,
                'transaction_type' => 'feeses_discount',
                'description_en' => 'Discount On feeses Of' . $productNameEN . 'of' . $projectNameEN,
                'description_ur' => $projectNameUR . ' کے ' . $productNameUR . ' کی فیسس پر ڈسکاؤنٹ',
                'document_number' => 'B-A' . '-' . $booking->id,
                'debit' => 0,
                'credit' => $booking->discount_amount,
            ];
            if (!empty($discountCreditData)) {
                AccountLedger::create($discountCreditData);
                GeneralJournal::create($discountCreditData);
            }

            $discountDebitData = [
                'date' => $booking->date,
                'project_id' => $booking->project_id,
                'invoice_id' => $booking->id,
                'is_fee_entry' => 0,
                'transaction_type' => null,
                'party_id' => null,
                'detail_account_id' => $booking->expense_account_id,
                'description_en' => 'Discount On feeses Of ' . $productNameEN . ' of ' . $projectNameEN,
                'description_ur' => $projectNameUR . ' کے ' . $productNameUR . ' کی فیسس پر ڈسکاؤنٹ',
                'document_number' => 'B-A' . '-' . $booking->id,
                'debit' => $booking->discount_amount,
                'credit' => 0,
            ];
            if (!empty($discountDebitData)) {
                AccountLedger::create($discountDebitData);
                GeneralJournal::create($discountDebitData);
            }
        }
    }

    public function createTransferCaseLedgerEntry($booking, $previousBooking): void
    {

        $productNameEN = Product::where('id', $booking->product_id)->value('name_en');
        $productNameUR = Product::where('id', $booking->product_id)->value('name_ur');
        $projectNameEN = Project::where('id', $booking->project_id)->value('name_en');
        $projectNameUR = Project::where('id', $booking->project_id)->value('name_ur');
        $partyNameEN = Party::where('id', $booking->party_id)->value('name_en');
        $partyNameUR = Party::where('id', $booking->party_id)->value('name_ur');
        $product = DetailAccount::where('project_id', $booking->project_id)->where('name_en', $productNameEN)->value('id');

        $debitData = [
            'date' => $booking->date,
            'project_id' => $previousBooking->project_id,
            'invoice_id' => $booking->id,
            'is_fee_entry' => 0,
            'transaction_type' => 'transfer_charges',
            'party_id' => $previousBooking->party_id,
            'detail_account_id' => $previousBooking->detail_account_id,
            'description_en' => "Transfer charges of {$productNameEN} in {$projectNameEN} for {$partyNameEN}",
            'description_ur' => "{$partyNameUR} کے لیے {$projectNameUR} میں {$productNameUR} کی منتقلی کے چارجز",
            'document_number' => 'B-A' . '-' . $booking->id,
            'debit' => $booking->transfer_charges,
            'credit' => 0,
        ];

        if (!empty($debitData)) {
            AccountLedger::create($debitData);
        }

        $generalJournalDebitData = [
            'date' => $booking->date,
            'project_id' => $previousBooking->project_id,
            'invoice_id' => $booking->id,
            'is_fee_entry' => 0,
            'transaction_type' => 'transfer_charges',
            'party_id' => $previousBooking->party_id,
            'detail_account_id' => $previousBooking->detail_account_id,
            'description_en' => "Transfer charges of {$productNameEN} in {$projectNameEN} for {$partyNameEN}",
            'description_ur' => "{$partyNameUR} کے لیے {$projectNameUR} میں {$productNameUR} کی منتقلی کے چارجز",
            'document_number' => 'B-A' . '-' . $booking->id,
            'debit' => $booking->transfer_charges,
            'credit' => 0,
        ];
        if (!empty($generalJournalDebitData)) {
            GeneralJournal::create($generalJournalDebitData);
        }

        $creditData = [
            'date' => $booking->date,
            'project_id' => $previousBooking->project_id,
            'invoice_id' => $booking->id,
            'is_fee_entry' => 0,
            'transaction_type' => null,
            'party_id' => null,
            'detail_account_id' => $product,
            'description_en' => "Transfer charges of {$productNameEN} in {$projectNameEN} for {$partyNameEN}",
            'description_ur' => "{$partyNameUR} کے لیے {$projectNameUR} میں {$productNameUR} کی منتقلی کے چارجز",
            'document_number' => 'B-A' . '-' . $booking->id,
            'debit' => 0,
            'credit' => $booking->transfer_charges,
        ];

        if (!empty($creditData)) {
            AccountLedger::create($creditData);
        }

        $generalJournalCreditData = [
            'date' => $booking->date,
            'project_id' => $previousBooking->project_id,
            'invoice_id' => $booking->id,
            'is_fee_entry' => 0,
            'transaction_type' => null,
            'party_id' => null,
            'detail_account_id' => $product,
            'description_en' => "Transfer charges of {$productNameEN} in {$projectNameEN} for {$partyNameEN}",
            'description_ur' => "{$partyNameUR} کے لیے {$projectNameUR} میں {$productNameUR} کی منتقلی کے چارجز",
            'document_number' => 'B-A' . '-' . $booking->id,
            'debit' => 0,
            'credit' => $booking->transfer_charges,
        ];
        if (!empty($generalJournalCreditData)) {
            GeneralJournal::create($generalJournalCreditData);
        }


        $debitChargesData = [
            'date' => $booking->date,
            'project_id' => $booking->project_id,
            'invoice_id' => $booking->id,
            'is_fee_entry' => 0,
            'transaction_type' => null,
            'party_id' => null,
            'detail_account_id' => $booking->transfer_charges_account_id,
            'description_en' => "Transfer charges of {$productNameEN} in {$projectNameEN} for {$partyNameEN}",
            'description_ur' => "{$partyNameUR} کے لیے {$projectNameUR} میں {$productNameUR} کی منتقلی کے چارجز",
            'document_number' => 'B-A' . '-' . $booking->id,
            'debit' => $booking->transfer_charges,
            'credit' => 0,
        ];

        if (!empty($debitChargesData)) {
            AccountLedger::create($debitChargesData);
        }

        $generalJournalChargesDebitData = [
            'date' => $booking->date,
            'project_id' => $booking->project_id,
            'invoice_id' => $booking->id,
            'is_fee_entry' => 0,
            'transaction_type' => null,
            'party_id' => null,
            'detail_account_id' => $booking->transfer_charges_account_id,
            'description_en' => "Transfer charges of {$productNameEN} in {$projectNameEN} for {$partyNameEN}",
            'description_ur' => "{$partyNameUR} کے لیے {$projectNameUR} میں {$productNameUR} کی منتقلی کے چارجز",
            'document_number' => 'B-A' . '-' . $booking->id,
            'debit' => $booking->transfer_charges,
            'credit' => 0,
        ];
        if (!empty($generalJournalChargesDebitData)) {
            GeneralJournal::create($generalJournalChargesDebitData);
        }

        $creditChargesData = [
            'date' => $booking->date,
            'project_id' => $previousBooking->project_id,
            'invoice_id' => $booking->id,
            'is_fee_entry' => 0,
            'transaction_type' => 'transfer_charges',
            'party_id' => $previousBooking->party_id,
            'detail_account_id' => $previousBooking->detail_account_id,
            'description_en' => "Transfer charges of {$productNameEN} in {$projectNameEN} for {$partyNameEN}",
            'description_ur' => "{$partyNameUR} کے لیے {$projectNameUR} میں {$productNameUR} کی منتقلی کے چارجز",
            'document_number' => 'B-A' . '-' . $booking->id,
            'debit' => 0,
            'credit' => $booking->transfer_charges,
        ];

        if (!empty($creditChargesData)) {
            AccountLedger::create($creditChargesData);
        }

        $generalJournalChargesCreditData = [
            'date' => $booking->date,
            'project_id' => $previousBooking->project_id,
            'invoice_id' => $booking->id,
            'is_fee_entry' => 0,
            'transaction_type' => 'transfer_charges',
            'party_id' => $previousBooking->party_id,
            'detail_account_id' => $previousBooking->detail_account_id,
            'description_en' => "Transfer charges of {$productNameEN} in {$projectNameEN} for {$partyNameEN}",
            'description_ur' => "{$partyNameUR} کے لیے {$projectNameUR} میں {$productNameUR} کی منتقلی کے چارجز",
            'document_number' => 'B-A' . '-' . $booking->id,
            'debit' => 0,
            'credit' => $booking->transfer_charges,
        ];
        if (!empty($generalJournalChargesCreditData)) {
            GeneralJournal::create($generalJournalChargesCreditData);
        }

        $stockLedgerInData = [

            'date' => $booking->date,
            'project_id' => $previousBooking->project_id,
            'product_id' => $previousBooking->product_id,
            'invoice_id' => $booking->id,
            'party_title_en' => $partyNameEN,
            'party_title_ur' => $partyNameUR,
            'description_en' => "Sale of {$productNameEN} in {$projectNameEN} to {$partyNameEN}",
            'description_ur' => "{$partyNameUR} کو {$projectNameUR} کے {$productNameUR} کی فروخت",
            'document_number' => 'B-A' . '-' . $booking->id,
            'stock_in_quantity' => 1,
            'stock_out_quantity' => 0,
        ];
        if (!empty($stockLedgerInData)) {
            StockLedger::create($stockLedgerInData);
        }

        $transferDebitData = [
            'date' => $booking->date,
            'project_id' => $booking->project_id,
            'invoice_id' => $booking->id,
            'is_fee_entry' => 0,
            'transaction_type' => 'booking_payment',
            'party_id' => $booking->party_id,
            'detail_account_id' => $booking->detail_account_id,
            'description_en' => "Sale of {$productNameEN} in {$projectNameEN} to {$partyNameEN}",
            'description_ur' => "{$partyNameUR} کو {$projectNameUR} کے {$productNameUR} کی فروخت",
            'document_number' => 'B-A' . '-' . $booking->id,
            'debit' => $booking->total_amount,
            'credit' => 0,
        ];

        if (!empty($transferDebitData)) {
            AccountLedger::create($transferDebitData);
        }

        $transferCreditData = [
            'date' => $booking->date,
            'project_id' => $previousBooking->project_id,
            'invoice_id' => $booking->id,
            'is_fee_entry' => 0,
            'transaction_type' => null,
            'party_id' => $previousBooking->party_id,
            'detail_account_id' => $previousBooking->detail_account_id,
            'description_en' => "Payment transferred to {$partyNameEN} for {$productNameEN} in {$projectNameEN}",
            'description_ur' => "{$projectNameUR} میں {$productNameUR} کی ادائیگی {$partyNameUR} کو منتقل کی گئی",
            'document_number' => 'B-A' . '-' . $booking->id,
            'debit' => 0,
            'credit' => $booking->total_amount,
        ];

        if (!empty($transferCreditData)) {
            AccountLedger::create($transferCreditData);
        }

        $stockLedgerOutData = [

            'date' => $booking->date,
            'project_id' => $booking->project_id,
            'product_id' => $booking->product_id,
            'invoice_id' => $booking->id,
            'party_title_en' => $partyNameEN,
            'party_title_ur' => $partyNameUR,
            'description_en' => "Sale of {$productNameEN} in {$projectNameEN} to {$partyNameEN}",
            'description_ur' => "{$partyNameUR} کو {$projectNameUR} کے {$productNameUR} کی فروخت",
            'document_number' => 'B-A' . '-' . $booking->id,
            'stock_in_quantity' => 0,
            'stock_out_quantity' => 1,
        ];
        if (!empty($stockLedgerOutData)) {
            StockLedger::create($stockLedgerOutData);
        }



        // Possession Fees
        if ($booking->possession_fees > 0) {
            $possessionFeesEntries = [
                [
                    'date' => $booking->date,
                    'project_id' => $booking->project_id,
                    'invoice_id' => $booking->id,
                    'party_id' => $booking->party_id,
                    'detail_account_id' => $booking->detail_account_id,
                    'description_en' => "Possession Fees of {$productNameEN} in {$projectNameEN} for {$partyNameEN}",
                    'description_ur' => "{$partyNameUR} کے لیے {$projectNameUR} میں {$productNameUR} کی فیس قبضہ",
                    'document_number' => 'B-A' . '-' . $booking->id,
                    'debit' => $booking->possession_fees,
                    'credit' => 0,
                    'is_fee_entry' => 1,
                    'transaction_type' => 'possession_fees',
                ],
                [
                    'date' => $booking->date,
                    'project_id' => $booking->project_id,
                    'invoice_id' => $booking->id,
                    'party_id' => null, // company account
                    'detail_account_id' => $product,
                    'description_en' => "Possession Fees of {$productNameEN} in {$projectNameEN} for {$partyNameEN}",
                    'description_ur' => "{$partyNameUR} کے لیے {$projectNameUR} میں {$productNameUR} کی فیس قبضہ",
                    'document_number' => 'B-A' . '-' . $booking->id,
                    'debit' => 0,
                    'credit' => $booking->possession_fees,
                    'is_fee_entry' => 0,
                    'transaction_type' => null,
                ],
                [
                    'date' => $booking->date,
                    'project_id' => $booking->project_id,
                    'invoice_id' => $booking->id,
                    'party_id' => null,
                    'detail_account_id' => $product,
                    'description_en' => "Possession Fees of {$productNameEN} in {$projectNameEN} for {$partyNameEN}",
                    'description_ur' => "{$partyNameUR} کے لیے {$projectNameUR} میں {$productNameUR} کی فیس قبضہ",
                    'document_number' => 'B-A' . '-' . $booking->id,
                    'debit' => $booking->possession_fees,
                    'credit' => 0,
                    'is_fee_entry' => 0,
                    'transaction_type' => null,
                ],
                [
                    'date' => $booking->date,
                    'project_id' => $booking->project_id,
                    'invoice_id' => $booking->id,
                    'party_id' => null,
                    'detail_account_id' => $booking->possession_receivable_account,
                    'description_en' => "Possession Fees of {$productNameEN} in {$projectNameEN} for {$partyNameEN}",
                    'description_ur' => "{$partyNameUR} کے لیے {$projectNameUR} میں {$productNameUR} کی فیس قبضہ",
                    'document_number' => 'B-A' . '-' . $booking->id,
                    'debit' => 0,
                    'credit' => $booking->possession_fees,
                    'is_fee_entry' => 0,
                    'transaction_type' => null,
                ],
            ];

            AccountLedger::insert($possessionFeesEntries);
            GeneralJournal::insert($possessionFeesEntries);
        }

        //  Proceeding Fees
        if ($booking->proceeding_fees > 0) {
            $proceedingFeesEntries = [
                [
                    'date' => $booking->date,
                    'project_id' => $booking->project_id,
                    'invoice_id' => $booking->id,
                    'party_id' => $booking->party_id,
                    'detail_account_id' => $booking->detail_account_id,
                    'description_en' => "Proceeding Fees of {$productNameEN} in {$projectNameEN} for {$partyNameEN}",
                    'description_ur' => "{$partyNameUR} کے لیے {$projectNameUR} میں {$productNameUR} کی پروسیڈنگ فیس",
                    'document_number' => 'B-A' . '-' . $booking->id,
                    'debit' => $booking->proceeding_fees,
                    'credit' => 0,
                    'is_fee_entry' => 1,
                    'transaction_type' => 'proceeding_fees',
                ],
                [
                    'date' => $booking->date,
                    'project_id' => $booking->project_id,
                    'invoice_id' => $booking->id,
                    'party_id' => null,
                    'detail_account_id' => $product,
                    'description_en' => "Proceeding Fees of {$productNameEN} in {$projectNameEN} for {$partyNameEN}",
                    'description_ur' => "{$partyNameUR} کے لیے {$projectNameUR} میں {$productNameUR} کی پروسیڈنگ فیس",
                    'document_number' => 'B-A' . '-' . $booking->id,
                    'debit' => 0,
                    'credit' => $booking->proceeding_fees,
                    'is_fee_entry' => 0,
                    'transaction_type' => null,
                ],
                [
                    'date' => $booking->date,
                    'project_id' => $booking->project_id,
                    'invoice_id' => $booking->id,
                    'party_id' => null,
                    'detail_account_id' => $product,
                    'description_en' => "Proceeding Fees of {$productNameEN} in {$projectNameEN} for {$partyNameEN}",
                    'description_ur' => "{$partyNameUR} کے لیے {$projectNameUR} میں {$productNameUR} کی پروسیڈنگ فیس",
                    'document_number' => 'B-A' . '-' . $booking->id,
                    'debit' => $booking->proceeding_fees,
                    'credit' => 0,
                    'is_fee_entry' => 0,
                    'transaction_type' => null,
                ],
                [
                    'date' => $booking->date,
                    'project_id' => $booking->project_id,
                    'invoice_id' => $booking->id,
                    'party_id' => null,
                    'detail_account_id' => $booking->proceeding_receivable_account,
                    'description_en' => "Proceeding Fees of {$productNameEN} in {$projectNameEN} for {$partyNameEN}",
                    'description_ur' => "{$partyNameUR} کے لیے {$projectNameUR} میں {$productNameUR} کی پروسیڈنگ فیس",
                    'document_number' => 'B-A' . '-' . $booking->id,
                    'debit' => 0,
                    'credit' => $booking->proceeding_fees,
                    'is_fee_entry' => 0,
                    'transaction_type' => null,
                ],
            ];

            AccountLedger::insert($proceedingFeesEntries);
            GeneralJournal::insert($proceedingFeesEntries);
        }

        //Development Charges
        if ($booking->development_charges > 0) {
            $developmentChargesEntries = [
                [
                    'date' => $booking->date,
                    'project_id' => $booking->project_id,
                    'invoice_id' => $booking->id,
                    'party_id' => $booking->party_id,
                    'detail_account_id' => $booking->detail_account_id,
                    'description_en' => "Development Charges of {$productNameEN} in {$projectNameEN} for {$partyNameEN}",
                    'description_ur' => "{$partyNameUR} کے لیے {$projectNameUR} میں {$productNameUR} کے ترقیاتی چارجز",
                    'document_number' => 'B-A' . '-' . $booking->id,
                    'debit' => $booking->development_charges,
                    'credit' => 0,
                    'is_fee_entry' => 1,
                    'transaction_type' => 'development_charges',
                ],
                [
                    'date' => $booking->date,
                    'project_id' => $booking->project_id,
                    'invoice_id' => $booking->id,
                    'party_id' => null,
                    'detail_account_id' => $product,
                    'description_en' => "Development Charges of {$productNameEN} in {$projectNameEN} for {$partyNameEN}",
                    'description_ur' => "{$partyNameUR} کے لیے {$projectNameUR} میں {$productNameUR} کے ترقیاتی چارجز",
                    'document_number' => 'B-A' . '-' . $booking->id,
                    'debit' => 0,
                    'credit' => $booking->development_charges,
                    'is_fee_entry' => 0,
                    'transaction_type' => null,
                ],
                [
                    'date' => $booking->date,
                    'project_id' => $booking->project_id,
                    'invoice_id' => $booking->id,
                    'party_id' => null,
                    'detail_account_id' => $product,
                    'description_en' => "Development Charges of {$productNameEN} in {$projectNameEN} for {$partyNameEN}",
                    'description_ur' => "{$partyNameUR} کے لیے {$projectNameUR} میں {$productNameUR} کے ترقیاتی چارجز",
                    'document_number' => 'B-A' . '-' . $booking->id,
                    'debit' => $booking->development_charges,
                    'credit' => 0,
                    'is_fee_entry' => 0,
                    'transaction_type' => null,
                ],
                [
                    'date' => $booking->date,
                    'project_id' => $booking->project_id,
                    'invoice_id' => $booking->id,
                    'party_id' => null,
                    'detail_account_id' => $booking->development_receivable_id,
                    'description_en' => "Development Charges of {$productNameEN} in {$projectNameEN} for {$partyNameEN}",
                    'description_ur' => "{$partyNameUR} کے لیے {$projectNameUR} میں {$productNameUR} کے ترقیاتی چارجز",
                    'document_number' => 'B-A' . '-' . $booking->id,
                    'debit' => 0,
                    'credit' => $booking->development_charges,
                    'is_fee_entry' => 0,
                    'transaction_type' => null,
                ],
            ];

            AccountLedger::insert($developmentChargesEntries);
            GeneralJournal::insert($developmentChargesEntries);
        }

        // GST
        if ($booking->gst > 0) {
            $gstEntries = [
                [
                    'date' => $booking->date,
                    'project_id' => $booking->project_id,
                    'invoice_id' => $booking->id,
                    'party_id' => $booking->party_id,
                    'detail_account_id' => $booking->detail_account_id,
                    'description_en' => "GST of {$productNameEN} in {$projectNameEN} for {$partyNameEN}",
                    'description_ur' => "{$partyNameUR} کے لیے {$projectNameUR} میں {$productNameUR} کا جی ایس ٹی",
                    'document_number' => 'B-A' . '-' . $booking->id,
                    'debit' => $booking->gst,
                    'credit' => 0,
                    'is_fee_entry' => 1,
                    'transaction_type' => 'gst',
                ],
                [
                    'date' => $booking->date,
                    'project_id' => $booking->project_id,
                    'invoice_id' => $booking->id,
                    'party_id' => null,
                    'detail_account_id' => $product,
                    'description_en' => "GST of {$productNameEN} in {$projectNameEN} for {$partyNameEN}",
                    'description_ur' => "{$partyNameUR} کے لیے {$projectNameUR} میں {$productNameUR} کا جی ایس ٹی",
                    'document_number' => 'B-A' . '-' . $booking->id,
                    'debit' => 0,
                    'credit' => $booking->gst,
                    'is_fee_entry' => 0,
                    'transaction_type' => null,
                ],
                [
                    'date' => $booking->date,
                    'project_id' => $booking->project_id,
                    'invoice_id' => $booking->id,
                    'party_id' => null,
                    'detail_account_id' => $product,
                    'description_en' => "GST of {$productNameEN} in {$projectNameEN} for {$partyNameEN}",
                    'description_ur' => "{$partyNameUR} کے لیے {$projectNameUR} میں {$productNameUR} کا جی ایس ٹی",
                    'document_number' => 'B-A' . '-' . $booking->id,
                    'debit' => $booking->gst,
                    'credit' => 0,
                    'is_fee_entry' => 0,
                    'transaction_type' => null,
                ],
                [
                    'date' => $booking->date,
                    'project_id' => $booking->project_id,
                    'invoice_id' => $booking->id,
                    'party_id' => null,
                    'detail_account_id' => $booking->gst_receivable_account_id,
                    'description_en' => "GST of {$productNameEN} in {$projectNameEN} for {$partyNameEN}",
                    'description_ur' => "{$partyNameUR} کے لیے {$projectNameUR} میں {$productNameUR} کا جی ایس ٹی",
                    'document_number' => 'B-A' . '-' . $booking->id,
                    'debit' => 0,
                    'credit' => $booking->gst,
                    'is_fee_entry' => 0,
                    'transaction_type' => null,
                ],
            ];

            AccountLedger::insert($gstEntries);
            GeneralJournal::insert($gstEntries);
        }

        // 7E Chalan
        // if ($booking->sevenE_chalan > 0) {
        //     $sevenEChalanEntries = [
        //         [
        //             'date' => $booking->date,
        //             'project_id' => $booking->project_id,
        //             'invoice_id' => $booking->id,
        //             'party_id' => $booking->party_id,
        //             'detail_account_id' => $booking->detail_account_id,
        //             'description_en' => "7E Chalan of {$productNameEN} in {$projectNameEN} for {$partyNameEN}",
        //             'description_ur' => "{$partyNameUR} کے لیے {$projectNameUR} میں {$productNameUR} کا 7E چالان",
        //             'document_number' => 'B-A' . '-' . $booking->id,
        //             'debit' => $booking->sevenE_chalan,
        //             'credit' => 0,
        //             'is_fee_entry' => 1,
        //         ],
        //         [
        //             'date' => $booking->date,
        //             'project_id' => $booking->project_id,
        //             'invoice_id' => $booking->id,
        //             'party_id' => null,
        //             'detail_account_id' => $product,
        //             'description_en' => "7E Chalan of {$productNameEN} in {$projectNameEN} for {$partyNameEN}",
        //             'description_ur' => "{$partyNameUR} کے لیے {$projectNameUR} میں {$productNameUR} کا 7E چالان",
        //             'document_number' => 'B-A' . '-' . $booking->id,
        //             'debit' => 0,
        //             'credit' => $booking->sevenE_chalan,
        //             'is_fee_entry' => 0,
        //         ],
        //         [
        //             'date' => $booking->date,
        //             'project_id' => $booking->project_id,
        //             'invoice_id' => $booking->id,
        //             'party_id' => null,
        //             'detail_account_id' => $product,
        //             'description_en' => "7E Chalan of {$productNameEN} in {$projectNameEN} for {$partyNameEN}",
        //             'description_ur' => "{$partyNameUR} کے لیے {$projectNameUR} میں {$productNameUR} کا 7E چالان",
        //             'document_number' => 'B-A' . '-' . $booking->id,
        //             'debit' => $booking->sevenE_chalan,
        //             'credit' => 0,
        //             'is_fee_entry' => 0,
        //         ],
        //         [
        //             'date' => $booking->date,
        //             'project_id' => $booking->project_id,
        //             'invoice_id' => $booking->id,
        //             'party_id' => null,
        //             'detail_account_id' => $booking->sevenE_chalan_receivable_account,
        //             'description_en' => "7E Chalan of {$productNameEN} in {$projectNameEN} for {$partyNameEN}",
        //             'description_ur' => "{$partyNameUR} کے لیے {$projectNameUR} میں {$productNameUR} کا 7E چالان",
        //             'document_number' => 'B-A' . '-' . $booking->id,
        //             'debit' => 0,
        //             'credit' => $booking->sevenE_chalan,
        //             'is_fee_entry' => 0,
        //         ],
        //     ];

        //     AccountLedger::insert($sevenEChalanEntries);
        //     GeneralJournal::insert($sevenEChalanEntries);
        // }

        if ($booking->discount_amount > 0) {

            $discountCreditData = [
                'date' => $booking->date,
                'project_id' => $booking->project_id,
                'invoice_id' => $booking->id,
                'is_fee_entry' => 0,
                'transaction_type' => 'feeses_discount',
                'party_id' => $booking->party_id,
                'detail_account_id' => $booking->detail_account_id,
                'description_en' => 'Discount On feeses Of' . $productNameEN . 'of' . $projectNameEN,
                'description_ur' => $projectNameUR . ' کے ' . $productNameUR . ' کی فیسس پر ڈسکاؤنٹ',
                'document_number' => 'B-A' . '-' . $booking->id,
                'debit' => 0,
                'credit' => $booking->discount_amount,
            ];
            if (!empty($discountCreditData)) {
                AccountLedger::create($discountCreditData);
                GeneralJournal::create($discountCreditData);
            }

            $discountDebitData = [
                'date' => $booking->date,
                'project_id' => $booking->project_id,
                'invoice_id' => $booking->id,
                'is_fee_entry' => 0,
                'transaction_type' => null,
                'party_id' => null,
                'detail_account_id' => $booking->expense_account_id,
                'description_en' => 'Discount On feeses Of ' . $productNameEN . ' of ' . $projectNameEN,
                'description_ur' => $projectNameUR . ' کے ' . $productNameUR . ' کی فیسس پر ڈسکاؤنٹ',
                'document_number' => 'B-A' . '-' . $booking->id,
                'debit' => $booking->discount_amount,
                'credit' => 0,
            ];
            if (!empty($discountDebitData)) {
                AccountLedger::create($discountDebitData);
                GeneralJournal::create($discountDebitData);
            }
        }

        $totalCredit = AccountLedger::where('detail_account_id', $previousBooking->detail_account_id)
            ->sum('credit');

        $totalDebit = AccountLedger::where('detail_account_id', $previousBooking->detail_account_id)
            ->sum('debit');
        $remainingAmount = $totalDebit - $totalCredit;

        if ($remainingAmount > 0) {
            // Debit is more → need CREDIT
            $debitAmount = 0;
            $creditAmount = $remainingAmount;

            if ($remainingAmount != 0) {
                $remainingDebitData = [
                    'date' => $booking->date,
                    'project_id' => $previousBooking->project_id,
                    'invoice_id' => $booking->id,
                    'is_fee_entry' => 0,
                    'transaction_type' => null,
                    'party_id' => $previousBooking->party_id,
                    'detail_account_id' => $previousBooking->detail_account_id,
                    'description_en' => "Payment transferred to {$partyNameEN} for {$productNameEN} in {$projectNameEN}",
                    'description_ur' => "{$projectNameUR} میں {$productNameUR} کی ادائیگی {$partyNameUR} کو منتقل کی گئی",
                    'document_number' => 'B-A' . '-' . $booking->id,
                    'debit' => $creditAmount,
                    'credit' => $debitAmount,
                ];

                if (!empty($remainingDebitData)) {
                    AccountLedger::create($remainingDebitData);
                }

                $generalJournalRemainingDebitData = [
                    'date' => $booking->date,
                    'project_id' => $previousBooking->project_id,
                    'invoice_id' => $booking->id,
                    'is_fee_entry' => 0,
                    'transaction_type' => 'null',
                    'party_id' => $previousBooking->party_id,
                    'detail_account_id' => $previousBooking->detail_account_id,
                    'description_en' => "Payment transferred to {$partyNameEN} for {$productNameEN} in {$projectNameEN}",
                    'description_ur' => "{$projectNameUR} میں {$productNameUR} کی ادائیگی {$partyNameUR} کو منتقل کی گئی",
                    'document_number' => 'B-A' . '-' . $booking->id,
                    'debit' => $creditAmount,
                    'credit' => $debitAmount,
                ];
                if (!empty($generalJournalRemainingDebitData)) {
                    GeneralJournal::create($generalJournalRemainingDebitData);
                }

                $remainingCreditData = [
                    'date' => $booking->date,
                    'project_id' => $booking->project_id,
                    'invoice_id' => $booking->id,
                    'is_fee_entry' => 0,
                    'transaction_type' => 'booking_amount',
                    'party_id' => $booking->party_id,
                    'detail_account_id' => $booking->detail_account_id,
                    'description_en' => "Payment transferred to {$partyNameEN} for {$productNameEN} in {$projectNameEN}",
                    'description_ur' => "{$projectNameUR} میں {$productNameUR} کی ادائیگی {$partyNameUR} کو منتقل کی گئی",
                    'document_number' => 'B-A' . '-' . $booking->id,
                    'debit' => $debitAmount,
                    'credit' => $creditAmount,
                ];

                if (!empty($remainingCreditData)) {
                    AccountLedger::create($remainingCreditData);
                }

                $generalJournalRemainingCreditData = [
                    'date' => $booking->date,
                    'project_id' => $booking->project_id,
                    'invoice_id' => $booking->id,
                    'is_fee_entry' => 0,
                    'transaction_type' => 'booking_amount',
                    'party_id' => $booking->party_id,
                    'detail_account_id' => $booking->detail_account_id,
                    'description_en' => "Payment transferred to {$partyNameEN} for {$productNameEN} in {$projectNameEN}",
                    'description_ur' => "{$projectNameUR} میں {$productNameUR} کی ادائیگی {$partyNameUR} کو منتقل کی گئی",
                    'document_number' => 'B-A' . '-' . $booking->id,
                    'debit' => $debitAmount,
                    'credit' => $creditAmount,
                ];
                if (!empty($generalJournalRemainingCreditData)) {
                    GeneralJournal::create($generalJournalRemainingCreditData);
                }
            }
        } else {
            if ($remainingAmount != 0) {

                // Credit is more → need DEBIT
                $debitAmount = abs($remainingAmount);
                $creditAmount = 0;

                $remainingDebitData = [
                    'date' => $booking->date,
                    'project_id' => $previousBooking->project_id,
                    'invoice_id' => $booking->id,
                    'is_fee_entry' => 0,
                    'transaction_type' => null,
                    'party_id' => $previousBooking->party_id,
                    'detail_account_id' => $previousBooking->detail_account_id,
                    'description_en' => "Payment transferred to {$partyNameEN} for {$productNameEN} in {$projectNameEN}",
                    'description_ur' => "{$projectNameUR} میں {$productNameUR} کی ادائیگی {$partyNameUR} کو منتقل کی گئی",
                    'document_number' => 'B-A' . '-' . $booking->id,
                    'debit' => $debitAmount,
                    'credit' => $creditAmount,
                ];

                if (!empty($remainingDebitData)) {
                    AccountLedger::create($remainingDebitData);
                }

                $generalJournalRemainingDebitData = [
                    'date' => $booking->date,
                    'project_id' => $previousBooking->project_id,
                    'invoice_id' => $booking->id,
                    'is_fee_entry' => 0,
                    'transaction_type' => null,
                    'party_id' => $previousBooking->party_id,
                    'detail_account_id' => $previousBooking->detail_account_id,
                    'description_en' => "Payment transferred to {$partyNameEN} for {$productNameEN} in {$projectNameEN}",
                    'description_ur' => "{$projectNameUR} میں {$productNameUR} کی ادائیگی {$partyNameUR} کو منتقل کی گئی",
                    'document_number' => 'B-A' . '-' . $booking->id,
                    'debit' => $debitAmount,
                    'credit' => $creditAmount,
                ];
                if (!empty($generalJournalRemainingDebitData)) {
                    GeneralJournal::create($generalJournalRemainingDebitData);
                }

                $remainingCreditData = [
                    'date' => $booking->date,
                    'project_id' => $booking->project_id,
                    'invoice_id' => $booking->id,
                    'is_fee_entry' => 0,
                    'transaction_type' => 'booking_amount',
                    'party_id' => $booking->party_id,
                    'detail_account_id' => $booking->detail_account_id,
                    'description_en' => "Payment transferred to {$partyNameEN} for {$productNameEN} in {$projectNameEN}",
                    'description_ur' => "{$projectNameUR} میں {$productNameUR} کی ادائیگی {$partyNameUR} کو منتقل کی گئی",
                    'document_number' => 'B-A' . '-' . $booking->id,
                    'debit' => $creditAmount,
                    'credit' => $debitAmount,
                ];

                if (!empty($remainingCreditData)) {
                    AccountLedger::create($remainingCreditData);
                }

                $generalJournalRemainingCreditData = [
                    'date' => $booking->date,
                    'project_id' => $booking->project_id,
                    'invoice_id' => $booking->id,
                    'is_fee_entry' => 0,
                    'transaction_type' => 'booking_amount',
                    'party_id' => $booking->party_id,
                    'detail_account_id' => $booking->detail_account_id,
                    'description_en' => "Payment transferred to {$partyNameEN} for {$productNameEN} in {$projectNameEN}",
                    'description_ur' => "{$projectNameUR} میں {$productNameUR} کی ادائیگی {$partyNameUR} کو منتقل کی گئی",
                    'document_number' => 'B-A' . '-' . $booking->id,
                    'debit' => $creditAmount,
                    'credit' => $debitAmount,
                ];
                if (!empty($generalJournalRemainingCreditData)) {
                    GeneralJournal::create($generalJournalRemainingCreditData);
                }
            }
        }
    }

    public function delete($id)
    {
        $bookingApplication = BookingApplication::findOrFail($id);
        $product = Product::findOrFail($bookingApplication->product_id);
        if ($product) {
            $product->update(['status' => 'Verified']);
        }

        BookingNomineeDetail::where('booking_id', $id)->delete();
        BookingPaymentShedule::where('booking_id', $id)->delete();

        return $bookingApplication->delete();
    }

    public function getDetailAccountForMainParty($partyId)
    {
        $field = App::getLocale() === 'ur' ? 'name_ur' : 'name_en';
        return DetailAccount::where('party_id', $partyId)->pluck($field, 'id'); //change here
    }

    public function prepareData(array $data): array
    {
        $bookingData = [
            'form_no'          => $data['form_no'],
            'party_id'         => $data['party_id'],
            'previous_booking_id'         => $data['previous_booking_id'],
            'case'         =>  $data['case'],
            'detail_account_id' => $data['detail_account_id'],
            'transfer_charges_account_id' => $data['transfer_charges_account_id'],
            'operating_start_date' => $data['operating_start_date'],
            'condition' => $data['condition'],
            'transfer_charges' => $data['transfer_charges'],
            'operating_charges' => $data['operating_charges'],
            'expense_account_id' => $data['expense_account_id'] ?? null,
            'discount_amount' => $data['discount_amount'] ?? 0,
            'project_id'       => $data['project_id'],
            'product_id'       => $data['product_id'],
            'status'       => $data['status'],
            'dealer_id'        => $data['dealer_id'],
            'care_off'        => $data['care_off'] ?? null,
            'date'             => $data['date'],
            'add_value'        => $data['add_value'] ?? 0,
            'discount'         => $data['discount'] ?? 0,
            'commission'       => $data['commission'] ?? 0,
            'total_amount'     => $data['total_amount'] ?? 0,
            'grand_total_amount'  => $data['grand_total_amount'] ?? 0,
            'possession_fees'   => $data['possession_fees'] ?? 0,
            'possession_receivable_account' => $data['possession_receivable_account'] ?? null,
            'receivable_dealer_id' => $data['receivable_dealer_id'] ?? null,
            'proceeding_fees'   => $data['proceeding_fees'] ?? 0,
            'proceeding_receivable_account' => $data['proceeding_receivable_account'] ?? null,
            'development_charges' => $data['development_charges'] ?? 0,
            'development_receivable_id' => $data['development_receivable_id'] ?? null,
            'operating_receivable_account' => $data['operating_receivable_account'] ?? null,
            'gst'               => $data['gst'] ?? 0,
            'gst_receivable_account_id' => $data['gst_receivable_account_id'] ?? null,
            'sevenE_chalan'     => $data['sevenE_chalan'] ?? 0,
            'sevenE_chalan_receivable_account' => $data['sevenE_chalan_receivable_account'] ?? null,
        ];

        $nominees = [];
        if (isset($data['relation_id'])) {
            foreach ($data['relation_id'] as $i => $relationId) {
                $nominees[] = [
                    'relation_id'      => $relationId,
                    'nominee_party_id' => $data['nominee_party_id'][$i] ?? null,
                ];
            }
        }

        $schedules = [];
        if (isset($data['schedule_type_id'])) {
            foreach ($data['schedule_type_id'] as $i => $scheduleTypeId) {
                $schedules[] = [
                    'schedule_type_id'    => $scheduleTypeId,
                    'schedule_period_id'  => $data['schedule_period_id'][$i] ?? null,
                    'due_date'            => $data['due_date'][$i] ?? null,
                    'number'              => $data['number'][$i] ?? 0,
                    'pay_amount'          => $data['pay_amount'][$i] ?? 0,
                    'calculated_total_amount' => $data['calculated_total_amount'][$i] ?? 0,
                ];
            }
        }

        return [
            'booking'  => $bookingData,
            'nominees' => $nominees,
            'schedules' => $schedules,
        ];
    }

    public function prepareTransferData(array $data): array
    {
        $bookingData = [
            'form_no'          => $data['form_no'],
            'party_id'         => $data['party_id'],
            'previous_booking_id'         => $data['previous_booking_id'],
            'case'         =>  $data['case'],
            'detail_account_id' => $data['detail_account_id'],
            'transfer_charges_account_id' => $data['transfer_charges_account_id'],
            'operating_start_date' => $data['operating_start_date'],
            'condition' => $data['condition'],
            'transfer_charges' => $data['transfer_charges'],
            'expense_account_id' => $data['expense_account_id'] ?? null,
            'discount_amount' => $data['discount_amount'] ?? 0,
            'operating_charges' => $data['operating_charges'],
            'project_id'       => $data['project_id'],
            'product_id'       => $data['product_id'],
            'status'       => $data['status'],
            'dealer_id'        => $data['dealer_id'],
            'care_off'        => $data['care_off'] ?? null,
            'date'             => $data['date'],
            'add_value'        => $data['add_value'] ?? 0,
            'discount'         => $data['discount'] ?? 0,
            'commission'       => $data['commission'] ?? 0,
            'total_amount'     => $data['total_amount'] ?? 0,
            'grand_total_amount'  => $data['grand_total_amount'] ?? 0,
            'possession_fees'   => $data['possession_fees'] ?? 0,
            'possession_receivable_account' => $data['possession_receivable_account'] ?? null,
            'proceeding_fees'   => $data['proceeding_fees'] ?? 0,
            'proceeding_receivable_account' => $data['proceeding_receivable_account'] ?? null,
            'development_charges' => $data['development_charges'] ?? 0,
            'development_receivable_id' => $data['development_receivable_id'] ?? null,
            'operating_receivable_account' => $data['operating_receivable_account'] ?? null,
            'gst'               => $data['gst'] ?? 0,
            'gst_receivable_account_id' => $data['gst_receivable_account_id'] ?? null,
            'sevenE_chalan'     => $data['sevenE_chalan'] ?? 0,
            'sevenE_chalan_receivable_account' => $data['sevenE_chalan_receivable_account'] ?? null,
        ];

        $nominees = [];
        if (isset($data['relation_id'])) {
            foreach ($data['relation_id'] as $i => $relationId) {
                $nominees[] = [
                    'relation_id'      => $relationId,
                    'nominee_party_id' => $data['nominee_party_id'][$i] ?? null,
                ];
            }
        }

        $schedules = [];
        if (isset($data['schedule_type_id'])) {
            foreach ($data['schedule_type_id'] as $i => $scheduleTypeId) {
                $schedules[] = [
                    'schedule_type_id'    => $scheduleTypeId,
                    'schedule_period_id'  => $data['schedule_period_id'][$i] ?? null,
                    'due_date'            => $data['due_date'][$i] ?? null,
                    'number'              => $data['number'][$i] ?? 0,
                    'pay_amount'          => $data['pay_amount'][$i] ?? 0,
                    'calculated_total_amount' => $data['calculated_total_amount'][$i] ?? 0,
                ];
            }
        }

        return [
            'booking'  => $bookingData,
            'nominees' => $nominees,
            'schedules' => $schedules,
        ];
    }

    public function prepareUpdateData(array $data, int $id): array
    {
        $productNameEN = Product::where('id', $data['product_id'])->value('name_en');
        $productNameUR = Product::where('id', $data['product_id'])->value('name_ur');
        $projectNameEN = Project::where('id', $data['project_id'])->value('name_en');
        $projectNameUR = Project::where('id', $data['project_id'])->value('name_ur');
        $partyNameEN = Party::where('id', $data['party_id'])->value('name_en');
        $partyNameUR = Party::where('id', $data['party_id'])->value('name_ur');
        $dealerMainParty = DetailAccount::where('id', $data['dealer_id'])->value('party_id');
        $product = DetailAccount::where('project_id', $data['project_id'])->where('name_en', $productNameEN)->value('id');

        $bookingData = [
            'form_no'          => $data['form_no'],
            'party_id'         => $data['party_id'],
            'previous_booking_id'         => $data['previous_booking_id'],
            'case'         =>  $data['case'],
            'detail_account_id' => $data['detail_account_id'],
            'transfer_charges_account_id' => $data['transfer_charges_account_id'],
            'operating_start_date' => $data['operating_start_date'],
            'condition' => $data['condition'],
            'transfer_charges' => $data['transfer_charges'],
            'operating_charges' => $data['operating_charges'],
            'expense_account_id' => $data['expense_account_id'] ?? null,
            'discount_amount' => $data['discount_amount'] ?? 0,
            'project_id'       => $data['project_id'],
            'product_id'       => $data['product_id'],
            'status'       => $data['status'],
            'dealer_id'        => $data['dealer_id'],
            'care_off'        => $data['care_off'] ?? null,
            'date'             => $data['date'],
            'add_value'        => $data['add_value'] ?? 0,
            'discount'         => $data['discount'] ?? 0,
            'commission'       => $data['commission'] ?? 0,
            'total_amount'     => $data['total_amount'] ?? 0,
            'grand_total_amount'  => $data['grand_total_amount'] ?? 0,
            'possession_fees'   => $data['possession_fees'] ?? 0,
            'possession_receivable_account' => $data['possession_receivable_account'] ?? null,
            'receivable_dealer_id' => $data['receivable_dealer_id'] ?? null,
            'proceeding_fees'   => $data['proceeding_fees'] ?? 0,
            'proceeding_receivable_account' => $data['proceeding_receivable_account'] ?? null,
            'development_charges' => $data['development_charges'] ?? 0,
            'development_receivable_id' => $data['development_receivable_id'] ?? null,
            'operating_receivable_account' => $data['operating_receivable_account'] ?? null,
            'gst'               => $data['gst'] ?? 0,
            'gst_receivable_account_id' => $data['gst_receivable_account_id'] ?? null,
            'sevenE_chalan'     => $data['sevenE_chalan'] ?? 0,
            'sevenE_chalan_receivable_account' => $data['sevenE_chalan_receivable_account'] ?? null,
        ];

        $creditData = [
            'date' => $data['date'],
            'project_id' => $data['project_id'],
            'invoice_id' => $id,
            'party_id' => null,
            'detail_account_id' => $product,
            'description_en' => "Sale of {$productNameEN} in {$projectNameEN} to {$partyNameEN}",
            'description_ur' => "{$partyNameUR} کو {$projectNameUR} کے {$productNameUR} کی فروخت",
            'document_number' => 'B-A' . '-' . $id,
            'transaction_type' => null,
            'is_fee_entry' => 0,
            'debit' => 0,
            'credit' => $data['total_amount'],
        ];

        $generalJournalCreditData = [
            'date' => $data['date'],
            'project_id' => $data['project_id'],
            'invoice_id' => $id,
            'party_id' => null,
            'detail_account_id' => $product,
            'description_en' => "Sale of {$productNameEN} in {$projectNameEN} to {$partyNameEN}",
            'description_ur' => "{$partyNameUR} کو {$projectNameUR} کے {$productNameUR} کی فروخت",
            'document_number' => 'B-A' . '-' . $id,
            'transaction_type' => null,
            'is_fee_entry' => 0,
            'debit' => 0,
            'credit' => $data['total_amount'],
        ];

        $commissionCreditData = [
            'date' => $data['date'],
            'project_id' => $data['project_id'],
            'invoice_id' => $id,
            'party_id' => $dealerMainParty ?? null,
            'detail_account_id' => $data['dealer_id'],
            'description_en' => 'Commission On Sale Of' . $productNameEN . 'of' . $projectNameEN,
            'description_ur' => $projectNameUR . ' کے ' . $productNameUR . ' کی فروخت پر کمیشن',
            'document_number' => 'B-A' . '-' . $id,
            'transaction_type' => null,
            'is_fee_entry' => 0,
            'debit' => 0,
            'credit' => $data['commission'],
        ];

        $generalJournalsCommissionCreditData = [
            'date' => $data['date'],
            'project_id' => $data['project_id'],
            'invoice_id' => $id,
            'party_id' => $dealerMainParty ?? null,
            'detail_account_id' => $data['dealer_id'],
            'description_en' => 'Commission On Sale Of' . $productNameEN . 'of' . $projectNameEN,
            'description_ur' => $projectNameUR . ' کے ' . $productNameUR . ' کی فروخت پر کمیشن',
            'document_number' => 'B-A' . '-' . $id,
            'transaction_type' => null,
            'is_fee_entry' => 0,
            'debit' => 0,
            'credit' => $data['commission'],
        ];

        $commissionDebitData = [
            'date' => $data['date'],
            'project_id' => $data['project_id'],
            'invoice_id' => $id,
            'party_id' =>  null,
            'detail_account_id' => $product,
            'description_en' => 'Commission On Sale Of' . $productNameEN . 'of' . $projectNameEN,
            'description_ur' => $projectNameUR . ' کے ' . $productNameUR . ' کی فروخت پر کمیشن',
            'document_number' => 'B-A' . '-' . $id,
            'transaction_type' => null,
            'is_fee_entry' => 0,
            'debit' => $data['commission'],
            'credit' => 0,
        ];

        $generalJournalsCommissionDebitData = [
            'date' => $data['date'],
            'project_id' => $data['project_id'],
            'invoice_id' => $id,
            'party_id' => null,
            'detail_account_id' => $product,
            'description_en' => 'Commission On Sale Of' . $productNameEN . 'of' . $projectNameEN,
            'description_ur' => $projectNameUR . ' کے ' . $productNameUR . ' کی فروخت پر کمیشن',
            'document_number' => 'B-A' . '-' . $id,
            'transaction_type' => null,
            'is_fee_entry' => 0,
            'debit' => $data['commission'],
            'credit' => 0,
        ];

        $receivableCommissionCreditData = [
            'date' => $data['date'],
            'project_id' => $data['project_id'],
            'invoice_id' => $id,
            'party_id' =>  null,
            'detail_account_id' => $product,
            'description_en' => 'Commission Received On Sale Of' . $productNameEN . 'of' . $projectNameEN,
            'description_ur' => $projectNameUR . ' کے ' . $productNameUR . ' کی فروخت پر کمیشن وصولی',
            'document_number' => 'B-A' . '-' . $id,
            'transaction_type' => null,
            'is_fee_entry' => 0,
            'debit' => 0,
            'credit' => $data['commission'],
        ];

        $receivableGeneralJournalsCommissionCreditData = [
            'date' => $data['date'],
            'project_id' => $data['project_id'],
            'invoice_id' => $id,
            'party_id' =>  null,
            'detail_account_id' => $product,
            'description_en' => 'Commission On Sale Of' . $productNameEN . 'of' . $projectNameEN,
            'description_ur' => $projectNameUR . ' کے ' . $productNameUR . ' کی فروخت پر کمیشن',
            'document_number' => 'B-A' . '-' . $id,
            'transaction_type' => null,
            'is_fee_entry' => 0,
            'debit' => 0,
            'credit' => $data['commission'],
        ];

        $receivableCommissionDebitData = [
            'date' => $data['date'],
            'project_id' => $data['project_id'],
            'invoice_id' => $id,
            'party_id' => $dealerMainParty ?? null,
            'detail_account_id' => $data['receivable_dealer_id'],
            'description_en' => 'Commission On Sale Of' . $productNameEN . 'of' . $projectNameEN,
            'description_ur' => $projectNameUR . ' کے ' . $productNameUR . ' کی فروخت پر کمیشن',
            'document_number' => 'B-A' . '-' . $id,
            'transaction_type' => null,
            'is_fee_entry' => 0,
            'debit' => $data['commission'],
            'credit' => 0,
        ];

        $receivableGeneralJournalsCommissionDebitData = [
            'date' => $data['date'],
            'project_id' => $data['project_id'],
            'invoice_id' => $id,
            'party_id' => $dealerMainParty ?? null,
            'detail_account_id' => $data['receivable_dealer_id'],
            'description_en' => 'Commission On Sale Of' . $productNameEN . 'of' . $projectNameEN,
            'description_ur' => $projectNameUR . ' کے ' . $productNameUR . ' کی فروخت پر کمیشن',
            'document_number' => 'B-A' . '-' . $id,
            'transaction_type' => null,
            'is_fee_entry' => 0,
            'debit' => $data['commission'],
            'credit' => 0,
        ];

        $discountCreditData = [
            'date' => $data['date'],
            'project_id' => $data['project_id'],
            'invoice_id' => $id,
            'party_id' => $data['party_id'] ?? null,
            'detail_account_id' => $data['detail_account_id'] ?? null,
            'description_en' => 'Discount On feeses Of' . $productNameEN . 'of' . $projectNameEN,
            'description_ur' => $projectNameUR . ' کے ' . $productNameUR . ' کی فیسس پر ڈسکاؤنٹ',
            'document_number' => 'B-A' . '-' . $id,
            'debit' => 0,
            'credit' => $data['discount_amount'],
            'is_fee_entry' => 0,
            'transaction_type' => 'feeses_discount',
        ];

        $discountDebitData = [
            'date' => $data['date'],
            'project_id' => $data['project_id'],
            'invoice_id' => $id,
            'party_id' => null,
            'detail_account_id' => $data['expense_account_id'] ?? null,
            'description_en' => 'Discount On feeses Of ' . $productNameEN . ' of ' . $projectNameEN,
            'description_ur' => $projectNameUR . ' کے ' . $productNameUR . ' کی فیسس پر ڈسکاؤنٹ',
            'document_number' => 'B-A' . '-' . $id,
            'debit' => $data['discount_amount'],
            'credit' => 0,
            'is_fee_entry' => 0,
            'transaction_type' => null,
        ];

        $stockLedgerData = [
            'date' => $data['date'],
            'project_id' => $data['project_id'],
            'product_id' => $data['product_id'],
            'invoice_id' => $id,
            'party_title_en' => $partyNameEN,
            'party_title_ur' => $partyNameUR,
            'description_en' => "Sale of {$productNameEN} in {$projectNameEN} to {$partyNameEN}",
            'description_ur' => "{$partyNameUR} کو {$projectNameUR} کے {$productNameUR} کی فروخت",
            'document_number' => 'B-A' . '-' . $id,
            'stock_in_quantity' => 0,
            'stock_out_quantity' => 1,
        ];

        // Possession Fees
        if ($data['possession_fees'] > 0) {
            $possessionFeesEntries = [
                [
                    'date' => $data['date'],
                    'project_id' => $data['project_id'],
                    'invoice_id' =>  $id,
                    'party_id' => $data['party_id'],
                    'detail_account_id' => $data['detail_account_id'],
                    'description_en' => "Possession Fees of {$productNameEN} in {$projectNameEN} for {$partyNameEN}",
                    'description_ur' => "{$partyNameUR} کے لیے {$projectNameUR} میں {$productNameUR} کی فیس قبضہ",
                    'document_number' => 'B-A' . '-' . $id,
                    'debit' => $data['possession_fees'],
                    'credit' => 0,
                    'is_fee_entry' => 1,
                    'transaction_type' => 'possession_fees',
                ],
                [
                    'date' =>  $data['date'],
                    'project_id' => $data['project_id'],
                    'invoice_id' => $id,
                    'party_id' => null,
                    'detail_account_id' => $product,
                    'description_en' => "Possession Fees of {$productNameEN} in {$projectNameEN} for {$partyNameEN}",
                    'description_ur' => "{$partyNameUR} کے لیے {$projectNameUR} میں {$productNameUR} کی فیس قبضہ",
                    'document_number' => 'B-A' . '-' . $id,
                    'debit' => 0,
                    'credit' => $data['possession_fees'],
                    'is_fee_entry' => 0,
                    'transaction_type' => null,
                ],
                [
                    'date' =>  $data['date'],
                    'project_id' => $data['project_id'],
                    'invoice_id' => $id,
                    'party_id' => null,
                    'detail_account_id' => $product,
                    'description_en' => "Possession Fees of {$productNameEN} in {$projectNameEN} for {$partyNameEN}",
                    'description_ur' => "{$partyNameUR} کے لیے {$projectNameUR} میں {$productNameUR} کی فیس قبضہ",
                    'document_number' => 'B-A' . '-' . $id,
                    'debit' => $data['possession_fees'],
                    'credit' => 0,
                    'is_fee_entry' => 0,
                    'transaction_type' => null,
                ],
                [
                    'date' =>  $data['date'],
                    'project_id' => $data['project_id'],
                    'invoice_id' => $id,
                    'party_id' => null,
                    'detail_account_id' => $data['possession_receivable_account'],
                    'description_en' => "Possession Fees of {$productNameEN} in {$projectNameEN} for {$partyNameEN}",
                    'description_ur' => "{$partyNameUR} کے لیے {$projectNameUR} میں {$productNameUR} کی فیس قبضہ",
                    'document_number' => 'B-A' . '-' . $id,
                    'debit' => 0,
                    'credit' => $data['possession_fees'],
                    'is_fee_entry' => 0,
                    'transaction_type' => null,
                ],
            ];
        }

        //  Proceeding Fees
        if ($data['proceeding_fees'] > 0) {
            $proceedingFeesEntries = [
                [
                    'date' =>  $data['date'],
                    'project_id' => $data['project_id'],
                    'invoice_id' => $id,
                    'party_id' => $data['party_id'],
                    'detail_account_id' => $data['detail_account_id'],
                    'description_en' => "Proceeding Fees of {$productNameEN} in {$projectNameEN} for {$partyNameEN}",
                    'description_ur' => "{$partyNameUR} کے لیے {$projectNameUR} میں {$productNameUR} کی پروسیڈنگ فیس",
                    'document_number' => 'B-A' . '-' . $id,
                    'debit' => $data['proceeding_fees'],
                    'credit' => 0,
                    'is_fee_entry' => 1,
                    'transaction_type' => 'proceeding_fees',
                ],
                [
                    'date' =>  $data['date'],
                    'project_id' => $data['project_id'],
                    'invoice_id' => $id,
                    'party_id' => null,
                    'detail_account_id' => $product,
                    'description_en' => "Proceeding Fees of {$productNameEN} in {$projectNameEN} for {$partyNameEN}",
                    'description_ur' => "{$partyNameUR} کے لیے {$projectNameUR} میں {$productNameUR} کی پروسیڈنگ فیس",
                    'document_number' => 'B-A' . '-' . $id,
                    'debit' => 0,
                    'credit' => $data['proceeding_fees'],
                    'is_fee_entry' => 0,
                    'transaction_type' => null,
                ],
                [
                    'date' =>  $data['date'],
                    'project_id' => $data['project_id'],
                    'invoice_id' => $id,
                    'party_id' => null,
                    'detail_account_id' => $product,
                    'description_en' => "Proceeding Fees of {$productNameEN} in {$projectNameEN} for {$partyNameEN}",
                    'description_ur' => "{$partyNameUR} کے لیے {$projectNameUR} میں {$productNameUR} کی پروسیڈنگ فیس",
                    'document_number' => 'B-A' . '-' . $id,
                    'debit' => $data['proceeding_fees'],
                    'credit' => 0,
                    'is_fee_entry' => 0,
                    'transaction_type' => null,
                ],
                [
                    'date' =>  $data['date'],
                    'project_id' => $data['project_id'],
                    'invoice_id' => $id,
                    'party_id' => null,
                    'detail_account_id' => $data['proceeding_receivable_account'],
                    'description_en' => "Proceeding Fees of {$productNameEN} in {$projectNameEN} for {$partyNameEN}",
                    'description_ur' => "{$partyNameUR} کے لیے {$projectNameUR} میں {$productNameUR} کی پروسیڈنگ فیس",
                    'document_number' => 'B-A' . '-' . $id,
                    'debit' => 0,
                    'credit' => $data['proceeding_fees'],
                    'is_fee_entry' => 0,
                    'transaction_type' => null,
                ],
            ];
        }

        //Development Charges
        if ($data['development_charges'] > 0) {
            $developmentChargesEntries = [
                [
                    'date' =>  $data['date'],
                    'project_id' => $data['project_id'],
                    'invoice_id' => $id,
                    'party_id' => $data['party_id'],
                    'detail_account_id' => $data['detail_account_id'],
                    'description_en' => "Development Charges of {$productNameEN} in {$projectNameEN} for {$partyNameEN}",
                    'description_ur' => "{$partyNameUR} کے لیے {$projectNameUR} میں {$productNameUR} کے ترقیاتی چارجز",
                    'document_number' => 'B-A' . '-' . $id,
                    'debit' => $data['development_charges'],
                    'credit' => 0,
                    'is_fee_entry' => 1,
                    'transaction_type' => 'development_charges',

                ],
                [
                    'date' =>  $data['date'],
                    'project_id' => $data['project_id'],
                    'invoice_id' => $id,
                    'party_id' => null,
                    'detail_account_id' => $product,
                    'description_en' => "Development Charges of {$productNameEN} in {$projectNameEN} for {$partyNameEN}",
                    'description_ur' => "{$partyNameUR} کے لیے {$projectNameUR} میں {$productNameUR} کے ترقیاتی چارجز",
                    'document_number' => 'B-A' . '-' . $id,
                    'debit' => 0,
                    'credit' => $data['development_charges'],
                    'is_fee_entry' => 0,
                    'transaction_type' => null,
                ],
                [
                    'date' =>  $data['date'],
                    'project_id' => $data['project_id'],
                    'invoice_id' => $id,
                    'party_id' => null,
                    'detail_account_id' => $product,
                    'description_en' => "Development Charges of {$productNameEN} in {$projectNameEN} for {$partyNameEN}",
                    'description_ur' => "{$partyNameUR} کے لیے {$projectNameUR} میں {$productNameUR} کے ترقیاتی چارجز",
                    'document_number' => 'B-A' . '-' . $id,
                    'debit' => $data['development_charges'],
                    'credit' => 0,
                    'is_fee_entry' => 0,
                    'transaction_type' => null,
                ],
                [
                    'date' =>  $data['date'],
                    'project_id' => $data['project_id'],
                    'invoice_id' => $id,
                    'party_id' => null,
                    'detail_account_id' => $data['development_receivable_id'],
                    'description_en' => "Development Charges of {$productNameEN} in {$projectNameEN} for {$partyNameEN}",
                    'description_ur' => "{$partyNameUR} کے لیے {$projectNameUR} میں {$productNameUR} کے ترقیاتی چارجز",
                    'document_number' => 'B-A' . '-' . $id,
                    'debit' => 0,
                    'credit' => $data['development_charges'],
                    'is_fee_entry' => 0,
                    'transaction_type' => null,
                ],
            ];
        }

        // GST
        if ($data['gst'] > 0) {
            $gstEntries = [
                [
                    'date' =>  $data['date'],
                    'project_id' => $data['project_id'],
                    'invoice_id' => $id,
                    'party_id' => $data['party_id'],
                    'detail_account_id' => $data['detail_account_id'],
                    'description_en' => "GST of {$productNameEN} in {$projectNameEN} for {$partyNameEN}",
                    'description_ur' => "{$partyNameUR} کے لیے {$projectNameUR} میں {$productNameUR} کا جی ایس ٹی",
                    'document_number' => 'B-A' . '-' . $id,
                    'debit' => $data['gst'],
                    'credit' => 0,
                    'is_fee_entry' => 1,
                    'transaction_type' => 'gst',
                ],
                [
                    'date' =>  $data['date'],
                    'project_id' => $data['project_id'],
                    'invoice_id' => $id,
                    'party_id' => null,
                    'detail_account_id' => $product,
                    'description_en' => "GST of {$productNameEN} in {$projectNameEN} for {$partyNameEN}",
                    'description_ur' => "{$partyNameUR} کے لیے {$projectNameUR} میں {$productNameUR} کا جی ایس ٹی",
                    'document_number' => 'B-A' . '-' . $id,
                    'debit' => 0,
                    'credit' => $data['gst'],
                    'is_fee_entry' => 0,
                    'transaction_type' => null,
                ],
                [
                    'date' =>  $data['date'],
                    'project_id' => $data['project_id'],
                    'invoice_id' => $id,
                    'party_id' => null,
                    'detail_account_id' => $product,
                    'description_en' => "GST of {$productNameEN} in {$projectNameEN} for {$partyNameEN}",
                    'description_ur' => "{$partyNameUR} کے لیے {$projectNameUR} میں {$productNameUR} کا جی ایس ٹی",
                    'document_number' => 'B-A' . '-' . $id,
                    'debit' => $data['gst'],
                    'credit' => 0,
                    'is_fee_entry' => 0,
                    'transaction_type' => null,
                ],
                [
                    'date' =>  $data['date'],
                    'project_id' => $data['project_id'],
                    'invoice_id' => $id,
                    'party_id' => null,
                    'detail_account_id' => $data['gst_receivable_account_id'],
                    'description_en' => "GST of {$productNameEN} in {$projectNameEN} for {$partyNameEN}",
                    'description_ur' => "{$partyNameUR} کے لیے {$projectNameUR} میں {$productNameUR} کا جی ایس ٹی",
                    'document_number' => 'B-A' . '-' . $id,
                    'debit' => 0,
                    'credit' => $data['gst'],
                    'is_fee_entry' => 0,
                    'transaction_type' => null,
                ],
            ];
        }

        // 7E Chalan
        // if ($data['sevenE_chalan'] > 0) {
        //     $sevenEChalanEntries = [
        //         [
        //             'date' =>  $data['date'],
        //             'project_id' => $data['project_id'],
        //             'invoice_id' => $id,
        //             'party_id' => $data['party_id'],
        //             'detail_account_id' => $data['detail_account_id'],
        //             'description_en' => "7E Chalan of {$productNameEN} in {$projectNameEN} for {$partyNameEN}",
        //             'description_ur' => "{$partyNameUR} کے لیے {$projectNameUR} میں {$productNameUR} کا 7E چالان",
        //             'document_number' => 'B-A' . '-' . $id,
        //             'debit' => $data['sevenE_chalan'],
        //             'credit' => 0,
        //             'is_fee_entry' => 1,
        //         ],
        //         [
        //             'date' =>  $data['date'],
        //             'project_id' => $data['project_id'],
        //             'invoice_id' => $id,
        //             'party_id' => null,
        //             'detail_account_id' => $product,
        //             'description_en' => "7E Chalan of {$productNameEN} in {$projectNameEN} for {$partyNameEN}",
        //             'description_ur' => "{$partyNameUR} کے لیے {$projectNameUR} میں {$productNameUR} کا 7E چالان",
        //             'document_number' => 'B-A' . '-' . $id,
        //             'debit' => 0,
        //             'credit' => $data['sevenE_chalan'],
        //             'is_fee_entry' => 0,
        //         ],
        //         [
        //             'date' =>  $data['date'],
        //             'project_id' => $data['project_id'],
        //             'invoice_id' => $id,
        //             'party_id' => null,
        //             'detail_account_id' => $product,
        //             'description_en' => "7E Chalan of {$productNameEN} in {$projectNameEN} for {$partyNameEN}",
        //             'description_ur' => "{$partyNameUR} کے لیے {$projectNameUR} میں {$productNameUR} کا 7E چالان",
        //             'document_number' => 'B-A' . '-' . $id,
        //             'debit' => $data['sevenE_chalan'],
        //             'credit' => 0,
        //             'is_fee_entry' => 0,
        //         ],
        //         [
        //             'date' =>  $data['date'],
        //             'project_id' => $data['project_id'],
        //             'invoice_id' => $id,
        //             'party_id' => null,
        //             'detail_account_id' => $data['sevenE_chalan_receivable_account'],
        //             'description_en' => "7E Chalan of {$productNameEN} in {$projectNameEN} for {$partyNameEN}",
        //             'description_ur' => "{$partyNameUR} کے لیے {$projectNameUR} میں {$productNameUR} کا 7E چالان",
        //             'document_number' => 'B-A' . '-' . $id,
        //             'debit' => 0,
        //             'credit' => $data['sevenE_chalan'],
        //             'is_fee_entry' => 0,
        //         ],
        //     ];
        // }

        $nominees = [];
        if (isset($data['relation_id'])) {
            foreach ($data['relation_id'] as $i => $relationId) {
                $nominees[] = [
                    'relation_id'      => $relationId,
                    'nominee_party_id' => $data['nominee_party_id'][$i] ?? null,
                ];
            }
        }

        $schedules = [];
        $debitData = [];
        $generalJournalDebitData = [];
        if (isset($data['schedule_type_id'])) {
            foreach ($data['schedule_type_id'] as $i => $scheduleTypeId) {
                $paymentTypeNameEN = ScheduleType::where('id',  $scheduleTypeId)->value('title_en');
                $paymentTypeNameUR = ScheduleType::where('id',  $scheduleTypeId)->value('title_ur');
                $schedules[] = [
                    'schedule_type_id'    => $scheduleTypeId,
                    'schedule_period_id'  => $data['schedule_period_id'][$i] ?? null,
                    'due_date'            => $data['due_date'][$i] ?? null,
                    'number'              => $data['number'][$i] ?? 0,
                    'pay_amount'          => $data['pay_amount'][$i] ?? 0,
                    'calculated_total_amount'        => $data['calculated_total_amount'][$i] ?? 0,
                ];

                $debitData[] = [
                    'date' => $data['due_date'][$i],
                    'project_id' => $data['project_id'],
                    'invoice_id' => $id,
                    'party_id' => $data['party_id'],
                    'detail_account_id' => $data['detail_account_id'],
                    'description_en' => $paymentTypeNameEN . ' Payment on Sale Of ' . $productNameEN . ' of ' . $projectNameEN,
                    'description_ur' => $projectNameUR . ' کے ' . $productNameUR . ' پر فروخت کی ' . $paymentTypeNameUR . ' رقم ',
                    'document_number' => 'B-A' . '-' . $id,
                    'debit' => $data['calculated_total_amount'][$i] ?? 0,
                    'credit' => 0,
                    'transaction_type' => 'booking_payment',
                    'is_fee_entry' => 0,
                ];

                $generalJournalDebitData[] = [
                    'date' => $data['due_date'][$i] ?? null,
                    'project_id' => $data['project_id'],
                    'invoice_id' => $id,
                    'party_id' => $data['party_id'],
                    'detail_account_id' => $data['detail_account_id'],
                    'description_en' => $paymentTypeNameEN . ' Payment on Sale Of ' . $productNameEN . ' of ' . $projectNameEN,
                    'description_ur' => $projectNameUR . ' کے ' . $productNameUR . ' پر فروخت کی ' . $paymentTypeNameUR . ' رقم ',
                    'document_number' => 'B-A' . '-' . $id,
                    'debit' => $data['calculated_total_amount'][$i] ?? 0,
                    'credit' => 0,
                    'transaction_type' => 'booking_payment',
                    'is_fee_entry' => 0,
                ];
            }
        }

        return [
            'booking'  => $bookingData,
            'nominees' => $nominees,
            'stockLedgerData' => $stockLedgerData,
            'creditData' => $creditData,
            'debitData' => $debitData,
            'schedules' => $schedules,
            'receivableCommissionCreditData' => $receivableCommissionCreditData,
            'receivableCommissionDebitData' => $receivableCommissionDebitData,
            'receivableGeneralJournalsCommissionCreditData' => $receivableGeneralJournalsCommissionCreditData,
            'receivableGeneralJournalsCommissionDebitData' => $receivableGeneralJournalsCommissionDebitData,
            'commissionCreditData' => $commissionCreditData,
            'commissionDebitData' => $commissionDebitData,
            'discountDebitData' => $discountDebitData ?? null,
            'discountCreditData' => $discountCreditData ?? null,
            'generalJournalCreditData' => $generalJournalCreditData,
            'generalJournalsCommissionCreditData' => $generalJournalsCommissionCreditData,
            'generalJournalsCommissionDebitData' => $generalJournalsCommissionDebitData,
            'generalJournalDebitData' => $generalJournalDebitData,
            // 'sevenEChalanEntries' => $sevenEChalanEntries ?? [],
            'gstEntries' => $gstEntries ?? [],
            'developmentChargesEntries' => $developmentChargesEntries ?? [],
            'proceedingFeesEntries' => $proceedingFeesEntries ?? [],
            'possessionFeesEntries' => $possessionFeesEntries ?? [],

        ];
    }

    public function prepareTransferUpdateData(array $data, int $id): array
    {
        $bookingData = [
            'form_no'          => $data['form_no'],
            'party_id'         => $data['party_id'],
            'previous_booking_id'         => $data['previous_booking_id'],
            'case'         =>  $data['case'],
            'detail_account_id' => $data['detail_account_id'],
            'transfer_charges_account_id' => $data['transfer_charges_account_id'],
            'operating_start_date' => $data['operating_start_date'],
            'condition' => $data['condition'],
            'transfer_charges' => $data['transfer_charges'],
            'expense_account_id' => $data['expense_account_id'] ?? null,
            'discount_amount' => $data['discount_amount'] ?? 0,
            'operating_charges' => $data['operating_charges'],
            'project_id'       => $data['project_id'],
            'product_id'       => $data['product_id'],
            'status'       => $data['status'],
            'dealer_id'        => $data['dealer_id'],
            'care_off'        => $data['care_off'] ?? null,
            'date'             => $data['date'],
            'add_value'        => $data['add_value'] ?? 0,
            'discount'         => $data['discount'] ?? 0,
            'commission'       => $data['commission'] ?? 0,
            'total_amount'     => $data['total_amount'] ?? 0,
            'grand_total_amount'  => $data['grand_total_amount'] ?? 0,
            'possession_fees'   => $data['possession_fees'] ?? 0,
            'possession_receivable_account' => $data['possession_receivable_account'] ?? null,
            'proceeding_fees'   => $data['proceeding_fees'] ?? 0,
            'proceeding_receivable_account' => $data['proceeding_receivable_account'] ?? null,
            'development_charges' => $data['development_charges'] ?? 0,
            'development_receivable_id' => $data['development_receivable_id'] ?? null,
            'operating_receivable_account' => $data['operating_receivable_account'] ?? null,
            'gst'               => $data['gst'] ?? 0,
            'gst_receivable_account_id' => $data['gst_receivable_account_id'] ?? null,
            'sevenE_chalan'     => $data['sevenE_chalan'] ?? 0,
            'sevenE_chalan_receivable_account' => $data['sevenE_chalan_receivable_account'] ?? null,
        ];

        $nominees = [];
        if (isset($data['relation_id'])) {
            foreach ($data['relation_id'] as $i => $relationId) {
                $nominees[] = [
                    'relation_id'      => $relationId,
                    'nominee_party_id' => $data['nominee_party_id'][$i] ?? null,
                ];
            }
        }

        $schedules = [];
        if (isset($data['schedule_type_id'])) {
            foreach ($data['schedule_type_id'] as $i => $scheduleTypeId) {
                $schedules[] = [
                    'schedule_type_id'    => $scheduleTypeId,
                    'schedule_period_id'  => $data['schedule_period_id'][$i] ?? null,
                    'due_date'            => $data['due_date'][$i] ?? null,
                    'number'              => $data['number'][$i] ?? 0,
                    'pay_amount'          => $data['pay_amount'][$i] ?? 0,
                    'calculated_total_amount' => $data['calculated_total_amount'][$i] ?? 0,
                ];
            }
        }

        return [
            'booking'  => $bookingData,
            'nominees' => $nominees,
            'schedules' => $schedules,
        ];
    }
}
