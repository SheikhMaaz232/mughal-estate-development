@extends('layouts.backend')

@section('content')
    <div class="block block-rounded col-md-12">
        <div class="block-header block-header-default">
            <h3 class="block-title">@lang('messages.add-purchase-invoice')</h3>
        </div>
        <div class="block-content block-content-full">
            <form id="purchase-invoice-form" action="{{ route('plot-purchase-invoice.store') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="purchase_invoice_no" class="form-label">@lang('messages.plot-purchase_invoice_no')</label>

                        <input class="form-control" value="{{ $maxId }}" disabled>
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
                            class="form-control select2 form-select @error('project_id') is-invalid @enderror">
                            <option value="">@lang('messages.main_party')</option>
                            @foreach ($projects as $project)
                                <option value="{{ $project->id }}"
                                    {{ old('project_id') == $project->id ? 'selected' : '' }}>
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
                                    {{ old('party_id') == $searchParty->id ? 'selected' : '' }}>
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
                                    {{ old('detail_account_id') == $detailAccount->id ? 'selected' : '' }}>
                                    {{ App::getLocale() === 'ur' ? $detailAccount->name_ur ?? '-' : $detailAccount->name_en ?? '-' }}
                                </option>
                            @endforeach
                        </select>
                        @error('detail_account_id')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <input type="hidden" name="status" value="{{ 'Unverified' }}">
                    @error('status')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror

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
                                        <th>
                                        </th>
                                        <th style="width: 35% !important">@lang('messages.products')</th>
                                        <th class="">
                                            @lang('messages.unit')</th>
                                        <th class="">
                                            @lang('messages.size')</th>
                                        <th class="">
                                            @lang('messages.per_marla_rate')</th>
                                        <th class="">
                                            @lang('messages.amount')</th>

                                    </tr>
                                    <tr aria-hidden="true" class="mt-3 d-block table-row-hidden">
                                    </tr>
                                </thead>
                                <tbody>
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


                <div class="row">
                    <div class="d-flex justify-content-end gap-2">
                        <button type="submit" class="btn btn-primary">@lang('messages.save')</button>
                        <a href="{{ route('plot-purchase-invoice.index') }}" class="btn btn-dark">@lang('messages.go-to-list')</a>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {

            // ==========================================
            // CALCULATE TOTAL QUANTITY + TOTAL AMOUNT
            // ==========================================
            function calculateInvoiceTotals() {
                let totalQuantity = 0;
                let totalAmount = 0;

                $('.item-main-row').each(function() {
                    let quantity = parseFloat($(this).find('.quantity').val()) || 0;
                    let amount = parseFloat($(this).find('.amount').val()) || 0;

                    totalQuantity += quantity;
                    totalAmount += amount;
                });

                $('#total_quantity').val(totalQuantity.toFixed(2));
                $('#gross_bill').val(totalAmount.toFixed(2));
            }

            // ==========================================
            // DELETE ONE COMPLETE ITEM GROUP
            // Main row + English remarks row + Urdu remarks row
            // ==========================================
            function deleteItemRow() {
                $('.item-table tbody').on('click', '.delete-item', function(e) {
                    e.preventDefault();

                    let $mainRow = $(this).closest('.item-main-row');

                    // Remove the two remarks rows belonging to this item.
                    $mainRow.next('.remarks-row').remove();
                    $mainRow.next('.remarks-row').remove();

                    // Remove the main item row.
                    $mainRow.remove();

                    // Recalculate invoice totals.
                    calculateInvoiceTotals();
                });
            }

            // ==========================================
            // ADD PRODUCT DETAIL
            // ==========================================
            $('.additem').on('click', function() {
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

                let $html =
                    '<tr class="item-main-row">' +

                    '<td class="delete-item-row" rowspan="3">' +
                    '<ul class="table-controls">' +
                    '<li>' +
                    '<a href="javascript:void(0);" class="delete-item" data-toggle="tooltip" data-placement="top" title="Delete">' +
                    '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">' +
                    '<circle cx="12" cy="12" r="10"></circle>' +
                    '<line x1="15" y1="9" x2="9" y2="15"></line>' +
                    '<line x1="9" y1="9" x2="15" y2="15"></line>' +
                    '</svg>' +
                    '</a>' +
                    '</li>' +
                    '</ul>' +
                    '</td>' +

                    '<td rowspan="3">' +
                    '<input type="hidden" name="row_id[]" class="row_id" value="' + currentIndex + '">' +
                    '</td>' +

                    '<td class="product_id">' +
                    '<select name="product_id[]" class="product_id form-control form-select select2 product_' +
                    currentIndex + '">' +
                    '<option value="">@lang('messages.select-product')</option>' +
                    '</select>' +
                    '</td>' +

                    '<td class="measurement_unit">' +
                    '<input type="text" style="color: black;" class="measurement_unit form-control measurement_unit_' +
                    currentIndex + '" placeholder="@lang('messages.unit')" readonly>' +
                    '</td>' +

                    '<td class="quantity">' +
                    '<input type="text" placeholder="@lang('messages.qty')" name="size[]" class="quantity form-control quantity_' +
                    currentIndex + '">' +
                    '</td>' +

                    '<td class="price">' +
                    '<input type="text" name="per_marla_rate[]" class="price form-control price_' + currentIndex +
                    '" placeholder="@lang('messages.price')">' +
                    '</td>' +

                    '<td class="amount">' +
                    '<input type="text" name="amount[]" class="amount form-control amount_' + currentIndex +
                    '" placeholder="@lang('messages.amount')" readonly>' +
                    '</td>' +

                    '</tr>' +

                    // English remarks row
                    '<tr class="remarks-row">' +
                    '<td colspan="5">' +
                    '<div class="row">' +
                    '<div class="col-md-2">' +
                    '<label class="form-label">Detail Remarks (EN)</label>' +
                    '</div>' +
                    '<div class="col-md-10">' +
                    '<textarea name="detail_remarks_en[]" ' +
                    'class="form-control detail_remarks_en_' + currentIndex + '" ' +
                    'placeholder="Detail Remarks (English)" rows="2"></textarea>' +
                    '</div>' +
                    '</div>' +
                    '</td>' +
                    '</tr>' +

                    // Urdu remarks row
                    '<tr class="remarks-row">' +
                    '<td colspan="5">' +
                    '<div class="row">' +
                    '<div class="col-md-2">' +
                    '<label class="form-label">Detail Remarks (UR)</label>' +
                    '</div>' +
                    '<div class="col-md-10">' +
                    '<textarea name="detail_remarks_ur[]" ' +
                    'class="form-control detail_remarks_ur_' + currentIndex + '" ' +
                    'placeholder="تفصیلی ریمارکس (اردو)" rows="2" dir="rtl"></textarea>' +
                    '</div>' +
                    '</div>' +
                    '</td>' +
                    '</tr>';

                $(".item-table tbody").append($html);

                // Populate products for the newly added row.
                let $select = $(".product_" + currentIndex);

                $.each(projectItems, function(id, name) {
                    $select.append('<option value="' + id + '">' + name + '</option>');
                });

                // Initialize Select2 for the new product dropdown.
                $select.select2();

                calculateInvoiceTotals();
            });

            // ==========================================
            // WHEN SIZE OR PRICE CHANGES
            // ==========================================
            $(document).on('input', '.quantity, .price', function() {
                let $row = $(this).closest('.item-main-row');

                let quantity = parseFloat($row.find('.quantity').val()) || 0;
                let price = parseFloat($row.find('.price').val()) || 0;

                // Calculate current row amount.
                let amount = quantity * price;

                $row.find('.amount').val(amount.toFixed(2));

                // Calculate invoice totals.
                calculateInvoiceTotals();
            });

            // ==========================================
            // WHEN PRODUCT IS SELECTED
            // ==========================================
            $(document).on('change', '.product_id', function() {
                let $row = $(this).closest('.item-main-row');
                let productId = $(this).val();

                if (!productId) {
                    $row.find('.measurement_unit').val('');
                    $row.find('.quantity').val('');
                    $row.find('.price').val('');
                    $row.find('.amount').val('');

                    calculateInvoiceTotals();
                    return;
                }

                let url = config.routes.getProductSizeDetail.replace(':id', productId);

                $.ajax({
                    url: url,
                    type: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            let data = response.data.original.data;

                            // Unit
                            $row.find('.measurement_unit').val(data.unit);

                            // Size
                            $row.find('.quantity').val(data.total_marla);

                            // Per Marla Rate
                            $row.find('.price').val(data.amount_in_pkr);

                            // Amount
                            $row.find('.amount').val(data.total_amount);

                            // Recalculate totals after AJAX values are loaded.
                            calculateInvoiceTotals();
                        }
                    },
                    error: function(xhr) {
                        console.log('ERROR:', xhr.responseText);

                        Swal.fire({
                            icon: 'error',
                            title: 'Something went wrong'
                        });
                    }
                });
            });

            // ==========================================
            // INITIALIZE DELETE HANDLER
            // ==========================================
            deleteItemRow();

            // Initial totals.
            calculateInvoiceTotals();
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
            selectProjectFirst: "{{ __('messages.select_project_first') }}"
        };
    </script>

    <script>
        var config = {
            routes: {
                getDetailAccounts: "{{ route('get.payable.detail.account.data.project') }}",
                getProductSizeDetail: "{{ route('plot-purchase.getProductSizeDetail', ['id' => ':id']) }}",
                getProjectItems: "{{ route('plot-purchase.getProjectProducts', ['projectId' => ':id']) }}"
            }
        };
    </script>
    <script>
        let projectItems = {}; // store items for selected project

        $('#project_id').on('change', function() {
            let projectId = $(this).val();

            if (!projectId) {
                Swal.fire({
                    icon: 'warning',
                    title: window.customTranslations.errorTitle,
                    text: window.customTranslations.selectProjectFirst
                });
                return;
            }

            // Clear all product rows
            $(".item-table tbody").empty();
            $('#detail_account_id').empty().append('<option selected disabled>' + window.customTranslations
                .pleaseSelect + '</option>');

            // Fetch project items via AJAX
            let url = config.routes.getProjectItems.replace(':id', projectId);
            $.ajax({
                url: url,
                type: 'GET',
                success: function(response) {
                    if (response.status === 'success') {
                        // Store items in a global variable
                        projectItems = response.data;
                    } else {
                        projectItems = {};
                    }
                }
            });
        });
    </script>

    <script src="{{ asset('js/plugins/sweetalert2/sweetalert2.all.js') }}"></script>
    <script src="{{ asset('js/plotPurchaseInvoice.js') }}"></script>
@endsection
