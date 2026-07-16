@extends('layouts.backend')

@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h1 class="h3 fw-bold mb-0">@lang('messages.receipt-history')</h1>
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">{{ $invoice->invoice_no }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row mb-3">
            <div class="col-md-8">
                <div class="alert alert-info">
                    <strong>@lang('messages.invoice-amount'):</strong> {{ number_format($invoice->amount, 2) }} |
                    <strong>@lang('messages.total-received'):</strong> {{ number_format($invoice->total_received, 2) }} |
                    <strong>@lang('messages.outstanding'):</strong> {{ number_format($invoice->outstanding_receivable, 2) }}
                </div>
            </div>
            <div class="col-md-4">
                <div class="btn-group float-end">
                    <a href="{{ route('client-invoices.receipts.create', $invoice) }}" class="btn btn-primary">
                        <i class="fa fa-plus me-1"></i> @lang('messages.link-receipt')
                    </a>
                    <a href="{{ route('client-invoices.show', $invoice) }}" class="btn btn-secondary">
                        <i class="fa fa-arrow-left me-1"></i> @lang('messages.back-to-invoice')
                    </a>
                </div>
            </div>
        </div>

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
                                    <th>@lang('messages.voucher-no')</th>
                                    <th>@lang('messages.voucher-type')</th>
                                    <th>@lang('messages.description')</th>
                                    <th>@lang('messages.amount')</th>
                                    <th>@lang('messages.created-by')</th>
                                    <th>@lang('messages.actions')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($receipts as $receipt)
                                    <tr>
                                        <td>{{ $receipt->created_at->format('d M Y H:i') }}</td>
                                        <td>
                                            <a href="#" class="text-primary" title="{{ $receipt->voucher->description_en }}">
                                                {{ $receipt->voucher->voucher_no }}
                                            </a>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $receipt->voucher_type === 'BRV' ? 'primary' : 'info' }}">
                                                {{ $receipt->voucher_type }}
                                            </span>
                                        </td>
                                        <td>{{ Str::limit($receipt->voucher->description_en, 50) }}</td>
                                        <td class="text-end fw-bold">{{ number_format($receipt->amount, 2) }}</td>
                                        <td>{{ $receipt->createdBy?->name }}</td>
                                        <td>
                                            <form action="{{ route('client-invoices.receipts.destroy', [$invoice, $receipt]) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('@lang('messages.confirm-remove-receipt')')">
                                                    <i class="fa fa-trash"></i> @lang('messages.remove')
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="table-active">
                                    <td colspan="4" class="text-end fw-bold">@lang('messages.total'):</td>
                                    <td class="text-end fw-bold">{{ number_format($receipts->sum('amount'), 2) }}</td>
                                    <td colspan="2"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fa fa-receipt fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">@lang('messages.no-receipts-found')</h4>
                        <p class="text-muted">@lang('messages.no-receipts-description')</p>
                        <a href="{{ route('client-invoices.receipts.create', $invoice) }}" class="btn btn-primary">
                            <i class="fa fa-plus me-1"></i> @lang('messages.link-first-receipt')
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
