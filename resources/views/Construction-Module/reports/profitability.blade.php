@extends('layouts.backend')

@section('content')
    @php
        $isUrdu = App::getLocale() === 'ur';
        $siteName = fn ($record) => $isUrdu ? ($record->name_ur ?: $record->name_en) : $record->name_en;
        $titleName = fn ($record) => $isUrdu ? ($record->title_ur ?: $record->title_en) : $record->title_en;
        $statusName = fn ($status) => __('messages.status_' . $status);
    @endphp
    <div dir="{{ $isUrdu ? 'rtl' : 'ltr' }}">
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div>
                    <h1 class="h3 fw-bold mb-1">@lang('messages.construction_profitability')</h1>
                    <p class="text-muted mb-0">@lang('messages.construction_profitability_subtitle')</p>
                </div>
                @if($site)
                    <span class="badge bg-primary fs-6">{{ $siteName($site) }}</span>
                @endif
            </div>
        </div>
    </div>

    <div class="content">
        <div class="block block-rounded mb-4">
            <div class="block-header block-header-default"><h3 class="block-title">@lang('messages.select_construction_site')</h3></div>
            <div class="block-content">
                <form method="GET" action="{{ route('construction.reports.profitability') }}" class="row g-3 align-items-end">
                    <div class="col-md-6">
                        <label class="form-label" for="site_id">@lang('messages.construction-site')</label>
                        <select class="form-select" id="site_id" name="site_id" required>
                            @forelse($sites as $availableSite)
                                <option value="{{ $availableSite->id }}" @selected($site?->id === $availableSite->id)>{{ $siteName($availableSite) }}</option>
                            @empty
                                <option value="">@lang('messages.no_construction_sites')</option>
                            @endforelse
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-primary" type="submit"><i class="fa fa-chart-line me-1"></i> @lang('messages.generate_report')</button>
                    </div>
                </form>
            </div>
        </div>

        @if($site)
            <div class="block block-rounded mb-4">
                <div class="block-content block-content-full">
                    <div class="row g-4">
                        <div class="col-md-4 border-end">
                            <div class="text-muted small">@lang('messages.site')</div>
                            <div class="fs-4 fw-bold">{{ $siteName($site) }}</div>
                            <div class="text-muted">{{ $site->project ? $siteName($site->project) : __('messages.no-records-found') }}</div>
                        </div>
                        <div class="col-md-4 border-end">
                            <div class="text-muted small">@lang('messages.estimated_schedule')</div>
                            <div class="fw-semibold">{{ $site->estimated_start_date?->format('d M Y') ?? 'N/A' }} - {{ $site->estimated_end_date?->format('d M Y') ?? 'N/A' }}</div>
                            <span class="badge bg-secondary mt-2">{{ $statusName($site->status) }}</span>
                        </div>
                        <div class="col-md-4">
                            <div class="text-muted small">@lang('messages.tenders_work_orders')</div>
                            <div class="fs-4 fw-bold">{{ $tenders->count() }} / {{ $workOrders->count() }}</div>
                            <div class="text-muted">{{ $bills->count() }} @lang('messages.active_contractor_bills')</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3 mb-4">
                @foreach([
                    ['label' => __('messages.expected_revenue'), 'value' => $expectedRevenue, 'color' => 'primary'],
                    ['label' => __('messages.committed_cost'), 'value' => $committedCost, 'color' => 'warning'],
                    ['label' => __('messages.billed_cost'), 'value' => $billedCost, 'color' => 'info'],
                    ['label' => __('messages.gross_profit'), 'value' => $grossProfit, 'color' => $grossProfit >= 0 ? 'success' : 'danger'],
                ] as $metric)
                    <div class="col-6 col-xl-3">
                        <div class="block block-rounded h-100 mb-0 border-top border-{{ $metric['color'] }} border-3">
                            <div class="block-content block-content-full">
                                <div class="text-muted small">{{ $metric['label'] }}</div>
                                <div class="fs-4 fw-bold {{ $metric['value'] < 0 ? 'text-danger' : '' }}">Rs. {{ number_format($metric['value'], 2) }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="block block-rounded mb-4">
                <div class="block-header block-header-default"><h3 class="block-title">@lang('messages.profitability_calculation')</h3></div>
                <div class="block-content table-responsive">
                    <table class="table table-vcenter">
                        <tbody>
                            <tr><td>@lang('messages.expected_revenue_from_tender_estimates')</td><td class="text-end">Rs. {{ number_format($expectedRevenue, 2) }}</td></tr>
                            <tr><td>@lang('messages.less_contractor_bills')</td><td class="text-end text-danger">(Rs. {{ number_format($billedCost, 2) }})</td></tr>
                            <tr class="fw-bold"><td>@lang('messages.current_gross_profit')</td><td class="text-end">Rs. {{ number_format($grossProfit, 2) }}</td></tr>
                            <tr><td>@lang('messages.projected_committed_cost')</td><td class="text-end">Rs. {{ number_format($committedCost, 2) }}</td></tr>
                            <tr class="fw-bold"><td>@lang('messages.projected_profit_after_committed_work')</td><td class="text-end">Rs. {{ number_format($projectedProfit, 2) }}</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="block block-rounded mb-4">
                <div class="block-header block-header-default"><h3 class="block-title">@lang('messages.accounting_cross_check')</h3></div>
                <div class="block-content">
                    <div class="row text-center">
                        <div class="col-md-6 border-end"><div class="text-muted small">@lang('messages.posted_revenue_ledger_total')</div><div class="fs-4 fw-bold">Rs. {{ number_format($accountingRevenue, 2) }}</div></div>
                        <div class="col-md-6"><div class="text-muted small">@lang('messages.posted_expense_ledger_total')</div><div class="fs-4 fw-bold">Rs. {{ number_format($accountingExpense, 2) }}</div></div>
                    </div>
                    <p class="text-muted small mt-3 mb-0">@lang('messages.ledger_totals_note')</p>
                </div>
            </div>

            <div class="block block-rounded mb-4">
                <div class="block-header block-header-default"><h3 class="block-title">@lang('messages.tender_profitability_detail')</h3></div>
                <div class="block-content table-responsive">
                    <table class="table table-vcenter table-hover">
                        <thead><tr><th>@lang('messages.tender')</th><th>@lang('messages.contractor')</th><th>@lang('messages.status')</th><th class="text-end">@lang('messages.expected_revenue')</th><th class="text-end">@lang('messages.billed_cost')</th><th class="text-end">@lang('messages.margin')</th></tr></thead>
                        <tbody>
                        @forelse($tenders as $tender)
                            @php($tenderBilled = $tender->contractorBills->where('status', '!=', 'cancelled')->sum('amount'))
                            <tr><td class="fw-semibold">{{ $titleName($tender) }}</td><td>{{ $tender->contractorAccount?->name_en ?? 'N/A' }}</td><td><span class="badge bg-secondary">{{ $statusName($tender->status) }}</span></td><td class="text-end">Rs. {{ number_format($tender->estimated_cost, 2) }}</td><td class="text-end">Rs. {{ number_format($tenderBilled, 2) }}</td><td class="text-end fw-semibold {{ $tender->estimated_cost - $tenderBilled < 0 ? 'text-danger' : 'text-success' }}">Rs. {{ number_format($tender->estimated_cost - $tenderBilled, 2) }}</td></tr>
                        @empty <tr><td colspan="6" class="text-center text-muted">@lang('messages.no_tenders_for_site')</td></tr> @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="block block-rounded">
                <div class="block-header block-header-default"><h3 class="block-title">@lang('messages.contractor_bill_exposure')</h3></div>
                <div class="block-content table-responsive">
                    <table class="table table-vcenter table-hover">
                        <thead><tr><th>@lang('messages.bill-no')</th><th>@lang('messages.tender')</th><th>@lang('messages.contractor')</th><th>@lang('messages.date')</th><th>@lang('messages.status')</th><th class="text-end">@lang('messages.amount')</th><th class="text-end">@lang('messages.outstanding')</th></tr></thead>
                        <tbody>
                        @forelse($bills as $bill)
                            <tr><td class="fw-semibold">{{ $bill->bill_no }}</td><td>{{ $titleName($bill->tender) }}</td><td>{{ $bill->contractorAccount?->name_en ?? 'N/A' }}</td><td>{{ $bill->bill_date?->format('d M Y') }}</td><td>{{ $statusName($bill->status) }}</td><td class="text-end">Rs. {{ number_format($bill->amount, 2) }}</td><td class="text-end fw-semibold">Rs. {{ number_format($bill->outstanding_amount, 2) }}</td></tr>
                        @empty <tr><td colspan="7" class="text-center text-muted">@lang('messages.no_active_contractor_bills')</td></tr> @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="block block-rounded"><div class="block-content text-center text-muted py-5">@lang('messages.create_site_for_profitability')</div></div>
        @endif
    </div>
@endsection
