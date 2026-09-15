@extends('layouts.backend')

@section('content')
    <div class="container">

        <div class="card">

            <div class="card-header">

                <h3 class="card-title">
                    @lang('messages.generate-booking-report')
                </h3>

            </div>

            <div class="card-body">

                <form action="{{ route('exective.reports.booking.report') }}" method="GET" target="_blank">

                    <div class="row">

                        {{-- =====================================================
                            PROJECT
                        ====================================================== --}}

                        <div class="col-md-6">

                            <label>
                                @lang('messages.projects')
                            </label>

                            <select name="project_id[]" id="project_id" multiple
                                class="form-control select2 custom-select2">

                                <option value="all">
                                    @lang('messages.all')
                                </option>

                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}">

                                        {{ app()->getLocale() == 'ur' ? $project->name_ur : $project->name_en }}

                                    </option>
                                @endforeach

                            </select>

                        </div>


                        {{-- =====================================================
                            PRODUCT
                        ====================================================== --}}

                        <div class="col-md-6">

                            <label>
                                @lang('messages.products')
                            </label>

                            <select name="product_id[]" id="product_id" multiple class="form-control select2 custom-select2"
                                disabled>

                                <option value="all">
                                    @lang('messages.all')
                                </option>

                            </select>

                        </div>

                    </div>


                    <br>


                    <div class="row">

                        {{-- =====================================================
                            PARTY
                        ====================================================== --}}

                        <div class="col-md-6">

                            <label>
                                Party
                            </label>

                            <select name="party_id[]" id="party_id" multiple class="form-control select2 custom-select2">

                                <option value="all">
                                    All
                                </option>

                                @foreach ($searchParties as $searchParty)
                                    <option value="{{ $searchParty->id }}"
                                        {{ collect(request('party_id'))->contains($searchParty->id) ? 'selected' : '' }}>
                                        {{ App::getLocale() === 'ur' ? $searchParty->name_ur ?? '-' : $searchParty->name_en ?? '-' }}
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; -
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $searchParty->cnic_no ?? 'N/A' }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        - {{ $searchParty->contact_number_1 ?? 'N/A' }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        -
                                        {{ App::getLocale() === 'ur' ? $searchParty->cast->title_ur ?? '-' : $searchParty->cast->title_en ?? '-' }}
                                    </option>
                                @endforeach

                            </select>

                        </div>


                        {{-- =====================================================
                            FROM DATE
                        ====================================================== --}}

                        <div class="col-md-3">

                            <label>
                                From Date
                            </label>

                            <input type="date" name="from_date" id="from_date" class="form-control">

                        </div>


                        {{-- =====================================================
                            TO DATE
                        ====================================================== --}}

                        <div class="col-md-3">

                            <label>
                                To Date
                            </label>

                            <input type="date" name="to_date" id="to_date" class="form-control">

                        </div>

                    </div>


                    <br>


                    {{-- =====================================================
                        BUTTONS
                    ====================================================== --}}

                    <button type="submit" class="btn btn-primary">

                        @lang('messages.generate_report')

                    </button>


                    <a href="{{ route('exective.reports.booking.report.filter') }}" class="btn btn-secondary">

                        @lang('messages.reset')

                    </a>

                </form>

            </div>

        </div>

    </div>


    {{-- =============================================================
        PRODUCT AJAX SCRIPT
    ============================================================= --}}

    <script>
        $(document).ready(function() {


            /*
            |--------------------------------------------------------------------------
            | PROJECT CHANGE
            |--------------------------------------------------------------------------
            */

            $('#project_id').on('change', function() {

                let projectIds = $(this).val();

                let productSelect = $('#product_id');


                console.log(
                    'Selected Projects:',
                    projectIds
                );


                /*
                |--------------------------------------------------------------------------
                | Clear Product Dropdown
                |--------------------------------------------------------------------------
                */

                productSelect.empty();

                productSelect.append(
                    '<option value="all">@lang('messages.all')</option>'
                );


                /*
                |--------------------------------------------------------------------------
                | No Project Selected
                |--------------------------------------------------------------------------
                */

                if (!projectIds || projectIds.length === 0) {

                    productSelect
                        .prop('disabled', true)
                        .val(null)
                        .trigger('change');

                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | If ALL selected
                |--------------------------------------------------------------------------
                */

                if (projectIds.includes('all')) {

                    console.log(
                        'All projects selected'
                    );

                    loadProducts(['all']);

                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | Load Selected Project Products
                |--------------------------------------------------------------------------
                */

                loadProducts(projectIds);

            });


            /*
            |--------------------------------------------------------------------------
            | LOAD PRODUCTS FUNCTION
            |--------------------------------------------------------------------------
            */

            function loadProducts(projectIds) {

                let productSelect = $('#product_id');


                productSelect
                    .prop('disabled', true)
                    .empty();


                productSelect.append(
                    '<option value="all">Loading products...</option>'
                );


                productSelect.trigger('change');


                $.ajax({

                    url: "{{ route('exective-reports.booking-payment.products') }}",

                    type: "GET",

                    data: {
                        project_id: projectIds
                    },


                    success: function(response) {

                        console.log(
                            'Products Response:',
                            response
                        );


                        /*
                        |--------------------------------------------------------------------------
                        | Clear Dropdown
                        |--------------------------------------------------------------------------
                        */

                        productSelect.empty();


                        /*
                        |--------------------------------------------------------------------------
                        | ALL PRODUCTS
                        |--------------------------------------------------------------------------
                        */

                        productSelect.append(
                            '<option value="all">@lang('messages.all')</option>'
                        );


                        /*
                        |--------------------------------------------------------------------------
                        | Check Response
                        |--------------------------------------------------------------------------
                        */

                        if (
                            response.products &&
                            response.products.length > 0
                        ) {


                            $.each(
                                response.products,
                                function(index, product) {


                                    productSelect.append(

                                        $('<option>', {

                                            value: product.id,

                                            text: "{{ app()->getLocale() }}" === 'ur' ?
                                                product.name_ur : product.name_en

                                        })

                                    );


                                }
                            );


                            /*
                            |--------------------------------------------------------------------------
                            | Enable Product Select
                            |--------------------------------------------------------------------------
                            */

                            productSelect
                                .prop('disabled', false)
                                .trigger('change');


                        } else {


                            /*
                            |--------------------------------------------------------------------------
                            | No Products
                            |--------------------------------------------------------------------------
                            */

                            productSelect.empty();


                            productSelect.append(

                                '<option value="all">' +
                                'No products found' +
                                '</option>'

                            );


                            productSelect
                                .prop('disabled', true)
                                .trigger('change');

                        }

                    },


                    error: function(xhr, status, error) {

                        console.error(
                            'Product AJAX Error:',
                            xhr.responseText
                        );


                        productSelect.empty();


                        productSelect.append(

                            '<option value="all">' +
                            'Unable to load products' +
                            '</option>'

                        );


                        productSelect
                            .prop('disabled', true)
                            .trigger('change');

                    }

                });

            }


            /*
            |--------------------------------------------------------------------------
            | DATE VALIDATION
            |--------------------------------------------------------------------------
            */

            $('#from_date, #to_date').on('change', function() {

                let fromDate = $('#from_date').val();

                let toDate = $('#to_date').val();


                if (
                    fromDate &&
                    toDate &&
                    fromDate > toDate
                ) {

                    alert(
                        'From Date cannot be greater than To Date.'
                    );

                    $('#to_date').val('');

                }

            });

        });
    </script>
@endsection
