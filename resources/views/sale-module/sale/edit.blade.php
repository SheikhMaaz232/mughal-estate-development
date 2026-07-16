@extends('layouts.backend')

@section('content')
    <div class="block block-rounded col-md-12">
        <div class="block-header block-header-default">
            <h3 class="block-title">@lang('messages.edit-sale-invoice')</h3>
        </div>
        <div class="block-content block-content-full">
            <form id="sale-invoice-form" action="{{ route('sale-invoice.update', $saleMaster->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="grn_no" class="form-label">@lang('messages.sale_invoice_no')</label>

                        <input class="form-control" style="background-color: #e9ecef !important;" name="sale_invoice_no"
                            value="{{ $saleMaster->id }}" readonly>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="date" class="form-label">@lang('messages.Date')</label>

                        <input type="date" class="form-control" name="date"
                            value="{{ old('date', isset($saleMaster->date) ? $saleMaster->date : now()->format('Y-m-d')) }}">

                        @error('date')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="project_id">@lang('messages.projects')</label>
                        <select name="project_id" id="project_id"
                            class="form-control select2 form-select @error('project_id') is-invalid @enderror">
                            <option value="">@lang('messages.main_party')</option>
                            @foreach ($projects as $project)
                                <option value="{{ $project->id }}"
                                    {{ (old('project_id') ?? $saleMaster->project_id) == $project->id ? 'selected' : '' }}>
                                    {{ App::getLocale() === 'ur' ? $project->name_ur ?? '-' : $project->name_en ?? '-' }}
                                </option>
                            @endforeach
                        </select>
                        @error('project_id')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label for="party_id">@lang('messages.main_party')</label>
                        <select name="party_id" id="party_id"
                            class="form-control select2 form-select @error('party_id') is-invalid @enderror">
                            <option value="">@lang('messages.main_party')</option>
                            @foreach ($searchParties as $searchParty)
                                <option value="{{ $searchParty->id }}"
                                    {{ (old('party_id') ?? $saleMaster->party_id) == $searchParty->id ? 'selected' : '' }}>
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

                    <div class="col-md-6 mb-3">
                        <label for="detail_account_id">@lang('messages.detail_account')</label>
                        <select name="detail_account_id" id="detail_account_id"
                            class="form-control select2 form-select @error('detail_account_id') is-invalid @enderror">
                            <option value="">@lang('messages.detail_account')</option>
                            @foreach ($detailAccounts as $detailAccount)
                                <option value="{{ $detailAccount->id }}"
                                    {{ (old('detail_account_id') ?? $saleMaster->detail_account_id) == $detailAccount->id ? 'selected' : '' }}>
                                    {{ App::getLocale() === 'ur' ? $detailAccount->name_ur ?? '-' : $detailAccount->name_en ?? '-' }}
                                </option>
                            @endforeach
                        </select>
                        @error('detail_account_id')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <input type="hidden" name="status" value="{{ $saleMaster->status }}">
                @error('status')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror


                <div class="row">

                    <div class="col-md-12 mb-3">
                        <label for="remarks">@lang('messages.remarks')</label>
                        <textarea type="text" class="form-control" id="remarks" name="remarks" placeholder="@lang('messages.remarks')"
                            autocomplete="off">{{ old('remarks', $saleMaster->remarks) }}</textarea>
                        @error('remarks')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
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
                                        <th>
                                        </th>
                                        <th style="width: 0% !important">
                                        </th>
                                        <th style="width: 25% !important">@lang('messages.products')</th>
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
                                <tbody>

                                    @if (!empty($saleDetails))
                                        @foreach ($saleDetails as $saleDetail)
                                            @php
                                                $index = $loop->index + 2;
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
                                                <td class="product_id">
                                                    <select id="product_id"
                                                        class="product form-control form-select select2 @error('product_id') is-invalid @enderror product_{{ $index }}"
                                                        name="product_id[]">
                                                        <option value="">@lang('messages.select-product')</option>
                                                        @foreach ($items as $productData)
                                                            <option value="{{ $productData->id }}"
                                                                {{ (old('product_id') ?? $saleDetail->product_id) == $productData->id ? 'selected' : '' }}>
                                                                {{ App::getLocale() === 'ur' ? $productData->name_ur ?? '-' : $productData->name_en ?? '-' }}
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                </td>
                                                <td class="measurement_unit">
                                                    <input id="measurement_unit"
                                                        style="background-color: #e9ecef !important; color: black; "
                                                        class = "measurement_unit form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}  measurement_unit_{{ $index }}"
                                                        placeholder="@lang('messages.unit')" readonly>
                                                </td>
                                                <td class="quantity">
                                                    <input type="text" style="color: black;  "
                                                        value="{{ $saleDetail->quantity }}"
                                                        placeholder="@lang('messages.qty')" id="quantity" name="quantity[]"
                                                        class ="quantity form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} quantity_{{ $index }}">
                                                </td>
                                                <td class="price">
                                                    <input type="number" name="price[]"
                                                        value="{{ $saleDetail->price }}" step="any" min="0"
                                                        onwheel="this.blur()"
                                                        class="price form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} price_{{ $index }}"
                                                        placeholder="@lang('messages.price') ">
                                                </td>
                                                <td class="amount">
                                                    <input type="text" name="amount[]"
                                                        value="{{ $saleDetail->amount }}"
                                                        style="background-color: #e9ecef !important;"
                                                        class="amount form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} amount_{{ $index }}"
                                                        placeholder="@lang('messages.amount') " readonly>
                                                </td>
                                                <td class="detail_remarks">
                                                    <textarea name="detail_remarks[]"
                                                        class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} detail_remarks_{{ $index }}"
                                                        placeholder="@lang('messages.remarks')" rows="2">{{ $saleDetail->detail_remarks }}</textarea>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>


                            </table>
                        </div>
                        <a href="javascript:void(0)" class="btn btn-dark additem">@lang('messages.add-product-detail')</a>

                    </div>
                </div>

                <div class="row justify-content-end">
                    <div class="col-md-6 mb-3 justify-content-end">
                        <div class="form-group row">
                            <div class="col-md-3">
                                <label for="client-phone">@lang('messages.total_quantity')</label>
                            </div>
                            <div class="col-md-9">
                                <input type="number" style="background-color: #e9ecef !important;" style="color: black;"
                                    name="total_quantity"
                                    class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} total_quantity"
                                    id="total_quantity" placeholder="@lang('messages.total_quantity')" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3 ">
                        <div class="form-group row">
                            <div class="col-md-3">
                                <label for="client-phone">@lang('messages.gross_amount')</label>
                            </div>
                            <div class="col-md-9">
                                <input type="number" style="background-color: #e9ecef !important;" style="color: black;"
                                    name="gross_bill"
                                    class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} gross_bill"
                                    id="gross-amount" placeholder="@lang('messages.gross_amount')" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="d-flex justify-content-end gap-2">
                        <button type="submit" class="btn btn-primary">@lang('messages.update')</button>
                        <a href="{{ route('sale-invoice.index') }}" class="btn btn-dark">@lang('messages.go-to-list')</a>
                    </div>
                </div>

            </form>
        </div>
    </div>


    <script>
        document.getElementsByClassName('additem')[0].addEventListener('click', function() {

            let getTableElement = document.querySelector('.item-table');
            let currentIndex = getTableElement.rows.length;

            let $html = '<tr>' +
                '<td class="delete-item-row">' +
                '<ul class="table-controls">' +
                '<li><a href="javascript:void(0);" class="delete-item" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-circle"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg></a></li>' +
                '</ul>' +
                '</td>' +
                '<td><input type="checkbox" name="row_id[]" class="row_id" value="' + currentIndex +
                '" hidden></td>' +
                '<td class="product_id"><select name="product_id[]" id="product_id" class="form-control form-select select2 @error('product_id') is-invalid @enderror product_' +
                currentIndex +
                '"><option value="">@lang('messages.select-product')</option> @foreach ($items as $item)<option value="{{ $item->id }}"{{ old('product_id') == $item->id ? 'selected' : '' }}>{{ App::getLocale() === 'ur' ? $item->name_ur ?? '-' : $item->name_en ?? '-' }}</option>@endforeach</select> ' +
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
                    // console.log(row_id + ", " + quantity + ", " + price);
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

            $(document).ready(function() {

                // Function to calculate totals
                function calculateTotals() {
                    let totalQuantity = 0;

                    // Loop through each row
                    $('input.quantity').each(function() {
                        const val = parseFloat($(this).val()) || 0;
                        totalQuantity += val;
                    });

                    // Set totals in footer fields
                    $('#total_quantity').val(totalQuantity.toFixed(2));
                }

                // Calculate totals on page load
                calculateTotals();

                // Recalculate whenever received quantity changes
                $(document).on('input', '.quantity', function() {
                    calculateTotals();
                });
                $(document).on('click', '.delete-item', function() {
                    calculateTotals();
                });
            });


            $(document).on('click', 'body *', function() {
                $('.amount').on("focusout", function() {
                    doAmountTotal();
                });

                $('.delete-item').on("click", function() {
                    doAmountTotal();
                    calculateTotals();
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
                // getDetailAccounts: "{{ route('get.detail.account.data', ['partyId' => ':id']) }}",
                getDetailAccounts: "{{ route('get.detail.account.data.project') }}",
                getProductSizeDetail: "{{ route('purchase-order.getProductSizeDetail', ['id' => ':id']) }}",
            }
        };
    </script>

    <script src="{{ asset('js/plugins/sweetalert2/sweetalert2.all.js') }}"></script>
    <script src="{{ asset('js/saleInvoice.js') }}"></script>
@endsection
