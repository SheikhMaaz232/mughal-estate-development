@extends('layouts.backend')

@section('content')
    <div class="block block-rounded col-md-12">
        <div class="block-header block-header-default">
            <h3 class="block-title">@lang('messages.edit-purchase-order')</h3>
        </div>
        <div class="block-content block-content-full">
            <form action="{{ route('purchase-order.update', $purchaseOrder->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="date" class="form-label">@lang('messages.purchase_order_no')</label>

                        <input class="form-control" value="{{ $purchaseOrder->id }}" disabled>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="date" class="form-label">@lang('messages.Date')</label>

                        <input type="date" class="form-control" name="date"
                            value="{{ old('date', isset($product->date) ? $product->date : now()->format('Y-m-d')) }}">

                        @error('date')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="project_id">@lang('messages.projects')</label>
                        <select name="project_id" id="project_id"
                            class="form-control select2 form-select @error('project_id') is-invalid @enderror">
                            <option value="">@lang('messages.main_party')</option>
                            @foreach ($projects as $project)
                                <option value="{{ $project->id }}"
                                    {{ (old('project_id') ?? $purchaseOrder->project_id) == $project->id ? 'selected' : '' }}>
                                    {{ App::getLocale() === 'ur' ? $project->name_ur ?? '-' : $project->name_en ?? '-' }}
                                </option>
                            @endforeach
                        </select>
                        @error('project_id')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="party_id">@lang('messages.main_party')</label>
                        <select name="party_id" id="party_id"
                            class="form-control select2 form-select @error('party_id') is-invalid @enderror">
                            <option value="">@lang('messages.main_party')</option>
                            @foreach ($searchParties as $searchParty)
                                <option value="{{ $searchParty->id }}"
                                    {{ (old('party_id') ?? $purchaseOrder->party_id) == $searchParty->id ? 'selected' : '' }}>
                                    {{ App::getLocale() === 'ur' ? $searchParty->name_ur ?? '-' : $searchParty->name_en ?? '-' }}
                                    -
                                    ({{ App::getLocale() === 'ur' ? 'ذات' : 'CAST' }}:
                                    {{ App::getLocale() === 'ur' ? $searchParty->cast->title_ur ?? '-' : $searchParty->cast->title_en ?? '-' }})
                                    ({{ App::getLocale() === 'ur' ? 'شناختی کارڈ' : 'CNIC' }}:
                                    {{ $searchParty->cnic_no ?? 'N/A' }})
                                    ({{ App::getLocale() === 'ur' ? 'فون' : 'Phone' }}:
                                    {{ $searchParty->contact_number_1 ?? 'N/A' }})
                                </option>
                            @endforeach
                        </select>
                        @error('party_id')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="detail_account_id">@lang('messages.detail_account')</label>
                        <select name="detail_account_id" id="detail_account_id"
                            class="form-control select2 form-select @error('detail_account_id') is-invalid @enderror">
                            <option value="">@lang('messages.detail_account')</option>
                            @foreach ($detailAccounts as $detailAccount)
                                <option value="{{ $detailAccount->id }}"
                                    {{ (old('detail_account_id') ?? $purchaseOrder->detail_account_id) == $detailAccount->id ? 'selected' : '' }}>
                                    {{ App::getLocale() === 'ur' ? $detailAccount->name_ur ?? '-' : $detailAccount->name_en ?? '-' }}
                                </option>
                            @endforeach
                        </select>
                        @error('detail_account_id')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="contact_person">@lang('messages.contact_person') </label>
                        <input type="text" class="form-control" id="contact_person" name="contact_person"
                            placeholder="@lang('messages.contact_person')"
                            value="{{ old('contact_person', $purchaseOrder->contact_person ?? '') }}">
                        @error('contact_person')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <input type="hidden" name="status" value="{{ $purchaseOrder->status }}">
                    @error('status')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-12 mb-5">
                    <label for="remarks">@lang('messages.remarks')</label>
                    <textarea type="text" class="form-control" id="remarks" name="remarks" placeholder="@lang('messages.remarks')"
                        autocomplete="off" value="{{ old('remarks') }}">{{ old('remarks', $purchaseOrder->remarks ?? '') }}</textarea>
                    @error('remarks')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-12">
                    <h2 style="color: red">@lang('messages.product_details')</h2>
                </div>

                <div class="tab-content" id="pills-tabContent" style="margin-bottom: 5px;">
                    <div class="invoice-detail-items" style="padding: 0px 0px 0px 0px !important;">

                        <div class="table-responsive">

                            <table class="table item-table">
                                <thead>
                                    <tr>
                                        <th class="">
                                        </th>
                                        <th>
                                        </th>
                                        <th style="width: 20% !important">@lang('messages.products')</th>
                                        <th class="">
                                            @lang('messages.unit')</th>
                                        <th class="">
                                            @lang('messages.qty')</th>
                                        <th class="">
                                            @lang('messages.price')</th>
                                        <th class="">
                                            @lang('messages.amount')</th>
                                        <th class="" style="width: 20%;">
                                            @lang('messages.remarks')</th>

                                    </tr>
                                    <tr aria-hidden="true" class="mt-3 d-block table-row-hidden">
                                    </tr>
                                </thead>
                                {{-- <tbody>
                                    @foreach ($purchaseOrderDetails as $purchaseOrderDetail)
                                        @php
                                            $index = $loop->index + 2; // Starts from 2
                                        @endphp
                                        <tr>
                                            <td class="delete-item-row">
                                                <ul class="table-controls">
                                                    <li><a href="javascript:void(0);" class="delete-item"
                                                            data-toggle="tooltip" data-placement="top" title=""
                                                            data-original-title="Delete"><svg
                                                                xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="feather feather-x-circle">
                                                                <circle cx="12" cy="12" r="10"></circle>
                                                                <line x1="15" y1="9" x2="9"
                                                                    y2="15"></line>
                                                                <line x1="9" y1="9" x2="15"
                                                                    y2="15"></line>
                                                            </svg></a></li>
                                                </ul>
                                            </td>
                                            <td><input type="checkbox" name="row_id[]" class="row_id"
                                                    value="{{ $index }}" hidden></td>
                                            <td class="product_id"><select name="product_id[]" id="product_id"
                                                    class="product form-control form-select select2 @error('product_id') is-invalid @enderror product_{{ $index }}">
                                                    <option value="">@lang('messages.select-product')</option>
                                                    @foreach ($itemsData as $productData)
                                                        <option value="{{ $productData->id }}"
                                                            {{ (old('product_id') ?? $purchaseOrderDetail->product_id) == $productData->id ? 'selected' : '' }}>
                                                            {{ App::getLocale() === 'ur' ? $productData->name_ur ?? '-' : $productData->name_en ?? '-' }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="measurement_unit">
                                                <input id="measurement_unit" style="color: black; "
                                                    class = "measurement_unit form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}  measurement_unit_{{ $index }}"
                                                    placeholder="@lang('messages.unit')" readonly>
                                            </td>
                                            <td class="quantity">
                                                <input type="text" style="color: black; "
                                                    value="{{ $purchaseOrderDetail->quantity }}"
                                                    placeholder="@lang('messages.quantity')" id="quantity" name="quantity[]"
                                                    class = "quantity form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} quantity_{{ $index }}">
                                            </td>
                                            <td class=" price">
                                                <input type="text" name="price[]"
                                                    value="{{ $purchaseOrderDetail->price }}"
                                                    class="price form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} price_{{ $index }}"
                                                    placeholder="@lang('messages.price') ">
                                            </td>
                                            <td class=" amount">
                                                <input type="text" name="amount[]"
                                                    value="{{ $purchaseOrderDetail->amount }}"
                                                    class="amount form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} amount_{{ $index }}"
                                                    placeholder="@lang('messages.amount') " readonly>
                                            </td>
                                            <td class="detail_remarks">

                                                <textarea name="detail_remarks[]"
                                                    class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} detail_remarks_{{ $index }}"
                                                    placeholder="@lang('messages.remarks')" rows="2">{{ $purchaseOrderDetail->detail_remarks }}</textarea>

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody> --}}
                                <tbody>
                                    @foreach ($purchaseOrderDetails as $index => $purchaseOrderDetail)
                                        @php
                                            $rowIndex = $loop->index + 2; // keep your existing row indexing
                                            $oldProducts = old(
                                                'product_id',
                                                $purchaseOrderDetails->pluck('product_id')->toArray(),
                                            );
                                            $oldQuantity = old(
                                                'quantity',
                                                $purchaseOrderDetails->pluck('quantity')->toArray(),
                                            );
                                            $oldPrice = old('price', $purchaseOrderDetails->pluck('price')->toArray());
                                            $oldAmount = old(
                                                'amount',
                                                $purchaseOrderDetails->pluck('amount')->toArray(),
                                            );
                                            $oldRemarks = old(
                                                'detail_remarks',
                                                $purchaseOrderDetails->pluck('detail_remarks')->toArray(),
                                            );
                                        @endphp
                                        <tr>
                                            <td class="delete-item-row">
                                                <ul class="table-controls">
                                                    <li><a href="javascript:void(0);" class="delete-item"
                                                            data-toggle="tooltip" data-placement="top" title=""
                                                            data-original-title="Delete"><svg
                                                                xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="feather feather-x-circle">
                                                                <circle cx="12" cy="12" r="10"></circle>
                                                                <line x1="15" y1="9" x2="9"
                                                                    y2="15"></line>
                                                                <line x1="9" y1="9" x2="15"
                                                                    y2="15"></line>
                                                            </svg></a></li>
                                                </ul>
                                            </td>

                                            <td><input type="checkbox" name="row_id[]" class="row_id"
                                                    value="{{ $rowIndex }}" hidden></td>

                                            {{-- Product --}}
                                            <td class="product_id">
                                                <select name="product_id[]"
                                                    class="product form-control form-select select2 product_{{ $rowIndex }}">
                                                    <option value="">@lang('messages.select-product')</option>
                                                    @foreach ($itemsData as $productData)
                                                        <option value="{{ $productData->id }}"
                                                            {{ (isset($oldProducts[$index]) ? $oldProducts[$index] : $purchaseOrderDetail->product_id) == $productData->id ? 'selected' : '' }}>
                                                            {{ App::getLocale() === 'ur' ? $productData->name_ur ?? '-' : $productData->name_en ?? '-' }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>

                                            {{-- Measurement Unit --}}
                                            <td class="measurement_unit">
                                                <input type="text"
                                                    class="measurement_unit form-control measurement_unit_{{ $rowIndex }}"
                                                    placeholder="@lang('messages.unit')" readonly
                                                    value="{{ $purchaseOrderDetail->measurement_unit ?? '' }}">
                                            </td>

                                            {{-- Quantity --}}
                                            <td class="quantity">
                                                <input type="text" name="quantity[]"
                                                    class="quantity form-control quantity_{{ $rowIndex }}"
                                                    placeholder="@lang('messages.qty')"
                                                    value="{{ $oldQuantity[$index] ?? $purchaseOrderDetail->quantity }}">
                                            </td>

                                            {{-- Price --}}
                                            <td class="price">
                                                <input type="text" name="price[]"
                                                    class="price form-control price_{{ $rowIndex }}"
                                                    placeholder="@lang('messages.price')"
                                                    value="{{ $oldPrice[$index] ?? $purchaseOrderDetail->price }}">
                                            </td>

                                            {{-- Amount --}}
                                            <td class="amount">
                                                <input type="text" name="amount[]"
                                                    class="amount form-control amount_{{ $rowIndex }}"
                                                    placeholder="@lang('messages.amount')" readonly
                                                    value="{{ $oldAmount[$index] ?? $purchaseOrderDetail->amount }}">
                                            </td>

                                            {{-- Detail Remarks --}}
                                            <td class="detail_remarks">
                                                <textarea name="detail_remarks[]" class="form-control detail_remarks_{{ $rowIndex }}" rows="2"
                                                    placeholder="@lang('messages.remarks')">{{ $oldRemarks[$index] ?? $purchaseOrderDetail->detail_remarks }}</textarea>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <a href="javascript:void(0)" class="btn btn-dark additem">@lang('messages.add-bank_details')</a>
                    </div>
                </div>
                <div class="row justify-content-end">
                    <div class="col-md-6 mb-3">
                        <div class="form-group row">
                            <div class="col-md-3">
                                <label for="client-phone">@lang('messages.gross_amount')</label>
                            </div>
                            <div class="col-md-9">
                                <input type="number" id="gross-amount" style="color: black;"
                                    class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} gross-amount"
                                    name="gross_total" id="gross-amount" placeholder="@lang('messages.gross_amount')"
                                    value="{{ old('gross_total', !empty($purchaseOrder->gross_total) ? $purchaseOrder->gross_total : '0') }}"
                                    readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-end">
                    <div class="col-md-6 mb-3">
                        <div class="form-group row">
                            <div class="col-md-3">
                                <label>@lang('messages.tax_amount')</label>
                            </div>
                            <div class="col-md-9">
                                <input type="number" id="tax" style="color: black;"
                                    class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} tax"
                                    name="tax_amount" placeholder="@lang('messages.tax_amount')"
                                    value="{{ old('tax_amount', !empty($purchaseOrder->tax_amount) ? $purchaseOrder->tax_amount : '0') }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-end">
                    <div class="col-md-6 mb-3">
                        <div class="form-group row">
                            <div class="col-md-3">
                                <label>@lang('messages.shipping_amount')</label>
                            </div>
                            <div class="col-md-9">
                                <input type="number" id="shipping" style="color: black;"
                                    class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} shipping"
                                    name="shipping_amount" placeholder="@lang('messages.shipping_amount')"
                                    value="{{ old('shipping_amount', !empty($purchaseOrder->shipping_amount) ? $purchaseOrder->shipping_amount : '0') }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-end">
                    <div class="col-md-6 mb-3">
                        <div class="form-group row">
                            <div class="col-md-3">
                                <label>@lang('messages.other_amount')</label>
                            </div>
                            <div class="col-md-9">
                                <input type="number" style="color: black;"
                                    class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} other"
                                    name="other_amount" id="otherAmount" placeholder="@lang('messages.other_amount')"
                                    value="{{ old('other_amount', !empty($purchaseOrder->other_amount) ? $purchaseOrder->other_amount : '0') }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-end">
                    <div class="col-md-6 mb-3">
                        <div class="form-group row">
                            <div class="col-md-3">
                                <label>@lang('messages.net_amount')</label>
                            </div>
                            <div class="col-md-9">
                                <input type="text" id="net-amount" style="color: black;"
                                    class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} net-amount"
                                    name="total_amount" placeholder="@lang('messages.net_amount')"
                                    value="{{ old('total_amount', !empty($purchaseOrder->total_amount) ? $purchaseOrder->total_amount : '0') }}"
                                    readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="d-flex justify-content-end gap-2">
                        <button type="submit" class="btn btn-primary">@lang('messages.update')</button>
                        <a href="{{ route('purchase-order.index') }}" class="btn btn-dark">@lang('messages.go-to-list')</a>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <script>
        document.getElementsByClassName('additem')[0].addEventListener('click', function() {

            let projectId = $('#project_id').val();

            if (!projectId) {
                Swal.fire({
                    icon: 'warning',
                    title: window.customTranslations.errorTitle,
                    text: window.customTranslations.selectProjectFirst
                });
                return;
            }

            let getTableElement = document.querySelector('.item-table');
            let currentIndex = getTableElement.rows.length;

            let $html = '<tr>' +
                '<td class="delete-item-row">' +
                '<ul class="table-controls">' +
                '<li><a href="javascript:void(0);" class="delete-item" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-circle"><circle cx="12" cy="12" r="10"></circle> <line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg></a></li>' +
                '</ul>' +
                '</td>' +

                '<td><input type="checkbox" name="row_id[]" class="row_id" value="' + currentIndex +
                '" hidden></td>' +
                '<td class="product_id"><select name="product_id[]" id="product_id" class="product_id form-control form-select select2 @error('product_id') is-invalid @enderror product_' +
                currentIndex +
                '"><option value="">@lang('messages.select-product')</option></select> ' +
                '</td> ' +
                '<td class="measurement_unit" >' +
                '<input id="measurement_unit" style="color: black; " class = "measurement_unit form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}  measurement_unit_' +
                currentIndex + '" placeholder="@lang('messages.unit')" readonly></td>' +
                '<td class="quantity" >' +
                '<input type="text" style="color: black; " placeholder="@lang('messages.qty')" id="quantity" name="quantity[]" class = "quantity form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} quantity_' +
                currentIndex + '" > </td> ' +
                '<td class=" price">' +
                '<input type="text" name="price[]" class="price form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} price_' +
                currentIndex + '" placeholder="@lang('messages.price') ">' +
                ' </td>' +
                '<td class=" amount">' +
                '<input type="text" name="amount[]" class="amount form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} amount_' +
                currentIndex + '" placeholder="@lang('messages.amount') " readonly>' +
                ' </td>' +
                '<td class="detail_remarks">' +
                '<textarea name="detail_remarks[]" ' +
                'class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} detail_remarks_' +
                currentIndex + '" ' +
                'placeholder="@lang('messages.remarks')" ' +
                'rows="2"></textarea>' +
                '</td>' +

                '<div class="form-check form-check-primary form-check-inline me-0 mb-0">' +
                // '<input class="form-check-input inbox-chkbox contact-chkbox" type="checkbox">' +
                '</div>' +
                '</div>' +
                '</td>' +
                '</tr>';

            $(".item-table tbody").append($html);

            let select = $(".product_" + currentIndex);
            $.each(projectItems, function(id, name) {
                select.append('<option value="' + id + '">' + name + '</option>');
            });
            select.select2();
            deleteItemRow();
            $('.select2').select2();


            $(document).ready(function() {
                $(".product_" + currentIndex).on('change', function() {
                    var row_id = $(this).closest("tr").find(".row_id").val();
                    var name = this.value;
                    let url = config.routes.getProductSizeDetail.replace(':id', name);
                    $.ajax({
                        url: url,
                        type: 'GET',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            $(".measurement_unit_" + row_id).val(response.data);
                        },
                        complete: function() {
                            $('#loading').css('display', 'none');
                        },
                        error: function(errorThrown) {
                            $('').val('');
                            var errors = errorThrown.responseJSON.errors;
                            Swal.fire({
                                icon: 'error',
                                title: 'Something went wrong',
                            })
                        }
                    })
                });
            });

            $(document).on('click', 'body *', function() {
                $('.price, .quantity').on("input", function() {
                    var row_id = $(this).closest("tr").find(".row_id").val();
                    let quantity = $(this).closest("tr").find(".quantity_" + row_id).val();
                    let price = $(this).closest("tr").find(".price_" + row_id).val();
                    if (parseInt(quantity) > 0) {
                        $(this).closest("tr").find(".amount_" + row_id).val(quantity * price);
                    } else {
                        $(this).closest("tr").find(".amount_" + row_id).val('');
                    }
                    doAmountTotal();
                });

                $('.delete-item').on("click", function() {
                    doAmountTotal();
                });

                function doAmountTotal() {
                    $('#total-amount').text("");
                    var totalAmount = 0;
                    $(".amount").each(function() {
                        if (!isNaN(this.value) && this.value.length != 0) {
                            totalAmount += parseFloat(this.value);
                        }
                    });
                    $('#gross-amount').val(totalAmount.toFixed(2));
                    // $('#net-amount').val(totalAmount.toFixed(2));
                }

                $(".price, #gross-amount, #tax, #shipping, #otherAmount").on("input", function() {
                    var totalAmount = 0;
                    $(".amount").each(function() {
                        if (!isNaN(this.value) && this.value.length != 0) {
                            totalAmount += parseFloat(this.value);
                        }
                    });
                    let tax = $("#tax").val() ? $("#tax").val() : 0;
                    let shipping = $("#shipping").val() ? $("#shipping").val() : 0;
                    let otherAmount = $("#otherAmount").val() ? $("#otherAmount").val() : 0;

                    var totalLessAmount = parseInt(tax) + parseInt(shipping) + parseInt(
                        otherAmount);

                    $('#net-amount').val((totalAmount + (totalLessAmount || 0)).toFixed(2));
                })



            });

            $(document).on('click', 'body *', function() {
                $('.amount').on("focusout", function() {
                    doAmountTotal();
                });

                $('.delete-item').on("click", function() {
                    doAmountTotal();
                });

                function doAmountTotal() {
                    $('#total-amount').text("");
                    var totalAmount = 0;
                    $(".amount").each(function() {
                        if (!isNaN(this.value) && this.value.length != 0) {
                            totalAmount += parseFloat(this.value);
                        }
                    });
                    $('#gross-amount').val(totalAmount.toFixed(2));
                }
            });

        })

        deleteItemRow();

        selectableDropdown(document.querySelectorAll('.invoice-select .dropdown-item'));
        selectableDropdown(document.querySelectorAll('.invoice-tax-select .dropdown-item'), getTaxValue);
        selectableDropdown(document.querySelectorAll('.invoice-discount-select .dropdown-item'), getDiscountValue);

        function deleteItemRow() {
            let deleteItem = document.querySelectorAll('.delete-item');
            for (var i = 0; i < deleteItem.length; i++) {
                deleteItem[i].addEventListener('click', function() {
                    this.parentElement.parentNode.parentNode.parentNode.remove();
                })
            }
        }
    </script>

    <script>
        // $(document).ready(function() {
        //     $(".product").on('input', function() {
        //         var row_id = $(this).closest("tr").find(".row_id").val();
        //         var name = this.value;
        //         let url = config.routes.getProductSizeDetail.replace(':id', name);
        //         $.ajax({
        //             url: url,
        //             type: 'GET',
        //             headers: {
        //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //             },
        //             success: function(response) {
        //                 $(".measurement_unit_" + row_id).val(response.data);
        //             },
        //             complete: function() {
        //                 $('#loading').css('display', 'none');
        //             },
        //             error: function(errorThrown) {
        //                 $('').val('');
        //                 var errors = errorThrown.responseJSON.errors;
        //                 Swal.fire({
        //                     icon: 'error',
        //                     title: 'Something went wrong',
        //                 })
        //             }
        //         })
        //     });

        // });

        $(document).on('click', 'body *', function() {
            $('.price, .quantity').on("change input", function() {
                var row_id = $(this).closest("tr").find(".row_id").val();
                let quantity = $(this).closest("tr").find(".quantity_" + row_id).val();
                let price = $(this).closest("tr").find(".price_" + row_id).val();
                if (parseInt(quantity) > 0) {
                    $(this).closest("tr").find(".amount_" + row_id).val(quantity * price);
                } else {
                    $(this).closest("tr").find(".amount_" + row_id).val('');
                }
                doAmountTotal();
            });



            $('.delete-item').on("click", function() {
                doAmountTotal();
            });

            function doAmountTotal() {
                $('#total-amount').text("");
                var totalAmount = 0;
                $(".amount").each(function() {
                    if (!isNaN(this.value) && this.value.length != 0) {
                        totalAmount += parseFloat(this.value);
                    }
                });
                $('#gross-amount').val(totalAmount.toFixed(2));
                // $('#net-amount').val(totalAmount.toFixed(2));
            }

            $(".price, #gross-amount, #tax, #shipping, #otherAmount").on("input", function() {
                var totalAmount = 0;
                $(".amount").each(function() {
                    if (!isNaN(this.value) && this.value.length != 0) {
                        totalAmount += parseFloat(this.value);
                    }
                });
                let tax = $("#tax").val() ? $("#tax").val() : 0;
                let shipping = $("#shipping").val() ? $("#shipping").val() : 0;
                let otherAmount = $("#otherAmount").val() ? $("#otherAmount").val() : 0;

                var totalLessAmount = parseInt(tax) + parseInt(shipping) + parseInt(
                    otherAmount);

                $('#net-amount').val((totalAmount + (totalLessAmount || 0)).toFixed(2));
            })
        });

        $(document).on('click', 'body *', function() {
            $('.amount').on("focusout", function() {
                doAmountTotal();
            });

            $('.delete-item').on("click", function() {
                doAmountTotal();
            });

            function doAmountTotal() {
                $('#total-amount').text("");
                var totalAmount = 0;
                $(".amount").each(function() {
                    if (!isNaN(this.value) && this.value.length != 0) {
                        totalAmount += parseFloat(this.value);
                    }
                });
                $('#gross-amount').val(totalAmount.toFixed(2));
            }
        });
    </script>

    <script>
        window.customTranslations = {
            pleaseSelect: "{{ __('messages.select-detail-accounts') }}",
            noData: "{{ __('messages.no-detail-account-found') }}",
            errorTitle: "{{ __('messages.error-title') }}",
            errorText: "{{ __('messages.control-head-fetch-failed') }}",
            loading: "{{ __('messages.loading') }}",
            selectSubHead: "{{ __('messages.select-sub-head') }}",
            selectSubSubHead: "{{ __('messages.select-sub-sub-heads') }}",
            selectSubSubSubHead: "{{ __('messages.select-sub-sub-sub-heads') }}",
            noSubHeads: "{{ __('messages.no-sub-head-found') }}",
            noSubSubSubHeads: "{{ __('messages.no-sub-sub-sub-head-found') }}",
            subHeaderrorTitle: "{{ __('messages.subHeaderror-title') }}",
            subHeaderrorText: "{{ __('messages.sub-head-fetch-failed') }}",
            errorTitle2: "{{ __('messages.validation_error') }}",
            errorText2: "{{ __('messages.total_and_schedule_not_same') }}",
            confirmButtonText: "{{ __('messages.ok') }}",
            selectProduct: "{{ __('messages.select-product') }}",
            selectProjectFirst: "{{ __('messages.select_project_first') }}"
        };
    </script>

    <script>
        var config = {
            routes: {
                getDetailAccounts: "{{ route('get.detail.account.data.project') }}",
                getProductSizeDetail: "{{ route('purchase-order.getProductSizeDetail', ['id' => ':id']) }}",
                getProjectItems: "{{ route('purchase-order.getProjectItems', ['projectId' => ':id']) }}"
            }
        };
    </script>
    <script>
        let projectItems = {}; // store items for selected project

        function loadProjectItems(projectId) {
            if (!projectId) return;

            let url = config.routes.getProjectItems.replace(':id', projectId);

            $.ajax({
                url: url,
                type: 'GET',
                success: function(response) {
                    if (response.status === 'success') {
                        projectItems = response.data;
                    } else {
                        projectItems = {};
                    }
                }
            });
        }
        $('#project_id').on('change', function() {

            let projectId = $(this).val();

            $(".item-table tbody").empty();

            $('#detail_account_id')
                .empty()
                .append('<option selected disabled>' + window.customTranslations.pleaseSelect + '</option>');

            $('#gross-amount').val('0.00');
            $('#net-amount').val('0.00');

            doAmountTotal();

            loadProjectItems(projectId);
        });

        $(document).ready(function() {

            let projectId = $('#project_id').val();

            if (projectId) {
                loadProjectItems(projectId);
            }

        });
        // Total calculation function
        function doAmountTotal() {
            let totalAmount = 0;

            // Sum all row amounts
            $(".amount").each(function() {
                let val = parseFloat($(this).val());
                if (!isNaN(val)) {
                    totalAmount += val;
                }
            });

            // Update gross amount
            $('#gross-amount').val(totalAmount.toFixed(2));

            // Read tax, shipping, and other amounts safely
            let tax = parseFloat($("#tax").val()) || 0;
            let shipping = parseFloat($("#shipping").val()) || 0;
            let otherAmount = parseFloat($("#otherAmount").val()) || 0;

            // Calculate net amount
            let netAmount = totalAmount + tax + shipping + otherAmount;
            $('#net-amount').val(netAmount.toFixed(2));
        }
    </script>

    <script src="{{ asset('js/plugins/sweetalert2/sweetalert2.all.js') }}"></script>
    <script src="{{ asset('js/purchaseOrder.js') }}"></script>
@endsection
