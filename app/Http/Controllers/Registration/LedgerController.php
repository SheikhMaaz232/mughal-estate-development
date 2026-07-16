<?php

namespace App\Http\Controllers\Registration;

use App\Models\Party;
use Illuminate\Http\Request;
use App\Models\AccountLedger;
use App\Models\DetailAccount;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;

class LedgerController extends Controller
{
    public function viewPartyAccountLedger()
    {
        return view('ledgers.account-ledger.partyAccount-ledger-view');
    }

    public function getPartyAccountLedger(Request $request)
    {
        $searchParties = Party::orderBy('name_en')->get();
        $detailAccounts = DetailAccount::orderBy('name_en')->get();
        $ledgers = collect();

        $selectedParty = null;
        if ($request->filled('party_id')) {
            $selectedParty = Party::find($request->party_id);
        }

        // --- Search by Party ---
        if ($request->filled('party_id')) {

            // Fetch all related detail accounts for that party
            $accounts = DetailAccount::where('party_id', $request->party_id);

            if ($request->filled('detail_account_id')) {
                $accounts->whereIn('id', $request->detail_account_id);
            }

            $accounts = $accounts->get();

            $ledgers = $this->fetchLedgers($accounts, $request);
        }

        // --- Search by Detail Accounts only ---
        elseif ($request->filled('detail_account_id')) {
            $accounts = DetailAccount::whereIn('id', $request->detail_account_id)->get();
            $ledgers = $this->fetchLedgers($accounts, $request);
        }

        return view('ledgers.account-ledger.partyAccount-ledger', compact(
            'searchParties',
            'detailAccounts',
            'ledgers',
            'request',
            'selectedParty'
        ));
    }


    private function fetchLedgers(Collection $accounts, Request $request)
    {
        $data = collect();

        foreach ($accounts as $account) {
            $query = AccountLedger::where('detail_account_id', $account->id);

            if ($request->filled('from_date')) {
                $query->whereDate('date', '>=', $this->formatDate($request->from_date));
            }
            if ($request->filled('to_date')) {
                $query->whereDate('date', '<=', $this->formatDate($request->to_date));
            }

            $entries = $query->orderBy('date', 'asc')->get();

            if ($entries->isEmpty()) {
                continue;
            }

            $balance = 0;
            $rows = [];

            foreach ($entries as $entry) {
                $balance += ($entry->debit - $entry->credit);

                $rows[] = [
                    'date' => $entry->date,
                    'document_number' => $entry->document_number,
                    'description_en' => $entry->description_en ?? '-',
                    'description_ur' => $entry->description_ur ?? '-',
                    'debit' => $entry->debit,
                    'credit' => $entry->credit,
                    'balance' => $balance,
                    'is_fee_entry' => $entry->is_fee_entry,
                ];
            }

            $data->push([
                'account_name_en' => $account->name_en ?? '-',
                'account_name_ur' => $account->name_ur ?? '-',
                'party_name_en' => optional($account->party)->name_en ?? '-',
                'party_name_ur' => optional($account->party)->name_ur ?? '-',
                'entries' => $rows,
                'closing_balance' => $balance,
            ]);
        }

        return $data;
    }

    private function formatDate($date)
    {
        $parts = explode('-', $date);
        return count($parts) === 3 ? "{$parts[2]}-{$parts[1]}-{$parts[0]}" : $date;
    }
}
