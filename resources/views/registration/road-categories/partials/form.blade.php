
@if(isset($roadCategory))
    @method('PUT')
@endif
<div class="row">
    <div class="col-md-6 mx-auto">
        <label for="title_en">@lang('messages.name') (EN)</label>
        <input type="text" name="title_en" class="form-control"  maxlength="100"  value="{{ old('title_en', $roadCategory->title_en ?? '') }}">
        @error('title_en')
                <div class="text-danger">{{ $message }}</div>
            @enderror
    </div>
    <div class="col-md-6 mb-3 text-end">
        <label for="title_ur" dir="rtl" class="w-100 text-end">@lang('messages.name') (اردو)</label>
        <input type="text" name="title_ur" class="form-control keyboardInput"  maxlength="100" dir="rtl" value="{{ old('title_ur', $roadCategory->title_ur ?? '') }}">
        @error('title_ur')
                <div class="text-danger">{{ $message }}</div>
            @enderror
    </div>

</div>

<div class="d-flex gap-2">
    <button type="submit" class="btn btn-sm btn-primary">
        @if(isset($roadCategory) && $roadCategory->id)
            @lang('messages.update')
        @else
            @lang('messages.save')
        @endif
    </button>
    <a href="{{ route('road-categories.index') }}" class="btn btn-sm btn-alt-primary">@lang('messages.go-to-list')</a>
</div>
