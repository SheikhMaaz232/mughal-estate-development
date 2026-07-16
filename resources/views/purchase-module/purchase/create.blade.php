@extends('layouts.backend')

@section('content')
    <div class="block block-rounded col-md-12">
        <div class="block-header block-header-default">
            <h3 class="block-title">@lang('messages.add-purchase-invoice')</h3>
        </div>
        <div class="block-content block-content-full">
            <form id="purchase-invoice-form" action="{{ route('purchase-invoice.store') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="grn_no" class="form-label">@lang('messages.purchase_invoice_no')</label>

                        <input class="form-control" value="{{ $maxId }}" disabled>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="purchase_order_no" class="form-label">@lang('messages.GRN')</label>

                        <input class="form-control" style="background-color: #e9ecef !important;" name="grn_no"
                            value="{{ $grnMaster->id }}" readonly>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="purchase_order_no" class="form-label">@lang('messages.purchase_order_no')</label>

                        <input class="form-control" style="background-color: #e9ecef !important;" name="purchase_order_no"
                            value="{{ $grnMaster->purchase_order_no }}" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="date" class="form-label">@lang('messages.Date')</label>

                        <input type="date" class="form-control" name="date"
                            value="{{ old('date', now()->format('Y-m-d')) }}">

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
                                    {{ (old('project_id') ?? $grnMaster->project_id) == $project->id ? 'selected' : '' }}>
                                    {{ App::getLocale() === 'ur' ? $project->name_ur ?? '-' : $project->name_en ?? '-' }}
                                </option>
                            @endforeach
                        </select>
                        <input type="hidden" name="project_id" value="{{ $grnMaster->project_id }}">
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
                                    {{ (old('party_id') ?? $grnMaster->party_id) == $searchParty->id ? 'selected' : '' }}>
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
                        <input type="hidden" name="party_id" value="{{ $grnMaster->party_id }}">
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
                                    {{ (old('detail_account_id') ?? $grnMaster->detail_account_id) == $detailAccount->id ? 'selected' : '' }}>
                                    {{ App::getLocale() === 'ur' ? $detailAccount->name_ur ?? '-' : $detailAccount->name_en ?? '-' }}
                                </option>
                            @endforeach
                        </select>
                        <input type="hidden" name="detail_account_id" value="{{ $grnMaster->detail_account_id }}">
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
                            value="{{ old('supplier_bill_no', $grnMaster->supplier_bill_no) }}" readonly>
                        @error('supplier_bill_no')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <input type="hidden" name="status" value="{{ 'Unverified' }}">
                    @error('status')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror


                    <div class="col-md-6 mb-3">
                        <label for="unloaded_by">@lang('messages.unloaded_by') </label>
                        <input type="text" style="background-color: #e9ecef !important;" class="form-control"
                            id="unloaded_by" name="unloaded_by" placeholder="@lang('messages.unloaded_by')"
                            value="{{ old('unloaded_by', $grnMaster->unloaded_by) }}" readonly>
                        @error('unloaded_by')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label for="remarks">@lang('messages.remarks') @lang('messages.english')</label>
                        <textarea type="text" class="form-control" id="remarks_en" name="remarks_en"
                            placeholder="@lang('messages.remarks') @lang('messages.english')" autocomplete="off" value="{{ old('remarks_en') }}"></textarea>
                        @error('remarks_en')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="remarks">@lang('messages.remarks') @lang('messages.urdu')</label>
                        <textarea type="text" class="form-control" id="remarks_ur" name="remarks_ur"
                            placeholder="@lang('messages.remarks') @lang('messages.urdu')" autocomplete="off" value="{{ old('remarks_ur') }}"></textarea>
                        @error('remarks_ur')
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
                                            @lang('messages.qty')</th>
                                        <th class="">
                                            @lang('messages.price')</th>
                                        <th class="">
                                            @lang('messages.amount')</th>

                                    </tr>
                                    <tr aria-hidden="true" class="mt-3 d-block table-row-hidden">
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (!empty($grnDetails))
                                        @foreach ($grnDetails as $grnDetail)
                                            @php
                                                $index = $loop->index + 2;
                                                $poDetail = $purchaseOrderDetails[$grnDetail->product_id] ?? null;
                                            @endphp

                                            <!-- First Row -->
                                            <tr>
                                                <td><input type="checkbox" name="row_id[]" class="row_id"
                                                        value="{{ $index }}" hidden></td>

                                                <td class="product_id">
                                                    <select id="product_id"
                                                        class="product form-control form-select select2 product_{{ $index }}"
                                                        disabled>
                                                        <option value="">@lang('messages.select-product')</option>
                                                        @foreach ($items as $productData)
                                                            <option value="{{ $productData->id }}"
                                                                {{ (old('product_id') ?? $grnDetail->product_id) == $productData->id ? 'selected' : '' }}>
                                                                {{ App::getLocale() === 'ur' ? $productData->name_ur ?? '-' : $productData->name_en ?? '-' }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <input type="hidden" name="product_id[]"
                                                        value="{{ $grnDetail->product_id }}">
                                                </td>

                                                <td>
                                                    <input
                                                        class="measurement_unit form-control measurement_unit_{{ $index }}"
                                                        readonly>
                                                </td>

                                                <td>
                                                    <input type="text" name="quantity[]"
                                                        value="{{ $grnDetail->received_qty }}"
                                                        class="quantity form-control quantity_{{ $index }}"
                                                        readonly>
                                                </td>

                                                <td>
                                                    <input type="number" name="price[]" value="{{ $poDetail->price }}"
                                                        class="price form-control price_{{ $index }}" readonly>
                                                </td>

                                                <td>
                                                    <input type="text" name="amount[]"
                                                        class="amount form-control amount_{{ $index }}" readonly>
                                                </td>
                                            </tr>

                                            <!-- Second Row for Remarks -->
                                            <tr>
                                                <td colspan="3">
                                                    <label><strong>@lang('messages.remarks') @lang('messages.english')</strong></label>
                                                    <textarea name="detail_remarks_en[]" class="form-control detail_remarks_en_{{ $index }}" rows="2"
                                                        placeholder="@lang('messages.remarks')"></textarea>
                                                </td>

                                                <td colspan="3">
                                                    <label><strong>@lang('messages.remarks') @lang('messages.urdu')</strong></label>
                                                    <textarea name="detail_remarks_ur[]" class="form-control detail_remarks_ur_{{ $index }}" rows="2"
                                                        placeholder="@lang('messages.remarks')"></textarea>
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
                                    style="color: black; background-color: #e9ecef !important;" name="carriage"
                                    value="{{ old('carriage', $grnMaster->fare) }}"
                                    class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} carriage"
                                    placeholder="@lang('messages.fare')" readonly>
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
                                    style="color: black; background-color: #e9ecef !important;" name="tax"
                                    value="{{ old('tax', $purchaseOrderMaster->tax_amount) }}"
                                    class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} tax"
                                    placeholder="@lang('messages.tax_amount')" readonly>
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
                                    style="color: black; background-color: #e9ecef !important;" name="other_amount"
                                    value="{{ old('other_amount', $purchaseOrderMaster->other_amount) }}"
                                    class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} other_amount"
                                    placeholder="@lang('messages.other_amount')" readonly>
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
                        <button type="submit" class="btn btn-primary">@lang('messages.save')</button>
                        <a href="{{ route('purchase-invoice.index') }}" class="btn btn-dark">@lang('messages.go-to-list')</a>
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
    <script src="{{ asset('js/grn.js') }}"></script>
@endsection
