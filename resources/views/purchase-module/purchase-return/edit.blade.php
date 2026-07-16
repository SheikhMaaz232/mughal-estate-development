@extends('layouts.backend')

@section('content')
    <div class="block block-rounded col-md-12">
        <div class="block-header block-header-default">
            <h3 class="block-title">@lang('messages.edit-purchase-return')</h3>
        </div>
        <div class="block-content block-content-full">
            <form id="purchase-return-form" action="{{ route('purchase-return.update', $purchaseReturnMaster->id) }}"
                method="POST" >
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="grn_no" class="form-label">@lang('messages.purchase_return_invoice_no')</label>

                        <input class="form-control" value="{{ $purchaseReturnMaster->id }}" disabled>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="grn_no" class="form-label">@lang('messages.purchase_invoice_no')</label>

                        <input class="form-control" style="background-color: #e9ecef !important;" name="purchase_invoice_no"
                            value="{{ $purchaseReturnMaster->purchase_invoice_no }}" readonly>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="purchase_order_no" class="form-label">@lang('messages.GRN')</label>

                        <input class="form-control" style="background-color: #e9ecef !important;" name="grn_no"
                            value="{{ $purchaseReturnMaster->id }}" readonly>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="purchase_order_no" class="form-label">@lang('messages.purchase_order_no')</label>

                        <input class="form-control" style="background-color: #e9ecef !important;" name="purchase_order_no"
                            value="{{ $purchaseReturnMaster->purchase_order_no }}" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="date" class="form-label">@lang('messages.Date')</label>

                        <input type="date" class="form-control" name="date"
                            value="{{ old('date', isset($purchaseReturnMaster->date) ? $purchaseReturnMaster->date : now()->format('Y-m-d')) }}">

                        @error('date')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="project_id">@lang('messages.projects')</label>
                        <select name="project_id" id="project_id"
                            class="form-control select2 form-select @error('project_id') is-invalid @enderror" disabled>
                            <option value="">@lang('messages.main_party')</option>
                            @foreach ($projects as $project)
                                <option value="{{ $project->id }}"
                                    {{ (old('project_id') ?? $purchaseReturnMaster->project_id) == $project->id ? 'selected' : '' }}>
                                    {{ App::getLocale() === 'ur' ? $project->name_ur ?? '-' : $project->name_en ?? '-' }}
                                </option>
                            @endforeach
                        </select>
                        <input type="hidden" name="project_id" value="{{ $purchaseReturnMaster->project_id }}">
                        @error('project_id')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label for="party_id">@lang('messages.main_party')</label>
                        <select name="party_id" id="party_id"
                            class="form-control select2 form-select @error('party_id') is-invalid @enderror" disabled>
                            <option value="">@lang('messages.main_party')</option>
                            @foreach ($searchParties as $searchParty)
                                <option value="{{ $searchParty->id }}"
                                    {{ (old('party_id') ?? $purchaseReturnMaster->party_id) == $searchParty->id ? 'selected' : '' }}>
                                    {{ App::getLocale() === 'ur' ? $searchParty->name_ur ?? '-' : $searchParty->name_en ?? '-' }}
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; -
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $searchParty->cnic_no ?? 'N/A' }}
                                </option>
                            @endforeach
                        </select>
                        <input type="hidden" name="party_id" value="{{ $purchaseReturnMaster->party_id }}">
                        @error('party_id')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="detail_account_id">@lang('messages.detail_account')</label>
                        <select name="detail_account_id" id="detail_account_id"
                            class="form-control select2 form-select @error('detail_account_id') is-invalid @enderror"
                            disabled>
                            <option value="">@lang('messages.detail_account')</option>
                            @foreach ($detailAccounts as $detailAccount)
                                <option value="{{ $detailAccount->id }}"
                                    {{ (old('detail_account_id') ?? $purchaseReturnMaster->detail_account_id) == $detailAccount->id ? 'selected' : '' }}>
                                    {{ App::getLocale() === 'ur' ? $detailAccount->name_ur ?? '-' : $detailAccount->name_en ?? '-' }}
                                </option>
                            @endforeach
                        </select>
                        <input type="hidden" name="detail_account_id"
                            value="{{ $purchaseReturnMaster->detail_account_id }}">
                        @error('detail_account_id')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label for="supplier_bill_no">@lang('messages.supplier-bill-no') </label>
                        <input type="text" style="background-color: #e9ecef !important;" class="form-control"
                            id="supplier_bill_no" name="supplier_bill_no" placeholder="@lang('messages.supplier-bill-no')"
                            value="{{ old('supplier_bill_no', $purchaseReturnMaster->supplier_bill_no) }}" readonly>
                        @error('supplier_bill_no')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <input type="hidden" name="status" value="{{ $purchaseReturnMaster->status }}">
                    @error('status')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror


                    <div class="col-md-6 mb-3">
                        <label for="unloaded_by">@lang('messages.unloaded_by') </label>
                        <input type="text" style="background-color: #e9ecef !important;" class="form-control"
                            id="unloaded_by" name="unloaded_by" placeholder="@lang('messages.unloaded_by')"
                            value="{{ old('unloaded_by', $purchaseReturnMaster->unloaded_by) }}" readonly>
                        @error('unloaded_by')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">

                    <div class="col-md-12 mb-3">
                        <label for="remarks">@lang('messages.remarks')</label>
                        <textarea type="text" class="form-control" id="remarks" name="remarks" placeholder="@lang('messages.remarks')"
                            autocomplete="off">{{ old('remarks', $purchaseReturnMaster->remarks) }}</textarea>
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
                                        <th style="width: 35% !important">@lang('messages.products')</th>
                                        <th class="">
                                            @lang('messages.unit')</th>
                                        <th class="">
                                            @lang('messages.quantity')</th>
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

                                    @if (!empty($purchaseReturnDetails))
                                        @foreach ($purchaseReturnDetails as $purchaseReturnDetail)
                                            @php
                                                $index = $loop->index + 2;
                                            @endphp
                                            <tr>
                                                <td><input type="checkbox" name="row_id[]" class="row_id"
                                                        value="{{ $index }}" hidden></td>
                                                <td class="product_id">
                                                    <select id="product_id"
                                                        class="product form-control form-select select2 @error('product_id') is-invalid @enderror product_{{ $index }}"
                                                        disabled>
                                                        <option value="">@lang('messages.select-product')</option>
                                                        @foreach ($items as $productData)
                                                            <option value="{{ $productData->id }}"
                                                                {{ (old('product_id') ?? $purchaseReturnDetail->product_id) == $productData->id ? 'selected' : '' }}>
                                                                {{ App::getLocale() === 'ur' ? $productData->name_ur ?? '-' : $productData->name_en ?? '-' }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <input type="hidden" name="product_id[]"
                                                        value="{{ $purchaseReturnDetail->product_id }}">
                                                </td>
                                                <td class="measurement_unit">
                                                    <input id="measurement_unit"
                                                        style="background-color: #e9ecef !important; color: black; "
                                                        class = "measurement_unit form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}  measurement_unit_{{ $index }}"
                                                        placeholder="@lang('messages.unit')" readonly>
                                                </td>
                                                <td class="quantity">
                                                    <input type="text"
                                                        style="color: black;"
                                                        value="{{ old('quantity',$purchaseReturnDetail->quantity) }}"
                                                        placeholder="@lang('messages.quantity')" id="quantity" name="quantity[]"
                                                        class = "quantity form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} quantity_{{ $index }}">
                                                </td>
                                                <td class="price">
                                                    <input type="number" name="price[]"
                                                        value="{{ $purchaseReturnDetail->price }}" step="any"
                                                        min="0" onwheel="this.blur()"
                                                        style="background-color: #e9ecef !important;"
                                                        class="price form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} price_{{ $index }}"
                                                        placeholder="@lang('messages.price') " readonly>
                                                </td>
                                                <td class="amount">
                                                    <input type="text" name="amount[]"
                                                        value="{{ $purchaseReturnDetail->amount }}"
                                                        style="background-color: #e9ecef !important;"
                                                        class="amount form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} amount_{{ $index }}"
                                                        placeholder="@lang('messages.amount') " readonly>
                                                </td>
                                                <td class="detail_remarks">
                                                    <textarea name="detail_remarks[]"
                                                        class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} detail_remarks_{{ $index }}"
                                                        placeholder="@lang('messages.remarks')" rows="2">{{ $purchaseReturnDetail->detail_remarks }}</textarea>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>


                            </table>
                        </div>

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
                                    id="total_quantity" placeholder="@lang('messages.gross_amount')" readonly>
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
                                    id="gross_bill" placeholder="@lang('messages.gross_amount')" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-end">
                    <div class="col-md-6 mb-3">
                        <div class="form-group row">
                            <div class="col-md-3">
                                <label>@lang('messages.fare')</label>
                            </div>
                            <div class="col-md-9">
                                <input type="number" id="carriage"
                                    style="color: black;" name="carriage" step="any" min="0" onwheel="this.blur()"
                                    value="{{ old('carriage', $purchaseReturnMaster->carriage) }}"
                                    class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} carriage"
                                    placeholder="@lang('messages.fare')">
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
                                <input type="number" id="tax"
                                    style="color: black;" name="tax" step="any" min="0" onwheel="this.blur()"
                                    value="{{ old('tax', $purchaseReturnMaster->tax) }}"
                                    class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} tax"
                                    placeholder="@lang('messages.tax_amount')">
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
                                <input type="number" id="other_amount"
                                    style="color: black;" name="other_amount" step="any" min="0" onwheel="this.blur()"
                                    value="{{ old('other_amount', $purchaseReturnMaster->other_amount) }}"
                                    class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} other_amount"
                                    placeholder="@lang('messages.other_amount')">
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
                                <input type="number" id="net_amount"
                                    style="color: black; background-color: #e9ecef !important;" name="net_amount"
                                    class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} net_amount"
                                    placeholder="@lang('messages.net_amount')" readonly>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="d-flex justify-content-end gap-2">
                        <button type="submit" class="btn btn-primary">@lang('messages.update')</button>
                        <a href="{{ route('purchase-return.index') }}" class="btn btn-dark">@lang('messages.go-to-list')</a>
                    </div>
                </div>

            </form>
        </div>
    </div>


    <script>
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
    </script>
    <script>
        $(document).ready(function() {

            // Function to calculate amount for a single row using row_id
            function calculateRowAmount(row) {
                var row_id = $(row).closest("tr").find(".row_id").val();

                let quantity = parseFloat($(row).find(".quantity_" + row_id).val()) || 0;
                let price = parseFloat($(row).find(".price_" + row_id).val()) || 0;

                if (quantity > 0) {
                    let amount = quantity * price;
                    $(row).find(".amount_" + row_id).val(amount.toFixed(2));
                    return amount;
                } else {
                    $(row).find(".amount_" + row_id).val('');
                    return 0;
                }
            }

            // Calculate all rows and gross bill
            function calculateAll() {
                let grossTotal = 0;
                $('tbody tr').each(function() {
                    grossTotal += calculateRowAmount(this);
                });
                $('#gross_bill').val(grossTotal.toFixed(2));
            }

            // Run calculation on page load
            calculateAll();

            $(document).on('input', '.quantity', function() {
                calculateAll();
            });
        });
        $(document).ready(function() {

            // Function to calculate net amount
            function calculateNetAmount() {

                let grossAmount = parseFloat($(".gross_bill").val()) || 0;
                let carriage = parseFloat($(".carriage").val()) || 0;
                let taxAmount = parseFloat($(".tax").val()) || 0;
                let otherAmount = parseFloat($(".other_amount").val()) || 0;

                let netAmount = grossAmount + carriage + taxAmount + otherAmount;

                $(".net_amount").val(netAmount.toFixed(2));

                return netAmount;
            }

            // Run calculation on page load
            calculateNetAmount();


            $(document).on('input', '.carriage, .tax, .other_amount, .quantity', function() {
                calculateNetAmount();
            });

        });

        // Function to calculate total quantity of all rows
        function calculateTotalQuantity() {
            let totalQty = 0;

            $('.quantity').each(function() {
                let qty = parseFloat($(this).val()) || 0;
                totalQty += qty;
            });

            $('#total_quantity').val(totalQty.toFixed(2));
        }
        $(document).ready(function() {
            calculateTotalQuantity();

            $(document).on('input', '.quantity', function() {
                calculateTotalQuantity();
            });
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
            errorText2: "{{ __('messages.po_and_received_qty_not_grater') }}",
            confirmButtonText: "{{ __('messages.ok') }}",
        };
    </script>

    <script>
        var config = {
            routes: {
                getDetailAccounts: "{{ route('get.detail.account.data', ['partyId' => ':id']) }}",
                getProductSizeDetail: "{{ route('purchase-order.getProductSizeDetail', ['id' => ':id']) }}",
            }
        };
    </script>

    <script src="{{ asset('js/plugins/sweetalert2/sweetalert2.all.js') }}"></script>
    <script src="{{ asset('js/purchaseReturn.js') }}"></script>
@endsection
