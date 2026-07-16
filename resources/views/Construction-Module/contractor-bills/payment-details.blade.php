@extends('layouts.backend')

@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h1 class="h3 fw-bold mb-3">@lang('messages.payment-details')</h1>
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
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <strong>@lang('messages.bill-no'):</strong>
                                    <p>{{ $bill->bill_no }}</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <strong>@lang('messages.contractor'):</strong>
                                    <p>{{ App::getLocale() === 'ur' ? $bill->contractorAccount->party->name_ur : $bill->contractorAccount->party->name_en }}</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <strong>@lang('messages.tender'):</strong>
                                    <p>{{ App::getLocale() === 'ur' ? $bill->tender->title_ur ?? 'N/A' : $bill->tender->title_en ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <strong>@lang('messages.status'):</strong>
                                    <p>
                                        <span class="badge bg-{{ $bill->status === 'paid' ? 'success' : ($bill->status === 'partial_paid' ? 'warning' : 'info') }}">
                                            {{ $bill->getStatusLabel() }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <div class="block block-rounded">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">@lang('messages.payment-status')</h3>
                    </div>
                    <div class="block-content block-content-full">
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="mb-3">
                                    <strong>@lang('messages.bill-amount'):</strong>
                                    <h4 class="text-primary">Rs. {{ number_format($bill->total_amount, 2) }}</h4>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <strong>@lang('messages.paid-amount'):</strong>
                                    <h4 class="text-success">Rs. {{ number_format($paid_amount, 2) }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="text-center mb-3">
                            <strong>@lang('messages.outstanding'):</strong>
                            <h4 class="text-{{ $outstanding_amount > 0 ? 'danger' : 'success' }}">
                                Rs. {{ number_format($outstanding_amount, 2) }}
                            </h4>
                        </div>

                        <!-- Progress Bar -->
                        <div class="mb-3">
                            <div class="progress">
                                <div class="progress-bar bg-success" role="progressbar"
                                    style="width: {{ ($paid_amount / $bill->total_amount) * 100 }}%"
                                    aria-valuenow="{{ $paid_amount }}"
                                    aria-valuemin="0"
                                    aria-valuemax="{{ $bill->total_amount }}">
                                </div>
                            </div>
                            <small class="text-muted">{{ number_format(($paid_amount / $bill->total_amount) * 100, 2) }}% @lang('messages.paid')</small>
                        </div>

                        @if($can_accept_payment)
                            <a href="{{ route('contractor-payments.make-payment-form', $bill->id) }}" class="btn btn-sm btn-primary w-100">
                                <i class="fa fa-money-bill"></i> @lang('messages.make-payment')
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="block block-rounded">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">@lang('messages.payment-summary')</h3>
                    </div>
                    <div class="block-content block-content-full">
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <strong>@lang('messages.posted-payments'):</strong>
                                    <h5>{{ count($payments->where('status', 'posted')) }}</h5>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <strong>@lang('messages.posted-amount'):</strong>
                                    <h5>Rs. {{ number_format($posted_payments, 2) }}</h5>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <strong>@lang('messages.pending-payments'):</strong>
                                    <h5>{{ count($payments->where('status', 'pending')) }}</h5>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <strong>@lang('messages.pending-amount'):</strong>
                                    <h5>Rs. {{ number_format($pending_payments, 2) }}</h5>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment History -->
        <div class="row">
            <div class="col-md-12">
                <div class="block block-rounded">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">@lang('messages.payment-history')</h3>
                        <div class="block-options">
                            <a href="{{ route('contractor-payments.bill-history', $bill->id) }}" class="btn btn-sm btn-info">
                                <i class="fa fa-history"></i> @lang('messages.view-all')
                            </a>
                        </div>
                    </div>
                    <div class="block-content block-content-full">
                        @forelse($payments as $payment)
                            <div class="mb-3 pb-3 border-bottom">
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>@lang('messages.voucher'): {{ $payment->voucher_type }} - #{{ $payment->voucher_id }}</strong><br>
                                        <small class="text-muted">{{ $payment->payment_date?->format('d M Y, H:i') }}</small>
                                    </div>
                                    <div class="col-md-3">
                                        <strong>Rs. {{ number_format($payment->amount, 2) }}</strong>
                                    </div>
                                    <div class="col-md-3 text-end">
                                        <span class="badge bg-{{ $payment->status === 'posted' ? 'success' : 'warning' }}">
                                            {{ $payment->getStatusLabel() }}
                                        </span>
                                    </div>
                                </div>
                                @if($payment->remarks)
                                    <small class="text-muted">{{ $payment->remarks }}</small>
                                @endif
                            </div>
                        @empty
                            <p class="text-muted">@lang('messages.no-payments')</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

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
