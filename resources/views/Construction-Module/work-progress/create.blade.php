@extends('layouts.backend')

@section('content')
    <div class="block block-rounded col-md-12">
        <div class="block-header block-header-default">
            <h3 class="block-title">@lang('messages.create-work-progress')</h3>
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

            <form action="{{ route('work-progress.store') }}" method="POST">
                @csrf
                <!-- Master Information -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">@lang('messages.construction-site')</label>
                        <input type="text" class="form-control"
                            value="{{ App::getLocale() === 'ur' ? $workOrderData->constructionSite->name_ur : $workOrderData->constructionSite->name_en }}"
                            disabled>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">@lang('messages.tender')</label>
                        <input type="text" class="form-control"
                            value="{{ App::getLocale() === 'ur' ? $workOrderData->tender->title_ur : $workOrderData->tender->title_en }}"
                            disabled>

                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">@lang('messages.work_order')</label>
                        <input type="text" class="form-control"
                            value="{{ App::getLocale() === 'ur' ? $workOrderData->description_ur : $workOrderData->description_en }}"
                            disabled>
                        <input type="hidden" name="work_order_id" value="{{ $workOrderData->id }}">
                        @error('work_order_id')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">@lang('messages.date')</label>
                        <input type="date" name="date" class="form-control @error('date') is-invalid @enderror"
                            value="{{ old('date') }}" required>

                        @error('date')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">@lang('messages.description') @lang('messages.english')</label>
                    <textarea name="description_en" class="form-control @error('description_en') is-invalid @enderror" rows="3">{{ old('description_en') }}</textarea>
                    @error('description_en')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">@lang('messages.description') @lang('messages.urdu')</label>
                    <textarea name="description_ur" class="form-control @error('description_ur') is-invalid @enderror" rows="3">{{ old('description_ur') }}</textarea>
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
                                        <th>@lang('messages.wo-quantity')</th>
                                        <th>@lang('messages.completed')</th>
                                        <th>@lang('messages.remaining')</th>
                                        <th>@lang('messages.qty')</th>
                                        <th>@lang('messages.action')</th>

                                    </tr>
                                </thead>
                                <tbody id="itemsTableBody">
                                    <!-- Rows will be added here -->
                                </tbody>
                            </table>
                        </div>

                        <button type="button" class="btn btn-success mt-3" id="addRowBtn">
                            <i class="fa fa-plus"></i> @lang('messages.add-item')
                        </button>
                    </div>

                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save"></i> @lang('messages.save')
                    </button>
                    <a href="{{ route('work-progress.index') }}" class="btn btn-secondary">
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
                        <select id="itemSelect" class="form-select select2 custom-select">
                            <option value="">@lang('messages.select-item')</option>
                            @foreach ($availableItems as $item)
                                <option value="{{ $item['id'] }}" data-rate="{{ $item['rate'] }}"
                                    data-quantity="{{ $item['remaining_quantity'] }}">
                                    {{ App::getLocale() === 'ur' ? $item['item_name_ur'] ?? $item['item_name_en'] : $item['item_name_en'] }}
                                    (@lang('messages.quantity'): {{ $item['remaining_quantity'] }}
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

        document.getElementById('addRowBtn').addEventListener('click', function() {
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

            // Prevent duplicate selection
            if (document.querySelector(`input[name="item_id[]"][value="${itemId}"]`)) {
                alert('Item already selected!');
                return;
            }

            const item = availableItems.find(i => i.id == itemId);

            addNewRow(item);

            // Disable selected option
            const option = select.querySelector(`option[value="${itemId}"]`);
            if (option) option.disabled = true;

            bootstrap.Modal.getInstance(document.getElementById('addItemModal')).hide();
            select.value = '';
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
                <input type="hidden" name="item_id[]" value="${item.id}">
                <input type="number"
                       name="completed_qty[]"
                       class="form-control quantity-input_${rowId}"
                       step="0.0001"
                       min="0"
                       max="${item.remaining_quantity}"
                       required>
            </td>
            <td>
                <button type="button" class="btn btn-sm btn-danger" onclick="removeRow('row_${rowId}')">
                    <i class="fa fa-trash"></i>
                </button>
            </td>
        `;

            attachRowListeners(rowId, item.remaining_quantity);
        }

        function attachRowListeners(rowId, maxQuantity) {
            const quantityInput = document.querySelector(`.quantity-input_${rowId}`);

            quantityInput.addEventListener('input', function() {
                validateQuantity(rowId, maxQuantity);
            });
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

        function removeRow(rowId) {
            const row = document.getElementById(rowId);

            //  Get item id from hidden input
            const itemId = row.querySelector('input[name="item_id[]"]').value;

            //  Re-enable option in dropdown
            const select = document.getElementById('itemSelect');
            const option = select.querySelector(`option[value="${itemId}"]`);
            if (option) option.disabled = false;

            row.remove();
        }
    </script>
@endsection
