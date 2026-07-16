<?php

namespace App\Services;

use App\Models\JournalEntry;
use App\Models\AccountLedger;
use App\Models\DetailAccount;
use App\Models\SubSubSubHead;
use App\Models\GeneralJournal;
use App\Models\JournalVoucher;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;


class JournalVoucherService
{
    protected $commonService;

    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }

    public function getById($id)
    {
        return JournalVoucher::findOrFail($id);
    }


    public function create(array $data): JournalVoucher
    {
        // Create the journalVoucher
        $journalVoucher = JournalVoucher::create($data);

        return $journalVoucher;
    }

    public function prepare(array $data): array
    {
        // Convert column-based arrays into row-wise entries
        $count = count($data['debit_detail_account_id'] ?? []);
        $entries = [];
        for ($i = 0; $i < $count; $i++) {
            $entries[] = [
                'debit_detail_account_id'  => $data['debit_detail_account_id'][$i] ?? null,
                'credit_detail_account_id' => $data['credit_detail_account_id'][$i] ?? null,
                'debit'                    => $data['debit'][$i] ?? 0,
                'credit'                   => $data['credit'][$i] ?? 0,
                'detail_description_en'    => $data['detail_description_en'][$i] ?? null,
                'detail_description_ur'    => $data['detail_description_ur'][$i] ?? null,
                'document_number'          => 'JV -' . ($data['id']),
            ];
        }


        // Prepare voucher data
        $voucherData = [
            'voucher_no'   => isset($data['id']) ? 'JV-' . $data['id'] : 'JV-' . time(),
            'voucher_date' => $data['voucher_date'] ?? now()->toDateString(),
            'description'  => $data['description'] ?? null,
            'total_debit'  => $data['total_debit'] ?? collect($entries)->sum('debit'),
            'total_credit' => $data['total_credit'] ?? collect($entries)->sum('credit'),
        ];

        return [
            'voucher' => $voucherData,
            'entries' => $entries,
        ];
    }

    /**
     * Store the journal voucher with entries, ledger, and general journal
     */
    public function store(array $preparedData)
    {

        return DB::transaction(function () use ($preparedData) {

            // Create Journal Voucher
            $voucher = JournalVoucher::create($preparedData['voucher']);

            // Loop through entries
            foreach ($preparedData['entries'] as $entry) {

                // Attach voucher id
                $entry['journal_voucher_id'] = $voucher->id;

                // Create Journal Entry
                $journalEntry = JournalEntry::create($entry);

                // Fetch related detail accounts
                $debitDetailAccount = DetailAccount::find($entry['debit_detail_account_id']);
                $creditDetailAccount = DetailAccount::find($entry['credit_detail_account_id']);

                // Fetch SubSubSubHead for project & party info
                $subSubSubHeadDebit = $debitDetailAccount
                    ? SubSubSubHead::find($debitDetailAccount->sub_sub_sub_head_id)
                    : null;

                $subSubSubHeadCredit = $creditDetailAccount
                    ? SubSubSubHead::find($creditDetailAccount->sub_sub_sub_head_id)
                    : null;

                // Account Ledger - Debit
                if ($entry['debit'] > 0 && $debitDetailAccount) {
                    AccountLedger::create([
                        'date'             => $preparedData['voucher']['voucher_date'],
                        'project_id'       => $subSubSubHeadDebit->project_id ?? null,
                        'invoice_id'       => $voucher->id,
                        'party_id'         => $debitDetailAccount->party_id ?? null,
                        'detail_account_id' => $entry['debit_detail_account_id'],
                        'description_en'   => $entry['detail_description_en'],
                        'description_ur'   => $entry['detail_description_ur'],
                        'document_number'  => 'JV-' . $voucher->id,
                        'debit'            => $entry['debit'],
                        'credit'           => 0,
                    ]);
                }

                // Account Ledger - Credit
                if ($entry['credit'] > 0 && $creditDetailAccount) {
                    AccountLedger::create([
                        'date'             => $preparedData['voucher']['voucher_date'],
                        'project_id'       => $subSubSubHeadCredit->project_id ?? null,
                        'invoice_id'       => $voucher->id,
                        'party_id'         => $creditDetailAccount->party_id ?? null,
                        'detail_account_id' => $entry['credit_detail_account_id'],
                        'description_en'   => $entry['detail_description_en'],
                        'description_ur'   => $entry['detail_description_ur'],
                        'document_number'  => 'JV-' . $voucher->id,
                        'debit'            => 0,
                        'credit'           => $entry['credit'],
                    ]);
                }

                // General Journal - Debit
                if ($entry['debit'] > 0 && $debitDetailAccount) {
                    GeneralJournal::create([
                        'date'             => $preparedData['voucher']['voucher_date'],
                        'project_id'       => $subSubSubHeadDebit->project_id ?? null,
                        'invoice_id'       => $voucher->id,
                        'party_id'         => $debitDetailAccount->party_id ?? null,
                        'detail_account_id' => $entry['debit_detail_account_id'],
                        'description_en'   => $entry['detail_description_en'],
                        'description_ur'   => $entry['detail_description_ur'],
                        'document_number'  => 'JV-' . $voucher->id,
                        'debit'            => $entry['debit'],
                        'credit'           => 0,
                    ]);
                }

                // General Journal - Credit
                if ($entry['credit'] > 0 && $creditDetailAccount) {
                    GeneralJournal::create([
                        'date'             => $preparedData['voucher']['voucher_date'],
                        'project_id'       => $subSubSubHeadCredit->project_id ?? null,
                        'invoice_id'       => $voucher->id,
                        'party_id'         => $creditDetailAccount->party_id ?? null,
                        'detail_account_id' => $entry['credit_detail_account_id'],
                        'description_en'   => $entry['detail_description_en'],
                        'description_ur'   => $entry['detail_description_ur'],
                        'document_number'  => 'JV-' . $voucher->id,
                        'debit'            => 0,
                        'credit'           => $entry['credit'],
                    ]);
                }
            }

            return $voucher;
        });
    }

    /**
     * Update the journal voucher with entries, ledger, and general journal
     */
    public function update(int $id, array $preparedData)
    {

        return DB::transaction(function () use ($id, $preparedData) {

            // Find existing voucher
            $voucher = JournalVoucher::findOrFail($id);

            // Update voucher main data
            $voucher->update($preparedData['voucher']);

            // Delete old Journal Entries
            JournalEntry::where('journal_voucher_id', $voucher->id)->delete();

            // Delete related Account Ledger records
            AccountLedger::where('invoice_id', $voucher->id)
                ->where('document_number', 'JV-' . $voucher->id)
                ->delete();

            // Delete related General Journal records
            GeneralJournal::where('invoice_id', $voucher->id)
                ->where('document_number', 'JV-' . $voucher->id)
                ->delete();

            // Recreate entries + ledger + general journal
            foreach ($preparedData['entries'] as $entry) {

                $entry['journal_voucher_id'] = $voucher->id;

                // Create Journal Entry
                $journalEntry = JournalEntry::create($entry);

                $debitDetailAccount = DetailAccount::find($entry['debit_detail_account_id']);
                $creditDetailAccount = DetailAccount::find($entry['credit_detail_account_id']);

                $subSubSubHeadDebit = $debitDetailAccount
                    ? SubSubSubHead::find($debitDetailAccount->sub_sub_sub_head_id)
                    : null;

                $subSubSubHeadCredit = $creditDetailAccount
                    ? SubSubSubHead::find($creditDetailAccount->sub_sub_sub_head_id)
                    : null;

                // Account Ledger - Debit
                if ($entry['debit'] > 0 && $debitDetailAccount) {
                    AccountLedger::create([
                        'date'              => $preparedData['voucher']['voucher_date'],
                        'project_id'        => $subSubSubHeadDebit->project_id ?? null,
                        'invoice_id'        => $voucher->id,
                        'party_id'          => $debitDetailAccount->party_id ?? null,
                        'detail_account_id' => $entry['debit_detail_account_id'],
                        'description_en'    => $entry['detail_description_en'],
                        'description_ur'    => $entry['detail_description_ur'],
                        'document_number'   => 'JV-' . $voucher->id,
                        'debit'             => $entry['debit'],
                        'credit'            => 0,
                    ]);
                }

                // Account Ledger - Credit
                if ($entry['credit'] > 0 && $creditDetailAccount) {
                    AccountLedger::create([
                        'date'              => $preparedData['voucher']['voucher_date'],
                        'project_id'        => $subSubSubHeadCredit->project_id ?? null,
                        'invoice_id'        => $voucher->id,
                        'party_id'          => $creditDetailAccount->party_id ?? null,
                        'detail_account_id' => $entry['credit_detail_account_id'],
                        'description_en'    => $entry['detail_description_en'],
                        'description_ur'    => $entry['detail_description_ur'],
                        'document_number'   => 'JV-' . $voucher->id,
                        'debit'             => 0,
                        'credit'            => $entry['credit'],
                    ]);
                }

                // General Journal - Debit
                if ($entry['debit'] > 0 && $debitDetailAccount) {
                    GeneralJournal::create([
                        'date'              => $preparedData['voucher']['voucher_date'],
                        'project_id'        => $subSubSubHeadDebit->project_id ?? null,
                        'invoice_id'        => $voucher->id,
                        'party_id'          => $debitDetailAccount->party_id ?? null,
                        'detail_account_id' => $entry['debit_detail_account_id'],
                        'description_en'    => $entry['detail_description_en'],
                        'description_ur'    => $entry['detail_description_ur'],
                        'document_number'   => 'JV-' . $voucher->id,
                        'debit'             => $entry['debit'],
                        'credit'            => 0,
                    ]);
                }

                // General Journal - Credit
                if ($entry['credit'] > 0 && $creditDetailAccount) {
                    GeneralJournal::create([
                        'date'              => $preparedData['voucher']['voucher_date'],
                        'project_id'        => $subSubSubHeadCredit->project_id ?? null,
                        'invoice_id'        => $voucher->id,
                        'party_id'          => $creditDetailAccount->party_id ?? null,
                        'detail_account_id' => $entry['credit_detail_account_id'],
                        'description_en'    => $entry['detail_description_en'],
                        'description_ur'    => $entry['detail_description_ur'],
                        'document_number'   => 'JV-' . $voucher->id,
                        'debit'             => 0,
                        'credit'            => $entry['credit'],
                    ]);
                }
            }

            return $voucher;
        });
    }

    private function generateVoucherNo(): string
    {
        return 'JV-' . now()->format('YmdHis');
    }

    /**
     * Delete the journal voucher with entries, ledger, and general journal
     */
    public function delete(int $id)
    {
        return DB::transaction(function () use ($id) {

            // Find existing voucher
            $voucher = JournalVoucher::findOrFail($id);

            // Delete related Journal Entries
            JournalEntry::where('journal_voucher_id', $voucher->id)->delete();

            // Delete related Account Ledger records
            AccountLedger::where('invoice_id', $voucher->id)
                ->where('document_number', 'JV-' . $voucher->id)
                ->delete();

            // Delete related General Journal records
            GeneralJournal::where('invoice_id', $voucher->id)
                ->where('document_number', 'JV-' . $voucher->id)
                ->delete();

            // Delete the voucher itself
            $voucher->delete();

            return true;
        });
    }

    public function prepareAccountDebitData($request, $voucherParentId)
    {
        return AccountLedger::create([
            'date' => Carbon::parse($request['date'])->format('Y-m-d'),
            'project_id' =>  $request['project_id'],
            'invoice_id' => $voucherParentId,
            'party_id' =>  null,
            'detail_account_id' =>  $request['cash_account_id'],
            'description_en' => $request['description_en'],
            'description_ur' => $request['description_ur'],
            'document_number' => 'CRV' . '-' . $voucherParentId,
            'debit' => $request['total_amount'],
            'credit' => config('constants.ZERO'),
        ]);
    }

    public function prepareAccountCreditData($request, $voucherParentId)
    {
        $partyId = DetailAccount::where('id', $request['detail_account_id'])->value('party_id');
        return AccountLedger::create([
            'date' => Carbon::parse($request['date'])->format('Y-m-d'),
            'project_id' =>  $request['project_id'],
            'invoice_id' => $voucherParentId,
            'party_id' =>  $partyId ?? null,
            'detail_account_id' => $request['detail_account_id'],
            'description_en' => $request['description_en'],
            'description_ur' => $request['description_ur'],
            'document_number' => 'CRV' . '-' . $voucherParentId,
            'debit' => config('constants.ZERO'),
            'credit' => $request['total_amount'],
        ]);
    }
}
