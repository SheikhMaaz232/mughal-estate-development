@csrf

@if(isset($department))
    @method('PUT')
@endif

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="city_id">@lang('messages.department')</label>
         <select name="department_type" id="department_type" class="form-control form-select @error('department_type') is-invalid @enderror">
             <option value="">@lang('messages.select-department')</option>
            @foreach($departmentTypes as $key => $label)
                <option value="{{ $key }}" {{ (old('department_type', $department->department_type ?? '') == $key) ? 'selected' : '' }}>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        @error('department_type')
            <div class="text-danger mt-1">{{ $message }}</div>
        @enderror
    </div>

</div>

<div class="row">
    <div class="col-md-6 mx-auto">
        <label for="title_en">@lang('messages.name') (EN)</label>
        <input type="text" name="title_en" class="form-control"  maxlength="100"  value="{{ old('title_en', $department->title_en ?? '') }}">
        @error('title_en')
                <div class="text-danger">{{ $message }}</div>
            @enderror
    </div>
    <div class="col-md-6 mb-3 text-end">
        <label for="title_ur" dir="rtl" class="w-100 text-end">@lang('messages.name') (اردو)</label>
        <input type="text" name="title_ur" class="form-control keyboardInput"  maxlength="100" dir="rtl" value="{{ old('title_ur', $department->title_ur ?? '') }}">
        @error('title_ur')
                <div class="text-danger">{{ $message }}</div>
            @enderror
    </div>

</div>

<div class="d-flex gap-2">
    <button type="submit" class="btn btn-sm btn-primary">
        @if(isset($department) && $department->id)
            @lang('messages.update')
        @else
            @lang('messages.save')
        @endif
    </button>
    <a href="{{ route('departments.index') }}" class="btn btn-sm btn-alt-primary">@lang('messages.go-to-list')</a>
</div>
