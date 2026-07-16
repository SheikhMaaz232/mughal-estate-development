@extends('layouts.backend')

@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h1 class="h3 fw-bold mb-3">@lang('messages.make-payment')</h1>
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">@lang('messages.bill-no'): {{ $paymentData['bill_no'] }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <h4 class="alert-heading">@lang('messages.validation-errors')</h4>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">@lang('messages.payment-information')</h3>
            </div>

            <div class="block-content block-content-full">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title">@lang('messages.bill-details')</h6>
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>@lang('messages.bill-no'):</strong></td>
                                        <td>{{ $paymentData['bill_no'] }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>@lang('messages.contractor'):</strong></td>
                                        <td>{{ $paymentData['contractor_name'] }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>@lang('messages.tender'):</strong></td>
                                        <td>{{ $paymentData['tender_name'] }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title">@lang('messages.payment-status')</h6>
                                <table class="table table-sm">
                                    <tr>
                                        <td><strong>@lang('messages.bill-amount'):</strong></td>
                                        <td class="text-end fw-bold">Rs. {{ number_format($paymentData['bill_amount'], 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>@lang('messages.paid-amount'):</strong></td>
                                        <td class="text-end text-success fw-bold">Rs. {{ number_format($paymentData['paid_amount'], 2) }}</td>
                                    </tr>
                                    <tr style="border-top: 2px solid #dee2e6;">
                                        <td><strong>@lang('messages.outstanding'):</strong></td>
                                        <td class="text-end text-danger fw-bold">Rs. {{ number_format($paymentData['outstanding_amount'], 2) }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <form action="{{ route('contractor-payments.initiate', $paymentData['bill_id']) }}" method="POST" id="paymentForm">
                    @csrf

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="amount">@lang('messages.payment-amount') <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">Rs.</span>
                                <input type="number"
                                    name="amount"
                                    id="amount"
                                    class="form-control @error('amount') is-invalid @enderror"
                                    step="0.01"
                                    min="0.01"
                                    max="{{ $paymentData['max_payment_amount'] }}"
                                    placeholder="0.00"
                                    required>
                                <small class="form-text text-muted d-block mt-2">
                                    @lang('messages.max-available'): Rs. {{ number_format($paymentData['max_payment_amount'], 2) }}
                                </small>
                            </div>
                            @error('amount')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="voucher_type">@lang('messages.payment-method') <span class="text-danger">*</span></label>
                            <select name="voucher_type" id="voucher_type" class="form-select @error('voucher_type') is-invalid @enderror" required>
                                <option value="">@lang('messages.select-payment-method')</option>
                                <option value="BPV" {{ old('voucher_type') === 'BPV' ? 'selected' : '' }}>
                                    @lang('messages.bank-payment-voucher') (BPV)
                                </option>
                                <option value="CPV" {{ old('voucher_type') === 'CPV' ? 'selected' : '' }}>
                                    @lang('messages.cash-payment-voucher') (CPV)
                                </option>
                            </select>
                            @error('voucher_type')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label" for="remarks">@lang('messages.remarks')</label>
                            <textarea name="remarks"
                                id="remarks"
                                class="form-control @error('remarks') is-invalid @enderror"
                                rows="3"
                                placeholder="@lang('messages.enter-remarks')">{{ old('remarks') }}</textarea>
                            @error('remarks')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="alert alert-info" role="alert">
                        <i class="fa fa-info-circle"></i>
                        <strong>@lang('messages.important'):</strong>
                        @lang('messages.payment-process-info')
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-success">
                                <i class="fa fa-check"></i> @lang('messages.proceed-to-voucher')
                            </button>
                            <a href="{{ route('contractor-payments.bill', $paymentData['bill_id']) }}" class="btn btn-secondary">
                                <i class="fa fa-times"></i> @lang('messages.cancel')
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('amount').addEventListener('change', function() {
            let amount = parseFloat(this.value);
            let max = parseFloat(this.max);

            if (amount > max) {
                this.value = max;
                alert('Amount cannot exceed outstanding balance of Rs. ' + max.toFixed(2));
            }
        });
    </script>
@endsection
