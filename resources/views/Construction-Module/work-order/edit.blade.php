@extends('layouts.backend')

@section('content')
    <div class="block block-rounded col-md-12">
        <div class="block-header block-header-default">
            <h3 class="block-title">@lang('messages.edit-work-order')</h3>
        </div>
        <div class="block-content block-content-full">
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show">
                    <strong>@lang('messages.validation-error')</strong>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('work-orders.update', $workOrder->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Master Information -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">@lang('messages.construction-site')</label>
                        <input type="text" class="form-control"
                            value="{{ App::getLocale() === 'ur' ? $workOrder->constructionSite->name_ur : $workOrder->constructionSite->name_en }}"
                            disabled>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">@lang('messages.tender')</label>
                        <input type="text" class="form-control"
                            value="{{ App::getLocale() === 'ur' ? $workOrder->tender->title_ur : $workOrder->tender->title_en }}"
                            disabled>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">@lang('messages.boq')</label>
                        <input type="text" class="form-control"
                            value="{{ App::getLocale() === 'ur' ? $workOrder->boqMaster->title_ur : $workOrder->boqMaster->title_en }}"
                            disabled>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">@lang('messages.status') <span class="text-danger">*</span></label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="pending" {{ $workOrder->status == 'pending' ? 'selected' : '' }}>
                                @lang('messages.pending')</option>
                            <option value="in_progress" {{ $workOrder->status == 'in_progress' ? 'selected' : '' }}>
                                @lang('messages.in_progress')</option>
                            <option value="completed" {{ $workOrder->status == 'completed' ? 'selected' : '' }}>
                                @lang('messages.completed')</option>
                        </select>
                        @error('status')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">@lang('messages.start-date') <span class="text-danger">*</span></label>
                        <input type="date" name="start_date"
                            class="form-control @error('start_date') is-invalid @enderror"
                            value="{{ old('start_date', $workOrder->start_date->format('Y-m-d')) }}" required>
                        @error('start_date')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">@lang('messages.end-date') <span class="text-danger">*</span></label>
                        <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror"
                            value="{{ old('end_date', $workOrder->end_date->format('Y-m-d')) }}" required>
                        @error('end_date')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">@lang('messages.description') @lang('messages.english')</label>
                    <textarea name="description_en" class="form-control @error('description_en') is-invalid @enderror" rows="3">{{ old('description_en', $workOrder->description_en) }}</textarea>
                    @error('description_en')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">@lang('messages.description') @lang('messages.urdu')</label>
                    <textarea name="description_ur" class="form-control @error('description_ur') is-invalid @enderror" rows="3">{{ old('description_ur', $workOrder->description_ur) }}</textarea>
                    @error('description_ur')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Work Order Items -->
                <div class="block block-rounded mt-4">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">@lang('messages.work-order-items')</h3>
                    </div>
                    <div class="block-content block-content-full">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>@lang('messages.item')</th>
                                        <th>@lang('messages.boq-quantity')</th>
                                        <th>@lang('messages.used-quantity')</th>
                                        <th>@lang('messages.remaining')</th>
                                        <th>@lang('messages.qty') <span class="text-danger">*</span></th>
                                        <th>@lang('messages.rate') <span class="text-danger">*</span></th>
                                        <th>@lang('messages.gross-amount')</th>
                                        <th>@lang('messages.action')</th>
                                    </tr>
                                </thead>
                                <tbody id="itemsTableBody">
                                    @forelse($workOrder->items as $index => $item)

                                        <tr id="row_{{ $index }}" class="item-row">
                                            <td>{{ App::getLocale() === 'ur' ? optional($item->boqItem->item)->name_ur : optional($item->boqItem->item)->name_en }}
                                            </td>
                                            {{-- <td><strong>{{ optional($item->boqItem)->quantity }}</strong></td>
                                            <td><strong>{{ optional($item->boqItem)->quantity - ($availableItems->firstWhere('id', $item->boq_item_id)['remaining_quantity'] ?? 0) }}</strong></td> --}}
                                            <td><strong>{{ $item->boqItem->quantity }}</strong></td>

                                            <td>
                                                <strong>
                                                    {{ $item->boqItem->quantity - $availableItems->firstWhere('id', $item->boq_item_id)['remaining_quantity'] }}
                                                </strong>
                                            </td>
                                            <td><strong
                                                    class="remaining-qty_{{ $index }}">{{ ($availableItems->firstWhere('id', $item->boq_item_id)['remaining_quantity'] ?? 0) + $item->quantity }}</strong>
                                            </td>
                                            <td>
                                                <input type="hidden" name="boq_item_id[]"
                                                    value="{{ $item->boq_item_id }}">
                                                <input type="number" name="quantity[]"
                                                    class="form-control quantity-input_{{ $index }}" step="0.0001"
                                                    min="0"
                                                    max="{{ ($availableItems->firstWhere('id', $item->boq_item_id)['remaining_quantity'] ?? 0) + $item->quantity }}"
                                                    required value="{{ $item->quantity }}">
                                            </td>
                                            <td>
                                                <input type="number" name="rate[]"
                                                    class="form-control rate-input_{{ $index }}" step="0.01"
                                                    min="0" required value="{{ $item->rate }}">
                                            </td>
                                            <td><strong
                                                    class="amount_{{ $index }}">{{ number_format($item->amount, 2) }}</strong>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-danger"
                                                    onclick="removeRow('row_{{ $index }}')">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <!-- No items will show empty, user adds via button -->
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <button type="button" class="btn btn-success mt-3" id="addRowBtn">
                            <i class="fa fa-plus"></i> @lang('messages.add-item')
                        </button>
                    </div>

                    <div class="block-content pt-3 border-top">
                        <div class="row">
                            <div class="col-md-6 text-end">
                                <h5>@lang('messages.total_amount'): <span id="totalAmount" class="text-primary fw-bold">0.00</span>
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save"></i> @lang('messages.save')
                    </button>
                    <a href="{{ route('work-orders.show', $workOrder->id) }}" class="btn btn-secondary">
                        @lang('messages.cancel')
                    </a>
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
                        <select id="itemSelect" class="form-select">
                            <option value="">@lang('messages.select-item')</option>
                            @foreach ($availableItems as $item)
                                <option value="{{ $item['id'] }}" data-rate="{{ $item['rate'] }}"
                                    data-quantity="{{ $item['remaining_quantity'] }}">
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
        let currentIndex = document.querySelectorAll('#itemsTableBody tr.item-row').length;
        let availableItems = @json($availableItems);

        // Initialize listeners for existing edit rows
        document.querySelectorAll('#itemsTableBody tr.item-row').forEach(row => {
            const rowId = row.id.replace('row_', '');
            const maxQuantity = parseFloat(document.querySelector(`.quantity-input_${rowId}`).max) || 0;
            attachRowListeners(rowId, maxQuantity);
        });
        refreshItemSelectOptions();
        calculateTotal();

        document.getElementById('addRowBtn').addEventListener('click', function() {
            const modal = new bootstrap.Modal(document.getElementById('addItemModal'));
            modal.show();
        });

        document.getElementById('confirmAddBtn').addEventListener('click', function() {
            const select = document.getElementById('itemSelect');
            const selectedIds = getSelectedBoqItemIds();

            if (!select.value) {
                alert('@lang('messages.please-select-an-item')');
                return;
            }

            if (selectedIds.includes(select.value)) {
                alert('This item is already selected.');
                return;
            }

            const itemId = select.value;
            const item = availableItems.find(i => i.id == itemId);

            addNewRow(item);
            bootstrap.Modal.getInstance(document.getElementById('addItemModal')).hide();
            select.value = '';
            refreshItemSelectOptions();
        });

        function addNewRow(item) {

            const tbody = document.getElementById('itemsTableBody');
            const rowId = currentIndex++;
            const row = tbody.insertRow();
            row.id = 'row_' + rowId;
            const itemName = currentLocale === 'ur' ?
                (item.item_name_ur || item.item_name_en) :
                item.item_name_en;
            row.className = 'item-row';
            row.innerHTML = `
            <td>${itemName}</td>
            <td><strong>${item.total_quantity}</strong></td>
            <td><strong>${item.used_quantity}</strong></td>
            <td><strong class="remaining-qty_${rowId}">${item.remaining_quantity}</strong></td>
            <td>
                <input type="hidden" name="boq_item_id[]" value="${item.id}">
                <input type="number" name="quantity[]" class="form-control quantity-input_${rowId}" step="0.0001" min="0" max="${item.remaining_quantity}" required value="">
            </td>
            <td>
                <input type="number" name="rate[]" class="form-control rate-input_${rowId}" step="0.01" min="0" required value="${item.rate}">
            </td>
            <td><strong class="amount_${rowId}">0.00</strong></td>
            <td>
                <button type="button" class="btn btn-sm btn-danger" onclick="removeRow('row_${rowId}')">
                    <i class="fa fa-trash"></i>
                </button>
            </td>
        `;

            attachRowListeners(rowId, item.remaining_quantity);
            refreshItemSelectOptions();
        }

        function getSelectedBoqItemIds() {
            return Array.from(document.querySelectorAll('input[name="boq_item_id[]"]')).map(input => input.value);
        }

        function refreshItemSelectOptions() {
            const selectedIds = getSelectedBoqItemIds();
            const select = document.getElementById('itemSelect');

            Array.from(select.options).forEach(option => {
                if (!option.value) {
                    return;
                }

                option.disabled = selectedIds.includes(option.value);
            });
        }

        //     function addNewRow(item) {
        //         const tbody = document.getElementById('itemsTableBody');
        //         const rowId = currentIndex++;

        //         // ✅ Select name based on language
        //         const itemName = currentLocale === 'ur' ?
        //             (item.item_name_ur || item.item_name_en) :
        //             item.item_name_en;

        //         const row = tbody.insertRow();
        //         row.id = 'row_' + rowId;
        //         row.className = 'item-row';

        //         row.innerHTML = `
    //     <td>${itemName}</td>
    //     <td><strong>${item.total_quantity}</strong></td>
    //     <td><strong>${item.used_quantity}</strong></td>
    //     <td><strong class="remaining-qty_${rowId}">${item.remaining_quantity}</strong></td>
    //     <td>
    //         <input type="hidden" name="boq_item_id[]" value="${item.id}">
    //         <input type="number" name="quantity[]" class="form-control quantity-input_${rowId}" step="0.0001" min="0" max="${item.remaining_quantity}" required value="">
    //     </td>
    //     <td>
    //         <input type="number" name="rate[]" class="form-control rate-input_${rowId}" step="0.01" min="0" required value="${item.rate}">
    //     </td>
    //     <td><strong class="amount_${rowId}">0.00</strong></td>
    //     <td>
    //         <button type="button" class="btn btn-sm btn-danger" onclick="removeRow('row_${rowId}')">
    //             <i class="fa fa-trash"></i>
    //         </button>
    //     </td>
    // `;

        //         attachRowListeners(rowId, item.remaining_quantity);
        //     }

        function attachRowListeners(rowId, maxQuantity) {
            const quantityInput = document.querySelector(`.quantity-input_${rowId}`);
            const rateInput = document.querySelector(`.rate-input_${rowId}`);

            [quantityInput, rateInput].forEach(input => {
                input.addEventListener('input', function() {
                    calculateRowAmount(rowId);
                    validateQuantity(rowId, maxQuantity);
                });
            });
        }

        function calculateRowAmount(rowId) {
            const quantity = parseFloat(document.querySelector(`.quantity-input_${rowId}`).value) || 0;
            const rate = parseFloat(document.querySelector(`.rate-input_${rowId}`).value) || 0;
            console.log(`Calculating amount for row ${rowId}: quantity=${quantity}, rate=${rate}`);
            const amount = quantity * rate;

            document.querySelector(`.amount_${rowId}`).textContent = amount.toFixed(2);
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
            document.querySelectorAll('tbody tr').forEach(row => {
                const amount = parseFloat(row.querySelector('strong[class*="amount_"]').textContent) || 0;
                total += amount;
            });
            console.log('Total calculated:', total);
            document.getElementById('totalAmount').textContent = total.toFixed(2);
        }

        function removeRow(rowId) {
            document.getElementById(rowId).remove();
            refreshItemSelectOptions();

            calculateTotal();
        }
    </script>
@endsection
