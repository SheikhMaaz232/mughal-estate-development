<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\AccountLedger;
use App\Models\BookingPaymentShedule;
use App\Models\DetailAccount;
use App\Models\Party;
use App\Models\Product;
use App\Models\Project;
use Illuminate\Support\Collection;
use App\Models\StockLedger;
use App\Models\SubSubSubHead;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function viewRecoverySheet()
    {
        return view('reports.recovery-sheet.view');
    }

    public function getRecoveryReport(Request $request)
    {
        $projects = $request->project_id;
        $subSubSubHeads = SubSubSubHead::where('sub_head_id', 1)->whereIn('project_id', $projects)->pluck('id');
        $detailAccounts = DetailAccount::whereIn('sub_sub_sub_head_id', $subSubSubHeads)->pluck('id');
        // $ledgerData = AccountLedger::whereIn('detail_account_id', $detailAccounts)->get();
        // $recoveryAccounts = AccountLedger::selectRaw('
        //     detail_account_id,
        //     SUM(debit) as total_debit,
        //     SUM(credit) as total_credit,
        //     (SUM(debit) - SUM(credit)) as balance
        // ')
        //     ->whereIn('detail_account_id', $detailAccounts)
        //     ->groupBy('detail_account_id')
        //     ->havingRaw('SUM(debit) > SUM(credit)')
        //     ->with('detailAccount') // relationship required
        //     ->get();

        $recoveryAccounts = AccountLedger::selectRaw('
        detail_account_id,
        SUM(debit) as total_debit,
        SUM(credit) as total_credit,
        (SUM(debit) - SUM(credit)) as balance
            ')
            ->whereHas('detailAccount.subSubSubHead', function ($q) use ($request) {
                $q->where('sub_head_id', 1)
                    ->whereIn('project_id', $request->project_id);
            })
            ->groupBy('detail_account_id')
            ->havingRaw('SUM(debit) > SUM(credit)')
            ->with('detailAccount.subSubSubHead.projects')
            ->get()
            ->groupBy(function ($item) {
                return $item->detailAccount->subSubSubHead->projects->name_en ?? 'Unknown Project';
            });


        return view('reports.recovery-sheet.recoveryReport', compact('recoveryAccounts'));
    }

    public function viewBillAgingReport(Request $request)
    {
        $projects = Project::orderBy('name_en')->get();
        $parties = Party::orderBy('name_en')->get();

        return view('reports.bill-aging.view', compact('projects', 'parties', 'request'));
    }

    public function getBillAgingReport(Request $request)
    {

        $asOfDate = null;
        if ($request->filled('as_of_date')) {
            $dateValue = str_replace('/', '-', trim($request->as_of_date));
            try {
                $asOfDate = Carbon::createFromFormat('d-m-Y', $dateValue)->endOfDay();
            } catch (\Exception $e) {
                try {
                    $asOfDate = Carbon::parse($dateValue)->endOfDay();
                } catch (\Exception $e) {
                    $asOfDate = null;
                }
            }
        }

        $bookingSchedules = BookingPaymentShedule::with(['booking.party', 'booking.project', 'booking.detailAccount', 'schedulePeriod'])
            ->whereHas('booking', function ($query) use ($request) {
                if ($request->filled('project_id') && !in_array('all', (array) $request->project_id)) {
                    $query->whereIn('project_id', (array) $request->project_id);
                }
                if ($request->filled('party_id') && !in_array('all', (array) $request->party_id)) {
                    $query->whereIn('party_id', (array) $request->party_id);
                }
            })
            ->get();

        $expandedSchedules = $bookingSchedules->flatMap(function ($schedule) {

            $scheduleCount = max(1, (int) $schedule->number);
            $intervalType = strtolower(optional($schedule->schedulePeriod)->title_en ?? '');
            $startDate = Carbon::parse($schedule->due_date);

            return collect(range(0, $scheduleCount - 1))->map(function ($index) use ($schedule, $intervalType, $startDate) {
                $dueDate = $startDate->copy();

                switch (trim(strtolower($intervalType))) {

                    case 'monthly':
                        $dueDate->addMonths($index);
                        break;

                    case 'quarter':
                    case 'quarterly':
                        $dueDate->addMonths($index * 3);
                        break;

                    case 'half year':
                    case 'half-year':
                    case 'half yearly':
                        $dueDate->addMonths($index * 6);
                        break;

                    case 'yearly':
                    case 'year':
                    case 'annual':
                        $dueDate->addYears($index);
                        break;

                    case 'nine monthly':
                        $dueDate->addMonths($index * 9);
                        break;

                    case 'weekly':
                    case 'week':
                        $dueDate->addWeeks($index);
                        break;

                    case 'one time':
                    default:
                        if ($index > 0) {
                            return null;
                        }
                        break;
                }

                return (object) [
                    'party_id' => $schedule->booking->party_id,
                    'account_id' => $schedule->booking->detail_account_id,
                    'party' => $schedule->booking->party,
                    'account' => $schedule->booking->detailAccount,
                    'project' => $schedule->booking->project,
                    'pay_amount' => $schedule->pay_amount,
                    'due_date' => $dueDate,
                ];
            })->filter();
        });

        $partySchedules = $expandedSchedules->filter(function ($schedule) {
            return $schedule->party;
        })->groupBy(function ($item) {
            return $item->party_id . '_' . $item->account_id;
        })->map(function ($schedules) use ($asOfDate) {
            $party = $schedules->first()->party;
            $account = $schedules->first()->account;
            $projectNamesEn = $schedules->pluck('project.name_en')->unique()->filter()->values()->all();
            $projectNamesUr = $schedules->pluck('project.name_ur')->unique()->filter()->values()->all();
            $totalSchedule = $schedules->sum('pay_amount');
            $scheduledByDate = $asOfDate ? $schedules->reduce(function ($carry, $schedule) use ($asOfDate) {
                return $carry + ($schedule->due_date->endOfDay()->lte($asOfDate) ? $schedule->pay_amount : 0);
            }, 0) : 0;
            $scheduledAfterDate = $totalSchedule - $scheduledByDate;

            return (object) [
                'party_id' => $party->id,
                'account_id' => $account?->id,
                'account_name_en' => $account?->name_en ?? '',
                'account_name_ur' => $account?->name_ur ?? '',
                'party_name_en' => $party->name_en,
                'party_name_ur' => $party->name_ur,
                'project_names' => $projectNamesEn,
                'project_names_en' => $projectNamesEn,
                'project_names_ur' => $projectNamesUr,
                'total_schedule' => $totalSchedule,
                'scheduled_by_date' => $scheduledByDate,
                'scheduled_after_date' => $scheduledAfterDate,
            ];
        })->values();

        $ledgerAging = AccountLedger::with(['party', 'detailAccount'])
            ->when($request->filled('project_id') && !in_array('all', (array) $request->project_id), function ($query) use ($request) {
                $query->whereIn('project_id', (array) $request->project_id);
            })
            ->when($request->filled('party_id') && !in_array('all', (array) $request->party_id), function ($query) use ($request) {
                $query->whereIn('party_id', (array) $request->party_id);
            })
            ->when($asOfDate, function ($query) use ($asOfDate) {
                $query->whereDate('date', '<=', $asOfDate);
            })
            ->get()
            ->groupBy(function ($entry) {
                return $entry->party_id . '_' . $entry->detail_account_id;
            })->map(function ($entries) {

                $party = $entries->first()->party;
                $account = $entries->first()->detailAccount;

                $debit = $entries->sum('debit');
                $credit = $entries->sum('credit');
                $balance = $debit - $credit;

                return (object) [

                    'party_id' => $party?->id,
                    'account_id' => $account?->id,

                    'party_name_en' => $party?->name_en,
                    'party_name_ur' => $party?->name_ur,

                    'account_name_en' => $account?->name_en ?? '',
                    'account_name_ur' => $account?->name_ur ?? '',

                    'debit' => $debit,
                    'credit' => $credit,
                    'balance' => $balance,
                ];
            })
            ->values();

        $partyCredits = $ledgerAging
            ->keyBy(function ($item) {
                return $item->party_id . '_' . $item->account_id;
            })
            ->map(fn($item) => $item->credit)
            ->all();
        $partySchedules = $partySchedules->map(function ($schedule) use ($partyCredits) {
            $key = $schedule->party_id . '_' . $schedule->account_id;

            $credit = $partyCredits[$key] ?? 0;
            $schedule->credit = $credit;
            $schedule->till_date_short_payment = max(0, $schedule->scheduled_by_date - $credit);
            return $schedule;
        });

        $reportType = $request->input('report_type', 'all');
        if ($reportType === 'receivable') {
            $ledgerAging = $ledgerAging->filter(function ($item) {
                return $item->balance >= 0;
            })->values();
        } elseif ($reportType === 'payable') {
            $ledgerAging = $ledgerAging->filter(function ($item) {
                return $item->balance < 0;
            })->values();
        }

        return view('reports.bill-aging.billAgingReport', compact(
            'asOfDate',
            'reportType',
            'partySchedules',
            'ledgerAging'
        ));
    }

    public function viewTrialBalance(Request $request)
    {
        $projects = Project::orderBy('name_en')->get();

        return view('reports.trial-balance.view', compact('projects', 'request'));
    }

    public function getTrialBalance(Request $request)
    {
        $asOfDate = null;
        if ($request->filled('as_of_date')) {
            $dateValue = str_replace('/', '-', trim($request->as_of_date));
            try {
                $asOfDate = Carbon::createFromFormat('d-m-Y', $dateValue)->endOfDay();
            } catch (\Exception $e) {
                try {
                    $asOfDate = Carbon::parse($dateValue)->endOfDay();
                } catch (\Exception $e) {
                    $asOfDate = null;
                }
            }
        }

        $projectTrialBalances = AccountLedger::with(['project', 'detailAccount.mainHead'])
            ->when($request->filled('project_id') && !in_array('all', (array) $request->project_id), function ($query) use ($request) {
                $query->whereIn('project_id', (array) $request->project_id);
            })
            ->when($asOfDate, function ($query) use ($asOfDate) {
                $query->whereDate('date', '<=', $asOfDate);
            })
            ->get()
            ->filter(function ($entry) {
                return $entry->detailAccount !== null;
            })
            ->groupBy('project_id')
            ->map(function ($projectEntries) {
                $project = $projectEntries->first()->project;

                $entries = $projectEntries
                    ->groupBy('detail_account_id')
                    ->map(function ($entries) {
                        $account = $entries->first()->detailAccount;
                        $debit = $entries->sum('debit');
                        $credit = $entries->sum('credit');
                        $balance = $debit - $credit;

                        return (object) [
                            'detail_account_id' => $account->id,
                            'account_name_en' => $account->name_en,
                            'account_name_ur' => $account->name_ur,
                            'account_code' => $account->mainHead?->name_en ?? 'Unknown',
                            'main_head_id' => $account->mainHead?->id,
                            'main_head_en' => $account->mainHead?->name_en ?? 'Unknown',
                            'main_head_ur' => $account->mainHead?->name_ur ?? 'Unknown',
                            'debit' => $balance >= 0 ? $balance : 0,
                            'credit' => $balance < 0 ? abs($balance) : 0,
                            'balance' => $balance,
                        ];
                    })->filter(function ($item) {
                        return $item->debit > 0 || $item->credit > 0;
                    })->sortBy(function ($item) {
                        return sprintf('%03d-%s', $item->main_head_id ?? 0, $item->account_name_en);
                    })->values();

                return (object) [
                    'project_id' => $project->id ?? null,
                    'project_name_en' => $project->name_en ?? __('messages.all_projects'),
                    'project_name_ur' => $project->name_ur ?? __('messages.all_projects'),
                    'entries' => $entries,
                    'total_debit' => $entries->sum('debit'),
                    'total_credit' => $entries->sum('credit'),
                ];
            })->values();

        $totalDebit = $projectTrialBalances->sum('total_debit');
        $totalCredit = $projectTrialBalances->sum('total_credit');

        return view('reports.trial-balance.trialBalanceReport', compact(
            'asOfDate',
            'projectTrialBalances',
            'totalDebit',
            'totalCredit'
        ));
    }

    public function viewBalanceSheet(Request $request)
    {
        $projects = Project::orderBy('name_en')->get();

        return view('reports.balance-sheet.view', compact('projects', 'request'));
    }

    public function getBalanceSheet(Request $request)
    {
        $isUrdu = app()->getLocale() === 'ur';

        $asOfDate = null;

        if ($request->filled('as_of_date')) {
            $dateValue = str_replace('/', '-', trim($request->as_of_date));

            try {
                $asOfDate = Carbon::createFromFormat('d-m-Y', $dateValue)->endOfDay();
            } catch (\Exception $e) {
                try {
                    $asOfDate = Carbon::parse($dateValue)->endOfDay();
                } catch (\Exception $e) {
                    $asOfDate = null;
                }
            }
        }

        $ledgerEntries = AccountLedger::with(['project', 'detailAccount.mainHead'])
            ->when(
                $request->filled('project_id') && !in_array('all', (array)$request->project_id),
                function ($query) use ($request) {
                    $query->whereIn('project_id', (array)$request->project_id);
                }
            )
            ->when($asOfDate, function ($query) use ($asOfDate) {
                $query->where('date', '<=', $asOfDate);
            })
            ->get()
            ->filter(fn($e) => $e->detailAccount !== null);

        $projectWiseData = $ledgerEntries
            ->groupBy('project_id')
            ->map(function ($projectEntries) {

                $project = $projectEntries->first()->project;

                $accounts = $projectEntries
                    ->groupBy('detail_account_id')
                    ->map(function ($entries) {

                        $account = $entries->first()->detailAccount;

                        $debit = $entries->sum('debit');
                        $credit = $entries->sum('credit');
                        $balance = $debit - $credit;

                        return (object)[
                            'account_name_en' => $account->name_en,
                            'account_name_ur' => $account->name_ur,
                            'main_head_id' => $account->mainHead?->id,
                            'main_head_en' => $account->mainHead?->name_en,
                            'main_head_ur' => $account->mainHead?->name_ur,
                            'balance' => $balance,
                        ];
                    });

                // MAIN HEAD IDS (your DB)
                $assets = $accounts->where('main_head_id', 1)->sortBy('account_name_en')->values();
                $liabilities = $accounts->where('main_head_id', 2)->sortBy('account_name_en')->values();
                $income = $accounts->where('main_head_id', 3)->sortBy('account_name_en')->values();
                $expenses = $accounts->where('main_head_id', 4)->sortBy('account_name_en')->values();
                $equity = $accounts->where('main_head_id', 5)->sortBy('account_name_en')->values();

                // Profit Calculation
                $totalIncome = $income->sum(fn($a) => abs($a->balance));
                $totalExpenses = $expenses->sum(fn($a) => abs($a->balance));
                $netProfit = $totalIncome - $totalExpenses;

                $totalAssets = $assets->sum(fn($a) => abs($a->balance));
                $totalLiabilities = $liabilities->sum(fn($a) => abs($a->balance));
                $ownerEquity = $equity->sum(fn($a) => abs($a->balance));
                $totalEquity = $ownerEquity + $netProfit;

                return (object)[
                    'project_name_en' => $project->name_en ?? 'Unknown',
                    'project_name_ur' => $project->name_ur ?? 'نامعلوم',
                    'assets' => $assets,
                    'liabilities' => $liabilities,
                    'equity' => $equity,
                    'income' => $income,
                    'expenses' => $expenses,
                    'total_assets' => $totalAssets,
                    'total_liabilities' => $totalLiabilities,
                    'total_equity' => $totalEquity,
                    'owner_equity' => $ownerEquity,
                    'net_profit' => $netProfit,
                ];
            })
            ->values();

        // GRAND TOTALS
        $grandAssets = $projectWiseData->sum('total_assets');
        $grandLiabilities = $projectWiseData->sum('total_liabilities');
        $grandOwnerEquity = $projectWiseData->sum('owner_equity');
        $grandNetProfit = $projectWiseData->sum('net_profit');
        $grandEquity = $projectWiseData->sum('total_equity');

        return view('reports.balance-sheet.balanceSheetReport', compact(
            'projectWiseData',
            'grandAssets',
            'grandLiabilities',
            'grandOwnerEquity',
            'grandNetProfit',
            'grandEquity',
            'asOfDate',
            'isUrdu'
        ));
    }

    public function stockReportFilter()
    {
        $projects = Project::orderBy('name_en')->get();
        $products = Product::where('type', 'item')->orderBy('name_en')->get();

        return view(
            'reports.stock.stockReportFilter',
            compact('projects', 'products')
        );
    }

    public function stockReport(Request $request)
    {
        $projects = Project::orderBy('name_en')->get();

        $products = Product::where('type', 'item')->orderBy('name_en')->get();
        $query = StockLedger::with(['project', 'product'])
            ->whereHas('product', function ($q) {
                $q->where('type', 'item');
            });

        if ($request->filled('project_id') && $request->project_id != 'all') {
            $query->where('project_id', $request->project_id);
        }

        if ($request->filled('product_id') && $request->product_id != 'all') {
            $query->where('product_id', $request->product_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', Carbon::parse($request->date_from));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', Carbon::parse($request->date_to));
        }

        $stockLedger = $query
            ->orderBy('date')
            ->orderBy('id')
            ->get();

        $runningBalance = 0;

        foreach ($stockLedger as $row) {

            $runningBalance +=
                ($row->stock_in_quantity - $row->stock_out_quantity);

            $row->balance = $runningBalance;
        }

        $totalIn = $stockLedger->sum('stock_in_quantity');
        $totalOut = $stockLedger->sum('stock_out_quantity');

        $closingStock = $totalIn - $totalOut;

        return view(
            'reports.stock.stockReport',
            compact(
                'projects',
                'products',
                'stockLedger',
                'totalIn',
                'totalOut',
                'closingStock'
            )
        );
    }

    public function availablePlotsReportFilter()
    {
        $projects = Project::orderBy('name_en')->get();

        $products = Product::where('type', 'Direct')
            ->where('status', '!=', 'Booked')
            ->orderBy('name_en')
            ->get();

        return view(
            'reports.availablePlots.filter',
            compact('projects', 'products')
        );
    }

    public function availablePlotsReport(Request $request)
    {
        $query = Product::with('project')
            ->where('type', 'Direct')
            ->where('status', '!=', 'Booked');

        // Multiple Projects
        if (
            $request->filled('project_id')
            && !in_array('all', (array)$request->project_id)
        ) {
            $query->whereIn(
                'project_id',
                (array)$request->project_id
            );
        }

        // Multiple Products
        if (
            $request->filled('product_id')
            && !in_array('all', (array)$request->product_id)
        ) {
            $query->whereIn(
                'id',
                (array)$request->product_id
            );
        }

        $products = $query
            ->orderBy('project_id')
            ->orderBy('unit_no')
            ->get();

        $groupedProjects = $products->groupBy('project_id');

        $grandTotalMarla = $products->sum('total_marla');

        return view(
            'reports.availablePlots.report',
            compact(
                'groupedProjects',
                'grandTotalMarla'
            )
        );
    }


    public function viewBankBook()
    {
        return view('reports.bankBook.bank-book-view');
    }

    public function getBankBookLedger(Request $request)
    {
        $searchParties = Party::orderBy('name_en')->get();
        $detailAccounts = DetailAccount::orderBy('name_en')->get();
        $ledger = collect();

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

            $ledger = $this->fetchCombinedLedger($accounts, $request);
        }

        // --- Search by Detail Accounts only ---
        elseif ($request->filled('detail_account_id')) {
            $accounts = DetailAccount::whereIn('id', $request->detail_account_id)->get();
            $ledger = $this->fetchCombinedLedger($accounts, $request);
        }

        return view('reports.bankBook.bank-book-report', compact(
            'searchParties',
            'detailAccounts',
            'ledger',
            'request',
            'selectedParty'
        ));
    }


    private function fetchCombinedLedger(Collection $accounts, Request $request)
    {
        $entries = collect();

        $openingBalance = 0;

        foreach ($accounts as $account) {

            // Opening Balance
            if ($request->filled('from_date')) {

                $openingEntries = AccountLedger::where('detail_account_id', $account->id)
                    ->whereDate('date', '<', $this->formatDate($request->from_date))
                    ->get();

                $openingBalance += $openingEntries->sum(function ($item) {
                    return $item->debit - $item->credit;
                });
            }

            // Period Entries
            $query = AccountLedger::where('detail_account_id', $account->id);

            if ($request->filled('from_date')) {
                $query->whereDate('date', '>=', $this->formatDate($request->from_date));
            }

            if ($request->filled('to_date')) {
                $query->whereDate('date', '<=', $this->formatDate($request->to_date));
            }

            foreach ($query->get() as $entry) {

                $entries->push([
                    'date'            => $entry->date,
                    'document_number' => $entry->document_number,
                    'description_en'  => $entry->description_en,
                    'description_ur'  => $entry->description_ur,
                    'debit'           => $entry->debit,
                    'credit'          => $entry->credit,
                    'is_fee_entry'    => $entry->is_fee_entry,
                ]);
            }
        }

        // ASC Order
        $entries = $entries->sortBy('date')->values();

        // Running Balance starts from Opening Balance
        $balance = $openingBalance;

        $entries = $entries->map(function ($row) use (&$balance) {

            $balance += ($row['debit'] - $row['credit']);

            $row['balance'] = $balance;

            return $row;
        });

        return [
            'opening_balance' => $openingBalance,
            'entries'         => $entries,
            'closing_balance' => $balance,
            'total_debit'     => $entries->sum('debit'),
            'total_credit'    => $entries->sum('credit'),
        ];
    }

    private function formatDate($date)
    {
        $parts = explode('-', $date);
        return count($parts) === 3 ? "{$parts[2]}-{$parts[1]}-{$parts[0]}" : $date;
    }
}
