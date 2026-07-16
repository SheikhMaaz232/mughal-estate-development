@extends('layouts.backend')

@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h1 class="h3 fw-bold mb-0">@lang('messages.outstanding-receivables')</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <!-- Filters -->
        <div class="block block-rounded mb-3">
            <div class="block-header block-header-default">
                <h3 class="block-title">@lang('messages.filters')</h3>
            </div>

            <div class="block-content block-content-full">
                <form method="GET" action="{{ route('receipts.outstanding') }}" class="row g-3">
                    <div class="col-md-4">
                        <label for="client_id" class="form-label">@lang('messages.client')</label>
                        <select name="client_id" id="client_id" class="form-select">
                            <option value="">@lang('messages.all-clients')</option>
                            <!-- Client options would be populated here -->
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-primary d-block">
                            <i class="fa fa-search me-1"></i> @lang('messages.search')
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Outstanding Receivables -->
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">@lang('messages.outstanding-receivables')</h3>
            </div>

            <div class="block-content block-content-full">
                @if ($outstanding->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-vcenter">
                            <thead>
                                <tr>
                                    <th>@lang('messages.invoice-no')</th>
                                    <th>@lang('messages.client')</th>
                                    <th>@lang('messages.tender')</th>
                                    <th>@lang('messages.invoice-date')</th>
                                    <th>@lang('messages.invoice-amount')</th>
                                    <th>@lang('messages.total-received')</th>
                                    <th>@lang('messages.outstanding')</th>
                                    <th>@lang('messages.status')</th>
                                    <th>@lang('messages.actions')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($outstanding as $item)
                                    <tr>
                                        <td>
                                            <a href="{{ route('client-invoices.show', $item['invoice']) }}" class="text-primary">
                                                {{ $item['invoice']->invoice_no }}
                                            </a>
                                        </td>
                                        <td>{{ $item['invoice']->client?->name_en }}</td>
                                        <td>{{ $item['invoice']->tender?->title_en }}</td>
                                        <td>{{ $item['invoice']->invoice_date->format('d M Y') }}</td>
                                        <td class="text-end">{{ number_format($item['invoice']->amount, 2) }}</td>
                                        <td class="text-end">{{ number_format($item['total_received'], 2) }}</td>
                                        <td class="text-end fw-bold text-danger">{{ number_format($item['outstanding_amount'], 2) }}</td>
                                        <td>
                                            <span class="badge badge-{{ $item['invoice']->status === 'partial_received' ? 'warning' : 'secondary' }}">
                                                @lang('messages.status-' . $item['invoice']->status)
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('client-invoices.receipts.create', $item['invoice']) }}" class="btn btn-sm btn-primary">
                                                <i class="fa fa-money-bill-wave"></i> @lang('messages.receive-payment')
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="table-active">
                                    <td colspan="4" class="text-end fw-bold">@lang('messages.total-outstanding'):</td>
                                    <td class="text-end fw-bold">{{ number_format($outstanding->sum('invoice.amount'), 2) }}</td>
                                    <td class="text-end fw-bold">{{ number_format($outstanding->sum('total_received'), 2) }}</td>
                                    <td class="text-end fw-bold text-danger">{{ number_format($outstanding->sum('outstanding_amount'), 2) }}</td>
                                    <td colspan="2"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fa fa-check-circle fa-3x text-success mb-3"></i>
                        <h4 class="text-muted">@lang('messages.all-invoices-received')</h4>
                        <p class="text-muted">@lang('messages.no-outstanding-receivables')</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
