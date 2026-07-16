@csrf

@if (isset($device) && $device->id)
    @method('PUT')
@endif

<div class="row">
    <div class="col-md-6 mx-auto">
        <label for="name_en">@lang('messages.name') (EN)</label>
        <input type="text" name="name_en" class="form-control" maxlength="50"
            value="{{ old('name_en', @$device->name_en ?? '') }}">
        @error('name_en')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6 mb-3 text-end">
        <label for="name_ur" dir="rtl" class="w-100 text-end">@lang('messages.name') (اردو)</label>

        <input type="text" name="name_ur" class="form-control keyboardInput" maxlength="50" dir="rtl"
            value="{{ old('name_ur', @$device->name_ur ?? '') }}">
        @error('name_ur')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

</div>

<div class="row">
    <div class="col-md-6 mx-auto">
        <label for="ip_address">@lang('payroll::messages.IPAddress')</label>
        <input type="text" name="ip_address" class="form-control" maxlength="50"
            value="{{ old('ip_address', @$device->ip_address ?? '') }}">
        @error('ip_address')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mt-4">
        <label>
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" checked> 
        </label>
    </div>
</div>

<div class="d-flex mt-2 gap-2">
    <button type="submit" class="btn btn-sm btn-primary">
        @if (isset($device) && $device->id)
            @lang('messages.update')
        @else
            @lang('messages.save')
        @endif
    </button>
    <a href="{{ route('payroll.devices.index') }}" class="btn btn-sm btn-alt-primary">@lang('messages.go-to-list')</a>
</div>
