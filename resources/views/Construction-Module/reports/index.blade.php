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
                    <h1 class="h3 fw-bold mb-1">@lang('messages.construction_reports')</h1>
                    <p class="text-muted mb-0">@lang('messages.construction_reports_subtitle')</p>
                </div>
                <a href="{{ route('construction.reports.export', request()->query()) }}" class="btn btn-success">
                    <i class="fa fa-download me-1"></i> @lang('messages.export_bills_csv')
                </a>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="block block-rounded mb-4">
            <div class="block-header block-header-default"><h3 class="block-title">@lang('messages.report_filters')</h3></div>
            <div class="block-content">
                <form method="GET" action="{{ route('construction.reports.index') }}" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label" for="site_id">@lang('messages.construction-site')</label>
                        <select class="form-select" id="site_id" name="site_id">
                            <option value="">@lang('messages.all_sites')</option>
                            @foreach($sites as $site)
                                <option value="{{ $site->id }}" @selected($filters['siteId'] == $site->id)>{{ $siteName($site) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="status">@lang('messages.status')</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">@lang('messages.all_statuses')</option>
                            @foreach(['planned', 'active', 'in_progress', 'pending', 'completed', 'cancelled'] as $option)
                                <option value="{{ $option }}" @selected($filters['status'] === $option)>{{ $statusName($option) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label" for="from_date">@lang('messages.from_date')</label>
                        <input class="form-control" id="from_date" type="date" name="from_date" value="{{ $filters['fromDate'] }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label" for="to_date">@lang('messages.to_date')</label>
                        <input class="form-control" id="to_date" type="date" name="to_date" value="{{ $filters['toDate'] }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end gap-2">
                        <button class="btn btn-primary" type="submit"><i class="fa fa-filter me-1"></i> @lang('messages.apply')</button>
                        <a class="btn btn-alt-secondary" href="{{ route('construction.reports.index') }}" title="@lang('messages.reset')"><i class="fa fa-rotate-left"></i></a>
                    </div>
                </form>
            </div>
        </div>

        <div class="row g-3 mb-4">
            @foreach([
                ['label' => __('messages.sites'), 'value' => $summary['sites'], 'icon' => 'fa-building', 'color' => 'primary'],
                ['label' => __('messages.active_tenders'), 'value' => $summary['active_tenders'], 'icon' => 'fa-file-signature', 'color' => 'info'],
                ['label' => __('messages.work_orders'), 'value' => $summary['work_orders'], 'icon' => 'fa-list-check', 'color' => 'warning'],
                ['label' => __('messages.progress_updates'), 'value' => $summary['progress_updates'], 'icon' => 'fa-chart-line', 'color' => 'success'],
            ] as $metric)
                <div class="col-6 col-xl-3">
                    <div class="block block-rounded h-100 mb-0">
                        <div class="block-content block-content-full d-flex align-items-center gap-3">
                            <span class="item item-circle bg-{{ $metric['color'] }}-light"><i class="fa {{ $metric['icon'] }} text-{{ $metric['color'] }}"></i></span>
                            <div><div class="fs-3 fw-bold">{{ number_format($metric['value']) }}</div><div class="text-muted">{{ $metric['label'] }}</div></div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="block block-rounded mb-4">
            <div class="block-header block-header-default"><h3 class="block-title">@lang('messages.financial_position')</h3></div>
            <div class="block-content">
                <div class="row text-center">
                    @foreach([['boq_value', $summary['boq_value']], ['committed_work', $summary['committed_value']], ['billed', $summary['billed_value']], ['outstanding', $summary['outstanding_value']]] as $financial)
                        <div class="col-6 col-md-3 border-end mb-3 mb-md-0"><div class="text-muted small">@lang('messages.' . $financial[0])</div><div class="fs-4 fw-bold">Rs. {{ number_format($financial[1], 2) }}</div></div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="block block-rounded mb-4">
            <div class="block-header block-header-default"><h3 class="block-title">@lang('messages.site_schedule_status')</h3></div>
            <div class="block-content table-responsive">
                <table class="table table-vcenter table-hover">
                    <thead><tr><th>@lang('messages.site')</th><th>@lang('messages.project')</th><th>@lang('messages.status')</th><th>@lang('messages.estimated_dates')</th><th class="text-end">@lang('messages.tenders')</th></tr></thead>
                    <tbody>
                    @forelse($sites as $site)
                        <tr><td class="fw-semibold">{{ $siteName($site) }}</td><td>{{ $site->project ? $siteName($site->project) : 'N/A' }}</td><td><span class="badge bg-secondary">{{ $statusName($site->status) }}</span></td><td>{{ $site->estimated_start_date?->format('d M Y') ?? 'N/A' }} - {{ $site->estimated_end_date?->format('d M Y') ?? 'N/A' }}</td><td class="text-end">{{ $site->tenders_count }}</td></tr>
                    @empty <tr><td colspan="5" class="text-center text-muted">@lang('messages.no_sites_match_filters')</td></tr> @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="block block-rounded mb-4">
            <div class="block-header block-header-default"><h3 class="block-title">@lang('messages.tender_boq_register')</h3></div>
            <div class="block-content table-responsive">
                <table class="table table-vcenter table-hover">
                    <thead><tr><th>@lang('messages.tender')</th><th>@lang('messages.site')</th><th>@lang('messages.contractor')</th><th>@lang('messages.dates')</th><th>@lang('messages.status')</th><th class="text-end">@lang('messages.estimate')</th><th class="text-end">@lang('messages.billed')</th></tr></thead>
                    <tbody>
                    @forelse($tenders as $tender)
                        <tr><td class="fw-semibold">{{ $titleName($tender) }}</td><td>{{ $tender->constructionSite ? $siteName($tender->constructionSite) : 'N/A' }}</td><td>{{ $tender->contractorAccount?->name_en ?? 'N/A' }}</td><td>{{ $tender->start_date?->format('d M Y') }} - {{ $tender->end_date?->format('d M Y') }}</td><td><span class="badge bg-secondary">{{ $statusName($tender->status) }}</span></td><td class="text-end">Rs. {{ number_format($tender->estimated_cost, 2) }}</td><td class="text-end">Rs. {{ number_format($tender->contractor_bills_sum_amount ?? 0, 2) }}</td></tr>
                    @empty <tr><td colspan="7" class="text-center text-muted">@lang('messages.no_tenders_match_filters')</td></tr> @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-xl-6"><div class="block block-rounded h-100"><div class="block-header block-header-default"><h3 class="block-title">@lang('messages.work_order_delivery')</h3></div><div class="block-content table-responsive"><table class="table table-sm table-vcenter"><thead><tr><th>@lang('messages.description')</th><th>@lang('messages.status')</th><th>@lang('messages.dates')</th><th class="text-end">@lang('messages.value')</th></tr></thead><tbody>@forelse($workOrders as $order)<tr><td>{{ $order->description_en ?: 'Work order #' . $order->id }}</td><td>{{ $statusName($order->status) }}</td><td>{{ $order->start_date?->format('d M Y') }} - {{ $order->end_date?->format('d M Y') }}</td><td class="text-end">Rs. {{ number_format($order->total_amount, 2) }}</td></tr>@empty<tr><td colspan="4" class="text-center text-muted">@lang('messages.no_work_orders_found')</td></tr>@endforelse</tbody></table></div></div></div>
            <div class="col-xl-6"><div class="block block-rounded h-100"><div class="block-header block-header-default"><h3 class="block-title">@lang('messages.boq_register')</h3></div><div class="block-content table-responsive"><table class="table table-sm table-vcenter"><thead><tr><th>BOQ</th><th>@lang('messages.tender')</th><th class="text-end">@lang('messages.value')</th></tr></thead><tbody>@forelse($boqs as $boq)<tr><td>{{ $titleName($boq) }}</td><td>{{ $boq->tender ? $titleName($boq->tender) : 'N/A' }}</td><td class="text-end">Rs. {{ number_format($boq->total_amount, 2) }}</td></tr>@empty<tr><td colspan="3" class="text-center text-muted">@lang('messages.no_boqs_found')</td></tr>@endforelse</tbody></table></div></div></div>
        </div>

        <div class="block block-rounded mt-4">
            <div class="block-header block-header-default"><h3 class="block-title">@lang('messages.contractor_bills_outstanding')</h3></div>
            <div class="block-content table-responsive"><table class="table table-vcenter table-hover"><thead><tr><th>@lang('messages.bill-no')</th><th>@lang('messages.site_tender')</th><th>@lang('messages.contractor')</th><th>@lang('messages.date')</th><th>@lang('messages.status')</th><th class="text-end">@lang('messages.amount')</th><th class="text-end">@lang('messages.outstanding')</th></tr></thead><tbody>@forelse($bills as $bill)<tr><td class="fw-semibold">{{ $bill->bill_no }}</td><td>{{ $bill->tender?->constructionSite ? $siteName($bill->tender->constructionSite) : 'N/A' }}<br><small class="text-muted">{{ $bill->tender ? $titleName($bill->tender) : 'N/A' }}</small></td><td>{{ $bill->contractorAccount?->name_en ?? 'N/A' }}</td><td>{{ $bill->bill_date?->format('d M Y') }}</td><td><span class="badge bg-secondary">{{ $statusName($bill->status) }}</span></td><td class="text-end">Rs. {{ number_format($bill->amount, 2) }}</td><td class="text-end fw-semibold">Rs. {{ number_format($bill->outstanding_amount, 2) }}</td></tr>@empty<tr><td colspan="7" class="text-center text-muted">@lang('messages.no_bills_match_filters')</td></tr>@endforelse</tbody></table></div>
        </div>
    </div>
@endsection
