@extends('layouts.backend')

@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h1 class="h3 fw-bold mb-3">@lang('messages.contractor-payments')</h1>
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">{{ $contractor->name ?? 'N/A' }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <!-- Payment Summary Cards -->
        <div class="row mb-3">
            <div class="col-md-3">
                <div class="block block-rounded">
                    <div class="block-content block-content-full text-center">
                        <h6 class="text-muted mb-2">@lang('messages.total-bills')</h6>
                        <h3 class="text-primary">{{ $summary['total_bills'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="block block-rounded">
                    <div class="block-content block-content-full text-center">
                        <h6 class="text-muted mb-2">@lang('messages.total-bill-amount')</h6>
                        <h3 class="text-info">Rs. {{ number_format($summary['total_bill_amount'], 2) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="block block-rounded">
                    <div class="block-content block-content-full text-center">
                        <h6 class="text-muted mb-2">@lang('messages.total-paid-amount')</h6>
                        <h3 class="text-success">Rs. {{ number_format($summary['total_paid_amount'], 2) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="block block-rounded">
                    <div class="block-content block-content-full text-center">
                        <h6 class="text-muted mb-2">@lang('messages.total-outstanding')</h6>
                        <h3 class="text-danger">Rs. {{ number_format($summary['total_outstanding'], 2) }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Percentage Progress -->
        <div class="row mb-3">
            <div class="col-md-12">
                <div class="block block-rounded">
                    <div class="block-content block-content-full">
                        <strong>@lang('messages.payment-progress'): {{ number_format($summary['payment_percentage'], 2) }}%</strong>
                        <div class="progress mt-2">
                            <div class="progress-bar bg-success" role="progressbar"
                                style="width: {{ $summary['payment_percentage'] }}%"
                                aria-valuenow="{{ $summary['payment_percentage'] }}"
                                aria-valuemin="0"
                                aria-valuemax="100">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="row mb-3">
            <div class="col-md-12">
                <div class="block block-rounded">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">@lang('messages.filters')</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-toggle="block-option" data-action="collapse">
                                <i class="si si-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content block-content-full">
                        <form action="{{ route('contractor-payments.contractor', $contractor->id) }}" method="GET" class="row">
                            <div class="col-md-3 mb-3">
                                <label class="form-label">@lang('messages.status')</label>
                                <select name="status" class="form-select">
                                    <option value="">@lang('messages.all')</option>
                                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>@lang('messages.pending')</option>
                                    <option value="posted" {{ request('status') === 'posted' ? 'selected' : '' }}>@lang('messages.posted')</option>
                                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>@lang('messages.cancelled')</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">@lang('messages.from-date')</label>
                                <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">@lang('messages.to-date')</label>
                                <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label">@lang('messages.voucher-type')</label>
                                <select name="voucher_type" class="form-select">
                                    <option value="">@lang('messages.all')</option>
                                    <option value="BPV" {{ request('voucher_type') === 'BPV' ? 'selected' : '' }}>BPV</option>
                                    <option value="CPV" {{ request('voucher_type') === 'CPV' ? 'selected' : '' }}>CPV</option>
                                </select>
                            </div>
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-search"></i> @lang('messages.search')
                                </button>
                                <a href="{{ route('contractor-payments.contractor', $contractor->id) }}" class="btn btn-secondary">
                                    <i class="fa fa-redo"></i> @lang('messages.reset')
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Table -->
        <div class="row">
            <div class="col-md-12">
                <div class="block block-rounded">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">@lang('messages.payment-details')</h3>
                        <div class="block-options">
                            <span class="badge bg-info">{{ $payments->total() }} @lang('messages.payments')</span>
                        </div>
                    </div>
                    <div class="block-content block-content-full">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>@lang('messages.bill-no')</th>
                                        <th>@lang('messages.tender')</th>
                                        <th>@lang('messages.payment-date')</th>
                                        <th>@lang('messages.voucher')</th>
                                        <th>@lang('messages.amount')</th>
                                        <th>@lang('messages.status')</th>
                                        <th class="text-center" style="width: 100px;">@lang('messages.action')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($payments as $payment)
                                        <tr>
                                            <td>
                                                <a href="{{ route('contractor-bills.show', $payment->contractorBill->id) }}" class="fw-bold">
                                                    {{ $payment->contractorBill->bill_no }}
                                                </a>
                                            </td>
                                            <td>{{ $payment->contractorBill->tender->title_en ?? 'N/A' }}</td>
                                            <td>
                                                @if($payment->payment_date)
                                                    {{ $payment->payment_date->format('d M Y') }}
                                                @else
                                                    <span class="text-muted">Pending</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">
                                                    {{ $payment->getVoucherTypeLabel() }} #{{ $payment->voucher_id }}
                                                </span>
                                            </td>
                                            <td class="text-end fw-bold">Rs. {{ number_format($payment->amount, 2) }}</td>
                                            <td>
                                                <span class="badge bg-{{ $payment->status === 'posted' ? 'success' : ($payment->status === 'pending' ? 'warning' : 'danger') }}">
                                                    {{ $payment->getStatusLabel() }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('contractor-payments.bill-history', $payment->contractorBill->id) }}"
                                                    class="btn btn-sm btn-info" title="@lang('messages.view-history')">
                                                    <i class="fa fa-history"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">
                                                @lang('messages.no-payments')
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if($payments->hasPages())
                            <div class="mt-3">
                                {{ $payments->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="row mt-3">
            <div class="col-12">
                <a href="{{ route('contractor-payments.index') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i> @lang('messages.back')
                </a>
            </div>
        </div>
    </div>
@endsection
