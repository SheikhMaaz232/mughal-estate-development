@extends('layouts.backend')

@section('content')
    <div class="block block-rounded col-md-12">
        <div class="block-header block-header-default">
            <h3 class="block-title">@lang('messages.edit-bpv')</h3>
        </div>
        <div class="block-content block-content-full">
            <form action="{{ route('bank-receipt-voucher.update', $bankReceiptVoucher->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label class="form-label">@lang('messages.Date')</label>
                        <input type="date" class="form-control" name="date"
                            value="{{ $bankReceiptVoucher->date ?? now()->format('Y-m-d') }}">
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
                            <option value="">@lang('messages.select-project')</option>
                            @foreach ($projects as $project)
                                <option value="{{ $project->id }}"
                                    {{ (old('project_id') ?? $bankReceiptVoucher->project_id) == $project->id ? 'selected' : '' }}>
                                    {{ App::getLocale() === 'ur' ? $project->name_ur ?? '-' : $project->name_en ?? '-' }}
                                </option>
                            @endforeach
                        </select>
                        @error('project_id')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>


                    <div class="col-md-6 mb-3">
                        <label for="detail_account_id">@lang('messages.detail_account')</label>
                        <select name="detail_account_id" id="detail_account_id"
                            class="form-control form-select @error('detail_account_id') is-invalid @enderror select2">
                            <option value="">Select</option>
                            @foreach ($coaReceivables as $coaReceivable)
                                <option value="{{ $coaReceivable->id }}"
                                    {{ (old('detail_account_id') ?? $bankReceiptVoucher->detail_account_id) == $coaReceivable->id ? 'selected' : '' }}>
                                    {{ App::getLocale() === 'ur' ? $coaReceivable->name_ur ?? '-' : $coaReceivable->name_en ?? '-' }}
                                </option>
                            @endforeach
                        </select>
                        @error('detail_account_id')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="bank_id">@lang('messages.banks')</label>
                        <select name="bank_id" id="bank_id"
                            class="form-control form-select @error('bank_id') is-invalid @enderror select2">
                            <option value="">@lang('messages.select-bank')</option>
                            @foreach ($coaBanks as $coaBank)
                                <option value="{{ $coaBank->id }}"
                                    {{ (old('bank_id') ?? $bankReceiptVoucher->bank_id) == $coaBank->id ? 'selected' : '' }}>
                                    {{ App::getLocale() === 'ur' ? $coaBank->name_ur ?? '-' : $coaBank->name_en ?? '-' }}
                                </option>
                            @endforeach
                        </select>
                        @error('bank_id')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>


                    <div class="col-md-6 mb-3">
                        <label for="total_amount">@lang('messages.total_amount') </label>
                        <input type="text" class="form-control" id="total_amount" name="total_amount" step="any"
                            min="0" onwheel="this.blur()"
                            value="{{ old('total_amount', $bankReceiptVoucher->total_amount ?? '') }}"
                            placeholder="@lang('messages.total_amount')">
                        @error('total_amount')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="description_en">@lang('messages.description') @lang('messages.english')</label>
                        <textarea class="form-control" id="description_en" name="description_en" placeholder="......" rows="3">{{ old('description_en', $bankReceiptVoucher->description_en ?? '') }}</textarea>
                        @error('description_en')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="description_ur">@lang('messages.description') @lang('messages.urdu')</label>
                        <textarea class="form-control" id="description_ur" name="description_ur" placeholder="......" rows="3">{{ old('description_ur', $bankReceiptVoucher->description_ur ?? '') }}</textarea>
                        @error('description_ur')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="transaction_type">@lang('messages.receiving_purpose')</label>

                        <select name="transaction_type" id="transaction_type"
                            class="form-control form-select @error('transaction_type') is-invalid @enderror">

                            <option value="">@lang('messages.select-purpose')</option>

                            <option value="booking_payment"
                                {{ old('transaction_type', $bankReceiptVoucher->transaction_type ?? '') == 'booking_payment' ? 'selected' : '' }}>
                                @lang('messages.booking_payment')
                            </option>

                            <option value="operating_charges"
                                {{ old('transaction_type', $bankReceiptVoucher->transaction_type ?? '') == 'operating_charges' ? 'selected' : '' }}>
                                @lang('messages.operating_charges')
                            </option>

                            <option value="transfer_charges"
                                {{ old('transaction_type', $bankReceiptVoucher->transaction_type ?? '') == 'transfer_charges' ? 'selected' : '' }}>
                                @lang('messages.transfer_charges')
                            </option>

                            <option value="possession_fees"
                                {{ old('transaction_type', $bankReceiptVoucher->transaction_type ?? '') == 'possession_fees' ? 'selected' : '' }}>
                                @lang('messages.possession_fees')
                            </option>

                            <option value="development_charges"
                                {{ old('transaction_type', $bankReceiptVoucher->transaction_type ?? '') == 'development_charges' ? 'selected' : '' }}>
                                @lang('messages.development_charges')
                            </option>

                            <option value="proceeding_fees"
                                {{ old('transaction_type', $bankReceiptVoucher->transaction_type ?? '') == 'proceeding_fees' ? 'selected' : '' }}>
                                @lang('messages.proceeding_fees')
                            </option>

                            <option value="gst"
                                {{ old('transaction_type', $bankReceiptVoucher->transaction_type ?? '') == 'gst' ? 'selected' : '' }}>
                                GST
                            </option>

                            <option value="registry_fees"
                                {{ old('transaction_type', $bankReceiptVoucher->transaction_type ?? '') == 'registry_fees' ? 'selected' : '' }}>
                                @lang('messages.registry_fees')
                            </option>

                            <option value="other"
                                {{ old('transaction_type', $bankReceiptVoucher->transaction_type ?? '') == 'other' ? 'selected' : '' }}>
                                @lang('messages.others')
                            </option>

                        </select>

                        @error('transaction_type')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">

                        <div class="form-group mb-3">

                            <label for="image">@lang('messages.image')</label><br>

                            <!-- File Upload Input -->
                            <input type="file" name="attachment" id="attachment" class="form-control"
                                onchange="previewImage(this)">

                            <!-- Image Preview (Shows avatar if no image is selected) -->
                            <div id="imagePreview" class="mt-2">
                                {{--  <p class="mb-1">@lang('messages.image-preview')</p>  --}}
                                <img id="previewImg"
                                    src="{{ isset($bankReceiptVoucher) && $bankReceiptVoucher->attachment ? asset('storage/' . $bankReceiptVoucher->attachment) : asset('images/No-Image-Placeholder.svg.png') }}"
                                    alt="" class="img-thumbnail" style="max-height: 200px;">
                            </div>

                            @error('attachment')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                </div>

                <div class="row">
                    <div class="d-flex justify-content-end gap-2">
                        <button type="submit" class="btn btn-primary">@lang('messages.update')</button>
                        <a href="{{ route('bank-receipt-voucher.index') }}" class="btn btn-dark">@lang('messages.go-to-list')</a>
                    </div>
                </div>

            </form>
        </div>
    </div>
    <script>
        window.customTranslations = {
            loading: "{{ __('messages.loading') }}",
            pleaseSelect: "{{ __('messages.select-an-option') }}",
            noData: "{{ __('messages.data-not-found') }}",
            errorTitle: "{{ __('messages.error-title') }}",
            errorText: "{{ __('messages.control-head-fetch-failed') }}",
        };

        var config = {
            routes: {
                getBanksDetailAccounts: "{{ route('get.brv.bank.detail.account', ['projectId' => ':id']) }}",
            }
        };
    </script>
    <script src="{{ asset('js/bRVouchers.js') }}"></script>
@endsection
