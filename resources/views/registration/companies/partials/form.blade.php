@csrf

@if(isset($company))
    @method('PUT')
@endif

    <div class="row">
        <div class="col-md-6 mb-3">
        <label for="company_code" >@lang('messages.select-group')</label>
        <select name="group_id" id="group_id" class="form-control form-select @error('group_id') is-invalid @enderror">
            <option value="">@lang('messages.select-group')</option>
            @foreach($groups as $group)
                <option value="{{ $group->id }}"
                    {{ (old('group_id') == $group->id || (isset($company) && $company->group_id == $group->id)) ? 'selected' : '' }}>
                    {{ $group->{'name_'.app()->getLocale()} }}
                </option>
            @endforeach
        </select>
        @error('group_id')
            <div class="text-danger mt-1">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label for="company_code">@lang('messages.company-code')</label>
        <input type="text" name="company_code" class="form-control" maxlength="10" value="{{ old('company_code', $company->company_code ?? '') }}">
        @error('company_code')
                <div class="text-danger">{{ $message }}</div>
            @enderror
    </div>

    <div class="col-md-6 mx-auto">
        <label for="name_en">@lang('messages.name') (EN)</label>
        <input type="text" name="name_en" class="form-control"  maxlength="100"  value="{{ old('name_en', $company->name_en ?? '') }}">
        @error('name_en')
                <div class="text-danger">{{ $message }}</div>
            @enderror
    </div>

    <div class="col-md-6 mb-3 text-end">
        <label for="name_ur" dir="rtl" class="w-100 text-end">@lang('messages.name') (اردو)</label>
        <input type="text" name="name_ur" class="form-control keyboardInput"  maxlength="100" dir="rtl" value="{{ old('name_ur', $company->name_ur ?? '') }}">
        @error('name_ur')
                <div class="text-danger">{{ $message }}</div>
            @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="description_en">@lang('messages.description') (EN)</label>
        <textarea name="description_en" class="form-control" rows="3">{{ old('description_en', $company->description_en ?? '') }}</textarea>
        @error('description_en')
                <div class="text-danger">{{ $message }}</div>
            @enderror
    </div>
    <div class="col-md-6 mb-3 text-end">
        <label for="description_ur" class="w-100 text-end" dir="rtl"> تفصیل اردو</label>
        <textarea name="description_ur" class="form-control keyboardInput"  dir="rtl" rows="3">{{ old('description_ur', $company->description_ur ?? '') }}</textarea>
        @error('description_ur')
                <div class="text-danger">{{ $message }}</div>
            @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="address_en">@lang('messages.address') (EN)</label>
        <textarea name="address_en" class="form-control" rows="3">{{ old('address_en', $company->address_en ?? '') }}</textarea>
        @error('address_en')
                <div class="text-danger">{{ $message }}</div>
            @enderror
    </div>
    <div class="col-md-6 mb-3 text-end">
        <label for="address_ur" class="w-100 text-end" dir="rtl">@lang('messages.address') اردو</label>
        <textarea name="address_ur" class="form-control keyboardInput" dir="rtl" rows="3">{{ old('address_ur', $company->address_ur ?? '') }}</textarea>
        @error('address_ur')
                <div class="text-danger">{{ $message }}</div>
            @enderror
    </div>

    <div class="form-group mb-3">
    <label for="logo">@lang('messages.logo')</label><br>

    <!-- File Upload Input -->
    <input type="file"
           name="logo"
           id="logo"
           class="form-control"
           onchange="previewImage(this)">

    <!-- Image Preview (Shows avatar if no image is selected) -->
    <div id="imagePreview" class="mt-2">
        {{--  <p class="mb-1">@lang('messages.image-preview')</p>  --}}
        <img id="previewImg"
             src="{{ isset($company) && $company->logo ? asset('storage/' . $company->logo) : asset('images/No-Image-Placeholder.svg.png') }}"
             alt=""
             class="img-thumbnail"
             style="max-height: 200px;">
    </div>

    @error('logo')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

    {{--  <div class="col-md-6 mb-3">
        <label for="logo">@lang('messages.company-logo')</label>
        <input type="file" name="logo" class="form-control">
    </div>  --}}
</div>

<div class="d-flex gap-2">
    <button type="submit" class="btn btn-sm btn-primary">
        @if(isset($company) && $company->id)
            @lang('messages.update')
        @else
            @lang('messages.save')
        @endif
    </button>
    <a href="{{ route('companies.index') }}" class="btn btn-sm btn-alt-primary">@lang('messages.go-to-list')</a>
</div>
