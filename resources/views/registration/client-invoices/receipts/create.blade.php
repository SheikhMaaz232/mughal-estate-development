@extends('layouts.backend')

@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h1 class="h3 fw-bold mb-0">@lang('messages.link-receipt')</h1>
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">{{ $invoice->invoice_no }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="alert alert-info">
            <strong>@lang('messages.invoice-amount'):</strong> {{ number_format($invoice->amount, 2) }} |
            <strong>@lang('messages.total-received'):</strong> {{ number_format($invoice->total_received, 2) }} |
            <strong>@lang('messages.outstanding'):</strong> {{ number_format($invoice->outstanding_receivable, 2) }}
        </div>

        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">@lang('messages.select-voucher-to-link')</h3>
            </div>

            <div class="block-content block-content-full">
                <form action="{{ route('client-invoices.receipts.store', $invoice) }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="voucher_id" class="form-label">@lang('messages.select-voucher') <span class="text-danger">*</span></label>
                                <select name="voucher_id" id="voucher_id" class="form-select" required>
                                    <option value="">@lang('messages.select-voucher')</option>
                                    @foreach ($availableVouchers as $voucher)
                                        <option value="{{ $voucher->id }}"
                                                data-amount="{{ $voucher->total_debit }}"
                                                data-description="{{ $voucher->description_en }}">
                                            {{ $voucher->voucher_no }} - {{ $voucher->description_en }} ({{ $voucher->date->format('d M Y') }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="amount" class="form-label">@lang('messages.receipt-amount') <span class="text-danger">*</span></label>
                                <input type="number" name="amount" id="amount" class="form-control"
                                       step="0.01" min="0.01" max="{{ $invoice->outstanding_receivable }}" required>
                                <div class="form-text">@lang('messages.max-amount'): {{ number_format($invoice->outstanding_receivable, 2) }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div id="voucher-details" class="mb-3" style="display: none;">
                                <div class="card border-info">
                                    <div class="card-header bg-light">
                                        <h5 class="card-title mb-0">@lang('messages.voucher-details')</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <strong>@lang('messages.voucher-no'):</strong> <span id="voucher-no"></span><br>
                                                <strong>@lang('messages.date'):</strong> <span id="voucher-date"></span><br>
                                            </div>
                                            <div class="col-md-6">
                                                <strong>@lang('messages.amount'):</strong> <span id="voucher-amount"></span><br>
                                                <strong>@lang('messages.description'):</strong> <span id="voucher-description"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-link me-1"></i> @lang('messages.link-receipt')
                            </button>
                            <a href="{{ route('client-invoices.receipts.show', $invoice) }}" class="btn btn-secondary">
                                <i class="fa fa-times me-1"></i> @lang('messages.cancel')
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @if ($availableVouchers->count() === 0)
            <div class="block block-rounded">
                <div class="block-content block-content-full">
                    <div class="text-center py-5">
                        <i class="fa fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                        <h4 class="text-muted">@lang('messages.no-available-vouchers')</h4>
                        <p class="text-muted">@lang('messages.no-available-vouchers-description')</p>
                        <a href="{{ route('bank-receipt-voucher.create') }}" class="btn btn-primary me-2" target="_blank">
                            <i class="fa fa-plus me-1"></i> @lang('messages.create-brv')
                        </a>
                        <a href="{{ route('cash-receipt-voucher.create') }}" class="btn btn-info" target="_blank">
                            <i class="fa fa-plus me-1"></i> @lang('messages.create-crv')
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const voucherSelect = document.getElementById('voucher_id');
    const amountInput = document.getElementById('amount');
    const voucherDetails = document.getElementById('voucher-details');

    voucherSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];

        if (this.value) {
            // Show voucher details
            document.getElementById('voucher-no').textContent = selectedOption.text.split(' - ')[0];
            document.getElementById('voucher-amount').textContent = selectedOption.getAttribute('data-amount');
            document.getElementById('voucher-description').textContent = selectedOption.getAttribute('data-description');
            document.getElementById('voucher-date').textContent = selectedOption.text.split('(')[1]?.replace(')', '') || '';

            voucherDetails.style.display = 'block';

            // Auto-fill amount if voucher amount is less than or equal to outstanding
            const voucherAmount = parseFloat(selectedOption.getAttribute('data-amount')) || 0;
            const maxAmount = parseFloat(amountInput.getAttribute('max')) || 0;

            if (voucherAmount <= maxAmount) {
                amountInput.value = voucherAmount;
            } else {
                amountInput.value = maxAmount;
            }
        } else {
            voucherDetails.style.display = 'none';
            amountInput.value = '';
        }
    });
});
</script>
@endsection
