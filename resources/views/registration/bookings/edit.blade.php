@extends('layouts.backend')

@section('content')
    <div class="block block-rounded col-md-12">
        <div class="block-header block-header-default">
            <h3 class="block-title">@lang('messages.edit-booking')</h3>
        </div>
        <div class="block-content block-content-full">
            <form id="booking-form" action="{{ route('bookings.update', $booking->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Booking Info --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">@lang('messages.booking_application_no')</label>
                        <input type="text" class="form-control" value="{{ old('form_no', $booking->form_no) }}" disabled>
                        <input type="hidden" name="form_no" value="{{ old('form_no', $booking->form_no) }}">
                        <input type="hidden" name="product_id" value="{{ old('product_id', $booking->product_id) }}">
                        <input type="hidden" name="status" value="{{ old('status', $booking->status) }}">
                        <input type="hidden" name="case" value="{{ $booking->case }}">
                        <input type="hidden" name="previous_booking_id"
                            value="{{ $booking->previous_booking_id ?? null }}">
                        @error('form_no')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                        @error('product_id')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                        @error('status')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">@lang('messages.booking_date')</label>
                        <input type="date" class="form-control" name="date"
                            value="{{ old('date', isset($booking->date) ? $booking->date : now()->format('Y-m-d')) }}">
                        @error('date')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">@lang('messages.projects')</label>
                        <input type="hidden" name="project_id" value="{{ old('project_id', $booking->project_id) }}">
                        <input type="text" class="form-control"
                            value="{{ App::getLocale() === 'ur' ? $booking->project->name_ur ?? '-' : $booking->project->name_en ?? '-' }}"
                            disabled>
                        @error('project_id')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">@lang('messages.unit')</label>
                        <input type="text" class="form-control"
                            value="{{ App::getLocale() === 'ur' ? $booking->product->name_ur ?? '-' : $booking->product->name_en ?? '-' }}"
                            disabled>
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
                                    {{ old('party_id', $booking->party_id) == $searchParty->id ? 'selected' : '' }}>
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
                                    {{ old('detail_account_id', $booking->detail_account_id) == $detailAccount->id ? 'selected' : '' }}>
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
                    @if ($booking->case === 'transfer')
                        <input type="hidden" name="dealer_id" value="">
                        <input type="hidden" name="receivable_dealer_id" value="">
                    @else
                        <div class="col-md-6 mb-3">
                            <label for="dealer_id">@lang('messages.dealers_liability_account')</label>
                            <select name="dealer_id" id="dealer_id"
                                class="form-control select2 form-select @error('dealer_id') is-invalid @enderror">
                                <option value="">@lang('messages.main_party')</option>
                                @foreach ($dealerAccounts as $coaDealer)
                                    <option value="{{ $coaDealer->id }}"
                                        {{ old('dealer_id', $booking->dealer_id) == $coaDealer->id ? 'selected' : '' }}>
                                        {{ App::getLocale() === 'ur' ? $coaDealer->name_ur ?? '-' : $coaDealer->name_en ?? '-' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('dealer_id')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="receivable_dealer_id">@lang('messages.dealers_receivable_account')</label>
                            <select name="receivable_dealer_id" id="receivable_dealer_id"
                                class="form-control select2 form-select @error('receivable_dealer_id') is-invalid @enderror">
                                <option value="">@lang('messages.main_party')</option>
                                @foreach ($dealerReceivableAccounts as $coaReceivableDealer)
                                    <option value="{{ $coaReceivableDealer->id }}"
                                        {{ old('receivable_dealer_id', $booking->receivable_dealer_id) == $coaReceivableDealer->id ? 'selected' : '' }}>
                                        {{ App::getLocale() === 'ur' ? $coaReceivableDealer->name_ur ?? '-' : $coaReceivableDealer->name_en ?? '-' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('receivable_dealer_id')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    @endif
                    <div class="col-md-6 mb-3">
                        <label for="care_off">@lang('messages.care_off')</label>
                        <input type="text" class="form-control" value="{{ old('care_off', $booking->care_off) }}"
                            name="care_off" value="{{ old('care_off') }}">
                        @error('care_off')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label for="possession_fees">@lang('messages.possession_fees') </label>
                            <input type="number" class="form-control" id="possession_fees" name="possession_fees"
                                step="any" min="0" onwheel="this.blur()"
                                value="{{ old('possession_fees', $booking->possession_fees) }}"
                                placeholder="@lang('messages.possession_fees')" autocomplete="off" required>
                            @error('possession_fees')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="possession_receivable_account">@lang('messages.possession_fees_receivable_account')</label>
                            <select name="possession_receivable_account" id="possession_receivable_account"
                                class="form-control select2 form-select @error('possession_receivable_account') is-invalid @enderror">
                                <option value="">@lang('messages.possession_fees_receivable_account')</option>
                                @foreach ($possessionAccounts as $possessionAccount)
                                    <option value="{{ $possessionAccount->id }}"
                                        {{ old('possession_receivable_account', $booking->possession_receivable_account) == $possessionAccount->id ? 'selected' : '' }}>
                                        {{ App::getLocale() === 'ur' ? $possessionAccount->name_ur ?? '-' : $possessionAccount->name_en ?? '-' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('possession_receivable_account')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label for="proceeding_fees">@lang('messages.proceeding_fees') </label>
                            <input type="number" class="form-control" id="proceeding_fees" name="proceeding_fees"
                                step="any" min="0" onwheel="this.blur()"
                                value="{{ old('proceeding_fees', $booking->proceeding_fees) }}"
                                placeholder="@lang('messages.proceeding_fees')" autocomplete="off" required>
                            @error('proceeding_fees')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="proceeding_receivable_account">@lang('messages.proceeding_fees_receivable_account')</label>
                            <select name="proceeding_receivable_account" id="proceeding_receivable_account"
                                class="form-control select2 form-select @error('proceeding_receivable_account') is-invalid @enderror">
                                <option value="">@lang('messages.proceeding_fees_receivable_account')</option>
                                @foreach ($proceedingAccounts as $proceedingAccount)
                                    <option value="{{ $proceedingAccount->id }}"
                                        {{ old('proceeding_receivable_account', $booking->proceeding_receivable_account) == $proceedingAccount->id ? 'selected' : '' }}>
                                        {{ App::getLocale() === 'ur' ? $proceedingAccount->name_ur ?? '-' : $proceedingAccount->name_en ?? '-' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('proceeding_receivable_account')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label for="development_charges">@lang('messages.development_charges') </label>
                            <input type="number" class="form-control" id="development_charges"
                                name="development_charges" step="any" min="0" onwheel="this.blur()"
                                value="{{ old('development_charges', $booking->development_charges) }}"
                                placeholder="@lang('messages.development_charges')" autocomplete="off" required>
                            @error('development_charges')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="development_receivable_id">@lang('messages.development_charges_receivable_account')</label>
                            <select name="development_receivable_id" id="development_receivable_id"
                                class="form-control select2 form-select @error('development_receivable_id') is-invalid @enderror">
                                <option value="">@lang('messages.development_charges_receivable_account')</option>
                                @foreach ($developmentChargesAccounts as $developmentChargesAccount)
                                    <option value="{{ $developmentChargesAccount->id }}"
                                        {{ old('development_receivable_id', $booking->development_receivable_id) == $developmentChargesAccount->id ? 'selected' : '' }}>
                                        {{ App::getLocale() === 'ur' ? $developmentChargesAccount->name_ur ?? '-' : $developmentChargesAccount->name_en ?? '-' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('development_receivable_id')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label for="gst">@lang('messages.gst') </label>
                            <input type="number" class="form-control" id="gst" name="gst" step="any"
                                min="0" onwheel="this.blur()" value="{{ old('gst', $booking->gst) }}"
                                placeholder="@lang('messages.gst')" autocomplete="off" required>
                            @error('gst')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="gst_receivable_account_id">@lang('messages.gst_receivable_account')</label>
                            <select name="gst_receivable_account_id" id="gst_receivable_account_id"
                                class="form-control select2 form-select @error('gst_receivable_account_id') is-invalid @enderror">
                                <option value="">@lang('messages.gst_receivable_account')</option>
                                @foreach ($gstAccounts as $gstAccount)
                                    <option value="{{ $gstAccount->id }}"
                                        {{ old('gst_receivable_account_id', $booking->gst_receivable_account_id) == $gstAccount->id ? 'selected' : '' }}>
                                        {{ App::getLocale() === 'ur' ? $gstAccount->name_ur ?? '-' : $gstAccount->name_en ?? '-' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('gst_receivable_account_id')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- <div class="row">

                        <div class="col-md-6 mb-3">
                            <label for="sevenE_chalan">@lang('messages.sevenE_chalan') </label>
                            <input type="number" class="form-control" id="sevenE_chalan" name="sevenE_chalan"
                                step="any" min="0" onwheel="this.blur()"
                                value="{{ old('sevenE_chalan', $booking->sevenE_chalan) }}"
                                placeholder="@lang('messages.sevenE_chalan')" autocomplete="off" required>
                            @error('sevenE_chalan')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="sevenE_chalan_receivable_account">@lang('messages.sevenE_chalan_receivable_account')</label>
                            <select name="sevenE_chalan_receivable_account" id="sevenE_chalan_receivable_account"
                                class="form-control select2 form-select @error('sevenE_chalan_receivable_account') is-invalid @enderror">
                                <option value="">@lang('messages.sevenE_chalan_receivable_account')</option>
                                @foreach ($sevenEAccounts as $sevenEAccount)
                                    <option value="{{ $sevenEAccount->id }}"
                                        {{ old('sevenE_chalan_receivable_account', $booking->sevenE_chalan_receivable_account) == $sevenEAccount->id ? 'selected' : '' }}>
                                        {{ App::getLocale() === 'ur' ? $sevenEAccount->name_ur ?? '-' : $sevenEAccount->name_en ?? '-' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('sevenE_chalan_receivable_account')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div> --}}

                </div>

                @if ($booking->case === 'transfer')
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="transfer_charges_account_id">
                                @lang('messages.transfer_charges_account')
                            </label>

                            <select name="transfer_charges_account_id" id="transfer_charges_account_id"
                                class="form-control select2 form-select @error('transfer_charges_account_id') is-invalid @enderror">
                                <option value="">@lang('messages.main_party')</option>
                                @foreach ($detailAccounts as $coaDealer)
                                    <option value="{{ $coaDealer->id }}"
                                        {{ old('transfer_charges_account_id', $booking->transfer_charges_account_id) == $coaDealer->id ? 'selected' : '' }}>
                                        {{ App::getLocale() === 'ur' ? $coaDealer->name_ur ?? '-' : $coaDealer->name_en ?? '-' }}
                                    </option>
                                @endforeach
                            </select>

                            @error('transfer_charges_account_id')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="transfer_charges">@lang('messages.transferCharges')</label>
                            <input type="text" class="form-control" name="transfer_charges"
                                value="{{ old('transfer_charges', $booking->transfer_charges) }}">
                            @error('transfer_charges')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                @else
                    {{-- Force NULL submission when case is not transfer --}}
                    <input type="hidden" name="transfer_charges_account_id" value="">
                    <input type="hidden" name="transfer_charges" value="">
                @endif

                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h4 class="card-title mb-0" style="color: red">
                                <i class="fa fa-align-left text-primary me-1"></i> @lang('messages.discount_expense_details')
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="expense_account">@lang('messages.expense_account')</label>
                                    <select name="expense_account_id" id="expense_account_id"
                                        class="form-control select2 form-select @error('expense_account_id') is-invalid @enderror">
                                        <option value="">@lang('messages.expense_account')</option>
                                        @foreach ($expenseAccounts as $expenseAccount)
                                            <option value="{{ $expenseAccount->id }}"
                                                {{ old('expense_account_id', $booking->expense_account_id) == $expenseAccount->id ? 'selected' : '' }}>
                                                {{ App::getLocale() === 'ur' ? $expenseAccount->name_ur ?? '-' : $expenseAccount->name_en ?? '-' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('expense_account_id')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="discount_amount">@lang('messages.discount_amount') </label>
                                    <input type="number" class="form-control" id="discount_amount"
                                        name="discount_amount" step="any" min="0" onwheel="this.blur()"
                                        value="{{ old('discount_amount', $booking->discount_amount) }}" placeholder="@lang('messages.discount_amount')"
                                        autocomplete="off">
                                    @error('discount_amount')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h4 class="card-title mb-0" style="color: red">
                                <i class="fa fa-align-left text-primary me-1"></i> @lang('messages.operating_charges')
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="operating_start_date" class="form-label">@lang('messages.operating_charges_start_date')</label>

                                    <input type="date" class="form-control" name="operating_start_date"
                                        value="{{ old('operating_start_date', isset($booking->operating_start_date) ? $booking->operating_start_date : now()->format('Y-m-d')) }}">

                                    @error('operating_start_date')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="operating_charges">@lang('messages.operating_charges') </label>
                                    <input type="number" class="form-control" id="operating_charges"
                                        name="operating_charges" step="any" min="0" onwheel="this.blur()"
                                        value="{{ old('operating_charges', $booking->operating_charges) }}"
                                        placeholder="@lang('messages.operating_charges')" autocomplete="off">
                                    @error('operating_charges')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="operating_charges_receivable_account_id">@lang('messages.operating_charges_receivable_account')</label>
                                    <select name="operating_receivable_account"
                                        id="operating_charges_receivable_account_id"
                                        class="form-control select2 form-select @error('operating_charges_receivable_account_id') is-invalid @enderror">
                                        <option value="">@lang('messages.operating_charges_receivable_account')</option>
                                        @foreach ($operatingChargesAccounts as $operatingChargesAccount)
                                            <option value="{{ $operatingChargesAccount->id }}"
                                                {{ old('operating_charges_receivable_account_id', $booking->operating_receivable_account) == $operatingChargesAccount->id ? 'selected' : '' }}>
                                                {{ App::getLocale() === 'ur' ? $operatingChargesAccount->name_ur ?? '-' : $operatingChargesAccount->name_en ?? '-' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('operating_receivable_account')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>


                                <div class="col-md-6 mb-3">
                                    <label for="condition" class="form-label">@lang('messages.condition')</label>
                                    <select class="form-control form-select condition-dropdown" id="condition"
                                        name="condition">
                                        <option value="not_allow"
                                            {{ old('condition', 'not_allow') === 'not_allow' ? 'selected' : '' }}>
                                            @lang('messages.not_allow')
                                        </option>
                                        <option value="allow"
                                            {{ old('condition', 'allow') === 'allow' ? 'selected' : '' }}>
                                            @lang('messages.allow')
                                        </option>
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- Nominee Info --}}
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h4 class="card-title mb-0" style="color: red">
                                <i class="fa fa-align-left text-primary me-1"></i> @lang('messages.nominee_info')
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table item-table">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th></th>
                                            <th>@lang('messages.relations')</th>
                                            <th class="">
                                                @lang('messages.nominee_party')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($nominees as $nominee)
                                            @php
                                                $index = $loop->index + 1;
                                            @endphp
                                            <tr class="tr_clone validator_{{ $index }}">
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
                                                                    <circle cx="12" cy="12" r="10"></circle>
                                                                    <line x1="15" y1="9" x2="9"
                                                                        y2="15"></line>
                                                                    <line x1="9" y1="9" x2="15"
                                                                        y2="15"></line>
                                                                </svg>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </td>
                                                <td>
                                                    <input type="text" name="row_id[]" class="row_id"
                                                        value="{{ $index }}" hidden>
                                                </td>
                                                <td>
                                                    <select name="relation_id[]"
                                                        class="form-control form-select select2 @error('relation_id') is-invalid @enderror relation_{{ $index }}">
                                                        <option value="">@lang('messages.select-relation')</option>
                                                        @foreach ($relations as $relation)
                                                            <option value="{{ $relation->id }}"
                                                                {{ old('relation_id', $nominee->relation_id) == $relation->id ? 'selected' : '' }}>
                                                                {{ App::getLocale() === 'ur' ? $relation->name_ur : $relation->name_en }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td class="nominee_party_id">
                                                    <select name="nominee_party_id[]" id="nominee_party_id"
                                                        class="form-control form-select select2 @error('nominee_party_id') is-invalid @enderror nominee_party_id_{{ $index }}">
                                                        <option value="">@lang('messages.select-party')</option>
                                                        @foreach ($searchParties as $searchParty)
                                                            <option value="{{ $searchParty->id }}"
                                                                {{ old('nominee_party_id', $nominee->nominee_party_id) == $searchParty->id ? 'selected' : '' }}>
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
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <a href="javascript:void(0)" class="btn btn-dark additem">@lang('messages.add_nominee_info')</a>
                        </div>
                    </div>
                </div>

                {{-- Price Schedule --}}
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h4 class="card-title mb-0" style="color: red">
                                <i class="fa fa-calendar text-primary me-1"></i> @lang('messages.price_shedule')
                            </h4>
                        </div>
                        <div class="card-body">
                            @if ($booking->case === 'transfer')
                                <div class="row">

                                    <input type="hidden" name="add_value" value="0">
                                    <input type="hidden" name="commission" value="0">
                                    <input type="hidden" name="discount" value="0">
                                    <div class="col-md-3 mb-3">
                                        <label for="total_amount">@lang('messages.total_amount') </label>
                                        <input type="number" class="form-control" id="total_amount" name="total_amount"
                                            step="any" min="0" onwheel="this.blur()"
                                            value="{{ old('total_amount', $booking->total_amount) }}"
                                            placeholder="@lang('messages.total_amount')" autocomplete="off" readonly>
                                        @error('total_amount')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>


                                </div>
                            @else
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label for="add_value">@lang('messages.add_value') </label>
                                        <input type="number" class="form-control" id="add_value" name="add_value"
                                            step="any" min="0" onwheel="this.blur()"
                                            value="{{ old('add_value', $booking->add_value) }}"
                                            placeholder="@lang('messages.add_value')" autocomplete="off">
                                        @error('add_value')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="discount">@lang('messages.discount') </label>
                                        <input type="number" class="form-control" id="discount" name="discount"
                                            step="any" min="0" onwheel="this.blur()"
                                            value="{{ old('discount', $booking->discount) }}"
                                            placeholder="@lang('messages.discount')" autocomplete="off">
                                        @error('discount')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="commission">@lang('messages.commission') </label>
                                        <input type="number" class="form-control" id="commission" name="commission"
                                            step="any" min="0" onwheel="this.blur()"
                                            value="{{ old('commission', $booking->commission) }}"
                                            placeholder="@lang('messages.commission')" autocomplete="off">
                                        @error('commission')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label for="total_amount">@lang('messages.total_amount') </label>
                                        <input type="number" class="form-control" id="total_amount" name="total_amount"
                                            step="any" min="0" onwheel="this.blur()"
                                            value="{{ old('total_amount', $booking->product->total_amount ?? '0') }}"
                                            placeholder="@lang('messages.total_amount')" autocomplete="off" readonly>
                                        @error('total_amount')
                                            <div class="text-danger mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            @endif

                            <div class="table-responsive">
                                <table class="table schedule-table">
                                    <thead>
                                        <tr>
                                            <th class="">
                                            </th>
                                            <th>
                                            </th>
                                            <th style="width: 15% !important">@lang('messages.schedule-types')</th>
                                            <th class="" style="width: 15% !important">
                                                @lang('messages.schedule-time')</th>
                                            <th class="">
                                                @lang('messages.due_date')</th>
                                            <th class="" style="width: 10% !important">
                                                @lang('messages.number')</th>
                                            <th class="" style="width: 20% !important">
                                                @lang('messages.installment_amount')</th>
                                            <th class="" style="width: 20% !important">
                                                @lang('messages.total_amount')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($schedules as $schedule)
                                            @php
                                                $index = $loop->index + 1;
                                            @endphp
                                            <tr class="tr_clone validator_{{ $index }}">
                                                <td class="delete-item-row">
                                                    <ul class="table-controls">
                                                        <li>
                                                            <a href="javascript:void(0);" class="delete-item2"
                                                                data-toggle="tooltip" data-placement="top" title=""
                                                                data-original-title="Delete">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                                    height="24" viewBox="0 0 24 24" fill="none"
                                                                    stroke="currentColor" stroke-width="2"
                                                                    stroke-linecap="round" stroke-linejoin="round"
                                                                    class="feather feather-x-circle">
                                                                    <circle cx="12" cy="12" r="10"></circle>
                                                                    <line x1="15" y1="9" x2="9"
                                                                        y2="15"></line>
                                                                    <line x1="9" y1="9" x2="15"
                                                                        y2="15"></line>
                                                                </svg>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </td>
                                                <td>
                                                    <input type="text" name="row_id[]" class="row_id"
                                                        value="{{ $index }}" hidden>
                                                </td>

                                                <td class="schedule_type_id">
                                                    <select name="schedule_type_id[]" id="schedule_type_id"
                                                        class="form-control form-select select2 @error('schedule_type_id') is-invalid @enderror schedule_type_id_{{ $index }}">
                                                        <option value="">@lang('messages.select-schedule-type')</option>
                                                        @foreach ($scheduleTypes as $scheduleType)
                                                            <option value="{{ $scheduleType->id }}"
                                                                {{ old('schedule_type_id', $schedule->schedule_type_id) == $scheduleType->id ? 'selected' : '' }}>
                                                                {{ App::getLocale() === 'ur' ? $scheduleType->title_ur ?? '-' : $scheduleType->title_en ?? '-' }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td class="schedule_period_id">
                                                    <select name="schedule_period_id[]" id="schedule_period_id"
                                                        class="form-control form-select select2 @error('schedule_period_id') is-invalid @enderror schedule_period_id_{{ $index }}">
                                                        <option value="">@lang('messages.select-schedule-period')</option>
                                                        @foreach ($schedulePeriods as $schedulePeriod)
                                                            <option value="{{ $schedulePeriod->id }}"
                                                                {{ old('schedule_period_id', $schedule->schedule_period_id) == $schedulePeriod->id ? 'selected' : '' }}>
                                                                {{ App::getLocale() === 'ur' ? $schedulePeriod->title_ur ?? '-' : $schedulePeriod->title_en ?? '-' }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                                <td class="due_date">
                                                    <input id="due_date" style="color: black;" type="date"
                                                        name="due_date[]"
                                                        value="{{ $schedule->due_date ?? now()->format('Y-m-d') }}"
                                                        class="due_date form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} due_date_{{ $index }}">
                                                </td>
                                                <td class="number">
                                                    <input id="number" style="color: black; " type="number"
                                                        step="any" min="0" onwheel="this.blur()"
                                                        name="number[]" value="{{ old('number', $schedule->number) }}"
                                                        class = "number form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} number_{{ $index }}"
                                                        placeholder="@lang('messages.number')">
                                                </td>
                                                <td class="pay_amount">
                                                    <input id="pay_amount" style="color: black; " type="number"
                                                        type="number" step="any" min="0"
                                                        value="{{ old('pay_amount', $schedule->pay_amount) }}"
                                                        onwheel="this.blur()" name="pay_amount[]"
                                                        class = "pay_amount form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}  pay_amount_{{ $index }}"
                                                        placeholder="@lang('messages.installment_amount')">
                                                </td>
                                                <td class="calculated_total_amount">
                                                    <input type="text" style="color: black; "
                                                        placeholder="@lang('messages.total_amount')" id="calculated_total_amount"
                                                        name="calculated_total_amount[]"
                                                        value="{{ old('calculated_total_amount', $schedule->calculated_total_amount) }}"
                                                        class = "calculated_total_amount form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} calculated_total_amount_{{ $index }}"
                                                        readonly>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="row justify-content-end">
                                    <div class="col-md-3 mb-3">
                                        <label for="grand_total" class="form-label">@lang('messages.grand_total')</label>
                                        <input id="grand-total" type="text" class="form-control grand-total"
                                            value="{{ old('grand_total_amount', $booking->grand_total_amount) }}"
                                            name="grand_total_amount" readonly>
                                    </div>
                                </div>
                            </div>
                            <a href="javascript:void(0)" class="btn btn-dark additem3">@lang('messages.add-detail')</a>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">@lang('messages.update')</button>
                    <a href="{{ route('bookings.bookingListing') }}" class="btn btn-dark">@lang('messages.go-to-list')</a>
                </div>
            </form>
        </div>
    </div>

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
                '<td><input type="checkbox" name="row_id[]" class="row_id" value="' + currentIndex +
                '" hidden></td>' +
                '<td class="relation_id"><select name="relation_id[]" id="relation_id" class="form-control form-select select2 @error('relation_id') is-invalid @enderror relation_' +
                currentIndex +
                '"><option value="">@lang('messages.select-relation')</option>@foreach ($relations as $relation)<option value="{{ $relation->id }}"{{ old('relation_id') == $relation->id ? 'selected' : '' }}>{{ App::getLocale() === 'ur' ? $relation->name_ur ?? '-' : $relation->name_en ?? '-' }}</option>@endforeach</select> ' +
                '</td> ' +
                '<td class="nominee_party_id"><select name="nominee_party_id[]" id="nominee_party_id" class="form-control form-select select2 @error('nominee_party_id') is-invalid @enderror nominee_party_id_' +
                currentIndex +
                '"><option value="">@lang('messages.select-party')</option> @foreach ($searchParties as $searchParty)<option value="{{ $searchParty->id }}"{{ old('nominee_party_id') == $searchParty->id ? 'selected' : '' }}>{{ App::getLocale() === 'ur' ? $searchParty->name_ur ?? '-' : $searchParty->name_en ?? '-' }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; -  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $searchParty->cnic_no ?? 'N/A' }}</option>@endforeach</select> ' +
                '</td> ' +

                '<div class="form-check form-check-primary form-check-inline me-0 mb-0">' +

                '</div>' +
                '</div>' +
                '</td>' +
                '</tr>';

            $(".item-table tbody").append($html);
            deleteItemRow();
            $('.select2').select2();

        })

        deleteItemRow();

        selectableDropdown(document.querySelectorAll('.invoice-select .dropdown-item'));
        selectableDropdown(document.querySelectorAll('.invoice-tax-select .dropdown-item'), getTaxValue);
        selectableDropdown(document.querySelectorAll('.invoice-discount-select .dropdown-item'), getDiscountValue);

        function deleteItemRow() {
            let deleteItem = document.querySelectorAll('.delete-item');
            for (var i = 0; i < deleteItem.length; i++) {
                deleteItem[i].addEventListener('click', function() {
                    this.parentElement.parentNode.parentNode.parentNode.remove();
                })
            }
        }
    </script>

    <script>
        document.getElementsByClassName('additem3')[0].addEventListener('click', function() {

            let getTableElement = document.querySelector('.schedule-table');
            let currentIndex = getTableElement.rows.length;

            let $html = '<tr>' +
                '<td class="delete-item-row">' +
                '<ul class="table-controls">' +
                '<li><a href="javascript:void(0);" class="delete-item2" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-circle"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg></a></li>' +
                '</ul>' +
                '</td>' +
                '<td><input type="checkbox" name="row_id[]" class="row_id" value="' + currentIndex +
                '" hidden></td>' +
                '<td class="schedule_type_id"><select name="schedule_type_id[]" id="schedule_type_id" class="form-control form-select select2 @error('schedule_type_id') is-invalid @enderror schedule_type_id_' +
                currentIndex +
                '"><option value="">@lang('messages.select-schedule-type')</option>@foreach ($scheduleTypes as $scheduleType)<option value="{{ $scheduleType->id }}"{{ old('schedule_type_id') == $scheduleType->id ? 'selected' : '' }}>{{ App::getLocale() === 'ur' ? $scheduleType->title_ur ?? '-' : $scheduleType->title_en ?? '-' }}</option>@endforeach</select> ' +
                '</td> ' +
                '<td class="schedule_period_id"><select name="schedule_period_id[]" id="schedule_period_id" class="form-control form-select select2 @error('schedule_period_id') is-invalid @enderror schedule_period_id_' +
                currentIndex +
                '"><option value="">@lang('messages.select-schedule-period')</option>@foreach ($schedulePeriods as $schedulePeriod)<option value="{{ $schedulePeriod->id }}"{{ old('schedule_period_id') == $schedulePeriod->id ? 'selected' : '' }}>{{ App::getLocale() === 'ur' ? $schedulePeriod->title_ur ?? '-' : $schedulePeriod->title_en ?? '-' }}</option>@endforeach</select> ' +
                '</td> ' +
                '<td class="due_date">' +
                '<input id="due_date" style="color: black;" type="date" name="due_date[]" class="due_date form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} due_date_' +
                currentIndex + '" >' +
                '</td>' +
                '<td class="number" >' +
                '<input id="number" style="color: black; " type="number" step="any" min="0" onwheel="this.blur()" name="number[]" class = "number form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} number_' +
                currentIndex + '" placeholder="@lang('messages.number')" ></td>' +
                '<td class="pay_amount" >' +
                '<input id="pay_amount" style="color: black; " type="number" type="number" step="any" min="0" onwheel="this.blur()" name="pay_amount[]" class = "pay_amount form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }}  pay_amount_' +
                currentIndex + '" placeholder="@lang('messages.installment_amount')" ></td>' +
                '<td class="calculated_total_amount" >' +
                '<input type="text" style="color: black; " placeholder="@lang('messages.total_amount')" id="calculated_total_amount" name="calculated_total_amount[]" class = "calculated_total_amount form-control {{ config('constants.css-classes.ELEMENT_SIZE_CLASS') }} calculated_total_amount_' +
                currentIndex + '" readonly> </td> ' +

                '<div class="form-check form-check-primary form-check-inline me-0 mb-0">' +
                // '<input class="form-check-input inbox-chkbox contact-chkbox" type="checkbox">' +
                '</div>' +
                '</div>' +
                '</td>' +
                '</tr>';

            $(".schedule-table tbody").append($html);
            deleteItemRow2();
            $('.select2').select2();

        })

        deleteItemRow2();

        selectableDropdown(document.querySelectorAll('.invoice-select .dropdown-item'));
        selectableDropdown(document.querySelectorAll('.invoice-tax-select .dropdown-item'), getTaxValue);
        selectableDropdown(document.querySelectorAll('.invoice-discount-select .dropdown-item'), getDiscountValue);

        function deleteItemRow2() {
            let deleteItem = document.querySelectorAll('.delete-item2');
            for (var i = 0; i < deleteItem.length; i++) {
                deleteItem[i].addEventListener('click', function() {
                    this.parentElement.parentNode.parentNode.parentNode.remove();
                })
            }
        }
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
            errorText2: "{{ __('messages.total_and_schedule_not_same') }}",
            confirmButtonText: "{{ __('messages.ok') }}",
        };
    </script>

    <script>
        var config = {
            routes: {
                getDetailAccounts: "{{ route('get.detail.account.data', ['partyId' => ':id']) }}",
            }
        };
    </script>

    <script src="{{ asset('js/plugins/sweetalert2/sweetalert2.all.js') }}"></script>
    <script src="{{ asset('js/booking.js') }}"></script>
@endsection
