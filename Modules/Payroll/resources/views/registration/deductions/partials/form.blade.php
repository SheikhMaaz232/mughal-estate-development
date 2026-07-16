@csrf

@if(isset($deduction))
    @method('PUT')
@endif

<div class="row">
    <div class="col-md-6 mx-auto">
        <label for="title_en">@lang('messages.title') (EN)</label>
        <input type="text" name="title_en" class="form-control"  maxlength="50"  value="{{ old('title_en', @$deduction->title_en ?? '') }}">
        @error('title_en')
                <div class="text-danger">{{ $message }}</div>
            @enderror
    </div>
    <div class="col-md-6 mb-3 text-end">
                <label for="title_ur" dir="rtl" class="w-100 text-end">@lang('messages.title') (اردو)</label>

        <input type="text" name="title_ur" class="form-control keyboardInput"  maxlength="50" dir="rtl" value="{{ old('title_ur', @$deduction->title_ur ?? '') }}">
        @error('title_ur')
                <div class="text-danger">{{ $message }}</div>
            @enderror
    </div>
</div>

<div class="d-flex gap-2">
    <button type="submit" class="btn btn-sm btn-primary">
        @if(isset($deduction) && $deductions->id)
            @lang('messages.update')
        @else
            @lang('messages.save')
        @endif
    </button>
    <a href="{{ route(name: 'payroll.deductions.index') }}" class="btn btn-sm btn-alt-primary">@lang('messages.go-to-list')</a>
</div>
