@extends('layouts.backend')

@section('content')
    <div class="block block-rounded col-md-12">
        <div class="block-header block-header-default">
            <h3 class="block-title">@lang('messages.jv')</h3>
        </div>
        <div class="block-content block-content-full">
            <form id="jvForm" action="{{ !empty($jv) ? route('jv-voucher.update') : route('jv-voucher.store') }}"
                method="POST" class="row g-3">
                @csrf
                <input type="hidden" name="id" id="id" value="{{ $maxid }}" />
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-lg-6 mt-4">
                            <label for="">
                                <h4>@lang('messages.voucher_no'){{ @$maxid }}
                                    {{ @$currentid }}</h4>
                            </label>

                        </div>

                        <div class="col-md-6">
                            <label class="form-label">@lang('messages.Date')</label>
                            <input type="date" name="voucher_date" value="{{ $jv->voucher_date ?? date('Y-m-d') }}"
                                class="form-control form-control-sm" required>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <label class="form-label">@lang('messages.description')</label>
                    <textarea type="text" name="description" class="form-control form-control-sm">{{ $jv->description ?? '' }}</textarea>
                </div>
                {{-- </div> --}}


                <div class="tab-content" id="pills-tabContent">
                    <div class="invoice-detail-items" style="padding: 0px 0px 0px 0px;">
                        <div class="table-responsive">
                            <table class="table item-table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th style="width: 30% ! important;">@lang('messages.debit_account')</th>
                                        <th style="width: 30% ! important;">@lang('messages.credit_account')</th>
                                        <th style="width: 20% ! important;">@lang('messages.debit')</th>
                                        <th style="width: 20% ! important;">@lang('messages.credit')</th>
                                    </tr>
                                    <tr aria-hidden="true" class="mt-3 d-block table-row-hidden">
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>

                        <a href="javascript:void(0);" class="btn btn-dark additem mt-3" id="add-item">@lang('messages.add_details')</a>

                    </div>

                    <div class="col-xl-6 invoice-address-client invoice-detail-total mt-3" style="float:right">
                        <div class="invoice-address-client-fields">
                            <div class="form-group row">
                                <label for="total_debit"
                                    class="col-sm-4 col-form-label col-form-label-sm ">@lang('messages.total_debit')
                                </label>
                                <div class="col-sm-8">
                                    <input type="text" id="total_debit"
                                        class="form-control form-control-sm gross-amount " name="total_debit"
                                        style="color: black" placeholder="@lang('messages.total_debit')" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="total_credit"
                                    class="col-sm-4 col-form-label col-form-label-sm ">@lang('messages.total_credit')
                                </label>
                                <div class="col-sm-8">
                                    <input type="text" id="total_credit"
                                        class="form-control form-control-sm total_credit " name="total_credit"
                                        style="color: black" id="total_credit" placeholder="@lang('messages.total_credit')" readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- <div class="invoice-detail-terms">
                    <div class="row"> --}}
                        <div class="col-xl-12 ">
                            <a href="{{ route('jv-voucher.index') }}" style="float: right;"
                                class="btn btn-dark rounded bs-popover ml-2 mt-5  mb-4">@lang('messages.cancel')</a>
                            <button type="submit" style="float: right"
                                class="btn btn-success  rounded bs-popover me-1 mt-5 mb-4 mr-5" data-bs-container="body"
                                data-bs-placement="right" data-bs-content="Tooltip on right">
                                {{ isset($jv) ? __('messages.update') : __('messages.save') }}
                            </button>
                        </div>
                    {{-- </div>

                </div> --}}
            </form>
        </div>
    </div>
    <script>
        document.getElementById('jvForm').addEventListener('submit', function(e) {

            const debitAmount = parseFloat(document.getElementById('total_debit').value) || 0;
            const creditAmount = parseFloat(document.getElementById('total_credit').value) || 0;

            if (debitAmount !== creditAmount) {
                e.preventDefault(); // stop form only if invalid

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
                '<td hidden><input type="text" name="row_id[]" class="row_id" value="' + currentIndex +
                '" hidden></td>' +
                '<td class="description"><select name="debit_detail_account_id[]" id="debit_account" class="form-control form-select @error('debit_detail_account_id') is-invalid @enderror select2"> <option value="">@lang('messages.select_debit')</option> @foreach ($detailAccounts as $detailAccount) <option value="{{ $detailAccount->id }}" {{ old('debit_detail_account_id') == $detailAccount->id ? 'selected' : '' }}> {{ App::getLocale() === 'ur' ? $detailAccount->name_ur ?? '-' : $detailAccount->name_en ?? '-' }} </option> @endforeach </select></td>' +
                '<td class="description"><select name="credit_detail_account_id[]" id="credit_account" class="form-control form-select @error('credit_detail_account_id') is-invalid @enderror select2"> <option value="">@lang('messages.select_credit')</option> @foreach ($detailAccounts as $detailAccount) <option value="{{ $detailAccount->id }}" {{ old('credit_detail_account_id') == $detailAccount->id ? 'selected' : '' }}> {{ App::getLocale() === 'ur' ? $detailAccount->name_ur ?? '-' : $detailAccount->name_en ?? '-' }} </option> @endforeach </select></td>' +

                '<td class="text-right qty"> <input id="debit" type="number" name="debit[]"  placeholder="@lang('messages.debit')" class="form-control form-control-sm debit"></td>' +
                '<td class="text-right qty"> <input id="credit" type="number" name="credit[]" placeholder="@lang('messages.credit') " class="form-control form-control-sm credit"></td>' +
                '</td>' +

                '<div class="form-check form-check-primary form-check-inline me-0 mb-0">' +
                '</div>' +
                '</div>' +
                '</td>' +
                '</tr>' +
                '<tr>' +

                '<td>' +
                '</td>' +

                '<td class="text-right qty"> <textarea id="detail_description" type="text" name="detail_description_en[]" placeholder="@lang('messages.description_en') " class="form-control form-control-sm detail_description_en"></textarea></td>' +
                '<td class="text-right qty"> <textarea id="detail_description" type="text" name="detail_description_ur[]" placeholder="@lang('messages.description_ur') " class="form-control form-control-sm detail_description_ur"></textarea></td>' +
                '</tr>';

            $(".item-table tbody").append($html);
            deleteItemRow();
            $('.select2').select2();
            $(document).on('click', 'body *', function() {
                $('.debit').on("input", function() {
                    doAmountTotal();
                });

                $('.delete-item').on("click", function() {
                    doAmountTotal();
                });

                function doAmountTotal() {
                    $('#total_debit').text("");
                    var totalAmount = 0;
                    $(".debit").each(function() {
                        if (!isNaN(this.value) && this.value.length != 0) {
                            totalAmount += parseFloat(this.value);
                        }
                    });
                    $('#total_debit').val(totalAmount.toFixed(2));
                }
            });

            $(document).on('click', 'body *', function() {
                $('.credit').on("input", function() {
                    doAmountTotal();
                });

                $('.delete-item').on("click", function() {
                    doAmountTotal();
                });

                function doAmountTotal() {
                    $('#total_credit').text("");
                    console.log('in do amount total');
                    var totalAmount = 0;
                    $(".credit").each(function() {
                        if (!isNaN(this.value) && this.value.length != 0) {
                            totalAmount += parseFloat(this.value);
                        }
                    });
                    $('#total_credit').val(totalAmount.toFixed(2));
                }
            });

        })

        deleteItemRow();
        selectableDropdown(document.querySelectorAll('.invoice-select .dropdown-item'));
        selectableDropdown(document.querySelectorAll('.invoice-tax-select .dropdown-item'), getTaxValue);
        selectableDropdown(document.querySelectorAll('.invoice-discount-select .dropdown-item'), getDiscountValue);

        var f2 = flatpickr(document.getElementById('due'), {
            defaultDate: currentDate.setDate(currentDate.getDate() + 5),
        });

        $(document).ready(function() {
            $('.select2').select2();
        });


        function deleteItemRow() {
            let deleteItem = document.querySelectorAll('.delete-item');
            for (var i = 0; i < deleteItem.length; i++) {
                deleteItem[i].addEventListener('click', function() {

                    // Get the first row (debit row)
                    let firstRow = this.closest('tr');

                    // Get the second row (description/credit row)
                    let secondRow = firstRow.nextElementSibling;

                    // Remove both rows
                    if (secondRow) {
                        secondRow.remove();
                    }

                    firstRow.remove();
                });
            }
        }
    </script>
    <script src="{{ asset('js/sweetalert2.min.js') }}"></script>

    <script src="{{ asset('js/jvVoucher.js') }}"></script>
@endsection
