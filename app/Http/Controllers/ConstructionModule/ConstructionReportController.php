<?php

namespace App\Http\Controllers\ConstructionModule;

use App\Http\Controllers\Controller;
use App\Models\BOQMaster;
use App\Models\AccountLedger;
use App\Models\ConstructionSite;
use App\Models\ContractorBill;
use App\Models\Tender;
use App\Models\WorkOrder;
use App\Models\WorkProgress;
use Illuminate\Http\Request;

class ConstructionReportController extends Controller
{
    public function profitability(Request $request)
    {
        $sites = ConstructionSite::query()->orderBy('name_en')->get();
        $site = ConstructionSite::with('project')->find($request->input('site_id'));

        if (!$site && $sites->isNotEmpty()) {
            $site = $sites->first();
        }

        $tenders = $site ? Tender::with(['contractorAccount', 'contractorBills'])
            ->where('construction_site_id', $site->id)
            ->orderBy('start_date')
            ->get() : collect();
        $workOrders = $site ? WorkOrder::where('construction_site_id', $site->id)->get() : collect();
        $bills = $site ? ContractorBill::with(['tender', 'contractorAccount', 'payments'])
            ->whereHas('tender', fn ($query) => $query->where('construction_site_id', $site->id))
            ->where('status', '!=', 'cancelled')
            ->orderByDesc('bill_date')
            ->get() : collect();

        $bills->each(function ($bill) {
            $bill->paid_amount = $bill->payments->whereIn('status', ['pending', 'posted'])->sum('amount');
            $bill->outstanding_amount = max(0, (float) $bill->amount - $bill->paid_amount);
        });

        $revenueAccountIds = $tenders->pluck('revenue_account_id')->filter()->unique();
        $expenseAccountIds = $tenders->pluck('expense_account_id')->filter()->unique();
        $ledger = $site ? AccountLedger::where('project_id', $site->project_id)
            ->whereIn('detail_account_id', $revenueAccountIds->merge($expenseAccountIds))
            ->get() : collect();
        $accountingRevenue = $ledger->whereIn('detail_account_id', $revenueAccountIds)->sum(fn ($entry) => $entry->credit - $entry->debit);
        $accountingExpense = $ledger->whereIn('detail_account_id', $expenseAccountIds)->sum(fn ($entry) => $entry->debit - $entry->credit);

        $expectedRevenue = $tenders->sum('estimated_cost');
        $billedCost = $bills->sum('amount');
        $committedCost = $workOrders->sum('total_amount');

        return view('Construction-Module.reports.profitability', [
            'sites' => $sites,
            'site' => $site,
            'tenders' => $tenders,
            'workOrders' => $workOrders,
            'bills' => $bills,
            'accountingRevenue' => $accountingRevenue,
            'accountingExpense' => $accountingExpense,
            'expectedRevenue' => $expectedRevenue,
            'billedCost' => $billedCost,
            'committedCost' => $committedCost,
            'grossProfit' => $expectedRevenue - $billedCost,
            'projectedProfit' => $expectedRevenue - $committedCost,
        ]);
    }

    public function index(Request $request)
    {
        return view('Construction-Module.reports.index', $this->reportData($request));
    }

    public function export(Request $request)
    {
        $data = $this->reportData($request);
        $handle = fopen('php://memory', 'r+');

        fputcsv($handle, ['Bill No', 'Site', 'Tender', 'Contractor', 'Bill Date', 'Bill Amount', 'Paid', 'Outstanding', 'Status']);
        foreach ($data['bills'] as $bill) {
            fputcsv($handle, [
                $bill->bill_no,
                $bill->tender?->constructionSite?->name_en,
                $bill->tender?->title_en,
                $bill->contractorAccount?->name,
                optional($bill->bill_date)->format('Y-m-d'),
                $bill->amount,
                $bill->paid_amount,
                $bill->outstanding_amount,
                $bill->status,
            ]);
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="construction-bills-' . now()->format('Y-m-d') . '.csv"',
        ]);
    }

    private function reportData(Request $request): array
    {
        $siteId = $request->input('site_id');
        $status = $request->input('status');
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');

        $sitesQuery = ConstructionSite::query()->with('project')->withCount('tenders')->orderBy('name_en');
        if ($siteId) {
            $sitesQuery->whereKey($siteId);
        }
        if ($status) {
            $sitesQuery->where('status', $status);
        }
        $sites = $sitesQuery->get();
        $siteIds = $sites->pluck('id');

        $tenders = Tender::with(['constructionSite', 'contractorAccount'])
            ->withSum('contractorBills', 'amount')
            ->whereIn('construction_site_id', $siteIds)
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($fromDate, fn ($query) => $query->whereDate('start_date', '>=', $fromDate))
            ->when($toDate, fn ($query) => $query->whereDate('end_date', '<=', $toDate))
            ->orderBy('start_date')
            ->get();

        $boqs = BOQMaster::with(['constructionSite', 'tender'])
            ->whereIn('construction_site_id', $siteIds)
            ->when($fromDate, fn ($query) => $query->whereDate('created_at', '>=', $fromDate))
            ->when($toDate, fn ($query) => $query->whereDate('created_at', '<=', $toDate))
            ->orderBy('title_en')
            ->get();

        $workOrders = WorkOrder::with(['constructionSite', 'tender'])
            ->whereIn('construction_site_id', $siteIds)
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($fromDate, fn ($query) => $query->whereDate('start_date', '>=', $fromDate))
            ->when($toDate, fn ($query) => $query->whereDate('end_date', '<=', $toDate))
            ->withCount('items')
            ->orderBy('start_date')
            ->get();

        $bills = ContractorBill::with(['tender.constructionSite', 'contractorAccount', 'payments'])
            ->whereHas('tender', fn ($query) => $query->whereIn('construction_site_id', $siteIds))
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($fromDate, fn ($query) => $query->whereDate('bill_date', '>=', $fromDate))
            ->when($toDate, fn ($query) => $query->whereDate('bill_date', '<=', $toDate))
            ->orderByDesc('bill_date')
            ->get();

        $bills->each(function ($bill) {
            $bill->paid_amount = $bill->payments->whereIn('status', ['pending', 'posted'])->sum('amount');
            $bill->outstanding_amount = max(0, (float) $bill->amount - $bill->paid_amount);
        });

        $progressQuery = WorkProgress::whereHas('workOrder', fn ($query) => $query->whereIn('construction_site_id', $siteIds));
        if ($fromDate) {
            $progressQuery->whereDate('date', '>=', $fromDate);
        }
        if ($toDate) {
            $progressQuery->whereDate('date', '<=', $toDate);
        }

        return [
            'sites' => $sites,
            'tenders' => $tenders,
            'boqs' => $boqs,
            'workOrders' => $workOrders,
            'bills' => $bills,
            'filters' => compact('siteId', 'status', 'fromDate', 'toDate'),
            'summary' => [
                'sites' => $sites->count(),
                'active_tenders' => $tenders->whereNotIn('status', ['completed', 'cancelled'])->count(),
                'work_orders' => $workOrders->count(),
                'progress_updates' => $progressQuery->count(),
                'boq_value' => $boqs->sum('total_amount'),
                'committed_value' => $workOrders->sum('total_amount'),
                'billed_value' => $bills->sum('amount'),
                'outstanding_value' => $bills->sum('outstanding_amount'),
            ],
        ];
    }
}
