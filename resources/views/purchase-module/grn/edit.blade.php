@extends('layouts.backend')

@section('content')
    <div class="block block-rounded col-md-12">
        <div class="block-header block-header-default">
            <h3 class="block-title">@lang('messages.edit-grn')</h3>
        </div>
        <div class="block-content block-content-full">
            <form id="grn-form" action="{{ route('grn.update', $grnMaster->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="grn_no" class="form-label">@lang('messages.grn_no')</label>

                        <input class="form-control" value="{{ $grnMaster->id }}" disabled>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="purchase_order_no" class="form-label">@lang('messages.purchase_order_no')</label>

                        <input class="form-control" name="purchase_order_no" value="{{ $grnMaster->purchase_order_no }}"
                            readonly>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="date" class="form-label">@lang('messages.Date')</label>

                        <input type="date" class="form-control" name="date"
                            value="{{ old('date', isset($grnMaster->date) ? $grnMaster->date : now()->format('Y-m-d')) }}">

                        @error('date')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row">
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
                </div>

                <div class="row">
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

                    <div class="col-md-6 mb-3">
                        <label for="supplier_bill_no">@lang('messages.supplier-bill-no') </label>
                        <input type="text" class="form-control" id="supplier_bill_no" name="supplier_bill_no"
                            placeholder="@lang('messages.supplier-bill-no')"
                            value="{{ old('supplier_bill_no', $grnMaster->supplier_bill_no) }}">
                        @error('supplier_bill_no')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <input type="hidden" name="status" value="{{ $grnMaster->status }}">
                    @error('status')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="driver_name">@lang('messages.driver_name') </label>
                        <input type="text" class="form-control" id="driver_name" name="driver_name"
                            placeholder="@lang('messages.driver_name')" value="{{ old('driver_name', $grnMaster->driver_name) }}">
                        @error('driver_name')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="fare">@lang('messages.fare') </label>
                        <input type="text" class="form-control" id="fare" name="fare"
                            placeholder="@lang('messages.fare')" value="{{ old('fare', $grnMaster->fare) }}">
                        @error('fare')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="unloaded_by">@lang('messages.unloaded_by') </label>
                        <input type="text" class="form-control" id="unloaded_by" name="unloaded_by"
                            placeholder="@lang('messages.unloaded_by')" value="{{ old('unloaded_by', $grnMaster->unloaded_by) }}">
                        @error('unloaded_by')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="remarks">@lang('messages.remarks')</label>
                        <textarea type="text" class="form-control" id="remarks" name="remarks" placeholder="@lang('messages.remarks')"
                            autocomplete="off" value="{{ old('remarks') }}">{{ old('remarks', $grnMaster->remarks) }}</textarea>
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
                                            @lang('messages.po_quantity')</th>
                                        <th class="">
                                            @lang('messages.received_quantity')</th>
                                        <th class="">
                                            @lang('messages.balance')</th>
                                        <th class="" style="width: 20%;">
                                            @lang('messages.remarks')</th>

                                    </tr>
                                    <tr aria-hidden="true" class="mt-3 d-block table-row-hidden">
                                    </tr>
                                </thead>
                                <tbody>

                                    @if (!empty($grnDetails))
                                        @foreach ($grnDetails as $grnDetail)
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
                                                                {{ (old('product_id') ?? $grnDetail->product_id) == $productData->id ? 'selected' : '' }}>
                                                                {{ App::getLocale() === 'ur' ? $productData->name_ur ?? '-' : $productData->name_en ?? '-' }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <input type="hidden" name="product_id[]"
                                                        value="{{ $grnDetail->product_id }}">
                                                </td>
                                                <td class="measurement_unit">
                                                    <input id="measurement_unit" style="color: black; "
                                                        class = "measurement_unit form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}  measurement_unit_{{ $index }}"
                                                        placeholder="@lang('messages.unit')" readonly>
                                                </td>
                                                <td class="po_quantity">
                                                    <input type="text" style="color: black; "
                                                        value="{{ $grnDetail->po_quantity }}"
                                                        placeholder="@lang('messages.po_quantity')" id="po_quantity"
                                                        name="po_quantity[]"
                                                        class = "po_quantity form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} po_quantity_{{ $index }}"
                                                        readonly>
                                                </td>
                                                <td class="received_qty">
                                                    <input type="number" name="received_qty[]"
                                                        value="{{ $grnDetail->received_qty }}" step="any"
                                                        min="0" onwheel="this.blur()"
                                                        class="received_qty form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} received_qty_{{ $index }}"
                                                        placeholder="@lang('messages.received_quantity') ">
                                                </td>
                                                <td class="balance">
                                                    <input type="text" name="balance[]"
                                                        value="{{ $grnDetail->balance }}"
                                                        class="balance form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} balance_{{ $index }}"
                                                        placeholder="@lang('messages.balance') " readonly>
                                                </td>
                                                <td class="detail_remarks">
                                                    <textarea name="detail_remarks[]"
                                                        class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} detail_remarks_{{ $index }}"
                                                        placeholder="@lang('messages.remarks')" rows="2">{{ $grnDetail->detail_remarks }}</textarea>
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
                    <div class="col-md-6 mb-3">
                        <div class="form-group row">
                            <div class="col-md-3">
                                <label for="client-phone">@lang('messages.total_po_quantity')</label>
                            </div>
                            <div class="col-md-9">
                                <input type="number" id="total_po_quantity" style="color: black;"
                                    name="total_po_quantity"
                                    class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} total_po_quantity"
                                    id="total_po_quantity" placeholder="@lang('messages.gross_amount')" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-end">
                    <div class="col-md-6 mb-3">
                        <div class="form-group row">
                            <div class="col-md-3">
                                <label>@lang('messages.total_received_qty')</label>
                            </div>
                            <div class="col-md-9">
                                <input type="number" id="total_received_qty" style="color: black;"
                                    name="total_received_quantity"
                                    class="form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} total_received_qty"
                                    placeholder="@lang('messages.total_received_qty')" readonly>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="d-flex justify-content-end gap-2">
                        <button type="submit" class="btn btn-primary">@lang('messages.update')</button>
                        <a href="{{ route('grn.index') }}" class="btn btn-dark">@lang('messages.go-to-list')</a>
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
