@csrf

@if(isset($tehsil))
    @method('PUT')
@endif

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="city_id">@lang('messages.city')</label>
        <select name="city_id" id="city_id" class="form-control form-select @error('city_id') is-invalid @enderror">
            <option value="">@lang('messages.select-city')</option>
            @foreach($cities as $city)
                <option value="{{ $city->id }}"
                    {{ (old('city_id') ?? ($tehsil->city_id ?? null)) == $city->id ? 'selected' : '' }}>
                    {{ $city->name_en }}
                </option>
            @endforeach
        </select>
        @error('city_id')
            <div class="text-danger mt-1">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6 mx-auto">
        <label for="name_en">@lang('messages.name') (EN)</label>
        <input type="text" name="name_en" class="form-control"  maxlength="100"  value="{{ old('name_en', $tehsil->name_en ?? '') }}">
        @error('name_en')
                <div class="text-danger">{{ $message }}</div>
            @enderror
    </div>
    <div class="col-md-6 mb-3 text-end">
        <label for="name_ur" dir="rtl" class="w-100 text-end">@lang('messages.name') (اردو)</label>
        <input type="text" name="name_ur" class="form-control keyboardInput"  maxlength="100" dir="rtl" value="{{ old('name_ur', $tehsil->name_ur ?? '') }}">
        @error('name_ur')
                <div class="text-danger">{{ $message }}</div>
            @enderror
    </div>

</div>

<div class="d-flex gap-2">
    <button type="submit" class="btn btn-sm btn-primary">
        @if(isset($tehsil) && $tehsil->id)
            @lang('messages.update')
        @else
            @lang('messages.save')
        @endif
    </button>
    <a href="{{ route('tehsils.index') }}" class="btn btn-sm btn-alt-primary">@lang('messages.go-to-list')</a>
</div>
