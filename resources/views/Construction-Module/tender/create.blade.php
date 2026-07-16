@extends('layouts.backend')

@section('content')
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">@lang('messages.add-tender')</h3>
        </div>
        <div class="block-content block-content-full">
            <form action="{{ route('tenders.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="construction_site_id" class="form-label">@lang('messages.construction_site_id')</label>

                        <input class="form-control" style="background-color: #e9ecef !important;" name="construction_site_id"
                            value="{{ $site->id }}" readonly>
                    </div>


                    <!-- Status -->
                    <div class="col-md-6 mb-4">
                        <label for="status" class="form-label">@lang('messages.status') <span
                                class="text-danger">*</span></label>
                        <select name="status" id="status"
                            class="form-control form-select select2 custom-select @error('status') is-invalid @enderror">
                            <option value="">@lang('messages.select-status')</option>
                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>@lang('messages.draft')
                            </option>
                            <option value="approved" {{ old('status') == 'approved' ? 'selected' : '' }}>@lang('messages.approved')
                            </option>
                            <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>
                                @lang('messages.in-progress')</option>
                            <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>
                                @lang('messages.completed')</option>
                        </select>
                        @error('status')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Contractee Account -->
                    <div class="col-md-6 mb-4">
                        <label for="contractee_account_id" class="form-label">@lang('messages.contractee-account') <span
                                class="text-danger">*</span></label>
                        <select name="contractee_account_id" id="contractee_account_id"
                            class="form-control form-select select2 custom-select @error('contractee_account_id') is-invalid @enderror">
                            <option value="">@lang('messages.select-contractee-account')</option>
                            @foreach ($detailAccounts as $account)
                                <option value="{{ $account->id }}"
                                    {{ old('contractee_account_id') == $account->id ? 'selected' : '' }}>
                                    {{ App::getLocale() === 'ur' ? $account->name_ur : $account->name_en }}
                                </option>
                            @endforeach
                        </select>
                        @error('contractee_account_id')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Contractor Account -->
                    <div class="col-md-6 mb-4">
                        <label for="contractor_account_id" class="form-label">@lang('messages.contractor-account') <span
                                class="text-danger">*</span></label>
                        <select name="contractor_account_id" id="contractor_account_id"
                            class="form-control form-select select2 custom-select @error('contractor_account_id') is-invalid @enderror">
                            <option value="">@lang('messages.select-contractor-account')</option>
                            @foreach ($detailAccounts as $account)
                                <option value="{{ $account->id }}"
                                    {{ old('contractor_account_id') == $account->id ? 'selected' : '' }}>
                                    {{ App::getLocale() === 'ur' ? $account->name_ur : $account->name_en }}
                                </option>
                            @endforeach
                        </select>
                        @error('contractor_account_id')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Revenue Account -->
                    <div class="col-md-6 mb-4">
                        <label for="revenue_account_id" class="form-label">@lang('messages.revenue-account') <span
                                class="text-danger">*</span></label>
                        <select name="revenue_account_id" id="revenue_account_id"
                            class="form-control form-select select2 custom-select @error('revenue_account_id') is-invalid @enderror">
                            <option value="">@lang('messages.select-revenue-account')</option>
                            @foreach ($detailAccounts as $account)
                                <option value="{{ $account->id }}"
                                    {{ old('revenue_account_id') == $account->id ? 'selected' : '' }}>
                                    {{ App::getLocale() === 'ur' ? $account->name_ur : $account->name_en }}
                                </option>
                            @endforeach
                        </select>
                        @error('revenue_account_id')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Expense Account -->
                    <div class="col-md-6 mb-4">
                        <label for="expense_account_id" class="form-label">@lang('messages.expense-account') <span
                                class="text-danger">*</span></label>
                        <select name="expense_account_id" id="expense_account_id"
                            class="form-control form-select select2 custom-select @error('expense_account_id') is-invalid @enderror">
                            <option value="">@lang('messages.select-expense-account')</option>
                            @foreach ($detailAccounts as $account)
                                <option value="{{ $account->id }}"
                                    {{ old('expense_account_id') == $account->id ? 'selected' : '' }}>
                                    {{ App::getLocale() === 'ur' ? $account->name_ur : $account->name_en }}
                                </option>
                            @endforeach
                        </select>
                        @error('expense_account_id')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Title English -->
                    <div class="col-md-6 mb-4">
                        <label for="title_en" class="form-label">@lang('messages.title') (EN) <span
                                class="text-danger">*</span></label>
                        <input type="text" name="title_en" id="title_en"
                            class="form-control @error('title_en') is-invalid @enderror" value="{{ old('title_en') }}"
                            placeholder="@lang('messages.enter-title-english')" maxlength="255">
                        @error('title_en')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Title Urdu -->
                    <div class="col-md-6 mb-4 text-end">
                        <label for="title_ur" class="form-label">@lang('messages.title') (اردو) <span
                                class="text-danger">*</span></label>
                        <input type="text" name="title_ur" id="title_ur"
                            class="form-control keyboardInput @error('title_ur') is-invalid @enderror"
                            value="{{ old('title_ur') }}" dir="rtl" data-keyboard-id="keyboard-title-ur"
                            placeholder="@lang('messages.enter-title-urdu')" maxlength="255" autocomplete="off">
                        <div id="keyboard-title-ur" class="simple-keyboard mt-2 keyboard-container"
                            style="display: none;"></div>
                        @error('title_ur')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Description English -->
                    <div class="col-md-12 mb-4">
                        <label for="description_en" class="form-label">@lang('messages.description') (EN)</label>
                        <textarea name="description_en" id="description_en"
                            class="form-control @error('description_en') is-invalid @enderror" rows="3"
                            placeholder="@lang('messages.enter-description-english')">{{ old('description_en') }}</textarea>
                        @error('description_en')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Description Urdu -->
                    <div class="col-md-12 mb-4 text-end">
                        <label for="description_ur" class="form-label">@lang('messages.description') (اردو)</label>
                        <textarea name="description_ur" id="description_ur"
                            class="form-control keyboardInput @error('description_ur') is-invalid @enderror" rows="3" dir="rtl"
                            data-keyboard-id="keyboard-description-ur" placeholder="@lang('messages.enter-description-urdu')" autocomplete="off">{{ old('description_ur') }}</textarea>
                        <div id="keyboard-description-ur" class="simple-keyboard mt-2 keyboard-container"
                            style="display: none;"></div>
                        @error('description_ur')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Work Type -->
                    <div class="col-md-6 mb-4">
                        <label for="work_type" class="form-label">@lang('messages.work-type')</label>
                        <input type="text" name="work_type" id="work_type"
                            class="form-control @error('work_type') is-invalid @enderror" value="{{ old('work_type') }}"
                            placeholder="@lang('messages.enter-work-type')" maxlength="255">
                        @error('work_type')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Estimated Cost -->
                    <div class="col-md-6 mb-4">
                        <label for="estimated_cost" class="form-label">@lang('messages.estimated-cost')</label>
                        <input type="number" name="estimated_cost" id="estimated_cost"
                            class="form-control @error('estimated_cost') is-invalid @enderror"
                            value="{{ old('estimated_cost') }}" placeholder="@lang('messages.enter-estimated-cost')" step="0.01"
                            min="0">
                        @error('estimated_cost')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Start Date -->
                    <div class="col-md-6 mb-4">
                        <label for="start_date" class="form-label">@lang('messages.start-date')</label>
                        <input type="date" name="start_date" id="start_date"
                            class="form-control @error('start_date') is-invalid @enderror"
                            value="{{ old('start_date') }}">
                        @error('start_date')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- End Date -->
                    <div class="col-md-6 mb-4">
                        <label for="end_date" class="form-label">@lang('messages.end-date')</label>
                        <input type="date" name="end_date" id="end_date"
                            class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date') }}">
                        @error('end_date')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">@lang('messages.save')</button>
                    <a href="{{ route('tenders.index') }}" class="btn btn-alt-secondary">@lang('messages.cancel')</a>
                </div>
            </form>
        </div>
    </div>
@endsection
