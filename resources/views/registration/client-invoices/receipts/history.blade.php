@extends('layouts.backend')

@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h1 class="h3 fw-bold mb-0">@lang('messages.receipt-history')</h1>
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
                <form method="GET" action="{{ route('receipts.history') }}" class="row g-3">
                    <div class="col-md-3">
                        <label for="client_id" class="form-label">@lang('messages.client')</label>
                        <select name="client_id" id="client_id" class="form-select">
                            <option value="">@lang('messages.all-clients')</option>
                            <!-- Client options would be populated here -->
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="tender_id" class="form-label">@lang('messages.tender')</label>
                        <select name="tender_id" id="tender_id" class="form-select">
                            <option value="">@lang('messages.all-tenders')</option>
                            <!-- Tender options would be populated here -->
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label for="from_date" class="form-label">@lang('messages.from-date')</label>
                        <input type="date" name="from_date" id="from_date" class="form-control" value="{{ request('from_date') }}">
                    </div>

                    <div class="col-md-2">
                        <label for="to_date" class="form-label">@lang('messages.to-date')</label>
                        <input type="date" name="to_date" id="to_date" class="form-control" value="{{ request('to_date') }}">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn btn-primary d-block">
                            <i class="fa fa-search me-1"></i> @lang('messages.search')
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Receipt History -->
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">@lang('messages.receipt-history')</h3>
            </div>

            <div class="block-content block-content-full">
                @if ($receipts->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-vcenter">
                            <thead>
                                <tr>
                                    <th>@lang('messages.date')</th>
                                    <th>@lang('messages.invoice-no')</th>
                                    <th>@lang('messages.client')</th>
                                    <th>@lang('messages.tender')</th>
                                    <th>@lang('messages.voucher-no')</th>
                                    <th>@lang('messages.voucher-type')</th>
                                    <th>@lang('messages.amount')</th>
                                    <th>@lang('messages.created-by')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($receipts as $receipt)
                                    <tr>
                                        <td>{{ $receipt->created_at->format('d M Y') }}</td>
                                        <td>
                                            <a href="{{ route('client-invoices.show', $receipt->clientInvoice) }}" class="text-primary">
                                                {{ $receipt->clientInvoice->invoice_no }}
                                            </a>
                                        </td>
                                        <td>{{ $receipt->clientInvoice->client?->name_en }}</td>
                                        <td>{{ $receipt->clientInvoice->tender?->title_en }}</td>
                                        <td>{{ $receipt->voucher->voucher_no }}</td>
                                        <td>
                                            <span class="badge badge-{{ $receipt->voucher_type === 'BRV' ? 'primary' : 'info' }}">
                                                {{ $receipt->voucher_type }}
                                            </span>
                                        </td>
                                        <td class="text-end fw-bold">{{ number_format($receipt->amount, 2) }}</td>
                                        <td>{{ $receipt->createdBy?->name }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="table-active">
                                    <td colspan="6" class="text-end fw-bold">@lang('messages.total-receipts'):</td>
                                    <td class="text-end fw-bold">{{ number_format($receipts->sum('amount'), 2) }}</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-3">
                        {{ $receipts->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fa fa-receipt fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">@lang('messages.no-receipts-found')</h4>
                        <p class="text-muted">@lang('messages.no-receipts-description')</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
