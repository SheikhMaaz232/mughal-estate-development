@csrf

@if (isset($shift))
    @method('PUT')
@endif

<div class="row">
    <!-- Shift Name (English) -->
    <div class="col-md-6">
        <label for="shift_name_en">@lang('payroll::messages.shift_name') (EN)</label>
        <input type="text" name="shift_name_en" class="form-control" maxlength="200"
            value="{{ old('shift_name_en', @$shift->shift_name_en ?? '') }}">
        @error('shift_name_en')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="shift_name_ur">@lang('payroll::messages.shift_name') (اردو)</label>
        <input type="text" name="shift_name_ur" class="form-control keyboardInput" maxlength="200" dir="rtl"
            value="{{ old('shift_name_ur', @$shift->shift_name_ur ?? '') }}">
        @error('shift_name_ur')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row">

    <!-- Start Time -->
    <div class="col-md-6">
        <label for="start_time">@lang('payroll::messages.start_time')</label>
        <input type="time" name="start_time" class="form-control"
            value="{{ old('start_time', isset($shift) ? \Carbon\Carbon::parse($shift->start_time)->format('H:i') : '') }}"
            step="60"> <!-- step="60" ensures only minute increments -->
        @error('start_time')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <!-- End Time -->
    <div class="col-md-6">
        <label for="end_time">@lang('payroll::messages.end_time')</label>
        <input type="time" name="end_time" class="form-control"
            value="{{ old('end_time', isset($shift) ? \Carbon\Carbon::parse($shift->end_time)->format('H:i') : '') }}"
            step="60">
        @error('end_time')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>
<div class="row mt-2">
    <!-- Shift Name (English) -->
    <div class="col-md-6">
        <label for="grace_minutes">@lang('payroll::messages.grace-minutes') (EN)</label>
        <input type="text" name="grace_minutes" class="form-control" maxlength="200"
            value="{{ old('grace_minutes', @$shift->grace_minutes ?? '') }}">
        @error('grace_minutes')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>



<!-- Description -->
<div class="row mt-2">
    <div class="col-md-12">
        <label for="description">@lang('payroll::messages.description')</label>
        <textarea name="description" class="form-control" rows="3">{{ old('description', @$shift->description ?? '') }}</textarea>
        @error('description')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="d-flex gap-2 mt-3">
    <button type="submit" class="btn btn-sm btn-primary">
        @if (isset($shift) && $shift->id)
            @lang('payroll::messages.update')
        @else
            @lang('payroll::messages.save')
        @endif
    </button>
    <a href="{{ route('payroll.shifts.index') }}" class="btn btn-sm btn-alt-primary">@lang('payroll::messages.go-to-list')</a>
</div>
