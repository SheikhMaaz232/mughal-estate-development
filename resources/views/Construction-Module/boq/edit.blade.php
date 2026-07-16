@extends('layouts.backend')

@section('content')
    <div class="block block-rounded col-md-12">
        <div class="block-header block-header-default">
            <h3 class="block-title">@lang('messages.edit-boq')</h3>
        </div>
        <div class="block-content block-content-full">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="block block-rounded">
            <div class="block-content block-content-full">
                <form action="{{ route('boq-masters.update', $boqMaster->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Master Section -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="construction_site_id" class="form-label">@lang('messages.construction-site')</label>
                        <input type="text" class="form-control"
                            value="{{ App::getLocale() === 'ur' ? $boqMaster->constructionSite->name_ur : $boqMaster->constructionSite->name_en }}"
                            disabled>
                        <input type="hidden" name="construction_site_id" value="{{ $boqMaster->construction_site_id }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="tender_id" class="form-label">@lang('messages.tender')</label>
                        <input type="text" class="form-control"
                            value="{{ App::getLocale() === 'ur' ? $boqMaster->tender->title_ur : $boqMaster->tender->title_en }}" disabled>
                        <input type="hidden" name="tender_id" value="{{ $boqMaster->tender_id }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="title_en" class="form-label">@lang('messages.title') @lang('messages.english')</label>
                        <input type="text" name="title_en" id="title_en"
                            class="form-control @error('title_en') is-invalid @enderror" placeholder="@lang('messages.enter-title-english')"
                            value="{{ old('title_en', $boqMaster->title_en) }}" required>
                        @error('title_en')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="title_ur" class="form-label">@lang('messages.title') @lang('messages.urdu')</label>
                        <input type="text" name="title_ur" id="title_ur"
                            class="form-control @error('title_ur') is-invalid @enderror" placeholder="@lang('messages.enter-title-urdu')"
                            value="{{ old('title_ur', $boqMaster->title_ur) }}" required>
                        @error('title_ur')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Detail Section Header -->
                <div class="col-md-12 mb-3 mt-4">
                    <h5 style="color: #d63031; font-weight: bold;">@lang('messages.boq-details')</h5>
                </div>

                <!-- BOQ Details Table -->
                <div class="col-md-12 mb-5">
                    <div class="table-responsive">
                        <table class="table item-table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 5%;"></th>
                                    <th style="width: 30%;">@lang('messages.item')</th>
                                    <th class="text-center" style="width: 12%;">@lang('messages.unit')</th>
                                    <th class="text-center" style="width: 13%;">@lang('messages.quantity')</th>
                                    <th class="text-center" style="width: 13%;">@lang('messages.rate')</th>
                                    <th class="text-center" style="width: 15%;">@lang('messages.gross-amount')</th>
                                </tr>
                                <tr aria-hidden="true" class="mt-3 d-block table-row-hidden"></tr>
                            </thead>
                            <tbody>
                                @php
                                    $details = old('item_id') ?
                                        collect(old('item_id', []))->map(fn($id, $i) => (object)['item_id' => $id, 'quantity' => old('quantity')[$i], 'rate' => old('rate')[$i], 'gross_amount' => old('gross_amount')[$i]])
                                        : $boqMaster->details;
                                @endphp

                                @if (count($details) > 0)
                                    @foreach ($details as $index => $detail)
                                        <tr>
                                            <td class="text-center delete-item-row">
                                                <a href="javascript:void(0);" class="delete-item text-danger"
                                                    title="@lang('messages.delete')">
                                                    <i class="fa fa-times"></i>
                                                </a>
                                            </td>

                                            <td>
                                                <input type="checkbox" name="row_id[]" class="row_id" value="{{ $index }}" hidden>
                                                <select name="item_id[]"
                                                    class="form-control select2 @error('item_id.' . $index) is-invalid @enderror item-select item_{{ $index }}">
                                                    <option value="">@lang('messages.select-option')</option>
                                                    @if ($items)
                                                        @foreach ($items as $item)
                                                            <option value="{{ $item->id }}"
                                                                {{ ($detail->item_id ?? $detail->id) == $item->id ? 'selected' : '' }}>
                                                                {{ App::getLocale() === 'ur' ? $item->name_ur ?? '-' : $item->name_en ?? '-' }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                @error('item_id.' . $index)
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </td>

                                            <td>
                                                <input type="text" class="form-control unit-display measurement_unit_{{ $index }}" readonly>
                                            </td>

                                            <td>
                                                <input type="number" name="quantity[]"
                                                    class="form-control quantity-input quantity_{{ $index }} @error('quantity.' . $index) is-invalid @enderror"
                                                    value="{{ old('quantity.' . $index, $detail->quantity) }}" step="0.0001" min="0">
                                                @error('quantity.' . $index)
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </td>

                                            <td>
                                                <input type="number" name="rate[]"
                                                    class="form-control rate-input rate_{{ $index }} @error('rate.' . $index) is-invalid @enderror"
                                                    value="{{ old('rate.' . $index, $detail->rate) }}" step="0.01" min="0">
                                                @error('rate.' . $index)
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </td>

                                            <td>
                                                <input type="number" name="gross_amount[]"
                                                    class="form-control gross-amount-input gross_{{ $index }}" readonly
                                                    value="{{ old('gross_amount.' . $index, $detail->gross_amount) }}" step="0.01">
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <a href="javascript:void(0)" class="btn btn-dark additem">@lang('messages.add-detail')</a>
                </div>

                <!-- Summary Section -->
                <div class="row justify-content-end">
                    <div class="col-md-6 mb-3">
                        <div class="form-group row">
                            <div class="col-md-4">
                                <label for="total_amount" class="form-label">@lang('messages.total_amount')</label>
                            </div>
                            <div class="col-md-8">
                                <input type="number" name="total_amount" id="total_amount"
                                    class="form-control @error('total_amount') is-invalid @enderror"
                                    placeholder="@lang('messages.total_amount')" step="0.01" value="{{ old('total_amount', $boqMaster->total_amount) }}"
                                    readonly>
                                @error('total_amount')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="row">
                    <div class="d-flex justify-content-end gap-2">
                        <button type="submit" class="btn btn-primary">@lang('messages.update')</button>
                        <a href="{{ route('boq-masters.index') }}" class="btn btn-dark">@lang('messages.go-to-list')</a>
                    </div>
                </div>

            </form>
        </div>
    </div>
    <script>
        // Store items data for use in JavaScript
        const itemsData = {!! json_encode(
            $items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name_en' => $item->name_en,
                    'name_ur' => $item->name_ur,
                    'unit_id' => $item->measurement_unit_id,
                    'unit_name_en' => $item->measurementUnit->name_en ?? '-',
                    'unit_name_ur' => $item->measurementUnit->name_ur ?? '-',
                ];
            }),
        ) !!};

        const isUrdu = "{{ App::getLocale() }}" === 'ur';
        let currentIndex = {{ count($boqMaster->details) }};

        // Add item row functionality
        document.querySelector('.additem').addEventListener('click', function(e) {
            e.preventDefault();
            addNewRow();
        });

        function addNewRow() {
            currentIndex++;
            let tableBody = document.querySelector('.item-table tbody');
            let newRow = document.createElement('tr');

            let itemOptions = '<option value="">@lang('messages.select-option')</option>';
            itemsData.forEach(item => {
                let itemName = isUrdu ? item.name_ur : item.name_en;
                itemOptions +=
                    `<option value="${item.id}">${itemName}</option>`;
            });

            newRow.innerHTML = `
                <td class="text-center delete-item-row">
                    <a href="javascript:void(0);" class="delete-item text-danger" title="@lang('messages.delete')">
                        <i class="fa fa-times"></i>
                    </a>
                </td>
                <td>
                    <input type="checkbox" name="row_id[]" class="row_id" value="${currentIndex}" hidden>
                    <select name="item_id[]" class="form-control select2 item-select item_${currentIndex}">
                        ${itemOptions}
                    </select>
                </td>
                <td>
                    <input type="text" class="form-control unit-display measurement_unit_${currentIndex}" readonly>
                </td>
                <td>
                    <input type="number" name="quantity[]" class="form-control quantity-input quantity_${currentIndex}" step="0.0001" min="0">
                </td>
                <td>
                    <input type="number" name="rate[]" class="form-control rate-input rate_${currentIndex}" step="0.01" min="0">
                </td>
                <td>
                    <input type="number" name="gross_amount[]" class="form-control gross-amount-input gross_${currentIndex}" readonly step="0.01">
                </td>
            `;

            tableBody.appendChild(newRow);

            // Initialize select2 on new row
            if (typeof $.fn.select2 !== 'undefined') {
                $(newRow).find('.select2').select2();
            }

            // Attach listeners to new row
            $(document).ready(function() {
                $(".item_" + currentIndex).on('change', function() {
                    var row_id = $(this).closest("tr").find(".row_id").val();
                    var itemId = this.value;
                    handleItemSelectionAjax(row_id, itemId);
                });
            });

            newRow.querySelector('.quantity-input').addEventListener('input', calculateRowGrossAmount);
            newRow.querySelector('.rate-input').addEventListener('input', calculateRowGrossAmount);
        }

        // Fetch measurement unit via AJAX for selected item
        function handleItemSelectionAjax(row_id, itemId) {
            if (!itemId) {
                $(".measurement_unit_" + row_id).val('');
                return;
            }

            // Call API to get product/item details
            let url = config.routes.getProductSizeDetail.replace(':id', itemId);
            $.ajax({
                url: url,
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $(".measurement_unit_" + row_id).val(response.data || '-');
                },
                error: function(errorThrown) {
                    $(".measurement_unit_" + row_id).val('');
                    console.error('Error fetching measurement unit:', errorThrown);
                }
            });
        }

        // Handle item selection and display unit
        function handleItemSelection(e) {
            var row_id = $(e.target).closest("tr").find(".row_id").val();
            var itemId = e.target.value;
            handleItemSelectionAjax(row_id, itemId);
        }



        // Calculate gross amount for a single row
        function calculateRowGrossAmount(e) {
            let row = e.target.closest('tr');
            let quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;
            let rate = parseFloat(row.querySelector('.rate-input').value) || 0;
            let grossAmount = quantity * rate;
            row.querySelector('.gross-amount-input').value = grossAmount.toFixed(2);
            calculateTotal();
        }

        // Delete item row
        document.addEventListener('click', function(e) {
            if (e.target.closest('.delete-item')) {
                e.preventDefault();
                e.target.closest('tr').remove();
                calculateTotal();
            }
        });

        // Calculate total amount
        function calculateTotal() {
            let total = 0;
            document.querySelectorAll('.gross-amount-input').forEach(input => {
                total += parseFloat(input.value) || 0;
            });
            document.getElementById('total_amount').value = total.toFixed(2);
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof $.fn.select2 !== 'undefined') {
                $('.select2').select2();
            }

            // Fetch measurement units for existing selected items on page load
            document.querySelectorAll('.item-select').forEach(select => {
                var row_id = $(select).closest("tr").find(".row_id").val();
                var itemId = select.value;

                // Trigger AJAX fetch if item is already selected
                if (itemId) {
                    handleItemSelectionAjax(row_id, itemId);
                }
            });

            // Attach listeners to existing rows
            $(document).on('change', '.item-select', function() {
                var row_id = $(this).closest("tr").find(".row_id").val();
                var itemId = this.value;
                handleItemSelectionAjax(row_id, itemId);
            });

            document.querySelectorAll('.quantity-input, .rate-input').forEach(input => {
                input.addEventListener('input', calculateRowGrossAmount);
            });

            calculateTotal();
        });
    </script>
    <script>
        var config = {
            routes: {
                getProductSizeDetail: "{{ route('purchase-order.getProductSizeDetail', ['id' => ':id']) }}"
            }
        };
    </script>
@endsection

