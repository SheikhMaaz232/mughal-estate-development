@extends('layouts.backend')

@section('content')
    <div class="container-fluid">
        <div class="card custom-card">
            <div class="card-header">
                <h4 class="card-title">{{ __('messages.create_registry_letter') }}</h4>
            </div>

            <form action="{{ route('registry-order.store') }}" method="POST">
                @csrf

                <div class="card-body">

                    <div class="row">

                        <!-- Date -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('messages.Date') }} <span class="text-danger">*</span></label>

                                <input type="date" class="form-control" name="date"
                                    value="{{ old('date', now()->format('Y-m-d')) }}">
                                @error('date')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="party_id">@lang('messages.party')</label>
                            <select name="party_id" id="party_id"
                                class="form-control form-select select2 @error('party_id') is-invalid @enderror">
                                <option value="">@lang('messages.main_party')</option>
                                @foreach ($searchParties as $searchParty)
                                    <option value="{{ $searchParty->id }}"
                                        {{ old('party_id') == $searchParty->id ? 'selected' : '' }}>
                                        {{ App::getLocale() === 'ur' ? $searchParty->name_ur ?? '-' : $searchParty->name_en ?? '-' }}
                                        &nbsp; -
                                        &nbsp;{{ $searchParty->cnic_no ?? 'N/A' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('party_id')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                    </div>

                    <div class="row mb-3">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{ __('messages.relation_with_file_owner') }}</label>

                                <select name="relation" class="form-control">
                                    <option value="">{{ __('messages.select_relation') }}</option>

                                    <option value="file_owner"
                                        {{ old('relation', @$registryOrder->relation ?? '') == 1 ? 'selected' : '' }}>
                                        {{ __('messages.file_owner') }}
                                    </option>

                                    <option value="nominee"
                                        {{ old('relation', @$registryOrder->relation ?? '') == 2 ? 'selected' : '' }}>
                                        {{ __('messages.nominee') }}
                                    </option>

                                    <option value="blood_relation"
                                        {{ old('relation', @$registryOrder->relation ?? '') == 3 ? 'selected' : '' }}>
                                        {{ __('messages.blood_relation') }}
                                    </option>

                                    <option value="third_party"
                                        {{ old('relation', @$registryOrder->relation ?? '') == 4 ? 'selected' : '' }}>
                                        {{ __('messages.third_party') }}
                                    </option>
                                </select>

                                @error('relation')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('messages.fard_id')</label>
                                <input type="text" name="fard_id" value="{{ old('fard_id', @$registryOrder->fard_id) }}"
                                    class="form-control">
                                @error('fard_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label for="registry_fees">@lang('messages.registry_fees') </label>
                            <input type="number" class="form-control" id="registry_fees" name="registry_fees"
                                step="any" min="0" onwheel="this.blur()" value="{{ old('registry_fees') }}"
                                placeholder="@lang('messages.registry_fees')" autocomplete="off" required>
                            @error('registry_fees')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="registry_fees_receivable_account_id">@lang('messages.registry_fees_receivable_account')</label>
                            <select name="registry_fees_receivable_account" id="registry_fees_receivable_account_id"
                                class="form-control select2 form-select @error('registry_fees_receivable_account_id') is-invalid @enderror">
                                <option value="">@lang('messages.registry_fees_receivable_account')</option>
                                @foreach ($registryAccounts as $registryAccount)
                                    <option value="{{ $registryAccount->id }}"
                                        {{ old('registry_fees_receivable_account') == $registryAccount->id ? 'selected' : '' }}>
                                        {{ App::getLocale() === 'ur' ? $registryAccount->name_ur ?? '-' : $registryAccount->name_en ?? '-' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('registry_fees_receivable_account')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="registry_status" class="form-label">@lang('messages.registry_status')</label>
                            <select class="form-control form-select registry_status-dropdown" id="registry_status"
                                name="registry_status">

                                <option value="underprocess"
                                    {{ old('registry_status', 'underprocess') === 'underprocess' ? 'selected' : '' }}>
                                    @lang('messages.underprocessRegistry')
                                </option>
                                <option value="completed"
                                    {{ old('registry_status', 'completed') === 'completed' ? 'selected' : '' }}>
                                    @lang('messages.completedRegistry')
                                </option>
                                <option value="pending"
                                    {{ old('registry_status', 'pending') === 'pending' ? 'selected' : '' }}>
                                    @lang('messages.pendingRegistry')
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary">{{ __('messages.save') }}</button>
                    <a href="{{ route('registry-order.index') }}"
                        class="btn btn-secondary">{{ __('messages.cancel') }}</a>
                </div>

            </form>
        </div>
    </div>
@endsection
