@extends('layouts.backend')

@section('content')
    <div class="block block-rounded col-md-12">
        <div class="block-header block-header-default">
            <h3 class="block-title">@lang('messages.edit-work-progress')</h3>
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

            <form action="{{ route('work-progress.update', $progress->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- MASTER INFO --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">@lang('messages.construction-site')</label>
                        <input type="text" class="form-control"
                            value="{{ App::getLocale() === 'ur'
                                ? $progress->workOrder->constructionSite->name_ur
                                : $progress->workOrder->constructionSite->name_en }}"
                            disabled>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">@lang('messages.tender')</label>
                        <input type="text" class="form-control"
                            value="{{ App::getLocale() === 'ur' ? $progress->workOrder->tender->title_ur : $progress->workOrder->tender->title_en }}"
                            disabled>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">@lang('messages.work_order')</label>
                        <input type="text" class="form-control"
                            value="{{ App::getLocale() === 'ur' ? $progress->workOrder->description_ur : $progress->workOrder->description_en }}"
                            disabled>

                        <input type="hidden" name="work_order_id" value="{{ $progress->work_order_id }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">@lang('messages.date')</label>
                        <input type="date" name="date" class="form-control" value="{{ old('date', $progress->date) }}"
                            required>
                    </div>
                </div>

                {{-- DESCRIPTION --}}
                <div class="mb-3">
                    <label>@lang('messages.description') @lang('messages.english')</label>
                    <textarea name="description_en" class="form-control">{{ old('description_en', $progress->description_en) }}</textarea>
                </div>

                <div class="mb-3">
                    <label>@lang('messages.description') @lang('messages.urdu')</label>
                    <textarea name="description_ur" class="form-control">{{ old('description_ur', $progress->description_ur) }}</textarea>
                </div>

                {{-- ITEMS --}}
                <div class="block block-rounded mt-4">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">@lang('messages.work-order-items')</h3>
                    </div>

                    <div class="block-content block-content-full">

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">

                                <thead>
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

                                    @foreach ($availableItems as $index => $item)
                                        <tr id="row_{{ $index }}" class="item-row">

                                            <td>
                                                {{ App::getLocale() === 'ur' ? $item['item_name_ur'] ?? $item['item_name_en'] : $item['item_name_en'] }}
                                            </td>

                                            <td><strong>{{ $item['total_quantity'] }}</strong></td>

                                            <td><strong>{{ $item['used_quantity'] }}</strong></td>

                                            <td>
                                                <strong class="remaining-qty_{{ $index }}">
                                                    {{ $item['remaining_quantity'] }}
                                                </strong>
                                            </td>

                                            <td>
                                                <input type="hidden" name="item_id[]" value="{{ $item['id'] }}">

                                                <input type="number" name="completed_qty[]"
                                                    class="form-control quantity-input_{{ $index }}" step="0.0001"
                                                    min="0"
                                                    max="{{ $item['remaining_quantity'] + $item['existing_qty'] }}"
                                                    value="{{ $item['existing_qty'] }}" required>
                                            </td>

                                            <td>
                                                <button type="button" class="btn btn-danger btn-sm"
                                                    onclick="removeRow('row_{{ $index }}', {{ $item['id'] }})">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>

                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>

                        <button type="button" class="btn btn-success mt-3" id="addRowBtn">
                            <i class="fa fa-plus"></i> @lang('messages.add-item')
                        </button>

                    </div>
                </div>

                {{-- BUTTONS --}}
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

    {{-- MODAL (UNCHANGED) --}}
    <div class="modal fade" id="addItemModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5>@lang('messages.select-item')</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <select id="itemSelect" class="form-select">
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

                <div class="modal-footer">
                    <button class="btn btn-primary" id="confirmAddBtn">
                        @lang('messages.add')
                    </button>
                </div>

            </div>
        </div>
    </div>

    <script>
        let availableItems = @json($availableItems);
        let currentIndex = 1000;

        document.getElementById('addRowBtn').addEventListener('click', function() {
            new bootstrap.Modal(document.getElementById('addItemModal')).show();
        });

        document.getElementById('confirmAddBtn').addEventListener('click', function() {

            const select = document.getElementById('itemSelect');

            if (!select.value) {
                alert('Select item');
                return;
            }

            const item = availableItems.find(i => i.id == select.value);

            if (document.querySelector(`input[value="${item.id}"]`)) {
                alert('Already added');
                return;
            }

            addNewRow(item);

            bootstrap.Modal.getInstance(document.getElementById('addItemModal')).hide();
        });

        function addNewRow(item) {
            let tbody = document.getElementById('itemsTableBody');
            let rowId = currentIndex++;

            let row = `
    <tr id="row_${rowId}">

        <td>${item.item_name_en}</td>
        <td>${item.total_quantity}</td>
        <td>${item.used_quantity}</td>
        <td>
            <span class="remaining-qty_${rowId}">${item.remaining_quantity}</span>
        </td>

        <td>
            <input type="hidden" name="item_id[]" value="${item.id}">
            <input type="number"
                   name="completed_qty[]"
                   class="form-control qty-input_${rowId}"
                   step="0.0001"
                   min="0"
                   max="${item.remaining_quantity}"
                   required>
        </td>

        <td>
            <button type="button"
                    class="btn btn-danger btn-sm"
                    onclick="removeRow('row_${rowId}', ${item.id})">
                Delete
            </button>
        </td>

    </tr>
    `;

            tbody.insertAdjacentHTML('beforeend', row);

            attachValidation(rowId, item.remaining_quantity);
        }

        // QUANTITY VALIDATION (IMPORTANT FIX)
        function attachValidation(rowId, maxQty) {
            const input = document.querySelector(`.qty-input_${rowId}`);

            input.addEventListener('input', function() {

                let val = parseFloat(this.value) || 0;

                if (val > maxQty) {
                    alert(`Max allowed quantity is ${maxQty}`);
                    this.value = '';
                }
            });
        }

        // DELETE ROW FIX
        function removeRow(rowId, itemId) {
            document.getElementById(rowId).remove();

            let option = document.querySelector(`#itemSelect option[value="${itemId}"]`);
            if (option) option.disabled = false;
        }
    </script>
@endsection
