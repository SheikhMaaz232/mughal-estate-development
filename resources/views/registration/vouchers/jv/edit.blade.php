@extends('layouts.backend')

@section('content')
    <div class="block block-rounded col-md-12">

        <div class="block-header block-header-default">
            <h3 class="block-title">@lang('messages.jv')</h3>
        </div>

        <div class="block-content block-content-full">

            <form id="jvForm" action="{{ route('jv-voucher.update', $journalVoucher->id) }}" method="POST" class="row g-3">

                @csrf
                @method('PUT')

                <input type="hidden" name="id" id="id" value="{{ $journalVoucher->id }}">

                {{-- Voucher Information --}}
                <div class="col-md-12">
                    <div class="row">

                        <div class="col-lg-6 mt-4">
                            <label>
                                <h4>
                                    @lang('messages.voucher_no')
                                    {{ @$maxid }}
                                    {{ @$journalVoucher->id }}
                                </h4>
                            </label>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                @lang('messages.Date')
                            </label>

                            <input type="date" name="voucher_date"
                                value="{{ $journalVoucher->voucher_date ?? date('Y-m-d') }}"
                                class="form-control form-control-sm" required>
                        </div>

                    </div>
                </div>

                {{-- Voucher Description --}}
                <div class="col-md-12">
                    <label class="form-label">
                        @lang('messages.description')
                    </label>

                    <textarea name="description" class="form-control form-control-sm">{{ $journalVoucher->description ?? '' }}</textarea>
                </div>


                {{-- JV Details --}}
                <div class="tab-content" id="pills-tabContent">

                    <div class="invoice-detail-items" style="padding:0;">

                        <div class="table-responsive">

                            <table class="table item-table">

                                <thead>
                                    <tr>
                                        <th></th>

                                        <th style="width:30% !important;">
                                            @lang('messages.debit_account')
                                        </th>

                                        <th style="width:30% !important;">
                                            @lang('messages.credit_account')
                                        </th>

                                        <th style="width:20% !important;">
                                            @lang('messages.debit')
                                        </th>

                                        <th style="width:20% !important;">
                                            @lang('messages.credit')
                                        </th>
                                    </tr>

                                    <tr aria-hidden="true" class="mt-3 d-block table-row-hidden">
                                    </tr>
                                </thead>


                                <tbody>

                                    @foreach ($journalVoucherDetails as $detail)
                                        @php

                                            /*
                                             * Only find the selected accounts from the
                                             * already available collection.
                                             *
                                             * We DO NOT print the complete collection
                                             * inside the select.
                                             */

                                            $debitAccount = $detailAccounts->firstWhere(
                                                'id',
                                                $detail->debit_detail_account_id,
                                            );

                                            $creditAccount = $detailAccounts->firstWhere(
                                                'id',
                                                $detail->credit_detail_account_id,
                                            );

                                            $debitOld = old(
                                                'debit_detail_account_id.' . $loop->index,
                                                $detail->debit_detail_account_id,
                                            );

                                            $creditOld = old(
                                                'credit_detail_account_id.' . $loop->index,
                                                $detail->credit_detail_account_id,
                                            );

                                            $debitSelectedAccount = $detailAccounts->firstWhere('id', $debitOld);

                                            $creditSelectedAccount = $detailAccounts->firstWhere('id', $creditOld);

                                            $index = $loop->index + 1;

                                        @endphp


                                        {{-- Main Detail Row --}}
                                        <tr class="jv-detail-row">

                                            {{-- Delete --}}
                                            <td class="delete-item-row">

                                                <ul class="table-controls">
                                                    <li>

                                                        <a href="javascript:void(0);" class="delete-item"
                                                            data-toggle="tooltip" data-placement="top" title=""
                                                            data-original-title="Delete">

                                                            <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                height="24" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="feather feather-x-circle">

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


                                            {{-- Row ID --}}
                                            <td hidden>

                                                <input type="hidden" name="row_id[]" class="row_id"
                                                    value="{{ $index }}">

                                            </td>


                                            {{-- Debit Account --}}
                                            <td>

                                                <select name="debit_detail_account_id[]"
                                                    class="form-control form-select debit-account select2"
                                                    data-selected-id="{{ $debitSelectedAccount?->id }}">

                                                    <option value="">
                                                        @lang('messages.select_debit')
                                                    </option>

                                                    @if ($debitSelectedAccount)
                                                        <option value="{{ $debitSelectedAccount->id }}" selected>

                                                            {{ App::getLocale() === 'ur'
                                                                ? $debitSelectedAccount->name_ur ?? ($debitSelectedAccount->name_en ?? '-')
                                                                : $debitSelectedAccount->name_en ?? ($debitSelectedAccount->name_ur ?? '-') }}

                                                        </option>
                                                    @endif

                                                </select>

                                            </td>


                                            {{-- Credit Account --}}
                                            <td>

                                                <select name="credit_detail_account_id[]"
                                                    class="form-control form-select credit-account select2"
                                                    data-selected-id="{{ $creditSelectedAccount?->id }}">

                                                    <option value="">
                                                        @lang('messages.select_credit')
                                                    </option>

                                                    @if ($creditSelectedAccount)
                                                        <option value="{{ $creditSelectedAccount->id }}" selected>

                                                            {{ App::getLocale() === 'ur'
                                                                ? $creditSelectedAccount->name_ur ?? ($creditSelectedAccount->name_en ?? '-')
                                                                : $creditSelectedAccount->name_en ?? ($creditSelectedAccount->name_ur ?? '-') }}

                                                        </option>
                                                    @endif

                                                </select>

                                            </td>


                                            {{-- Debit --}}
                                            <td>

                                                <input type="number" name="debit[]"
                                                    value="{{ old('debit.' . $loop->index, $detail->debit) }}"
                                                    step="0.01" min="0" placeholder="@lang('messages.debit')"
                                                    class="form-control form-control-sm debit">

                                            </td>


                                            {{-- Credit --}}
                                            <td>

                                                <input type="number" name="credit[]"
                                                    value="{{ old('credit.' . $loop->index, $detail->credit) }}"
                                                    step="0.01" min="0" placeholder="@lang('messages.credit')"
                                                    class="form-control form-control-sm credit">

                                            </td>

                                        </tr>


                                        {{-- Description Row --}}
                                        <tr class="jv-description-row">

                                            <td></td>

                                            <td>

                                                <textarea name="detail_description_en[]" placeholder="@lang('messages.description_en')"
                                                    class="form-control form-control-sm detail_description_en">{{ old('detail_description_en.' . $loop->index, $detail->detail_description_en) }}</textarea>

                                            </td>

                                            <td>

                                                <textarea name="detail_description_ur[]" placeholder="@lang('messages.description_ur')"
                                                    class="form-control form-control-sm detail_description_ur">{{ old('detail_description_ur.' . $loop->index, $detail->detail_description_ur) }}</textarea>

                                            </td>

                                            <td></td>

                                            <td></td>

                                        </tr>
                                    @endforeach

                                </tbody>

                            </table>

                        </div>


                        {{-- Add Detail --}}
                        <a href="javascript:void(0);" class="btn btn-dark additem mt-3" id="add-item">

                            @lang('messages.add_details')

                        </a>

                    </div>


                    {{-- Totals --}}
                    <div class="col-xl-6 invoice-address-client invoice-detail-total mt-3" style="float:right;">

                        <div class="invoice-address-client-fields">

                            {{-- Total Debit --}}
                            <div class="form-group row">

                                <label for="total_debit" class="col-sm-4 col-form-label col-form-label-sm">

                                    @lang('messages.total_debit')

                                </label>

                                <div class="col-sm-8">

                                    <input type="text" id="total_debit"
                                        value="{{ $journalVoucher->total_debit ?? '0.00' }}"
                                        class="form-control form-control-sm gross-amount" name="total_debit"
                                        style="color:black" placeholder="@lang('messages.total_debit')" readonly>

                                </div>

                            </div>


                            {{-- Total Credit --}}
                            <div class="form-group row">

                                <label for="total_credit" class="col-sm-4 col-form-label col-form-label-sm">

                                    @lang('messages.total_credit')

                                </label>

                                <div class="col-sm-8">

                                    <input type="text" id="total_credit"
                                        value="{{ $journalVoucher->total_credit ?? '0.00' }}"
                                        class="form-control form-control-sm total_credit" name="total_credit"
                                        style="color:black" placeholder="@lang('messages.total_credit')" readonly>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Buttons --}}
                <div class="col-xl-12">

                    <a href="{{ route('jv-voucher.index') }}" style="float:right;"
                        class="btn btn-dark rounded bs-popover ml-2 mt-5 mb-4">

                        @lang('messages.go-to-list')

                    </a>


                    <button type="submit" style="float:right"
                        class="btn btn-success rounded bs-popover me-1 mt-5 mb-4 mr-5">

                        @lang('messages.update')

                    </button>

                </div>

            </form>

        </div>

    </div>


    <script src="{{ asset('js/sweetalert2.min.js') }}"></script>


    <script>
        /*
            |--------------------------------------------------------------------------
            | Select2 Detail Account
            |--------------------------------------------------------------------------
            */

        function initializeDetailAccountSelect2(element) {

            $(element).select2({

                theme: 'bootstrap-5',

                placeholder: "{{ __('messages.select-an-option') }}",

                allowClear: true,

                minimumInputLength: 1,

                ajax: {

                    url: "{{ route('clients.select2') }}",

                    dataType: 'json',

                    delay: 250,

                    data: function(params) {

                        return {

                            search: params.term || '',

                            page: params.page || 1

                        };

                    },

                    processResults: function(data, params) {

                        params.page = params.page || 1;

                        return {

                            results: data.results,

                            pagination: {

                                more: data.pagination.more

                            }

                        };

                    },

                    cache: true

                }

            });

        }


        /*
        |--------------------------------------------------------------------------
        | Initialize Existing Debit/Credit Accounts
        |--------------------------------------------------------------------------
        */

        $(document).ready(function() {

            $('.debit-account').each(function() {

                initializeDetailAccountSelect2(this);

            });


            $('.credit-account').each(function() {

                initializeDetailAccountSelect2(this);

            });


            calculateTotals();

        });


        /*
        |--------------------------------------------------------------------------
        | Add New JV Detail
        |--------------------------------------------------------------------------
        */

        $(document).on('click', '#add-item', function(e) {

            e.preventDefault();

            let tbody = $('.item-table tbody');

            let rowIndex = tbody.find('.jv-detail-row').length + 1;


            let html = `

                <tr class="jv-detail-row">

                    <td class="delete-item-row">

                        <ul class="table-controls">

                            <li>

                                <a href="javascript:void(0);"
                                    class="delete-item"
                                    data-toggle="tooltip"
                                    data-placement="top"
                                    title="Delete">

                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        width="24"
                                        height="24"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="feather feather-x-circle">

                                        <circle cx="12"
                                            cy="12"
                                            r="10">
                                        </circle>

                                        <line x1="15"
                                            y1="9"
                                            x2="9"
                                            y2="15">
                                        </line>

                                        <line x1="9"
                                            y1="9"
                                            x2="15"
                                            y2="15">
                                        </line>

                                    </svg>

                                </a>

                            </li>

                        </ul>

                    </td>


                    <td hidden>

                        <input type="hidden"
                            name="row_id[]"
                            class="row_id"
                            value="${rowIndex}">

                    </td>


                    <td>

                        <select name="debit_detail_account_id[]"
                            class="form-control form-select debit-account select2">

                            <option value="">
                                @lang('messages.select_debit')
                            </option>

                        </select>

                    </td>


                    <td>

                        <select name="credit_detail_account_id[]"
                            class="form-control form-select credit-account select2">

                            <option value="">
                                @lang('messages.select_credit')
                            </option>

                        </select>

                    </td>


                    <td>

                        <input type="number"
                            name="debit[]"
                            step="0.01"
                            min="0"
                            placeholder="@lang('messages.debit')"
                            class="form-control form-control-sm debit">

                    </td>


                    <td>

                        <input type="number"
                            name="credit[]"
                            step="0.01"
                            min="0"
                            placeholder="@lang('messages.credit')"
                            class="form-control form-control-sm credit">

                    </td>

                </tr>


                <tr class="jv-description-row">

                    <td></td>

                    <td>

                        <textarea name="detail_description_en[]"
                            placeholder="@lang('messages.description_en')"
                            class="form-control form-control-sm detail_description_en"></textarea>

                    </td>

                    <td>

                        <textarea name="detail_description_ur[]"
                            placeholder="@lang('messages.description_ur')"
                            class="form-control form-control-sm detail_description_ur"></textarea>

                    </td>

                    <td></td>

                    <td></td>

                </tr>

            `;


            tbody.append(html);


            /*
            |--------------------------------------------------------------------------
            | Get Newly Added Selects
            |--------------------------------------------------------------------------
            */

            let newDebitSelect = tbody
                .find('.jv-detail-row')
                .last()
                .find('.debit-account');


            let newCreditSelect = tbody
                .find('.jv-detail-row')
                .last()
                .find('.credit-account');


            /*
            |--------------------------------------------------------------------------
            | Initialize AJAX Select2
            |--------------------------------------------------------------------------
            */

            initializeDetailAccountSelect2(newDebitSelect);

            initializeDetailAccountSelect2(newCreditSelect);


            calculateTotals();

        });


        /*
        |--------------------------------------------------------------------------
        | Delete JV Detail
        |--------------------------------------------------------------------------
        */

        $(document).on('click', '.delete-item', function(e) {

            e.preventDefault();

            let detailRow = $(this).closest('.jv-detail-row');

            let descriptionRow = detailRow.next('.jv-description-row');


            /*
            | Remove both rows
            */

            detailRow.remove();

            if (descriptionRow.length) {

                descriptionRow.remove();

            }


            calculateTotals();

        });


        /*
        |--------------------------------------------------------------------------
        | Calculate Total Debit / Credit
        |--------------------------------------------------------------------------
        */

        function calculateTotals() {

            let totalDebit = 0;

            let totalCredit = 0;


            $('.debit').each(function() {

                let value = parseFloat($(this).val());

                if (!isNaN(value)) {

                    totalDebit += value;

                }

            });


            $('.credit').each(function() {

                let value = parseFloat($(this).val());

                if (!isNaN(value)) {

                    totalCredit += value;

                }

            });


            $('#total_debit').val(totalDebit.toFixed(2));

            $('#total_credit').val(totalCredit.toFixed(2));

        }


        /*
        |--------------------------------------------------------------------------
        | Debit Input
        |--------------------------------------------------------------------------
        */

        $(document).on('input', '.debit', function() {

            calculateTotals();

        });


        /*
        |--------------------------------------------------------------------------
        | Credit Input
        |--------------------------------------------------------------------------
        */

        $(document).on('input', '.credit', function() {

            calculateTotals();

        });


        /*
        |--------------------------------------------------------------------------
        | Form Submit Validation
        |--------------------------------------------------------------------------
        */

        $('#jvForm').on('submit', function(e) {

            calculateTotals();


            let debitAmount =
                parseFloat($('#total_debit').val()) || 0;


            let creditAmount =
                parseFloat($('#total_credit').val()) || 0;


            /*
            | Floating point safe comparison
            */

            if (Math.abs(debitAmount - creditAmount) > 0.01) {

                e.preventDefault();


                Swal.fire({

                    icon: 'error',

                    title: "@lang('messages.error')",

                    text: "@lang('messages.debit_credit_equal')",

                    confirmButtonText: "@lang('messages.ok')"

                });


                return false;

            }

        });
    </script>


    <script src="{{ asset('js/jvVoucher.js') }}"></script>
@endsection
