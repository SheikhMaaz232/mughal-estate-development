<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\AccountLedger;
use App\Models\BookingApplication;
use App\Models\BookingPaymentShedule;
use App\Models\DetailAccount;
use App\Models\Party;
use App\Models\Product;
use App\Models\Project;
use App\Models\StockLedger;
use App\Models\SubSubSubHead;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use OwenIt\Auditing\Models\Audit;

class ReportController extends Controller
{

    private function getMasterData()
    {
        return [
            'projects' => Cache::remember('projects_data', 3600, fn() =>
            \App\Models\Project::select('id', 'name_en', 'name_ur')->get()),

            'searchParties' => Cache::remember('parties_data', 3600, fn() =>
            \App\Models\Party::with('cast')->select('id', 'name_en', 'name_ur', 'cnic_no', 'contact_number_1', 'cast_id')->get()),

            'detailAccounts' => Cache::remember('detail_accounts_data', 3600, fn() =>
            DetailAccount::select('id', 'name_en', 'name_ur')->get()),
        ];
    }

    public function viewRecoverySheet()
    {
        return view('reports.recovery-sheet.view', $this->getMasterData());
    }

    public function getRecoveryReport(Request $request)
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

        $project = Project::find($request->project_id);

        $bookingSchedules = BookingPaymentShedule::with(['booking.party', 'booking.product', 'booking.project', 'booking.detailAccount', 'schedulePeriod'])
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
                    'product' => $schedule->booking->product,
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
            $product = $schedules->first()->product;
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
                'party_phone_no_1' => $party->contact_number_1,
                'party_phone_no_2' => $party->contact_number_2,
                'party_name_ur' => $party->name_ur,
                'product_name_en' => $product->name_en,
                'product_name_ur' => $product->name_ur,
                'product_size' => $product->total_marla,
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
        })->filter(function ($schedule) {
            return $schedule->till_date_short_payment != 0;
        })
            ->values();

        return view('reports.recovery-sheet.recoveryReport', compact(
            'asOfDate',
            'partySchedules',
            'project'
        ));
    }

    // public function getRecoveryReport(Request $request)
    // {
    //     $projects = $request->project_id;
    //     $subSubSubHeads = SubSubSubHead::where('sub_head_id', 1)->whereIn('project_id', $projects)->pluck('id');
    //     $detailAccounts = DetailAccount::whereIn('sub_sub_sub_head_id', $subSubSubHeads)->pluck('id');
    //     // $ledgerData = AccountLedger::whereIn('detail_account_id', $detailAccounts)->get();
    //     // $recoveryAccounts = AccountLedger::selectRaw('
    //     //     detail_account_id,
    //     //     SUM(debit) as total_debit,
    //     //     SUM(credit) as total_credit,
    //     //     (SUM(debit) - SUM(credit)) as balance
    //     // ')
    //     //     ->whereIn('detail_account_id', $detailAccounts)
    //     //     ->groupBy('detail_account_id')
    //     //     ->havingRaw('SUM(debit) > SUM(credit)')
    //     //     ->with('detailAccount') // relationship required
    //     //     ->get();

    //     $recoveryAccounts = AccountLedger::selectRaw('
    //     detail_account_id,
    //     SUM(debit) as total_debit,
    //     SUM(credit) as total_credit,
    //     (SUM(debit) - SUM(credit)) as balance
    //         ')
    //         ->whereHas('detailAccount.subSubSubHead', function ($q) use ($request) {
    //             $q->where('sub_head_id', 1)
    //                 ->whereIn('project_id', $request->project_id);
    //         })
    //         ->groupBy('detail_account_id')
    //         ->havingRaw('SUM(debit) > SUM(credit)')
    //         ->with('detailAccount.subSubSubHead.projects')
    //         ->get()
    //         ->groupBy(function ($item) {
    //             return $item->detailAccount->subSubSubHead->projects->name_en ?? 'Unknown Project';
    //         });


    //     return view('reports.recovery-sheet.recoveryReport', compact('recoveryAccounts'));
    // }

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

    public function viewFinancialPosition(Request $request)
    {
        $projects = Project::orderBy('name_en')->get();

        return view('reports.financial-position.view', compact('projects', 'request'));
    }

    public function viewAuditControl(Request $request)
    {
        $users = \App\Models\User::orderBy('name_en')->get(['id', 'name_en', 'name_ur']);
        $models = Audit::query()->select('auditable_type')->distinct()->orderBy('auditable_type')->pluck('auditable_type');
        $events = Audit::query()->select('event')->distinct()->orderBy('event')->pluck('event');

        return view('reports.audit-control.view', compact('users', 'models', 'events', 'request'));
    }

    public function getAuditControl(Request $request)
    {
        $request->validate([
            'from_date' => ['required', 'date'],
            'to_date' => ['required', 'date', 'after_or_equal:from_date'],
            'user_id' => ['nullable', 'integer'],
            'model' => ['nullable', 'string'],
            'event' => ['nullable', 'string'],
        ]);

        $fromDate = Carbon::parse($request->input('from_date'))->startOfDay();
        $toDate = Carbon::parse($request->input('to_date'))->endOfDay();
        $isUrdu = app()->getLocale() === 'ur';
        $audits = Audit::with('user')
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->when($request->filled('user_id'), fn ($query) => $query->where('user_id', $request->input('user_id')))
            ->when($request->filled('model'), fn ($query) => $query->where('auditable_type', $request->input('model')))
            ->when($request->filled('event'), fn ($query) => $query->where('event', $request->input('event')))
            ->latest()
            ->get();

        $eventCounts = $audits->groupBy('event')->map->count();
        $modelCounts = $audits->groupBy('auditable_type')->map->count()->sortDesc();
        $userCounts = $audits->groupBy('user_id')->map->count()->sortDesc();

        return view('reports.audit-control.report', compact(
            'audits', 'eventCounts', 'modelCounts', 'userCounts', 'fromDate', 'toDate', 'isUrdu'
        ));
    }

    public function getFinancialPosition(Request $request)
    {
        $request->validate([
            'as_of_date' => ['required', 'date'],
            'project_id' => ['nullable', 'array'],
        ]);

        $asOfDate = Carbon::parse($request->input('as_of_date'))->endOfDay();
        $isUrdu = app()->getLocale() === 'ur';
        $projectIds = array_values(array_filter((array) $request->input('project_id', []), fn ($id) => $id !== 'all' && $id !== ''));

        $entries = AccountLedger::with(['project', 'detailAccount.mainHead'])
            ->whereDate('date', '<=', $asOfDate)
            ->when($projectIds, fn ($query) => $query->whereIn('project_id', $projectIds))
            ->get()
            ->filter(fn ($entry) => $entry->detailAccount !== null);

        $projectWiseData = $entries->groupBy('project_id')->map(function ($projectEntries) {
            $project = $projectEntries->first()->project;
            $accounts = $projectEntries->groupBy('detail_account_id')->map(function ($accountEntries) {
                $account = $accountEntries->first()->detailAccount;
                return (object) [
                    'name_en' => $account->name_en,
                    'name_ur' => $account->name_ur,
                    'main_head_id' => $account->mainHead?->id,
                    'balance' => $accountEntries->sum('debit') - $accountEntries->sum('credit'),
                ];
            })->filter(fn ($account) => $account->balance != 0)->sortBy('name_en')->values();

            $assets = $accounts->where('main_head_id', 1)->values();
            $liabilities = $accounts->where('main_head_id', 2)->values();
            $equity = $accounts->where('main_head_id', 5)->values();
            $income = $accounts->where('main_head_id', 3)->values();
            $expenses = $accounts->where('main_head_id', 4)->values();
            $totalAssets = $assets->sum(fn ($account) => max(0, $account->balance));
            $totalLiabilities = $liabilities->sum(fn ($account) => max(0, -$account->balance));
            $ownerEquity = $equity->sum(fn ($account) => max(0, -$account->balance));
            $retainedEarnings = $income->sum(fn ($account) => -$account->balance) - $expenses->sum(fn ($account) => $account->balance);
            $totalEquity = $ownerEquity + $retainedEarnings;

            return (object) [
                'project_name_en' => $project?->name_en ?? __('messages.all_projects'),
                'project_name_ur' => $project?->name_ur ?? __('messages.all_projects'),
                'assets' => $assets,
                'liabilities' => $liabilities,
                'equity' => $equity,
                'total_assets' => $totalAssets,
                'total_liabilities' => $totalLiabilities,
                'owner_equity' => $ownerEquity,
                'retained_earnings' => $retainedEarnings,
                'total_equity' => $totalEquity,
                'liabilities_and_equity' => $totalLiabilities + $totalEquity,
                'difference' => $totalAssets - ($totalLiabilities + $totalEquity),
            ];
        })->values();

        $totalAssets = $projectWiseData->sum('total_assets');
        $totalLiabilities = $projectWiseData->sum('total_liabilities');
        $ownerEquity = $projectWiseData->sum('owner_equity');
        $retainedEarnings = $projectWiseData->sum('retained_earnings');
        $totalEquity = $projectWiseData->sum('total_equity');
        $liabilitiesAndEquity = $projectWiseData->sum('liabilities_and_equity');
        $difference = $totalAssets - $liabilitiesAndEquity;

        return view('reports.financial-position.report', compact(
            'projectWiseData', 'totalAssets', 'totalLiabilities', 'ownerEquity',
            'retainedEarnings', 'totalEquity', 'liabilitiesAndEquity', 'difference',
            'asOfDate', 'isUrdu'
        ));
    }

    public function viewProfitLoss(Request $request)
    {
        $projects = Project::orderBy('name_en')->get();

        return view('reports.profit-loss.view', compact('projects', 'request'));
    }

    public function getProfitLoss(Request $request)
    {
        $request->validate([
            'from_date' => ['required', 'date'],
            'to_date' => ['required', 'date', 'after_or_equal:from_date'],
            'project_id' => ['nullable', 'array'],
            'project_id.*' => ['nullable'],
        ]);

        $fromDate = $request->filled('from_date')
            ? Carbon::parse($request->input('from_date'))->startOfDay()
            : Carbon::now()->startOfYear()->startOfDay();
        $toDate = $request->filled('to_date')
            ? Carbon::parse($request->input('to_date'))->endOfDay()
            : Carbon::now()->endOfDay();
        $isUrdu = app()->getLocale() === 'ur';

        $projectIds = (array) $request->input('project_id', []);
        $projectIds = array_values(array_filter($projectIds, fn ($id) => $id !== 'all' && $id !== null && $id !== ''));

        $entries = AccountLedger::with(['project', 'detailAccount.mainHead'])
            ->whereBetween('date', [$fromDate, $toDate])
            ->when($projectIds, fn ($query) => $query->whereIn('project_id', $projectIds))
            ->get()
            ->filter(fn ($entry) => in_array($entry->detailAccount?->main_head_id, [3, 4], true));

        $projectWiseData = $entries->groupBy('project_id')->map(function ($projectEntries) {
            $project = $projectEntries->first()->project;
            $accounts = $projectEntries->groupBy('detail_account_id')->map(function ($accountEntries) {
                $account = $accountEntries->first()->detailAccount;
                $income = $account->main_head_id === 3;
                $balance = $income
                    ? $accountEntries->sum('credit') - $accountEntries->sum('debit')
                    : $accountEntries->sum('debit') - $accountEntries->sum('credit');

                return (object) [
                    'name_en' => $account->name_en,
                    'name_ur' => $account->name_ur,
                    'main_head_id' => $account->main_head_id,
                    'amount' => $balance,
                ];
            })->filter(fn ($account) => $account->amount != 0)->sortBy('name_en')->values();

            $income = $accounts->where('main_head_id', 3)->values();
            $expenses = $accounts->where('main_head_id', 4)->values();
            $totalIncome = $income->sum('amount');
            $totalExpenses = $expenses->sum('amount');

            return (object) [
                'project_id' => $project?->id,
                'project_name_en' => $project?->name_en ?? __('messages.all_projects'),
                'project_name_ur' => $project?->name_ur ?? __('messages.all_projects'),
                'income' => $income,
                'expenses' => $expenses,
                'total_income' => $totalIncome,
                'total_expenses' => $totalExpenses,
                'gross_profit' => $totalIncome - $totalExpenses,
            ];
        })->values();

        $totalIncome = $projectWiseData->sum('total_income');
        $totalExpenses = $projectWiseData->sum('total_expenses');
        $netProfit = $totalIncome - $totalExpenses;

        return view('reports.profit-loss.report', compact(
            'projectWiseData',
            'totalIncome',
            'totalExpenses',
            'netProfit',
            'fromDate',
            'toDate',
            'isUrdu'
        ));
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
        $products = Product::where('type', 'item')->orderBy('name_en')->get();

        return view(
            'reports.stock.stockReportFilter',
            array_merge(
                $this->getMasterData(),
                compact('products')
            )
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

        $products = Product::where('type', 'Direct')
            ->where('status', '!=', 'Booked')
            ->orderBy('name_en')
            ->get();

        return view(
            'reports.availablePlots.filter',
            array_merge(
                $this->getMasterData(),
                compact('products')
            )
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
            compact('groupedProjects', 'grandTotalMarla')
        );
    }

    public function directProductProjectReportFilter()
    {

        return view('exective_reports.direct_products.filterFile', $this->getMasterData());
    }

    public function directProductProjectReport(Request $request)
    {
        $query = Product::with('project')
            ->where('type', 'Direct');

        if ($request->filled('project_id') && !in_array('all', (array) $request->project_id)) {
            $query->whereIn('project_id', (array) $request->project_id);
        }

        $products = $query
            ->orderBy('project_id')
            ->orderBy('unit_no')
            ->get();

        $groupedProjects = $products->groupBy('project_id');

        $bookedProductIds = $products
            ->filter(fn($product) => strtolower((string) $product->status) === 'booked')
            ->pluck('id')
            ->values();

        $bookedAmountByProject = BookingApplication::whereIn('product_id', $bookedProductIds)
            ->selectRaw('project_id, SUM(total_amount) as total_amount')
            ->groupBy('project_id')
            ->get()
            ->mapWithKeys(function ($row) {
                return [$row->project_id => (float) $row->total_amount];
            });



        $bookingApplications = BookingApplication::whereIn(
            'product_id',
            $bookedProductIds
        )
            ->whereNotNull('detail_account_id')
            ->get([
                'id',
                'product_id',
                'project_id',
                'detail_account_id'
            ]);


        $detailAccountIds = $bookingApplications
            ->pluck('detail_account_id')
            ->filter()
            ->unique()
            ->values();

        $receivedAmountByAccount = AccountLedger::whereIn(
            'detail_account_id',
            $detailAccountIds
        )
            ->where('transaction_type', 'booking_payment')
            ->selectRaw('detail_account_id, SUM(credit) as received_amount')
            ->groupBy('detail_account_id')
            ->get()
            ->mapWithKeys(function ($row) {
                return [
                    $row->detail_account_id => (float) $row->received_amount
                ];
            });

        /*
    |--------------------------------------------------------------------------
    | Received Amount By Project
    |--------------------------------------------------------------------------
    */

        $receivedAmountByProject = $bookingApplications
            ->groupBy('project_id')
            ->mapWithKeys(function ($applications, $projectId) use ($receivedAmountByAccount) {

                $receivedAmount = $applications->sum(function ($application) use ($receivedAmountByAccount) {

                    return $receivedAmountByAccount[$application->detail_account_id] ?? 0;
                });

                return [
                    $projectId => (float) $receivedAmount
                ];
            });

        $grandTotals = [
            'marla_all' => $products->sum('total_marla'),
            'amount_all' => $products->sum('total_amount'),
            'marla_booked' => $products->filter(fn($product) => strtolower((string) $product->status) === 'booked')->sum('total_marla'),
            'amount_booked' => $bookedAmountByProject->sum(),
            'amount_received' => $receivedAmountByProject->sum(),
            'marla_verified' => $products->filter(fn($product) => strtolower((string) $product->status) === 'verified')->sum('total_marla'),
            'amount_verified' => $products->filter(fn($product) => strtolower((string) $product->status) === 'verified')->sum('total_amount'),
        ];

        return view('exective_reports.direct_products.report', compact(
            'groupedProjects',
            'grandTotals',
            'bookedAmountByProject',
            'receivedAmountByProject'
        ));
    }


    public function viewBankBook()
    {
        return view('reports.bankBook.bank-book-view', $this->getMasterData());
    }

    public function getBankBookLedger(Request $request)
    {
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

        return view('reports.bankBook.bank-book-report', array_merge(
            $this->getMasterData(),
            compact('ledger', 'request', 'selectedParty')
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

    public function bookingPaymentProducts(Request $request)
    {
        $projectIds = (array) $request->project_id;

        /*
    |--------------------------------------------------------------------------
    | Remove "all"
    |--------------------------------------------------------------------------
    */

        $projectIds = array_filter(
            $projectIds,
            function ($id) {
                return $id !== 'all'
                    && $id !== null
                    && $id !== '';
            }
        );


        /*
    |--------------------------------------------------------------------------
    | Product Query
    |--------------------------------------------------------------------------
    */

        $query = Product::query();


        /*
    |--------------------------------------------------------------------------
    | If specific projects selected
    |--------------------------------------------------------------------------
    */

        if (!empty($projectIds)) {

            $query->where('type', 'Direct')->whereIn(
                'project_id',
                $projectIds
            );
        }


        /*
    |--------------------------------------------------------------------------
    | Get Products
    |--------------------------------------------------------------------------
    */

        $products = $query
            ->orderBy('project_id')
            ->orderBy('unit_no')
            ->get([
                'id',
                'project_id',
                'unit_no',
                'name_en',
                'name_ur'
            ]);


        return response()->json([
            'products' => $products
        ]);
    }

    public function bookingPaymentReportFilter()
    {
        return view(
            'exective_reports.booking_report.filterFile',
            $this->getMasterData()
        );
    }

    public function bookingPaymentReport(Request $request)
    {
        $query = BookingApplication::with([
            'project',
            'party',
            'product',
        ]);

        /*
    |--------------------------------------------------------------------------
    | ACTIVE BOOKINGS
    |--------------------------------------------------------------------------
    */

        $query->where(function ($q) {
            $q->whereNull('case')
                ->orWhereNotIn('case', [
                    'transfer',
                    'ownership_changed',
                ]);
        });

        $query->where(function ($q) {
            $q->whereNull('status')
                ->orWhereRaw('LOWER(status) != ?', ['cancelled']);
        });


        /*
    |--------------------------------------------------------------------------
    | PROJECT FILTER
    |--------------------------------------------------------------------------
    */

        if (
            $request->filled('project_id') &&
            !in_array('all', (array) $request->project_id)
        ) {
            $query->whereIn(
                'project_id',
                (array) $request->project_id
            );
        }


        /*
    |--------------------------------------------------------------------------
    | PRODUCT FILTER
    |--------------------------------------------------------------------------
    */

        if (
            $request->filled('product_id') &&
            !in_array('all', (array) $request->product_id)
        ) {
            $query->whereIn(
                'product_id',
                (array) $request->product_id
            );
        }


        /*
    |--------------------------------------------------------------------------
    | GET BOOKINGS
    |--------------------------------------------------------------------------
    */

        $bookings = $query
            ->orderBy('project_id')
            ->orderBy('product_id')
            ->orderBy('date')
            ->get();


        /*
    |--------------------------------------------------------------------------
    | GROUP BY PROJECT
    |--------------------------------------------------------------------------
    */

        $groupedBookings = $bookings->groupBy('project_id');


        /*
    |--------------------------------------------------------------------------
    | RECEIVED AMOUNT
    |--------------------------------------------------------------------------
    |
    | BookingApplication.detail_account_id
    |              ↓
    | AccountLedger.detail_account_id
    |              ↓
    | transaction_type = booking_payment
    |              ↓
    | SUM(credit)
    |
    */

        $detailAccountIds = $bookings
            ->whereNotNull('detail_account_id')
            ->pluck('detail_account_id')
            ->unique()
            ->values();


        $receivedAmountByAccount = AccountLedger::whereIn(
            'detail_account_id',
            $detailAccountIds
        )
            ->where('transaction_type', 'booking_payment')
            ->selectRaw(
                'detail_account_id, SUM(credit) as received_amount'
            )
            ->groupBy('detail_account_id')
            ->get()
            ->mapWithKeys(function ($row) {

                return [
                    $row->detail_account_id =>
                    (float) $row->received_amount
                ];
            });


        /*
    |--------------------------------------------------------------------------
    | GRAND TOTALS
    |--------------------------------------------------------------------------
    */

        $totalBookingAmount = $bookings->sum('total_amount');

        $totalReceivedAmount = $bookings->sum(function ($booking) use (
            $receivedAmountByAccount
        ) {

            return $receivedAmountByAccount[$booking->detail_account_id] ?? 0;
        });


        $grandTotals = [

            'bookings' => $bookings->count(),

            'booking_amount' => $totalBookingAmount,

            'received_amount' => $totalReceivedAmount,

            'remaining_amount' =>
            $totalBookingAmount - $totalReceivedAmount,

        ];


        return view(
            'exective_reports.booking_report.report',
            compact(
                'groupedBookings',
                'receivedAmountByAccount',
                'grandTotals'
            )
        );
    }

    public function bookingPaymentReport2(Request $request)
    {
        /*
    |--------------------------------------------------------------------------
    | Booking Application Query
    |--------------------------------------------------------------------------
    */

        $query = BookingApplication::with([
            'project',
            'product',
            'party',
            'detailAccount'
        ]);

        /*
    |--------------------------------------------------------------------------
    | ACTIVE BOOKINGS ONLY
    |--------------------------------------------------------------------------
    |
    | Case should NOT be transfer
    | Status should NOT be Cancelled
    |
    | NULL is also treated as active.
    |
    */

        $query->where(function ($q) {
            $q->whereNull('case')
                ->orWhere(function ($q) {
                    $q->where('case', '!=', 'transfer')
                        ->where('case', '!=', 'ownership_changed');
                });
        });

        $query->where(function ($q) {
            $q->whereNull('status')
                ->orWhereRaw('LOWER(status) != ?', ['cancelled']);
        });


        /*
    |--------------------------------------------------------------------------
    | PROJECT FILTER
    |--------------------------------------------------------------------------
    |
    | Supports:
    | all
    | single project
    | multiple projects
    |
    */

        if ($request->filled('project_id')) {

            $projectIds = (array) $request->project_id;

            if (!in_array('all', $projectIds)) {

                $projectIds = array_filter(
                    $projectIds,
                    fn($id) => $id !== 'all' && $id !== null && $id !== ''
                );

                if (!empty($projectIds)) {
                    $query->whereIn('project_id', $projectIds);
                }
            }
        }


        /*
    |--------------------------------------------------------------------------
    | PRODUCT FILTER
    |--------------------------------------------------------------------------
    |
    | Supports:
    | all
    | single product
    | multiple products
    |
    */

        if ($request->filled('product_id')) {

            $productIds = (array) $request->product_id;

            if (!in_array('all', $productIds)) {

                $productIds = array_filter(
                    $productIds,
                    fn($id) => $id !== 'all' && $id !== null && $id !== ''
                );

                if (!empty($productIds)) {
                    $query->whereIn('product_id', $productIds);
                }
            }
        }


        /*
    |--------------------------------------------------------------------------
    | Get Active Bookings
    |--------------------------------------------------------------------------
    */

        $bookings = $query
            ->orderBy('project_id')
            ->orderBy('product_id')
            ->orderBy('id')
            ->get();


        /*
    |--------------------------------------------------------------------------
    | Detail Account IDs
    |--------------------------------------------------------------------------
    */

        $detailAccountIds = $bookings
            ->pluck('detail_account_id')
            ->filter()
            ->unique()
            ->values();


        /*
    |--------------------------------------------------------------------------
    | Booking Payment
    |--------------------------------------------------------------------------
    |
    | AccountLedger:
    |
    | detail_account_id = BookingApplication.detail_account_id
    | transaction_type = booking_payment
    | received amount = credit
    |
    */

        $paymentByAccount = AccountLedger::whereIn(
            'detail_account_id',
            $detailAccountIds
        )
            ->where('transaction_type', 'booking_payment')
            ->selectRaw(
                'detail_account_id, SUM(credit) as received_amount'
            )
            ->groupBy('detail_account_id')
            ->get()
            ->mapWithKeys(function ($row) {

                return [
                    $row->detail_account_id => (float) $row->received_amount
                ];
            });


        /*
    |--------------------------------------------------------------------------
    | Add Received Amount To Every Booking
    |--------------------------------------------------------------------------
    */

        $bookings->each(function ($booking) use ($paymentByAccount) {

            $booking->received_amount =
                $paymentByAccount[$booking->detail_account_id] ?? 0;

            /*
        | Outstanding Amount
        */

            $booking->remaining_amount =
                max(
                    0,
                    (float) $booking->total_amount
                        - (float) $booking->received_amount
                );
        });


        /*
    |--------------------------------------------------------------------------
    | Group By Project
    |--------------------------------------------------------------------------
    */

        $groupedProjects = $bookings->groupBy('project_id');


        /*
    |--------------------------------------------------------------------------
    | Grand Totals
    |--------------------------------------------------------------------------
    */

        $grandTotals = [

            'total_bookings' => $bookings->count(),

            'total_amount' => $bookings->sum(function ($booking) {
                return (float) $booking->total_amount;
            }),

            'total_received' => $bookings->sum(function ($booking) {
                return (float) $booking->received_amount;
            }),

            'total_remaining' => $bookings->sum(function ($booking) {
                return (float) $booking->remaining_amount;
            }),

        ];


        /*
    |--------------------------------------------------------------------------
    | Return View
    |--------------------------------------------------------------------------
    */

        return view(
            'exective_reports.booking_report.report',
            compact(
                'groupedProjects',
                'grandTotals'
            )
        );
    }
}
