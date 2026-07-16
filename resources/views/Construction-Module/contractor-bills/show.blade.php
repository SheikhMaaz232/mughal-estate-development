@extends('layouts.backend')

@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h1 class="h3 fw-bold mb-3">@lang('messages.contractor-bill') #{{ $bill->bill_no }}</h1>
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">@lang('messages.view-bill-details')</h2>
                </div>
                @if ($bill->canEdit())
                    <a href="{{ route('contractor-bills.edit', $bill->id) }}"
                        class="btn btn-sm btn-primary">@lang('messages.edit')</a>
                @endif
            </div>
        </div>
    </div>

    <div class="content">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Bill Information -->
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">@lang('messages.bill-information')</h3>
            </div>
            <div class="block-content block-content-full">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label">@lang('messages.bill-number'):</label>
                        <p class="form-control-plaintext">{{ $bill->bill_no }}</p>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">@lang('messages.bill-date'):</label>
                        <p class="form-control-plaintext">{{ $bill->bill_date->format('d-m-Y') }}</p>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">@lang('messages.status'):</label>
                        <p class="form-control-plaintext">
                            @if ($bill->status === 'draft')
                                <span class="badge bg-secondary">@lang('messages.draft')</span>
                            @elseif($bill->status === 'verified')
                                <span class="badge bg-info">@lang('messages.verified')</span>
                            @elseif($bill->status === 'partial_paid')
                                <span class="badge bg-primary">@lang('messages.partial-paid')</span>
                            @elseif($bill->status === 'paid')
                                <span class="badge bg-success">@lang('messages.paid')</span>
                            @else
                                <span class="badge bg-danger">@lang('messages.cancelled')</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">@lang('messages.amount'):</label>
                        <p class="form-control-plaintext"><strong>{{ number_format($bill->total_amount, 2) }}</strong></p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">@lang('messages.tender'):</label>
                        <p class="form-control-plaintext">
                            {{ App::getLocale() === 'ur' ? $bill->tender->title_ur : $bill->tender->title_en }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">@lang('messages.contractor'):</label>
                        <p class="form-control-plaintext">
                            {{ App::getLocale() === 'ur' ? $bill->contractorAccount->party->name_ur : $bill->contractorAccount->party->name_en }}
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">@lang('messages.work_order'):</label>
                        <p class="form-control-plaintext">
                            {{ App::getLocale() === 'ur' ? $bill->workOrder->description_ur : $bill->workOrder->description_en }}
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">@lang('messages.remarks'):</label>
                        <p class="form-control-plaintext">{{ $bill->remarks ?? '-' }}</p>
                    </div>
                </div>

                @if ($bill->isVerified())
                    <div class="row border-top pt-3">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">@lang('messages.verified-by')</label>
                            <p class="form-control-plaintext">{{ App::getLocale() === 'ur' ? $bill->verifiedByUser->name_ur : $bill->verifiedByUser->name_en }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">@lang('messages.verified-at')</label>
                            <p class="form-control-plaintext">{{ $bill->verified_at?->format('d-m-Y H:i') ?? '-' }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">@lang('messages.journal-voucher')</label>
                            <p class="form-control-plaintext">
                                {{ $bill->journalVoucher->voucher_no ?? ($bill->voucher_id ?? '-') }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Bill Items -->
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">@lang('messages.bill-items')</h3>
            </div>
            <div class="block-content block-content-full">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>@lang('messages.boq-item')</th>
                                <th>@lang('messages.unit')</th>
                                <th class="text-end">@lang('messages.qty')</th>
                                <th class="text-end">@lang('messages.rate')</th>
                                <th class="text-end">@lang('messages.amount')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bill->items as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td> {{ App::getLocale() === 'ur' ? $item->boqItem->item->name_ur ?? 'N/A' : $item->boqItem->item->name_en ?? 'N/A' }}</td>
                                    <td> {{ App::getLocale() === 'ur' ? $item->boqItem->item->measurementUnit->name_ur ?? 'N/A' : $item->boqItem->item->measurementUnit->name_en ?? 'N/A' }}</td>
                                    <td class="text-end">{{ number_format($item->quantity, 4) }}</td>
                                    <td class="text-end">{{ number_format($item->rate, 2) }}</td>
                                    <td class="text-end">{{ number_format($item->amount, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">@lang('messages.no-items-found')</td>
                                </tr>
                            @endforelse
                            <tr class="table-light">
                                <td colspan="5" class="text-end"><strong>@lang('messages.total'):</strong></td>
                                <td class="text-end"><strong>{{ number_format($bill->total_amount, 2) }}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Payment Information -->
        @if ($bill->isVerified())
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">@lang('messages.payment-information')</h3>
                    <div class="block-options">
                        <a href="{{ route('contractor-payments.bill', $bill->id) }}" class="btn btn-sm btn-info">
                            <i class="fa fa-eye"></i> @lang('messages.view-details')
                        </a>
                    </div>
                </div>
                <div class="block-content block-content-full">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="fs-sm fw-semibold text-muted text-uppercase mb-1">@lang('messages.bill-amount')</div>
                            <div class="fs-lg text-primary fw-bold">Rs. {{ number_format($bill->total_amount, 2) }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="fs-sm fw-semibold text-muted text-uppercase mb-1">@lang('messages.paid-amount')</div>
                            <div class="fs-lg text-success fw-bold">Rs. {{ number_format($bill->getPaidAmount(), 2) }}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="fs-sm fw-semibold text-muted text-uppercase mb-1">@lang('messages.outstanding')</div>
                            <div class="fs-lg text-danger fw-bold">Rs.
                                {{ number_format($bill->getOutstandingAmount(), 2) }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="fs-sm fw-semibold text-muted text-uppercase mb-1">@lang('messages.payment-status')</div>
                            <div class="mt-2">
                                <span
                                    class="badge bg-{{ $bill->status === 'paid' ? 'success' : ($bill->status === 'partial_paid' ? 'warning' : 'info') }} fs-base">
                                    {{ $bill->getStatusLabel() }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="mb-3">
                        <div class="progress" style="height: 25px;">
                            <div class="progress-bar bg-success" role="progressbar"
                                style="width: {{ ($bill->getPaidAmount() / $bill->total_amount) * 100 }}%"
                                aria-valuenow="{{ $bill->getPaidAmount() }}" aria-valuemin="0"
                                aria-valuemax="{{ $bill->total_amount }}">
                                {{ number_format(($bill->getPaidAmount() / $bill->total_amount) * 100, 1) }}%
                            </div>
                        </div>
                    </div>

                    @if ($bill->payments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th>@lang('messages.payment-date')</th>
                                        <th>@lang('messages.voucher-type')</th>
                                        <th>@lang('messages.voucher-id')</th>
                                        <th class="text-end">@lang('messages.amount')</th>
                                        <th>@lang('messages.status')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($bill->payments as $payment)
                                        <tr>
                                            <td>
                                                @if ($payment->payment_date)
                                                    {{ $payment->payment_date->format('d-m-Y') }}
                                                @else
                                                    <span class="text-muted">Pending</span>
                                                @endif
                                            </td>
                                            <td><span class="badge bg-secondary">{{ $payment->voucher_type }}</span></td>
                                            <td>#{{ $payment->voucher_id }}</td>
                                            <td class="text-end fw-bold">Rs. {{ number_format($payment->amount, 2) }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $payment->status === 'posted' ? 'success' : 'warning' }}">
                                                    {{ $payment->getStatusLabel() }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr class="table-light">
                                        <td colspan="3" class="text-end"><strong>@lang('messages.total-paid'):</strong></td>
                                        <td class="text-end fw-bold"><strong>Rs.
                                                {{ number_format($bill->getPaidAmount(), 2) }}</strong></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info mb-0">@lang('messages.no-payments-recorded')</div>
                    @endif

                    @if ($bill->canAcceptPayment())
                        <div class="mt-3">
                            <a href="{{ route('contractor-payments.make-payment-form', $bill->id) }}"
                                class="btn btn-primary">
                                <i class="fa fa-money-bill"></i> @lang('messages.make-payment')
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Actions -->
        <div class="row mt-3">
            <div class="col-12">
                @if ($bill->status === 'draft')
                    <form action="{{ route('contractor-bills.verify', $bill->id) }}" method="POST"
                        style="display:inline">
                        @csrf
                        <button type="submit" class="btn btn-success" onclick="return confirm('@lang('messages.confirm-verify')')">
                            <i class="fa fa-check"></i> @lang('messages.verify-bill')
                        </button>
                    </form>
                @endif

                <a href="{{ route('contractor-bills.print', $bill->id) }}" class="btn btn-secondary" target="_blank">
                    <i class="fa fa-print"></i> @lang('messages.print')
                </a>

                @if ($bill->canEdit())
                    <form action="{{ route('contractor-bills.destroy', $bill->id) }}" method="POST"
                        style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('@lang('messages.confirm-delete')')">
                            <i class="fa fa-trash"></i> @lang('messages.delete')
                        </button>
                    </form>
                @endif

                <a href="{{ route('contractor-bills.index') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i> @lang('messages.back')
                </a>
            </div>
        </div>
    </div>
@endsection
