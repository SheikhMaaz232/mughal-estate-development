@csrf

@if(isset($holiday) && $holiday->id)
    @method('PUT')
@endif

<div class="row g-3">
    <div class="col-md-6">
        <label for="name_en" class="form-label">@lang('messages.name') (EN)</label>
        <input type="text" name="name_en" id="name_en" class="form-control" maxlength="255"
            value="{{ old('name_en', $holiday->name_en ?? '') }}">
        @error('name_en')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label for="name_ur" class="form-label" dir="rtl">@lang('messages.name') (اردو)</label>
        <input type="text" name="name_ur" id="name_ur" class="form-control keyboardInput" maxlength="255" dir="rtl"
            value="{{ old('name_ur', $holiday->name_ur ?? '') }}">
        @error('name_ur')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="date" class="form-label">@lang('messages.date')</label>
        <input type="date" name="date" id="date" class="form-control"
            value="{{ old('date', isset($holiday) && $holiday->date ? $holiday->date->format('Y-m-d') : '') }}">
        @error('date')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4">
        <label for="holiday_type_id" class="form-label">@lang('payroll::messages.holiday-type')</label>
        <select name="holiday_type_id" id="holiday_type_id" class="form-select">
            <option value="">@lang('messages.select')</option>
            @foreach($holidayTypes as $holidayType)
                <option value="{{ $holidayType->id }}"
                    @selected(old('holiday_type_id', $holiday->holiday_type_id ?? '') == $holidayType->id)>
                    {{ $holidayType->{'title_' . app()->getLocale()} ?? $holidayType->title_en }}
                </option>
            @endforeach
        </select>
        @error('holiday_type_id')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-4 d-flex align-items-center">
        <div class="form-check mt-4">
            <input type="checkbox" name="is_paid" id="is_paid" class="form-check-input" value="1"
                @checked(old('is_paid', $holiday->is_paid ?? true))>
            <label class="form-check-label" for="is_paid">@lang('payroll::messages.paid')</label>
        </div>
    </div>
</div>

<div class="d-flex gap-2 mt-4">
    <button type="submit" class="btn btn-sm btn-primary">
        @if(isset($holiday) && $holiday->id)
            @lang('messages.update')
        @else
            @lang('messages.save')
        @endif
    </button>
    <a href="{{ route('payroll.holidays.index') }}" class="btn btn-sm btn-alt-primary">@lang('messages.go-to-list')</a>
</div>
