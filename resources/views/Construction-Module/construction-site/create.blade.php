@extends('layouts.backend')

@section('content')
<div class="block block-rounded">
    <div class="block-header block-header-default">
        <h3 class="block-title">@lang('messages.add-construction-site')</h3>
    </div>
    <div class="block-content block-content-full">
        <form action="{{ route('construction-sites.store') }}" method="POST">
            @csrf

            <div class="row">
                <!-- Company -->
                <div class="col-md-6 mb-4">
                    <label for="company_id" class="form-label">@lang('messages.company') <span class="text-danger">*</span></label>
                    <select name="company_id" id="company_id" class="form-control form-select @error('company_id') is-invalid @enderror">
                        <option value="">@lang('messages.select-company')</option>
                        @foreach ($companies ?? [] as $company)
                            <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>
                                {{ App::getLocale() === 'ur' ? $company->name_ur : $company->name_en }}
                            </option>
                        @endforeach
                    </select>
                    @error('company_id')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Project -->
                <div class="col-md-6 mb-4">
                    <label for="project_id" class="form-label">@lang('messages.project') <span class="text-danger">*</span></label>
                    <select name="project_id" id="project_id" class="form-control form-select select2 custom-select @error('project_id') is-invalid @enderror">
                        <option value="">@lang('messages.select-project')</option>
                        @foreach ($projects ?? [] as $project)
                            <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                {{ App::getLocale() === 'ur' ? $project->name_ur : $project->name_en }}
                            </option>
                        @endforeach
                    </select>
                    @error('project_id')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Party (Optional) -->
                <div class="col-md-6 mb-4">
                    <label for="party_id" class="form-label">@lang('messages.party')</label>
                    <select name="party_id" id="party_id" class="form-control select2 custom-select form-select @error('party_id') is-invalid @enderror">
                        <option value="">@lang('messages.select-party')</option>
                        @foreach ($parties ?? [] as $party)
                            <option value="{{ $party->id }}" {{ old('party_id') == $party->id ? 'selected' : '' }}>
                                {{ App::getLocale() === 'ur' ? $party->name_ur ?? '-' : $party->name_en ?? '-' }}
                                    -
                                    ({{ App::getLocale() === 'ur' ? 'ذات' : 'CAST' }}:
                                    {{ App::getLocale() === 'ur' ? $party->cast->title_ur ?? '-' : $party->cast->title_en ?? '-' }})
                                    ({{ App::getLocale() === 'ur' ? 'شناختی کارڈ' : 'CNIC' }}:
                                    {{ $party->cnic_no ?? 'N/A' }})
                                    ({{ App::getLocale() === 'ur' ? 'فون' : 'Phone' }}:
                                    {{ $party->contact_number_1 ?? 'N/A' }})
                            </option>
                        @endforeach
                    </select>
                    @error('party_id')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Status -->
                <div class="col-md-6 mb-4">
                    <label for="status" class="form-label">@lang('messages.status') <span class="text-danger">*</span></label>
                    <select name="status" id="status" class="form-control select2 custom-select form-select @error('status') is-invalid @enderror">
                        <option value="">@lang('messages.select-status')</option>
                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>@lang('messages.pending')</option>
                        <option value="ongoing" {{ old('status') == 'ongoing' ? 'selected' : '' }}>@lang('messages.ongoing')</option>
                        <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>@lang('messages.completed')</option>
                        <option value="on-hold" {{ old('status') == 'on-hold' ? 'selected' : '' }}>@lang('messages.on-hold')</option>
                    </select>
                    @error('status')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Name English -->
                <div class="col-md-6 mb-4">
                    <label for="name_en" class="form-label">@lang('messages.name') (EN) <span class="text-danger">*</span></label>
                    <input type="text" name="name_en" id="name_en" class="form-control @error('name_en') is-invalid @enderror"
                        value="{{ old('name_en') }}" placeholder="@lang('messages.enter-name-english')" maxlength="255">
                    @error('name_en')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Name Urdu -->
                <div class="col-md-6 mb-4 text-end">
                    <label for="name_ur" class="form-label">@lang('messages.name') (اردو) <span class="text-danger">*</span></label>
                    <input type="text" name="name_ur" id="name_ur" class="form-control keyboardInput @error('name_ur') is-invalid @enderror"
                        value="{{ old('name_ur') }}" dir="rtl" data-keyboard-id="keyboard-name-ur" placeholder="@lang('messages.enter-name-urdu')" maxlength="255" autocomplete="off">
                    <div id="keyboard-name-ur" class="simple-keyboard mt-2 keyboard-container" style="display: none;"></div>
                    @error('name_ur')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Description English -->
                <div class="col-md-12 mb-4">
                    <label for="description_en" class="form-label">@lang('messages.description') (EN)</label>
                    <textarea name="description_en" id="description_en" class="form-control @error('description_en') is-invalid @enderror"
                        rows="3" placeholder="@lang('messages.enter-description-english')">{{ old('description_en') }}</textarea>
                    @error('description_en')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Description Urdu -->
                <div class="col-md-12 mb-4 text-end">
                    <label for="description_ur" class="form-label">@lang('messages.description') (اردو)</label>
                    <textarea name="description_ur" id="description_ur" class="form-control keyboardInput @error('description_ur') is-invalid @enderror"
                        rows="3" dir="rtl" data-keyboard-id="keyboard-description-ur" placeholder="@lang('messages.enter-description-urdu')" autocomplete="off">{{ old('description_ur') }}</textarea>
                    <div id="keyboard-description-ur" class="simple-keyboard mt-2 keyboard-container" style="display: none;"></div>
                    @error('description_ur')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Address English -->
                <div class="col-md-12 mb-4">
                    <label for="address_en" class="form-label">@lang('messages.address') (EN) <span class="text-danger">*</span></label>
                    <textarea name="address_en" id="address_en" class="form-control @error('address_en') is-invalid @enderror"
                        rows="3" placeholder="@lang('messages.enter-address-english')">{{ old('address_en') }}</textarea>
                    @error('address_en')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Address Urdu -->
                <div class="col-md-12 mb-4 text-end">
                    <label for="address_ur" class="form-label">@lang('messages.address') (اردو) <span class="text-danger">*</span></label>
                    <textarea name="address_ur" id="address_ur" class="form-control keyboardInput @error('address_ur') is-invalid @enderror"
                        rows="3" dir="rtl" data-keyboard-id="keyboard-address-ur" placeholder="@lang('messages.enter-address-urdu')" autocomplete="off">{{ old('address_ur') }}</textarea>
                    <div id="keyboard-address-ur" class="simple-keyboard mt-2 keyboard-container" style="display: none;"></div>
                    @error('address_ur')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Estimated Start Date -->
                <div class="col-md-6 mb-4">
                    <label for="estimated_start_date" class="form-label">@lang('messages.estimated-start-date')</label>
                    <input type="date" name="estimated_start_date" id="estimated_start_date" class="form-control @error('estimated_start_date') is-invalid @enderror"
                        value="{{ old('estimated_start_date') }}">
                    @error('estimated_start_date')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Estimated End Date -->
                <div class="col-md-6 mb-4">
                    <label for="estimated_end_date" class="form-label">@lang('messages.estimated-end-date')</label>
                    <input type="date" name="estimated_end_date" id="estimated_end_date" class="form-control @error('estimated_end_date') is-invalid @enderror"
                        value="{{ old('estimated_end_date') }}">
                    @error('estimated_end_date')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary">@lang('messages.save')</button>
                <a href="{{ route('construction-sites.index') }}" class="btn btn-alt-secondary">@lang('messages.cancel')</a>
            </div>
        </form>
    </div>
</div>
@endsection
