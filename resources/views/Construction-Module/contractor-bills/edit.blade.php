@extends('layouts.backend')

@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h1 class="h3 fw-bold mb-3">@lang('messages.edit-contractor-bill')</h1>
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">@lang('messages.update-bill') {{ $bill->bill_no }}</h2>
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

        <form action="{{ route('contractor-bills.update', $bill->id) }}" method="POST" id="billForm">
            @csrf
            @method('PUT')
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">@lang('messages.bill-information')</h3>
                </div>

                <div class="block-content block-content-full">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">@lang('messages.tender')</label>
                            <input type="text" class="form-control"
                                value="{{ App::getLocale() === 'ur' ? $bill->tender->title_ur : $bill->tender->title_en }}"
                                disabled>
                            <input type="hidden" name="tender_id" value="{{ $bill->tender_id }}">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">@lang('messages.work_order')</label>
                            <input type="text" class="form-control"
                                value="{{ $bill->workOrder->id }}{{ App::getLocale() === 'ur' ? $bill->workOrder->description_ur : $bill->workOrder->description_en }}"
                                disabled>
                            <input type="hidden" name="work_order_id" value="{{ $bill->work_order_id }}">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">@lang('messages.contractor')</label>
                            <input type="text" class="form-control"
                                value="{{ App::getLocale() === 'ur' ? $bill->tender->contractorAccount->party->name_ur : $bill->tender->contractorAccount->party->name_en }}"
                                disabled>
                            <input type="hidden" name="contractor_account_id"
                                value="{{ $bill->tender->contractor_account_id }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label" for="bill_date">@lang('messages.bill-date') <span
                                    class="text-danger">*</span></label>
                            <input type="date" name="bill_date" id="bill_date"
                                class="form-control @error('bill_date') is-invalid @enderror"
                                value="{{ old('bill_date', $bill->bill_date->format('Y-m-d')) }}" required>
                            @error('bill_date')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-8 mb-3">
                            <label class="form-label" for="remarks">@lang('messages.remarks')</label>
                            <textarea name="remarks" id="remarks" class="form-control" rows="2">{{ old('remarks', $bill->remarks) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Items Section -->
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">@lang('messages.bill-items')</h3>
                </div>

                <div class="block-content block-content-full">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm" id="itemsTable">
                            <thead class="table-light">
                                <tr>
                                    <th>@lang('messages.boq-item')</th>
                                    <th>@lang('messages.unit')</th>
                                    <th>@lang('messages.remaining-quantity')</th>
                                    <th>@lang('messages.quantity') <span class="text-danger">*</span></th>
                                    <th>@lang('messages.rate') <span class="text-danger">*</span></th>
                                    <th>@lang('messages.amount')</th>
                                    <th class="text-center" style="width: 50px;">@lang('messages.action')</th>
                                </tr>
                            </thead>
                            <tbody id="itemsBody">
                                @forelse($bill->items as $item)
                                    <tr class="item-row" id="row_{{ $loop->index }}">
                                        <td>
                                            <select class="form-control form-control-sm boq_item_id"
                                                name="items[][boq_item_id]">
                                                @foreach ($availableItems as $availItem)
                                                    <option value="{{ $availItem['id'] }}"
                                                        {{ $availItem['id'] == $item->boq_item_id ? 'selected' : '' }}
                                                        data-unit="{{ $availItem['unit_en'] }}"
                                                        data-unit-ur="{{ $availItem['unit_ur'] }}"
                                                        data-remaining="{{ $availItem['remaining_quantity'] }}"
                                                        data-rate="{{ $availItem['rate'] }}">
                                                        {{ App::getLocale() === 'ur' ? $availItem['item_name_ur'] : $availItem['item_name_en'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td><span class="unit_{{ $loop->index }}">{{ $item->boqItem->unit }}</span></td>
                                        <td><span
                                                class="remaining_qty_{{ $loop->index }} text-end">{{ $item->getRemainigCompletedQuantity() }}</span>
                                        </td>
                                        <td><input type="number"
                                                class="form-control form-control-sm quantity-input_{{ $loop->index }}"
                                                name="items[][quantity]" step="0.0001" min="0"
                                                value="{{ $item->quantity }}" required></td>
                                        <td><input type="number"
                                                class="form-control form-control-sm rate-input_{{ $loop->index }}"
                                                name="items[][rate]" step="0.01" min="0"
                                                value="{{ $item->rate }}" required></td>
                                        <td><input type="number"
                                                class="form-control form-control-sm amount_{{ $loop->index }}"
                                                name="items[][amount]" step="0.0001" min="0" readonly
                                                value="{{ number_format($item->amount, 4) }}"></td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-danger"
                                                onclick="removeRow('row_{{ $loop->index }}')">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            @lang('messages.no-items-added')
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        <button type="button" class="btn btn-sm btn-outline-primary" id="addItemBtn">
                            <i class="fa fa-plus"></i> @lang('messages.add-item-row')
                        </button>
                    </div>

                    <!-- Total Section -->
                    <div class="row mt-4">
                        <div class="col-md-8"></div>
                        <div class="col-md-4">
                            <div class="block block-rounded" style="background-color: #f0f0f0;">
                                <div class="block-content">
                                    <div class="row mb-2">
                                        <div class="col-6">
                                            <strong>@lang('messages.total_amount'):</strong>
                                        </div>
                                        <div class="col-6 text-end">
                                            <h5 id="totalAmount">{{ number_format($bill->amount, 2) }}</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="row mt-3">
                <div class="col-12">
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-save"></i> @lang('messages.update')
                    </button>
                    <a href="{{ route('contractor-bills.show', $bill->id) }}" class="btn btn-secondary">
                        <i class="fa fa-arrow-left"></i> @lang('messages.back')
                    </a>
                </div>
            </div>
        </form>
    </div>

    <script>
        const workOrderId = {{ $bill->work_order_id }};
        const billId = {{ $bill->id }};
        const currentLang = "{{ app()->getLocale() }}";
        const availableItems = @json($availableItems);
        let currentIndex = {{ $bill->items->count() }};

        document.addEventListener('DOMContentLoaded', function() {
            // Attach handlers to existing rows on page load
            const existingRows = document.querySelectorAll('.item-row');
            existingRows.forEach((row) => {
                const rowId = row.id.replace('row_', '');
                attachRowHandlers(rowId);
            });

            // Calculate totals on load
            calculateTotal();

            // Add item button
            document.getElementById('addItemBtn').addEventListener('click', function() {
                addItemRow();
            });
        });

        function addItemRow(item = null) {
            const tbody = document.getElementById('itemsBody');

            // Remove "no items" message if exists
            const noItemsRow = tbody.querySelector('tr td[colspan="7"]');
            if (noItemsRow) {
                noItemsRow.parentElement.remove();
            }

            const rowId = currentIndex++;
            const row = document.createElement('tr');
            row.className = 'item-row';
            row.id = 'row_' + rowId;

            // Build item options
            let options = `<option value="">{{ addslashes(__('messages.select-item')) }}</option>`;
            availableItems.forEach(availItem => {
                const itemName = currentLang === 'ur' ? (availItem.item_name_ur || availItem.item_name_en) : availItem.item_name_en;
                const selected = item && item.id == availItem.id ? ' selected' : '';
                options += `<option value="${availItem.id}"
                    data-unit="${availItem.unit_en}"
                    data-unit-ur="${availItem.unit_ur}"
                    data-remaining="${availItem.remaining_quantity}"
                    data-rate="${availItem.rate}"
                    ${selected}>${itemName}</option>`;
            });

            const itemUnit = item ? (currentLang === 'ur' ? (item.unit_ur || item.unit_en) : item.unit_en) : '-';
            const itemRemaining = item ? item.remaining_quantity : '-';

            row.innerHTML = `
                <td>
                    <select class="form-control form-control-sm boq_item_id" name="items[][boq_item_id]">
                        ${options}
                    </select>
                </td>
                <td><span class="unit_${rowId}">${itemUnit}</span></td>
                <td><span class="remaining_qty_${rowId} text-end">${itemRemaining}</span></td>
                <td><input type="number" class="form-control form-control-sm quantity-input_${rowId}" name="items[][quantity]" step="0.0001" min="0" required></td>
                <td><input type="number" class="form-control form-control-sm rate-input_${rowId}" name="items[][rate]" step="0.01" min="0" value="${item ? item.rate : ''}" required></td>
                <td><input type="number" class="form-control form-control-sm amount_${rowId}" name="items[][amount]" step="0.0001" min="0" value="0.0000" readonly></td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-danger" onclick="removeRow('row_${rowId}')">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            `;

            tbody.appendChild(row);
            attachRowHandlers(rowId);
        }

        function attachRowHandlers(rowId) {
            const boqSelect = document.querySelector(`#row_${rowId} .boq_item_id`);
            const quantityInput = document.querySelector(`.quantity-input_${rowId}`);
            const rateInput = document.querySelector(`.rate-input_${rowId}`);

            // Item selection change
            if (boqSelect) {
                boqSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const unitEn = selectedOption.dataset.unit || '-';
                    const unitUr = selectedOption.dataset.unitUr || '-';
                    const remaining = selectedOption.dataset.remaining || '-';
                    const rate = selectedOption.dataset.rate || '0';

                    const unitSpan = document.querySelector(`.unit_${rowId}`);
                    const remainingSpan = document.querySelector(`.remaining_qty_${rowId}`);

                    if (unitSpan) {
                        unitSpan.textContent = currentLang === 'ur' ? unitUr : unitEn;
                    }
                    if (remainingSpan) {
                        remainingSpan.textContent = remaining;
                    }

                    if (rateInput) {
                        rateInput.value = parseFloat(rate).toFixed(2);
                    }

                    // Get remaining quantity from backend
                    if (this.value) {
                        fetch('{{ route('contractor-bills.getRemainingQuantity') }}?boq_item_id=' + this.value + '&work_order_id=' + workOrderId + '&bill_id=' + billId)
                            .then(response => response.json())
                            .then(data => {
                                if (remainingSpan) {
                                    remainingSpan.textContent = data.remaining_qty;
                                }
                            });
                    }
                });
            }

            // Quantity and rate input change
            [quantityInput, rateInput].forEach(input => {
                if (input) {
                    input.addEventListener('change', function() {
                        calculateRowAmount(rowId);
                    });
                    input.addEventListener('keyup', function() {
                        calculateRowAmount(rowId);
                    });
                    input.addEventListener('input', function() {
                        calculateRowAmount(rowId);
                    });
                }
            });
        }

        function calculateRowAmount(rowId) {
            const quantity = parseFloat(document.querySelector(`.quantity-input_${rowId}`).value) || 0;
            const rate = parseFloat(document.querySelector(`.rate-input_${rowId}`).value) || 0;
            const amount = quantity * rate;
            const amountField = document.querySelector(`.amount_${rowId}`);

            if (amountField) {
                amountField.value = amount.toFixed(4);
            }

            calculateTotal();
        }

        function calculateTotal() {
            let total = 0;
            document.querySelectorAll('.item-row').forEach(row => {
                const amountInput = row.querySelector('input[name="items[][amount]"]');
                if (amountInput) {
                    const amount = parseFloat(amountInput.value) || 0;
                    total += amount;
                }
            });
            document.getElementById('totalAmount').textContent = total.toFixed(2);
        }

        function removeRow(rowId) {
            const row = document.getElementById(rowId);
            if (row) {
                row.remove();
                calculateTotal();
            }
        }

        // Form submission validation
        document.getElementById('billForm').addEventListener('submit', function(e) {
            const rows = document.querySelectorAll('.item-row');
            if (rows.length === 0) {
                alert('@lang('messages.please-add-at-least-one-item')');
                e.preventDefault();
                return false;
            }

            let hasValidItems = false;
            rows.forEach(row => {
                const boqId = row.querySelector('input[name="items[][boq_item_id]"]') || row.querySelector('select.boq_item_id');
                const quantity = row.querySelector('input[name="items[][quantity]"]');
                const rate = row.querySelector('input[name="items[][rate]"]');

                if (boqId && quantity && rate) {
                    const boqValue = boqId.value || boqId.querySelector('option:checked')?.value;
                    if (boqValue && quantity.value && rate.value) {
                        hasValidItems = true;
                    }
                }
            });

            if (!hasValidItems) {
                alert('@lang('messages.please-add-at-least-one-item')');
                e.preventDefault();
                return false;
            }
        });
    </script>
@endsection
