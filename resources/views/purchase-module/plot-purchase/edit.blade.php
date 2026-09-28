@extends('layouts.backend')

@section('content')
    <div class="block block-rounded col-md-12">

        <div class="block-header block-header-default">
            <h3 class="block-title">
                @lang('messages.edit-purchase-invoice')
            </h3>
        </div>

        <div class="block-content block-content-full">

            <form id="purchase-invoice-form" action="{{ route('plot-purchase-invoice.update', $purchaseMaster->id) }}"
                method="POST" enctype="multipart/form-data">

                @csrf
                @method('PUT')

                {{-- =========================================================
                    INVOICE NUMBER
                ========================================================== --}}

                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label class="form-label">
                            @lang('messages.plot-purchase_invoice_no')
                        </label>

                        <input type="text" class="form-control"
                            value="{{ $purchaseMaster->purchase_invoice_no ?? $purchaseMaster->id }}" disabled>

                    </div>

                </div>


                {{-- =========================================================
                    DATE + PROJECT
                ========================================================== --}}

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label for="date" class="form-label">
                            @lang('messages.Date')
                        </label>

                        <input type="date" class="form-control @error('date') is-invalid @enderror" name="date"
                            value="{{ old('date', $purchaseMaster->date ? \Carbon\Carbon::parse($purchaseMaster->date)->format('Y-m-d') : now()->format('Y-m-d')) }}">

                        @error('date')
                            <div class="text-danger mt-1">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    <div class="col-md-6 mb-3">

                        <label for="project_id">
                            @lang('messages.projects')
                        </label>

                        <select name="project_id" id="project_id"
                            class="form-control select2 form-select @error('project_id') is-invalid @enderror">

                            <option value="">
                                @lang('messages.main_party')
                            </option>

                            @foreach ($projects as $project)
                                <option value="{{ $project->id }}"
                                    {{ old('project_id', $purchaseMaster->project_id) == $project->id ? 'selected' : '' }}>

                                    {{ App::getLocale() === 'ur' ? $project->name_ur ?? '-' : $project->name_en ?? '-' }}

                                </option>
                            @endforeach

                        </select>

                        @error('project_id')
                            <div class="text-danger mt-1">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>


                {{-- =========================================================
                    PARTY + DETAIL ACCOUNT
                ========================================================== --}}

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label for="party_id">
                            @lang('messages.main_party')
                        </label>

                        <select name="party_id" id="party_id"
                            class="form-control select2 form-select @error('party_id') is-invalid @enderror">

                            <option value="">
                                @lang('messages.main_party')
                            </option>

                            @foreach ($searchParties as $searchParty)
                                <option value="{{ $searchParty->id }}"
                                    {{ old('party_id', $purchaseMaster->party_id) == $searchParty->id ? 'selected' : '' }}>

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
                            <div class="text-danger mt-1">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    <div class="col-md-6 mb-3">

                        <label for="detail_account_id">
                            @lang('messages.detail_account')
                        </label>

                        <select name="detail_account_id" id="detail_account_id"
                            class="form-control select2 form-select @error('detail_account_id') is-invalid @enderror">

                            <option value="">
                                @lang('messages.detail_account')
                            </option>

                            @foreach ($detailAccounts as $detailAccount)
                                <option value="{{ $detailAccount->id }}"
                                    {{ old('detail_account_id', $purchaseMaster->detail_account_id) == $detailAccount->id ? 'selected' : '' }}>

                                    {{ App::getLocale() === 'ur' ? $detailAccount->name_ur ?? '-' : $detailAccount->name_en ?? '-' }}

                                </option>
                            @endforeach

                        </select>

                        @error('detail_account_id')
                            <div class="text-danger mt-1">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>


                {{-- =========================================================
                    STATUS
                ========================================================== --}}

                <input type="hidden" name="status" value="{{ old('status', $purchaseMaster->status ?? 'Unverified') }}">


                {{-- =========================================================
                    MASTER REMARKS
                ========================================================== --}}

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label for="remarks_en">
                            @lang('messages.remarks')
                            @lang('messages.english')
                        </label>

                        <textarea class="form-control" id="remarks_en" name="remarks_en" placeholder="@lang('messages.remarks') @lang('messages.english')"
                            autocomplete="off">{{ old('remarks_en', $purchaseMaster->remarks_en ?? '') }}</textarea>

                        @error('remarks_en')
                            <div class="text-danger mt-1">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>


                    <div class="col-md-6 mb-3">

                        <label for="remarks_ur">
                            @lang('messages.remarks')
                            @lang('messages.urdu')
                        </label>

                        <textarea class="form-control" id="remarks_ur" name="remarks_ur" placeholder="@lang('messages.remarks') @lang('messages.urdu')"
                            autocomplete="off" dir="rtl">{{ old('remarks_ur', $purchaseMaster->remarks_ur ?? '') }}</textarea>

                        @error('remarks_ur')
                            <div class="text-danger mt-1">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>


                {{-- =========================================================
                    PRODUCT DETAILS
                ========================================================== --}}

                <div class="col-md-12">

                    <h2 style="color: red">
                        @lang('messages.product_details')
                    </h2>

                </div>


                <div class="tab-content" id="pills-tabContent" style="margin-bottom: 5px;">

                    <div class="invoice-detail-items" style="padding: 0 !important;">

                        <div class="table-responsive">

                            <table class="table item-table">

                                <thead>

                                    <tr>

                                        <th></th>

                                        <th></th>

                                        <th style="width: 35% !important">
                                            @lang('messages.products')
                                        </th>

                                        <th>
                                            @lang('messages.unit')
                                        </th>

                                        <th>
                                            @lang('messages.size')
                                        </th>

                                        <th>
                                            @lang('messages.per_marla_rate')
                                        </th>

                                        <th>
                                            @lang('messages.amount')
                                        </th>

                                    </tr>

                                    <tr aria-hidden="true" class="mt-3 d-block table-row-hidden">
                                    </tr>

                                </thead>


                                <tbody>

                                    @foreach ($purchaseDetails as $index => $detail)
                                        {{-- =====================================================
                                            MAIN PRODUCT ROW
                                        ====================================================== --}}

                                        <tr class="item-main-row">

                                            {{-- DELETE --}}
                                            <td class="delete-item-row" rowspan="3">

                                                <ul class="table-controls">

                                                    <li>

                                                        <a href="javascript:void(0);" class="delete-item" title="Delete">

                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round">

                                                                <circle cx="12" cy="12" r="10">
                                                                </circle>

                                                                <line x1="15" y1="9" x2="9"
                                                                    y2="15">
                                                                </line>

                                                                <line x1="9" y1="9" x2="15"
                                                                    y2="15">
                                                                </line>

                                                            </svg>

                                                        </a>

                                                    </li>

                                                </ul>

                                            </td>


                                            {{-- DETAIL ID --}}
                                            <td rowspan="3">

                                                <input type="hidden" name="detail_id[]" class="detail_id"
                                                    value="{{ $detail->id }}">

                                                <input type="hidden" name="row_id[]" class="row_id"
                                                    value="{{ $detail->id }}">

                                            </td>


                                            {{-- PRODUCT --}}
                                            <td class="product_id">

                                                <select name="product_id[]"
                                                    class="product_id form-control form-select select2 product_{{ $index }}">

                                                    <option value="">
                                                        @lang('messages.select-product')
                                                    </option>

                                                    @foreach ($itemsData as $productData)
                                                        <option value="{{ $productData->id }}"
                                                            {{ old("product_id.$index", $detail->product_id) == $productData->id ? 'selected' : '' }}>
                                                            {{ App::getLocale() === 'ur' ? $productData->name_ur ?? '-' : $productData->name_en ?? '-' }}
                                                        </option>
                                                    @endforeach

                                                </select>

                                            </td>


                                            {{-- UNIT --}}
                                            <td class="measurement_unit">

                                                <input type="text" style="color: black;"
                                                    class="measurement_unit form-control measurement_unit_{{ $index }}"
                                                    value="{{ old("measurement_unit.$index", app()->getLocale() === 'ur' ? 'مرلہ' : 'Marla') }}"
                                                    placeholder="@lang('messages.unit')" readonly>

                                            </td>


                                            {{-- SIZE --}}
                                            <td class="quantity">

                                                <input type="text" name="size[]"
                                                    class="quantity form-control quantity_{{ $index }}"
                                                    value="{{ old("size.$index", $detail->size ?? ($detail->quantity ?? '')) }}"
                                                    placeholder="@lang('messages.qty')">

                                            </td>


                                            {{-- RATE --}}
                                            <td class="price">

                                                <input type="text" name="per_marla_rate[]"
                                                    class="price form-control price_{{ $index }}"
                                                    value="{{ old("per_marla_rate.$index", $detail->per_marla_rate ?? ($detail->price ?? '')) }}"
                                                    placeholder="@lang('messages.price')">

                                            </td>


                                            {{-- AMOUNT --}}
                                            <td class="amount">

                                                <input type="text" name="amount[]"
                                                    class="amount form-control amount_{{ $index }}"
                                                    value="{{ old("amount.$index", $detail->amount ?? '') }}"
                                                    placeholder="@lang('messages.amount')" readonly>

                                            </td>

                                        </tr>


                                        {{-- =====================================================
                                            ENGLISH DETAIL REMARKS
                                        ====================================================== --}}

                                        <tr class="remarks-row">

                                            <td colspan="5">

                                                <div class="row">

                                                    <div class="col-md-2">

                                                        <label class="form-label">
                                                            {{ __('messages.remarks') }}
                                                            {{ __('messages.english') }}
                                                        </label>

                                                    </div>

                                                    <div class="col-md-10">

                                                        <textarea name="detail_remarks_en[]" class="form-control detail_remarks_en_{{ $index }}"
                                                            placeholder="{{ __('messages.remarks') }} {{ __('messages.english') }}" rows="2">{{ old("detail_remarks_en.$index", $detail->detail_remarks_en ?? ($detail->remarks_en ?? '')) }}</textarea>

                                                    </div>

                                                </div>

                                            </td>

                                        </tr>


                                        {{-- =====================================================
                                            URDU DETAIL REMARKS
                                        ====================================================== --}}

                                        <tr class="remarks-row">

                                            <td colspan="5">

                                                <div class="row">

                                                    <div class="col-md-2">

                                                        <label class="form-label">
                                                            {{ __('messages.remarks') }}
                                                            {{ __('messages.urdu') }}
                                                        </label>

                                                    </div>

                                                    <div class="col-md-10">

                                                        <textarea name="detail_remarks_ur[]" class="form-control detail_remarks_ur_{{ $index }}"
                                                            placeholder="{{ __('messages.remarks') }} {{ __('messages.urdu') }}" rows="2" dir="rtl">{{ old("detail_remarks_ur.$index", $detail->detail_remarks_ur ?? ($detail->remarks_ur ?? '')) }}</textarea>

                                                    </div>

                                                </div>

                                            </td>

                                        </tr>
                                    @endforeach

                                </tbody>

                            </table>

                        </div>


                        <a href="javascript:void(0)" class="btn btn-dark additem">

                            @lang('messages.add-product-detail')

                        </a>

                    </div>

                </div>


                {{-- =========================================================
                    TOTALS
                ========================================================== --}}

                <div class="row justify-content-end">

                    <div class="col-md-6 mb-3">

                        <div class="form-group row">

                            <div class="col-md-3">

                                <label>
                                    @lang('messages.total_quantity')
                                </label>

                            </div>

                            <div class="col-md-9">

                                <input type="number" style="background-color: #e9ecef !important; color: black;"
                                    name="total_quantity" class="form-control total_quantity" id="total_quantity"
                                    value="{{ old('total_quantity', $purchaseMaster->total_quantity ?? '') }}" 
                                    readonly>

                            </div>

                        </div>

                    </div>


                    <div class="col-md-6 mb-3">

                        <div class="form-group row">

                            <div class="col-md-3">

                                <label>
                                    @lang('messages.gross_amount')
                                </label>

                            </div>

                            <div class="col-md-9">

                                <input type="number" style="background-color: #e9ecef !important; color: black;"
                                    name="gross_bill" class="form-control gross_bill" id="gross_bill"
                                    value="{{ old('gross_bill', $purchaseMaster->gross_bill ?? '') }}" readonly>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- =========================================================
                    BUTTONS
                ========================================================== --}}

                <div class="row">

                    <div class="d-flex justify-content-end gap-2">

                        <button type="submit" class="btn btn-primary">

                            @lang('messages.update')

                        </button>

                        <a href="{{ route('plot-purchase-invoice.index') }}" class="btn btn-dark">

                            @lang('messages.go-to-list')

                        </a>

                    </div>

                </div>

            </form>

        </div>
    </div>


    {{-- ================================================================
        TRANSLATIONS
    ================================================================= --}}

    <script>
        window.customTranslations = {

            pleaseSelect: @json(__('messages.select-detail-accounts')),

            noData: @json(__('messages.no-detail-account-found')),

            errorTitle: @json(__('messages.error-title')),

            errorText: @json(__('messages.control-head-fetch-failed')),

            loading: @json(__('messages.loading')),

            selectSubHead: @json(__('messages.select-sub-head')),

            selectSubSubHead: @json(__('messages.select-sub-sub-heads')),

            selectSubSubSubHead: @json(__('messages.select-sub-sub-sub-heads')),

            noSubHeads: @json(__('messages.no-sub-head-found')),

            noSubSubSubHeads: @json(__('messages.no-sub-sub-sub-head-found')),

            subHeaderrorTitle: @json(__('messages.subHeaderror-title')),

            subHeaderrorText: @json(__('messages.sub-head-fetch-failed')),

            errorTitle2: @json(__('messages.validation_error')),

            errorText2: @json(__('messages.po_and_received_qty_not_grater')),

            confirmButtonText: @json(__('messages.ok')),

            selectProjectFirst: @json(__('messages.select_project_first'))

        };


        /*
        |--------------------------------------------------------------------------
        | Dynamic Row Translations
        |--------------------------------------------------------------------------
        */

        window.purchaseTranslations = {

            selectProduct: @json(__('messages.select-product')),

            unit: @json(__('messages.unit')),

            qty: @json(__('messages.qty')),

            price: @json(__('messages.price')),

            amount: @json(__('messages.amount')),

            remarksEnglish: @json(__('messages.remarks') . ' ' . __('messages.english')),

            remarksUrdu: @json(__('messages.remarks') . ' ' . __('messages.urdu'))

        };
    </script>


    {{-- ================================================================
        ROUTES
    ================================================================= --}}

    <script>
        window.config = {

            routes: {

                getDetailAccounts: "{{ route('get.payable.detail.account.data.project') }}",

                getProductSizeDetail: "{{ route('plot-purchase.getProductSizeDetail', ['id' => ':id']) }}",

                getProjectItems: "{{ route('plot-purchase.getProjectProducts', ['projectId' => ':id']) }}"

            }

        };
    </script>


    {{-- ================================================================
        PROJECT ITEMS
    ================================================================= --}}

    <script>
        /*
                    |--------------------------------------------------------------------------
                    | Existing project products
                    |--------------------------------------------------------------------------
                    |
                    | Controller should send:
                    |
                    | 'projectItems' => [
                    |     product_id => product_name
                    | ]
                    |
                    */

        let projectItems = @json($projectItems ?? []);

        let existingProjectId =
            @json($purchaseMaster->project_id ?? null);


        /*
        |--------------------------------------------------------------------------
        | Load project items
        |--------------------------------------------------------------------------
        */

        function loadProjectItems(projectId, callback = null) {

            if (!projectId) {

                projectItems = {};

                if (callback) {
                    callback();
                }

                return;

            }


            let url =
                window.config.routes.getProjectItems
                .replace(':id', projectId);


            $.ajax({

                url: url,

                type: 'GET',

                success: function(response) {

                    if (response.status === 'success') {

                        projectItems =
                            response.data || {};

                    } else {

                        projectItems = {};

                    }


                    if (callback) {
                        callback();
                    }

                },

                error: function(xhr) {

                    console.log(
                        'PROJECT ITEMS ERROR:',
                        xhr.responseText
                    );

                    projectItems = {};

                    if (callback) {
                        callback();
                    }

                }

            });

        }


        /*
        |--------------------------------------------------------------------------
        | Calculate totals
        |--------------------------------------------------------------------------
        */

        function calculateInvoiceTotals() {
          
            let totalQuantity = 0;

            let totalAmount = 0;


            $('.item-main-row:visible').each(function() {

                let quantity =
                    parseFloat(
                        $(this).find('.quantity').val()
                    ) || 0;


                let amount =
                    parseFloat(
                        $(this).find('.amount').val()
                    ) || 0;


                totalQuantity += quantity;

                totalAmount += amount;

            });


            $('#total_quantity')
                .val(totalQuantity.toFixed(2));


            $('#gross_bill')
                .val(totalAmount.toFixed(2));

        }


        /*
        |--------------------------------------------------------------------------
        | Calculate row amount
        |--------------------------------------------------------------------------
        */

        function calculateRowAmount($row) {

            let quantity =
                parseFloat(
                    $row.find('.quantity').val()
                ) || 0;


            let price =
                parseFloat(
                    $row.find('.price').val()
                ) || 0;


            let amount =
                quantity * price;


            $row.find('.amount')
                .val(amount.toFixed(2));


            calculateInvoiceTotals();

        }


        /*
        |--------------------------------------------------------------------------
        | Quantity / Rate changed
        |--------------------------------------------------------------------------
        */

        $(document).on(
            'input',
            '.quantity, .price',
            function() {

                let $row =
                    $(this).closest('.item-main-row');

                calculateRowAmount($row);

            }
        );


        /*
        |--------------------------------------------------------------------------
        | Product selected
        |--------------------------------------------------------------------------
        */

        $(document).on('change', '.product_id', function() {

            let $row =
                $(this).closest('.item-main-row');


            let productId =
                $(this).val();


            if (!productId) {

                $row.find('.measurement_unit').val('');

                $row.find('.quantity').val('');

                $row.find('.price').val('');

                $row.find('.amount').val('');

                calculateInvoiceTotals();

                return;

            }


            let url =
                window.config.routes.getProductSizeDetail
                .replace(':id', productId);


            $.ajax({

                url: url,

                type: 'GET',

                headers: {

                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

                },

                success: function(response) {

                    if (
                        response.status === 'success' &&
                        response.data &&
                        response.data.original &&
                        response.data.original.data
                    ) {

                        let data =
                            response.data.original.data;


                        $row.find('.measurement_unit')
                            .val(data.unit || '');


                        $row.find('.quantity')
                            .val(data.total_marla || '');

                        // $row.find('.price')
                        //     .val(data.amount_in_pkr || '');

                        $row.find('.price').val(
                            parseFloat(data.total_amount) / parseFloat(data.total_marla)
                        );


                        $row.find('.amount')
                            .val(data.total_amount || '');


                        calculateInvoiceTotals();

                    }

                },

                error: function(xhr) {

                    console.log(
                        'PRODUCT DETAIL ERROR:',
                        xhr.responseText
                    );


                    Swal.fire({

                        icon: 'error',

                        title: 'Something went wrong'

                    });

                }

            });

        });


        /*
        |--------------------------------------------------------------------------
        | Add product detail
        |--------------------------------------------------------------------------
        */

        $('.additem').on('click', function() {

            let projectId =
                $('#project_id').val();


            if (!projectId) {

                Swal.fire({

                    icon: 'warning',

                    title: window.customTranslations.errorTitle,

                    text: window.customTranslations.selectProjectFirst

                });

                return;

            }


            let currentIndex =
                $('.item-main-row').length;


            /*
            |--------------------------------------------------------------------------
            | Product options
            |--------------------------------------------------------------------------
            */

            let productOptions =
                '<option value="">' +
                purchaseTranslations.selectProduct +
                '</option>';


            $.each(
                projectItems,
                function(id, name) {

                    productOptions +=
                        '<option value="' +
                        id +
                        '">' +
                        name +
                        '</option>';

                }
            );


            /*
            |--------------------------------------------------------------------------
            | Main row
            |--------------------------------------------------------------------------
            */

            let html =

                '<tr class="item-main-row">' +

                '<td class="delete-item-row" rowspan="3">' +

                '<ul class="table-controls">' +

                '<li>' +

                '<a href="javascript:void(0);" ' +
                'class="delete-item" ' +
                'title="Delete">' +

                '<svg xmlns="http://www.w3.org/2000/svg" ' +
                'width="24" ' +
                'height="24" ' +
                'viewBox="0 0 24 24" ' +
                'fill="none" ' +
                'stroke="currentColor" ' +
                'stroke-width="2" ' +
                'stroke-linecap="round" ' +
                'stroke-linejoin="round">' +

                '<circle cx="12" cy="12" r="10"></circle>' +

                '<line x1="15" y1="9" x2="9" y2="15"></line>' +

                '<line x1="9" y1="9" x2="15" y2="15"></line>' +

                '</svg>' +

                '</a>' +

                '</li>' +

                '</ul>' +

                '</td>' +


                '<td rowspan="3">' +

                '<input type="hidden" ' +
                'name="detail_id[]" ' +
                'class="detail_id" ' +
                'value="">' +

                '<input type="hidden" ' +
                'name="row_id[]" ' +
                'class="row_id" ' +
                'value="">' +

                '</td>' +


                '<td class="product_id">' +

                '<select name="product_id[]" ' +
                'class="product_id form-control form-select select2 product_' +
                currentIndex + '">' +

                productOptions +

                '</select>' +

                '</td>' +


                '<td class="measurement_unit">' +

                '<input type="text" ' +
                'style="color: black;" ' +
                'class="measurement_unit form-control measurement_unit_' +
                currentIndex + '" ' +
                'placeholder="' +
                purchaseTranslations.unit +
                '" readonly>' +

                '</td>' +


                '<td class="quantity">' +

                '<input type="text" ' +
                'name="size[]" ' +
                'class="quantity form-control quantity_' +
                currentIndex + '" ' +
                'placeholder="' +
                purchaseTranslations.qty +
                '">' +

                '</td>' +


                '<td class="price">' +

                '<input type="text" ' +
                'name="per_marla_rate[]" ' +
                'class="price form-control price_' +
                currentIndex + '" ' +
                'placeholder="' +
                purchaseTranslations.price +
                '">' +

                '</td>' +


                '<td class="amount">' +

                '<input type="text" ' +
                'name="amount[]" ' +
                'class="amount form-control amount_' +
                currentIndex + '" ' +
                'placeholder="' +
                purchaseTranslations.amount +
                '" readonly>' +

                '</td>' +

                '</tr>' +


                /*
                |--------------------------------------------------------------------------
                | English remarks
                |--------------------------------------------------------------------------
                */

                '<tr class="remarks-row">' +

                '<td colspan="5">' +

                '<div class="row">' +

                '<div class="col-md-2">' +

                '<label class="form-label">' +

                purchaseTranslations.remarksEnglish +

                '</label>' +

                '</div>' +

                '<div class="col-md-10">' +

                '<textarea name="detail_remarks_en[]" ' +
                'class="form-control detail_remarks_en_' +
                currentIndex + '" ' +
                'placeholder="' +
                purchaseTranslations.remarksEnglish +
                '" ' +
                'rows="2"></textarea>' +

                '</div>' +

                '</div>' +

                '</td>' +

                '</tr>' +


                /*
                |--------------------------------------------------------------------------
                | Urdu remarks
                |--------------------------------------------------------------------------
                */

                '<tr class="remarks-row">' +

                '<td colspan="5">' +

                '<div class="row">' +

                '<div class="col-md-2">' +

                '<label class="form-label">' +

                purchaseTranslations.remarksUrdu +

                '</label>' +

                '</div>' +

                '<div class="col-md-10">' +

                '<textarea name="detail_remarks_ur[]" ' +
                'class="form-control detail_remarks_ur_' +
                currentIndex + '" ' +
                'placeholder="' +
                purchaseTranslations.remarksUrdu +
                '" ' +
                'rows="2" dir="rtl"></textarea>' +

                '</div>' +

                '</div>' +

                '</td>' +

                '</tr>';


            $('.item-table tbody')
                .append(html);


            /*
            |--------------------------------------------------------------------------
            | Initialize Select2 only on new row
            |--------------------------------------------------------------------------
            */

            $('.product_' + currentIndex)
                .select2();


            calculateInvoiceTotals();

        });


        /*
        |--------------------------------------------------------------------------
        | Delete row
        |--------------------------------------------------------------------------
        */

        $(document).on('click', '.delete-item', function() {

            let $mainRow =
                $(this).closest('.item-main-row');


            /*
             * Delete the two remark rows immediately
             * following this main row.
             */

            $mainRow.nextUntil('.item-main-row')
                .remove();


            $mainRow.remove();


            calculateInvoiceTotals();

        });


        /*
        |--------------------------------------------------------------------------
        | Project changed
        |--------------------------------------------------------------------------
        */

        $('#project_id').on('change', function() {

            let projectId =
                $(this).val();


            if (!projectId) {

                $('.item-table tbody')
                    .empty();

                projectItems = {};

                return;

            }


            /*
             * Existing details belong to old project,
             * therefore clear them.
             */

            $('.item-table tbody')
                .empty();


            loadProjectItems(projectId);

        });


        /*
        |--------------------------------------------------------------------------
        | Document Ready
        |--------------------------------------------------------------------------
        */

        $(document).ready(function() {

            $('.select2').select2();


            /*
             * If projectItems were not prepared by controller,
             * fetch them.
             */

            if (
                existingProjectId &&
                Object.keys(projectItems).length === 0
            ) {

                loadProjectItems(existingProjectId);

            }



        });
    </script>


    <script src="{{ asset('js/plugins/sweetalert2/sweetalert2.all.js') }}"></script>

    <script src="{{ asset('js/plotPurchaseInvoice.js') }}"></script>
@endsection
