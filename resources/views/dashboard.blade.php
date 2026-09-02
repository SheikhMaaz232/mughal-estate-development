@extends('layouts.backend')

@section('content')
    @can('dashboard-statistic.view')
        @php($isUrdu = App::getLocale() === 'ur')
        <div dir="{{ $isUrdu ? 'rtl' : 'ltr' }}">
            <div class="bg-body-light">
                <div class="content content-full">
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center py-3">
                        <div>
                            <p class="text-primary text-uppercase fw-semibold small mb-1">@lang('messages.account_dashboard')</p>

                            <h1 class="h2 fw-bold mb-1">@lang('messages.account_module')</h1>
                            <p class="text-muted mb-0">@lang('messages.account_dashboard_subtitle')</p>
                        </div>
                        <div class="text-sm-end mt-3 mt-sm-0">
                            <div class="small text-muted">@lang('messages.reporting_period')</div>
                            <div class="fw-semibold">{{ now()->startOfMonth()->format('d M Y') }} - {{ now()->format('d M Y') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content">
                {{-- @can('accounts.view') --}}
                <div class="row g-3 mb-4">
                    @foreach ([['label' => __('messages.total_debit'), 'value' => $accountStats['total_debit'], 'icon' => 'fa-arrow-down', 'color' => 'danger'], ['label' => __('messages.total_credit'), 'value' => $accountStats['total_credit'], 'icon' => 'fa-arrow-up', 'color' => 'success'], ['label' => __('messages.net_cash_flow'), 'value' => $accountStats['net_cash_flow'], 'icon' => 'fa-wallet', 'color' => $accountStats['net_cash_flow'] >= 0 ? 'success' : 'danger'], ['label' => __('messages.net_result'), 'value' => $accountStats['net_result'], 'icon' => 'fa-scale-balanced', 'color' => $accountStats['net_result'] >= 0 ? 'success' : 'danger']] as $metric)
                        <div class="col-6 col-xl-3">
                            <div class="block block-rounded h-100 mb-0 border-top border-{{ $metric['color'] }} border-3">
                                <div class="block-content block-content-full d-flex align-items-center gap-3"><span
                                        class="item item-circle bg-{{ $metric['color'] }}-light"><i
                                            class="fa {{ $metric['icon'] }} text-{{ $metric['color'] }}"></i></span>
                                    <div>
                                        <div class="fs-4 fw-bold">Rs. {{ number_format($metric['value'], 2) }}</div>
                                        <div class="text-muted small">{{ $metric['label'] }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-xl-8">
                        <div class="block block-rounded h-100 mb-0">
                            <div class="block-header block-header-default">
                                <h3 class="block-title">@lang('messages.account_trend')</h3>
                                <div class="block-options text-muted small">@lang('messages.last_six_months')</div>
                            </div>
                            <div class="block-content">
                                <div style="height: 310px"><canvas id="account-trend-chart"></canvas></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4">
                        <div class="block block-rounded h-100 mb-0">
                            <div class="block-header block-header-default">
                                <h3 class="block-title">@lang('messages.account_head_summary')</h3>
                            </div>
                            <div class="block-content">
                                <div class="list-group list-group-flush">
                                    @forelse($accountHeadSummary as $head)
                                        <div class="list-group-item px-0">
                                            <div class="d-flex justify-content-between"><span
                                                    class="fw-semibold">{{ $head['head'] }}</span><span
                                                    class="badge bg-body-dark text-dark">Rs.
                                                    {{ number_format($head['balance'], 2) }}</span></div><small
                                                class="text-muted">@lang('messages.total_debit'): Rs.
                                                {{ number_format($head['debit'], 2) }} / @lang('messages.total_credit'): Rs.
                                                {{ number_format($head['credit'], 2) }}</small>
                                    </div>@empty<div class="text-muted small">@lang('messages.no_records_found')</div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-xl-7">
                        <div class="block block-rounded h-100 mb-0">
                            <div class="block-header block-header-default">
                                <h3 class="block-title">@lang('messages.recent_ledger_entries')</h3>
                            </div>
                            <div class="block-content table-responsive">
                                <table class="table table-vcenter">
                                    <thead>
                                        <tr>
                                            <th>@lang('messages.date')</th>
                                            <th>@lang('messages.document_no')</th>
                                            <th>@lang('messages.party')</th>
                                            <th>@lang('messages.debit')</th>
                                            <th>@lang('messages.credit')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentLedgerEntries as $entry)
                                            <tr>
                                                <td>{{ $entry->date?->format('d M Y') }}</td>
                                                <td>{{ $entry->document_number ?? '-' }}</td>
                                                <td>{{ $entry->party ? ($isUrdu ? ($entry->party->name_ur ?: $entry->party->name_en) : $entry->party->name_en) : 'N/A' }}
                                                </td>
                                                <td>Rs. {{ number_format($entry->debit, 2) }}</td>
                                                <td>Rs. {{ number_format($entry->credit, 2) }}</td>
                                        </tr>@empty<tr>
                                                <td colspan="5" class="text-center text-muted py-4">@lang('messages.no_records_found')</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-5">
                        <div class="block block-rounded h-100 mb-0">
                            <div class="block-header block-header-default">
                                <h3 class="block-title">@lang('messages.account_summary')</h3>
                            </div>
                            <div class="block-content">
                                <div class="list-group list-group-flush">
                                    <div class="list-group-item px-0 d-flex justify-content-between">
                                        <span>@lang('messages.total_income')</span><span class="fw-semibold">Rs.
                                            {{ number_format($accountStats['income'], 2) }}</span></div>
                                    <div class="list-group-item px-0 d-flex justify-content-between">
                                        <span>@lang('messages.total_expenses')</span><span class="fw-semibold">Rs.
                                            {{ number_format($accountStats['expenses'], 2) }}</span></div>
                                    <div class="list-group-item px-0 d-flex justify-content-between">
                                        <span>@lang('messages.net_result')</span><span
                                            class="fw-semibold {{ $accountStats['net_result'] < 0 ? 'text-danger' : 'text-success' }}">Rs.
                                            {{ number_format($accountStats['net_result'], 2) }}</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- @endcan --}}

                {{-- @can('dashboard.view') --}}
                <div class="border-top pt-4 mt-4">
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-3">
                        <div>
                            <h2 class="h4 fw-bold mb-1">@lang('messages.operational_overview')</h2>
                            <p class="text-muted mb-0">@lang('messages.operational_dashboard_subtitle')</p>
                        </div>
                        <span class="badge bg-primary-subtle text-primary mt-2 mt-sm-0">@lang('messages.last_six_months')</span>
                    </div>
                    <div class="row g-3 mb-4">
                        @foreach ([['label' => __('messages.bookings'), 'value' => $operationalStats['bookings'], 'amount' => $operationalStats['booking_value'], 'icon' => 'fa-file-signature', 'color' => 'primary'], ['label' => __('messages.purchase_invoices'), 'value' => $operationalStats['purchases'], 'amount' => $operationalStats['purchase_value'], 'icon' => 'fa-cart-shopping', 'color' => 'warning'], ['label' => __('messages.purchase_orders'), 'value' => $operationalStats['purchase_orders'], 'amount' => $operationalStats['purchase_order_value'], 'icon' => 'fa-file-contract', 'color' => 'info'], ['label' => __('messages.sales_invoices'), 'value' => $operationalStats['sales'], 'amount' => $operationalStats['sales_value'], 'icon' => 'fa-chart-line', 'color' => 'success']] as $metric)
                            <div class="col-6 col-xl-3">
                                <div class="block block-rounded h-100 mb-0 border-top border-{{ $metric['color'] }} border-3">
                                    <div class="block-content block-content-full">
                                        <div class="d-flex align-items-center gap-2 mb-2"><span
                                                class="item item-circle bg-{{ $metric['color'] }}-light"><i
                                                    class="fa {{ $metric['icon'] }} text-{{ $metric['color'] }}"></i></span><span
                                                class="text-muted small">{{ $metric['label'] }}</span></div>
                                        <div class="fs-4 fw-bold">{{ number_format($metric['value']) }}</div>
                                        <div class="text-muted small">Rs. {{ number_format($metric['amount'], 2) }}</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="row g-4 mb-4">
                        <div class="col-xl-7">
                            <div class="block block-rounded h-100 mb-0">
                                <div class="block-header block-header-default">
                                    <h3 class="block-title">@lang('messages.project_performance')</h3>
                                </div>
                                <div class="block-content">
                                    <div style="height: 340px"><canvas id="project-performance-chart"></canvas></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-5">
                            <div class="block block-rounded h-100 mb-0">
                                <div class="block-header block-header-default">
                                    <h3 class="block-title">@lang('messages.purchase_control')</h3>
                                </div>
                                <div class="block-content">
                                    <div class="list-group list-group-flush">
                                        <div class="list-group-item px-0 d-flex justify-content-between">
                                            <span>@lang('messages.purchase_returns')</span><span class="fw-semibold text-danger">Rs.
                                                {{ number_format($operationalStats['purchase_returns'], 2) }}</span></div>
                                        <div class="list-group-item px-0 d-flex justify-content-between">
                                            <span>@lang('messages.purchase_orders')</span><span
                                                class="fw-semibold">{{ number_format($operationalStats['purchase_orders']) }}</span>
                                        </div>
                                        <div class="list-group-item px-0 d-flex justify-content-between">
                                            <span>@lang('messages.purchase_invoices')</span><span
                                                class="fw-semibold">{{ number_format($operationalStats['purchases']) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="block block-rounded mb-0">
                        <div class="block-header block-header-default">
                            <h3 class="block-title">@lang('messages.project_wise_statistics')</h3>
                        </div>
                        <div class="block-content table-responsive">
                            <table class="table table-vcenter">
                                <thead>
                                    <tr>
                                        <th>@lang('messages.project')</th>
                                        <th>@lang('messages.bookings')</th>
                                        <th>@lang('messages.booking_value')</th>
                                        <th>@lang('messages.purchase_invoices')</th>
                                        <th>@lang('messages.purchase_value')</th>
                                        <th>@lang('messages.purchase_orders')</th>
                                        <th>@lang('messages.sales_value')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($projectStats as $project)
                                        <tr>
                                            <td class="fw-semibold">
                                                {{ $isUrdu ? ($project['name_ur'] ?: $project['name_en']) : $project['name_en'] }}
                                            </td>
                                            <td>{{ number_format($project['bookings']) }}</td>
                                            <td>Rs. {{ number_format($project['booking_value'], 2) }}</td>
                                            <td>{{ number_format($project['purchases']) }}</td>
                                            <td>Rs. {{ number_format($project['purchase_value'], 2) }}</td>
                                            <td>{{ number_format($project['purchase_orders']) }}</td>
                                            <td>Rs. {{ number_format($project['sales_value'], 2) }}</td>
                                    </tr>@empty<tr>
                                            <td colspan="7" class="text-center text-muted py-4">@lang('messages.no_project_activity')</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {{-- @endcan --}}
                @if (!auth()->user()->can('accounts.view') && !auth()->user()->can('dashboard.view'))
                    <div class="block block-rounded">
                        <div class="block-content text-center py-5">
                            <h3 class="h4 text-muted">@lang('messages.no_access')</h3>
                            <p class="text-muted mb-0">@lang('messages.account_access_required')</p>
                        </div>
                    </div>
                @endif
            </div>
        @endcan
    @endsection

    @push('scripts')
        <script src="{{ asset('js/plugins/chart.js/chart.umd.js') }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const chartFont = {
                    family: "'Segoe UI', sans-serif"
                };
                @can('accounts.view')
                    new Chart(document.getElementById('account-trend-chart'), {
                        type: 'line',
                        data: {
                            labels: @json($accountTrend['labels']),
                            datasets: [{
                                label: @json(__('messages.total_debit')),
                                data: @json($accountTrend['debit']),
                                borderColor: '#d94c4c',
                                backgroundColor: 'rgba(217,76,76,.12)',
                                fill: true,
                                tension: .35,
                                yAxisID: 'value'
                            }, {
                                label: @json(__('messages.total_credit')),
                                data: @json($accountTrend['credit']),
                                borderColor: '#16794c',
                                backgroundColor: 'transparent',
                                tension: .35,
                                yAxisID: 'value'
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            interaction: {
                                mode: 'index',
                                intersect: false
                            },
                            plugins: {
                                legend: {
                                    labels: {
                                        usePointStyle: true,
                                        font: chartFont
                                    }
                                }
                            },
                            scales: {
                                value: {
                                    beginAtZero: true,
                                    ticks: {
                                        callback: value => 'Rs. ' + Number(value).toLocaleString()
                                    }
                                }
                            }
                        }
                    });
                @endcan
                @can('dashboard.view')
                    new Chart(document.getElementById('project-performance-chart'), {
                        type: 'bar',
                        data: {
                            labels: @json($projectStats->map(fn($project) => $isUrdu ? ($project['name_ur'] ?: $project['name_en']) : $project['name_en'])->values()),
                            datasets: [{
                                label: @json(__('messages.booking_value')),
                                data: @json($projectStats->pluck('booking_value')->values()),
                                backgroundColor: '#1769aa'
                            }, {
                                label: @json(__('messages.purchase_value')),
                                data: @json($projectStats->pluck('purchase_value')->values()),
                                backgroundColor: '#f0a202'
                            }, {
                                label: @json(__('messages.sales_value')),
                                data: @json($projectStats->pluck('sales_value')->values()),
                                backgroundColor: '#16794c'
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        callback: value => 'Rs. ' + Number(value).toLocaleString()
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    labels: {
                                        usePointStyle: true,
                                        font: chartFont
                                    }
                                }
                            }
                        }
                    });
                @endcan
            });
        </script>
    @endpush
