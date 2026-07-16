@extends('layouts.backend')

@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h1 class="h3 fw-bold mb-0">@lang('messages.invoice-details')</h1>
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
            <div class="col-md-8"></div>
            <div class="col-md-4">
                <div class="btn-group float-end">
                    @if ($invoice->canBeEdited())
                        <a href="{{ route('client-invoices.edit', $invoice) }}" class="btn btn-warning">
                            <i class="fa fa-edit me-1"></i> @lang('messages.edit')
                        </a>
                        <form action="{{ route('client-invoices.verify', $invoice) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success" onclick="return confirm('@lang('messages.verify-invoice-confirm')')">
                                <i class="fa fa-check me-1"></i> @lang('messages.verify')
                            </button>
                        </form>
                    @endif
                    @if (in_array($invoice->status, ['verified', 'partial_received']))
                        <a href="{{ route('client-invoices.receipts.create', $invoice) }}" class="btn btn-primary">
                            <i class="fa fa-money-bill-wave me-1"></i> @lang('messages.receive-payment')
                        </a>
                        <a href="{{ route('client-invoices.receipts.show', $invoice) }}" class="btn btn-info">
                            <i class="fa fa-history me-1"></i> @lang('messages.receipt-history')
                        </a>
                    @endif
                    <a href="{{ route('client-invoices.print', $invoice) }}" class="btn btn-info" target="_blank">
                        <i class="fa fa-print me-1"></i> @lang('messages.print')
                    </a>
                    <a href="{{ route('client-invoices.index') }}" class="btn btn-secondary">
                        <i class="fa fa-arrow-left me-1"></i> @lang('messages.back')
                    </a>
                </div>
            </div>
        </div>

        <!-- Invoice Information -->
        <div class="block block-rounded mb-3">
            <div class="block-header block-header-default">
                <h3 class="block-title">@lang('messages.invoice-information')</h3>
            </div>

            <div class="block-content block-content-full">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">@lang('messages.invoice-no'):</label>
                            <p>{{ $invoice->invoice_no }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">@lang('messages.tender'):</label>
                            <p>
                                {{ app()->getLocale() === 'ur' ? $invoice->tender->title_ur : $invoice->tender->title_en }}
                            </p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">@lang('messages.client'):</label>
                            <p>{{ app()->getLocale() === 'ur' ? $invoice->client?->name_ur : $invoice->client?->name_en }}
                            </p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">@lang('messages.invoice-date'):</label>
                            <p>{{ $invoice->invoice_date->format('d M Y') }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">@lang('messages.amount'):</label>
                            <p class="fw-bold text-success fs-5">{{ number_format($invoice->amount, 2) }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">@lang('messages.status'):</label>
                            <p>
                                <span
                                    class="badge bg-{{ $invoice->status === 'verified' ? 'success' : ($invoice->status === 'draft' ? 'warning' : 'secondary') }}">
                                    @lang('messages.status-' . $invoice->status)
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                @if ($invoice->remarks_en)
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">@lang('messages.remarks') @lang('messages.english'):</label>
                                <p>{{ $invoice->remarks_en }}</p>
                            </div>
                        </div>
                @endif
                @if ($invoice->remarks_ur)
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">@lang('messages.remarks') @lang('messages.urdu'):</label>
                            <p>{{ $invoice->remarks_ur }}</p>
                        </div>
                    </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Verification Details -->
    @if ($invoice->isVerified())
        <div class="block block-rounded mb-3">
            <div class="block-header block-header-default">
                <h3 class="block-title">@lang('messages.verification-details')</h3>
            </div>

            <div class="block-content block-content-full">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">@lang('messages.verified-by'):</label>
                            <p>{{ app()->getLocale() === 'ur' ? $invoice->verifiedBy?->name_ur : $invoice->verifiedBy?->name_en }}
                            </p>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">@lang('messages.verified-at'):</label>
                            <p>{{ $invoice->verified_at?->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                </div>

                @if ($invoice->isJVPosted())
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle me-2"></i>
                                @lang('messages.journal-voucher-posted'): <strong>JV-{{ $invoice->journal_voucher_id }}</strong>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Receipt Tracking -->
    @if ($invoice->isVerified())
        <div class="block block-rounded mb-3">
            <div class="block-header block-header-default">
                <h3 class="block-title">@lang('messages.receipt-tracking')</h3>
            </div>

            <div class="block-content block-content-full">
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">@lang('messages.total-received'):</label>
                            <p class="fw-bold text-info fs-5">{{ number_format($invoice->total_received, 2) }}</p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">@lang('messages.outstanding-receivable'):</label>
                            <p
                                class="fw-bold {{ $invoice->outstanding_receivable > 0 ? 'text-danger' : 'text-success' }} fs-5">
                                {{ number_format($invoice->outstanding_receivable, 2) }}
                            </p>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="mb-3">
                            <label class="form-label fw-bold">@lang('messages.payment-status'):</label>
                            <p>
                                @if ($invoice->isFullyReceived())
                                    <span class="badge badge-success">@lang('messages.fully-received')</span>
                                @elseif ($invoice->hasPartialReceipts())
                                    <span class="badge badge-warning">@lang('messages.partially-received')</span>
                                @else
                                    <span class="badge badge-secondary">@lang('messages.pending')</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                @if ($invoice->receipts->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-vcenter">
                            <thead>
                                <tr>
                                    <th>@lang('messages.date')</th>
                                    <th>@lang('messages.voucher-no')</th>
                                    <th>@lang('messages.voucher-type')</th>
                                    <th>@lang('messages.amount')</th>
                                    <th>@lang('messages.created-by')</th>
                                    <th>@lang('messages.actions')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($invoice->receipts as $receipt)
                                    <tr>
                                        <td>{{ $receipt->created_at->format('d M Y') }}</td>
                                        <td>{{ $receipt->voucher->voucher_no }}</td>
                                        <td>
                                            <span
                                                class="badge badge-{{ $receipt->voucher_type === 'BRV' ? 'primary' : 'info' }}">
                                                {{ $receipt->voucher_type }}
                                            </span>
                                        </td>
                                        <td>{{ number_format($receipt->amount, 2) }}</td>
                                        <td>{{ $receipt->createdBy?->name }}</td>
                                        <td>
                                            <form
                                                action="{{ route('client-invoices.receipts.destroy', [$invoice, $receipt]) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('@lang('messages.confirm-remove-receipt')')">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Audit Information -->
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">@lang('messages.audit-information')</h3>
        </div>

        <div class="block-content block-content-full">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold">@lang('messages.created-by'):</label>
                        <p>{{ app()->getLocale() === 'ur' ? $invoice->createdByUser?->name_ur : $invoice->createdByUser?->name_en }}
                            ({{ $invoice->created_at->format('d M Y H:i') }})
                        </p>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label fw-bold">@lang('messages.updated-by'):</label>
                        <p>{{ app()->getLocale() === 'ur' ? $invoice->updatedByUser?->name_ur : $invoice->updatedByUser?->name_en }}
                            {{ $invoice->updated_at && $invoice->updated_at !== $invoice->created_at ? '(' . $invoice->updated_at->format('d M Y H:i') . ')' : '' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
