@extends('layouts.backend')

@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h1 class="h3 fw-bold mb-3">@lang('messages.payment-history')</h1>
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">@lang('messages.bill-no'): {{ $bill->bill_no }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="row mb-3">
            <div class="col-md-12">
                <div class="block block-rounded">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">@lang('messages.bill-summary')</h3>
                    </div>
                    <div class="block-content block-content-full">
                        <div class="row">
                            <div class="col-md-2">
                                <strong>@lang('messages.bill-amount'):</strong>
                                <h5>Rs. {{ number_format($bill->total_amount, 2) }}</h5>
                            </div>
                            <div class="col-md-2">
                                <strong>@lang('messages.paid-amount'):</strong>
                                <h5 class="text-success">Rs. {{ number_format($payments->sum('amount'), 2) }}</h5>
                            </div>
                            <div class="col-md-2">
                                <strong>@lang('messages.outstanding'):</strong>
                                <h5 class="text-danger">Rs. {{ number_format($bill->getOutstandingAmount(), 2) }}</h5>
                            </div>
                            <div class="col-md-2">
                                <strong>@lang('messages.status'):</strong>
                                <h5>
                                    <span class="badge bg-{{ $bill->status === 'paid' ? 'success' : ($bill->status === 'partial_paid' ? 'warning' : 'info') }}">
                                        {{ $bill->getStatusLabel() }}
                                    </span>
                                </h5>
                            </div>
                            <div class="col-md-4 text-end">
                                @if($bill->canAcceptPayment())
                                    <a href="{{ route('contractor-payments.make-payment-form', $bill->id) }}" class="btn btn-sm btn-primary">
                                        <i class="fa fa-money-bill"></i> @lang('messages.make-payment')
                                    </a>
                                @endif
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
                        <form action="{{ route('contractor-payments.bill-history', $bill->id) }}" method="GET" class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">@lang('messages.status')</label>
                                <select name="status" class="form-select">
                                    <option value="">@lang('messages.all')</option>
                                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>@lang('messages.pending')</option>
                                    <option value="posted" {{ request('status') === 'posted' ? 'selected' : '' }}>@lang('messages.posted')</option>
                                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>@lang('messages.cancelled')</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <button type="submit" class="btn btn-primary mt-4">
                                    <i class="fa fa-search"></i> @lang('messages.search')
                                </button>
                                <a href="{{ route('contractor-payments.bill-history', $bill->id) }}" class="btn btn-secondary mt-4">
                                    <i class="fa fa-redo"></i> @lang('messages.reset')
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment History Table -->
        <div class="row">
            <div class="col-md-12">
                <div class="block block-rounded">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">@lang('messages.payment-records')</h3>
                        <div class="block-options">
                            <span class="badge bg-info">{{ $payments->total() }} @lang('messages.payments')</span>
                        </div>
                    </div>
                    <div class="block-content block-content-full">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>@lang('messages.payment-date')</th>
                                        <th>@lang('messages.voucher')</th>
                                        <th>@lang('messages.voucher-no')</th>
                                        <th>@lang('messages.amount')</th>
                                        <th>@lang('messages.status')</th>
                                        <th>@lang('messages.created-by')</th>
                                        <th class="text-center" style="width: 80px;">@lang('messages.action')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($payments as $payment)
                                        <tr>
                                            <td>
                                                @if($payment->payment_date)
                                                    {{ $payment->payment_date->format('d M Y, H:i') }}
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>
                                            <td>{{ $payment->getVoucherTypeLabel() }}</td>
                                            <td>
                                                <span class="badge bg-secondary">#{{ $payment->voucher_id }}</span>
                                            </td>
                                            <td class="text-end fw-bold">Rs. {{ number_format($payment->amount, 2) }}</td>
                                            <td>
                                                <span class="badge bg-{{ $payment->status === 'posted' ? 'success' : ($payment->status === 'pending' ? 'warning' : 'danger') }}">
                                                    {{ $payment->getStatusLabel() }}
                                                </span>
                                            </td>
                                            <td>
                                                <small>{{ App::getLocale() === 'ur' ? $payment->createdBy->name_ur : $payment->createdBy->name_en }}</small><br>
                                                <small class="text-muted">{{ $payment->created_at?->diffForHumans() }}</small>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    @if($payment->canCancel())
                                                        <form action="{{ route('contractor-payments.cancel', $payment->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('@lang('messages.are-you-sure')');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger" title="@lang('messages.cancel')">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
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

        <!-- Remarks -->
        @php
            $remarksData = $payments->filter(fn($p) => $p->remarks)->groupBy('remarks');
        @endphp
        @if($remarksData->count() > 0)
            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="block block-rounded">
                        <div class="block-header block-header-default">
                            <h3 class="block-title">@lang('messages.payment-notes')</h3>
                        </div>
                        <div class="block-content block-content-full">
                            <ul>
                                @foreach($remarksData as $remark)
                                    <li>{{ $remark->first()->remarks }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Action Buttons -->
        <div class="row mt-3">
            <div class="col-12">
                <a href="{{ route('contractor-bills.show', $bill->id) }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i> @lang('messages.back')
                </a>
            </div>
        </div>
    </div>
@endsection
