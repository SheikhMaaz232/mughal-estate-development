@extends('layouts.backend')

@section('content')
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h1 class="h3 fw-bold mb-3">@lang('messages.create-contractor-bill')</h1>
            {{-- <h2 class="block-title">@lang('messages.create-new-bill')</h2> --}}
        </div>
        <div class="block-content block-content-full">
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

            <form action="{{ route('contractor-bills.store') }}" method="POST" id="billForm" class="js-validation">
                @csrf

                <div class="block block-rounded">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">@lang('messages.bill-information')</h3>
                    </div>

                    <div class="block-content block-content-full">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="tender_id">@lang('messages.tender') <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control"
                                    value="{{ App::getLocale() === 'ur' ? $workOrderData->tender->title_ur : $workOrderData->tender->title_en }}"
                                    disabled>
                                <input type="hidden" name="tender_id" value="{{ $workOrderData->tender->id }}">
                                @error('tender_id')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="work_order_id">@lang('messages.work_order') <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control"
                                    value="{{ App::getLocale() === 'ur' ? $workOrderData->description_ur : $workOrderData->description_en }}"
                                    disabled>
                                <input type="hidden" name="work_order_id" value="{{ $workOrderData->id }}">
                                @error('work_order_id')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="contractor_account_id">@lang('messages.contractor') <span
                                        class="text-danger">*</span></label>

                                <input type="text" class="form-control"
                                    value="{{ App::getLocale() === 'ur' ? $workOrderData->tender->contractorAccount->name_ur : $workOrderData->tender->contractorAccount->name_en }}"
                                    disabled>
                                <input type="hidden" name="contractor_account_id"
                                    value="{{ $workOrderData->tender->contractor_account_id }}">

                                @error('contractor_account_id')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="bill_date">@lang('messages.bill-date') <span
                                        class="text-danger">*</span></label>
                                <input type="date" name="bill_date" id="bill_date"
                                    class="form-control @error('bill_date') is-invalid @enderror"
                                    value="{{ old('bill_date', date('Y-m-d')) }}" required>
                                @error('bill_date')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">

                            <label class="form-label" for="remarks">@lang('messages.remarks')</label>
                            <textarea name="remarks" id="remarks" class="form-control" rows="2" placeholder="@lang('messages.enter-remarks')">{{ old('remarks') }}</textarea>
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
                                        <th>@lang('messages.completed_quantity')</th>
                                        <th>@lang('messages.billed-quantity')</th>
                                        <th>@lang('messages.remaining-quantity')</th>
                                        <th>@lang('messages.qty') <span class="text-danger">*</span></th>
                                        <th>@lang('messages.rate') <span class="text-danger">*</span></th>
                                        <th>@lang('messages.amount')</th>
                                        <th class="text-center" style="width: 50px;">@lang('messages.action')</th>
                                    </tr>
                                </thead>
                                <tbody id="itemsBody">
                                    <!-- Rows will be added here -->
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            <button type="button" class="btn btn-sm btn-outline-primary" id="addItemBtn">
                                <i class="fa fa-plus"></i> @lang('messages.add-item')
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
                                                <input type="number" name="total_amount" id="totalAmount"
                                                    class="form-control @error('total_amount') is-invalid @enderror"
                                                    placeholder="@lang('messages.total_amount')" step="0.01"
                                                    value="{{ old('total_amount', 0) }}" readonly>
                                                @error('total_amount')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                @enderror
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
                            <i class="fa fa-save"></i> @lang('messages.create')
                        </button>
                        <a href="{{ route('contractor-bills.index') }}" class="btn btn-secondary">
                            <i class="fa fa-arrow-left"></i> @lang('messages.back')
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Item Modal -->
    <div class="modal fade" id="addItemModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('messages.select-item')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">@lang('messages.available-items')</label>
                        <select id="itemSelect" class="form-select select2 custom-select">
                            <option value="">@lang('messages.select-item')</option>
                            @foreach ($availableItems as $item)
                                <option value="{{ $item['id'] }}" data-rate="{{ $item['rate'] }}"
                                    data-completed="{{ $item['completed_quantity'] }}"
                                    data-billed="{{ $item['billed_quantity'] }}"
                                    data-remaining="{{ $item['remaining_quantity'] }}">
                                    {{ App::getLocale() === 'ur' ? $item['item_name_ur'] ?? $item['item_name_en'] : $item['item_name_en'] }}
                                    (@lang('messages.remaining'): {{ $item['remaining_quantity'] }}
                                    {{ App::getLocale() === 'ur' ? $item['unit_ur'] ?? $item['unit_en'] : $item['unit_en'] }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('messages.cancel')</button>
                    <button type="button" class="btn btn-primary" id="confirmAddBtn">@lang('messages.add')</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        const currentLocale = "{{ App::getLocale() }}";
    </script>

    <script>
        let currentIndex = 0;
        let availableItems = @json($availableItems);

        document.getElementById('addItemBtn').addEventListener('click', function() {
            const modal = new bootstrap.Modal(document.getElementById('addItemModal'));
            modal.show();
        });

        document.getElementById('confirmAddBtn').addEventListener('click', function() {
            const select = document.getElementById('itemSelect');

            if (!select.value) {
                alert('@lang('messages.please-select-an-item')');
                return;
            }

            const itemId = select.value;
            const item = availableItems.find(i => i.id == itemId);

            addNewRow(item);
            bootstrap.Modal.getInstance(document.getElementById('addItemModal')).hide();
            select.value = '';
        });

        function addNewRow(item) {
            const tbody = document.getElementById('itemsBody');
            const rowId = currentIndex++;
            const row = tbody.insertRow();
            row.id = 'row_' + rowId;
            const itemName = currentLocale === 'ur' ?
                (item.item_name_ur || item.item_name_en) :
                item.item_name_en;
            const unitName = currentLocale === 'ur' ?
                (item.unit_ur || item.unit_en) :
                item.unit_en;

            row.className = 'item-row';
            row.innerHTML = `
                <td>${itemName}</td>
                <td>${unitName}</td>
                <td><strong>${item.completed_quantity}</strong></td>
                <td><strong>${item.billed_quantity}</strong></td>
                <td><strong class="remaining-qty_${rowId}">${item.remaining_quantity}</strong></td>
                <td>
                    <input type="hidden" name="items[${rowId}][boq_item_id]" value="${item.id}">
                    <input type="number" name="items[${rowId}][quantity]" class="form-control form-control-sm quantity-input_${rowId}" step="0.0001" min="0" max="${item.remaining_quantity}" required value="">
                </td>
                <td>
                    <input type="number" name="items[${rowId}][rate]" class="form-control form-control-sm rate-input_${rowId}" step="0.01" min="0" required value="${item.rate}">
                </td>
                <td>
                    <input type="number" class="form-control form-control-sm amount_${rowId}" step="0.0001" min="0" value="" readonly>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-danger" onclick="removeRow('row_${rowId}')">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            `;

            attachRowListeners(rowId, item.remaining_quantity);
        }

        function attachRowListeners(rowId, maxQuantity) {
            const quantityInput = document.querySelector(`.quantity-input_${rowId}`);
            const rateInput = document.querySelector(`.rate-input_${rowId}`);

            [quantityInput, rateInput].forEach(input => {
                input.addEventListener('input', function() {
                    validateQuantity(rowId, maxQuantity);
                    calculateRowAmount(rowId);
                });
            });
        }

        function calculateRowAmount(rowId) {
            const quantity = parseFloat(document.querySelector(`.quantity-input_${rowId}`).value) || 0;
            const rate = parseFloat(document.querySelector(`.rate-input_${rowId}`).value) || 0;
            const amount = quantity * rate;
            document.querySelector(`.amount_${rowId}`).value = amount.toFixed(2);
            calculateTotal();
        }

        function validateQuantity(rowId, maxQuantity) {
            const quantityInput = document.querySelector(`.quantity-input_${rowId}`);
            const quantity = parseFloat(quantityInput.value) || 0;

            if (quantity > maxQuantity) {
                quantityInput.classList.add('is-invalid');
                alert(`@lang('messages.quantity-exceeds') ${maxQuantity}`);
                quantityInput.value = '';
                quantityInput.classList.remove('is-invalid');
            }
        }

        function calculateTotal() {
            let total = 0;
            console.log('Calculated total:', total);
            document.querySelectorAll('.item-row').forEach(row => {
                const amount = parseFloat(row.querySelector('input[name="items[][amount]"]').value) || 0;
                total += amount;
            });

            document.getElementById('totalAmount').value = total.toFixed(2);

        }

        function removeRow(rowId) {
            document.getElementById(rowId).remove();
            calculateTotal();
        }

        // Form validation
        document.getElementById('billForm').addEventListener('submit', function(e) {
            const rows = document.querySelectorAll('.item-row');
            if (rows.length === 0) {
                alert('@lang('messages.please-add-at-least-one-item')');
                e.preventDefault();
                return false;
            }

            rows.forEach(row => {
                const boqId = row.querySelector('input[name="items[][boq_item_id]"]').value;
                const quantity = row.querySelector('input[name="items[][quantity]"]').value;
                const rate = row.querySelector('input[name="items[][rate]"]').value;

                if (!boqId || !quantity || !rate) {
                    row.remove();
                }
            });

            if (document.querySelectorAll('.item-row').length === 0) {
                alert('@lang('messages.please-add-at-least-one-item')');
                e.preventDefault();
                return false;
            }
        });
    </script>
@endsection
