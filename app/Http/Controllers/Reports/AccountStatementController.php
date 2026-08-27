<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\AccountLedger;
use App\Models\DetailAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountStatementController extends Controller
{
    /**
     * Account Statement Filter Page
     */
    public function index()
    {
        return view('reports.account_statement.index');
    }


    /**
     * Generate Account Statement
     */
    public function report(Request $request)
    {
        $validated = $request->validate([
            'detail_account_id' => [
                'required',
                'exists:detail_accounts,id',
            ],

            'from_date' => [
                'required',
                'date',
            ],

            'to_date' => [
                'required',
                'date',
                'after_or_equal:from_date',
            ],
        ]);


        $accountId = $validated['detail_account_id'];
        $fromDate = $validated['from_date'];
        $toDate   = $validated['to_date'];


        /*
        |--------------------------------------------------------------------------
        | Get Account
        |--------------------------------------------------------------------------
        */

        $account = DetailAccount::with([
            'mainHead',
            'controlHead',
            'subHead',
            'subSubHead',
            'subSubSubHead',
            'party',
            'projects',
        ])->findOrFail($accountId);

        /*
        |--------------------------------------------------------------------------
        | Opening Balance
        |--------------------------------------------------------------------------
        |
        | All transactions BEFORE from_date.
        |
        */

        $openingData = AccountLedger::query()
            ->where('detail_account_id', $accountId)
            ->whereDate('date', '<', $fromDate)
            ->selectRaw('
                COALESCE(SUM(debit), 0) AS debit,
                COALESCE(SUM(credit), 0) AS credit
            ')
            ->first();


        $openingDebit = (float) ($openingData->debit ?? 0);
        $openingCredit = (float) ($openingData->credit ?? 0);


        /*
        |--------------------------------------------------------------------------
        | Opening Balance
        |--------------------------------------------------------------------------
        |
        | Debit - Credit
        |
        */

        $openingBalance = $openingDebit - $openingCredit;


        /*
        |--------------------------------------------------------------------------
        | Transactions
        |--------------------------------------------------------------------------
        */

        $transactions = AccountLedger::query()
            ->with([
                'party',
                'project',
            ])
            ->where('detail_account_id', $accountId)
            ->whereDate('date', '>=', $fromDate)
            ->whereDate('date', '<=', $toDate)
            ->orderBy('date', 'asc')
            ->orderBy('id', 'asc')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Running Balance
        |--------------------------------------------------------------------------
        */

        $runningBalance = $openingBalance;

        $totalDebit = 0;
        $totalCredit = 0;


        foreach ($transactions as $transaction) {

            $debit = (float) ($transaction->debit ?? 0);
            $credit = (float) ($transaction->credit ?? 0);

            $totalDebit += $debit;
            $totalCredit += $credit;


            $runningBalance += $debit;
            $runningBalance -= $credit;


            $transaction->running_balance = $runningBalance;


            /*
            |--------------------------------------------------------------------------
            | Balance Type
            |--------------------------------------------------------------------------
            */

            if ($runningBalance > 0) {

                $transaction->balance_type = 'Dr';
            } elseif ($runningBalance < 0) {

                $transaction->balance_type = 'Cr';
            } else {

                $transaction->balance_type = '';
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Closing Balance
        |--------------------------------------------------------------------------
        */

        $closingBalance =
            $openingBalance
            + $totalDebit
            - $totalCredit;


        /*
        |--------------------------------------------------------------------------
        | Closing Balance Type
        |--------------------------------------------------------------------------
        */

        if ($closingBalance > 0) {

            $closingBalanceType = 'Dr';
        } elseif ($closingBalance < 0) {

            $closingBalanceType = 'Cr';
        } else {

            $closingBalanceType = '';
        }


        /*
        |--------------------------------------------------------------------------
        | Report
        |--------------------------------------------------------------------------
        */

        return view(
            'reports.account_statement.report',
            compact(
                'account',
                'transactions',
                'fromDate',
                'toDate',
                'openingDebit',
                'openingCredit',
                'openingBalance',
                'totalDebit',
                'totalCredit',
                'closingBalance',
                'closingBalanceType'
            )
        );
    }
}
