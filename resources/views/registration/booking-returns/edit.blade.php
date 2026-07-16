@extends('layouts.backend')

@section('content')
    <div class="block block-rounded col-md-12">
        <div class="block-header block-header-default">
            <h3 class="block-title">@lang('messages.edit-booking-return')</h3>
        </div>
        <div class="block-content block-content-full">
            <form id="booking-return-form" action="{{ route('bookingReturns.update', $bookingReturn->id) }}" method="POST">
                @csrf
                @method('PUT')

                <input type="hidden" name="booking_id" value="{{ old('booking_id', $bookingReturn->booking_id) }}">
                <input type="hidden" name="status" value="{{ $bookingReturn->status}}">
                @error('booking_id')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
                @error('status')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
                <input type="hidden" name="project_id" value="{{ old('project_id', $bookingReturn->project_id) }}">
                @error('project_id')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="date" class="form-label">@lang('messages.Date')</label>

                        <input type="date" class="form-control" name="date"
                             value="{{ old('date', isset($bookingReturn->date) ? $bookingReturn->date : now()->format('Y-m-d')) }}">

                        @error('date')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="detail_account_id">@lang('messages.amount_transfer_to_plot')</label>
                        <select name="detail_account_id" id="detail_account_id"
                            class="form-control select2 form-select @error('detail_account_id') is-invalid @enderror">
                            <option value="">@lang('messages.detail_account')</option>
                            @foreach ($liabilityAccounts as $liabilityAccount)
                                <option value="{{ $liabilityAccount->id }}"
                                    {{ old('detail_account_id', $bookingReturn->detail_account_id) == $liabilityAccount->id ? 'selected' : '' }}>
                                    {{ App::getLocale() === 'ur' ? $liabilityAccount->name_ur ?? '-' : $liabilityAccount->name_en ?? '-' }}
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
                        <label for="receivable_detail_account_id">@lang('messages.receivable_account')</label>
                        <select name="receivable_detail_account_id" id="receivable_detail_account_id"
                            class="form-control select2 form-select @error('receivable_detail_account_id') is-invalid @enderror">
                            <option value="">@lang('messages.detail_account')</option>
                            @foreach ($cancellationReceivableAccounts as $cancellationReceivableAccount)
                                <option value="{{ $cancellationReceivableAccount->id }}"
                                    {{ old('receivable_detail_account_id', $bookingReturn->receivable_detail_account_id) == $cancellationReceivableAccount->id ? 'selected' : '' }}>
                                    {{ App::getLocale() === 'ur' ? $cancellationReceivableAccount->name_ur ?? '-' : $cancellationReceivableAccount->name_en ?? '-' }}
                                </option>
                            @endforeach
                        </select>
                        @error('receivable_detail_account_id')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="cancellation_charges_account_id">@lang('messages.cancellation_charges_account')</label>
                        <select name="cancellation_charges_account_id" id="cancellation_charges_account_id"
                            class="form-control select2 form-select @error('cancellation_charges_account_id') is-invalid @enderror">
                            <option value="">@lang('messages.detail_account')</option>
                            @foreach ($incomeAccounts as $incomeAccount)
                                <option value="{{ $incomeAccount->id }}"
                                    {{ old('cancellation_charges_account_id', $bookingReturn->cancellation_charges_account_id) == $incomeAccount->id ? 'selected' : '' }}>
                                    {{ App::getLocale() === 'ur' ? $incomeAccount->name_ur ?? '-' : $incomeAccount->name_en ?? '-' }}
                                </option>
                            @endforeach
                        </select>
                        @error('cancellation_charges_account_id')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="cash_bank_account">@lang('messages.cash/bank')</label>
                        <select name="cash_bank_account" id="cash_bank_account"
                            class="form-control select2 form-select @error('cash_bank_account') is-invalid @enderror">
                            <option value="">@lang('messages.detail_account')</option>
                            @foreach ($cashBankAccounts as $cashBankAccount)
                                <option value="{{ $cashBankAccount->id }}"
                                    {{ old('cash_bank_account', $bookingReturn->cash_bank_account) == $cashBankAccount->id ? 'selected' : '' }}>
                                    {{ App::getLocale() === 'ur' ? $cashBankAccount->name_ur ?? '-' : $cashBankAccount->name_en ?? '-' }}
                                </option>
                            @endforeach
                        </select>
                        @error('cash_bank_account')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="percentage_value">@lang('messages.percentage_value') </label>
                        <input type="number" class="form-control" id="percentage_value" name="percentage_value"
                            step="any" min="0" onwheel="this.blur()" value="{{ old('percentage_value',  $bookingReturn->percentage_value) }}"
                            placeholder="@lang('messages.percentage_value')" autocomplete="off">
                        @error('percentage_value')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="remarks">@lang('messages.remarks')</label>
                        <textarea class="form-control" id="remarks" name="remarks" placeholder="......" rows="3">{{ old('remarks', $bookingReturn->remarks) }}</textarea>
                        @error('remarks')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">@lang('messages.update')</button>
                    <a href="{{ route('bookingReturns.index') }}" class="btn btn-dark">@lang('messages.go-to-list')</a>
                </div>

            </form>
        </div>
    </div>

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
        };
    </script>
@endsection
