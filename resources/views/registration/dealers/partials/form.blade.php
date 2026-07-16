<div class="row">
    <div class="col-md-4 mx-auto">
        <div class="form-group mb-4">
            <label for="name">@lang('messages.name') (EN)</label>
            <input type="text" name="name_en" class="form-control" maxlength="50" value="{{ old('name_eng', $dealer->name_en ?? '') }}" autocomplete="off">
            @error('name_en')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-4 text-end">
            <label for="name_ur">@lang('messages.name') (اردو)</label>
            <input type="text" name="name_ur" class="form-control keyboardInput" maxlength="50" id="name_ur" dir="rtl" data-keyboard-id="keyboard-name-ur" value="{{ old('name_ur', $dealer->name_ur ?? '') }}" autocomplete="off">
            <div id="keyboard-name-ur" class="simple-keyboard mt-2 keyboard-container" style="display: none;"></div>
            @error('name_ur')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-4">
            <label for="address_en">@lang('messages.address') (EN)</label>
            <input type="text" name="address_en" class="form-control" maxlength="200" value="{{ old('address_en', $dealer->address_en ?? '') }}" autocomplete="off">
            @error('address_en')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-4 text-end">
            <label for="address_ur">@lang('messages.address') (اردو)</label>
            <input type="text" name="address_ur" class="form-control keyboardInput" maxlength="200" dir="rtl" id="address_ur" data-keyboard-id="keyboard-address-ur" value="{{ old('address_ur', $dealer->address_ur ?? '') }}" autocomplete="off">
            <div id="keyboard-address-ur" class="simple-keyboard mt-2 keyboard-container" style="display: none;"></div>
            @error('address_ur')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
         <div class="form-group mb-4">
            <label for="mobile_number">@lang('messages.mobile-number') </label>
            <input type="text" name="mobile_number" class="form-control" maxlength="20" dir="rtl" id="mobile_number" value="{{ old('mobile_number', $dealer->mobile_number ?? '') }}" autocomplete="off">
        </div>
         <div class="form-group mb-4">
            <label for="phone_number">@lang('messages.phone-number') </label>
            <input type="text" name="phone_number" class="form-control" maxlength="20" id="phone_number" value="{{ old('phone_number', $dealer->phone_number ?? '') }}" autocomplete="off">
        </div>

        <div class="form-group mb-3">
            <label for="photo">@lang('messages.photo')</label><br>

            <!-- File Upload Input -->
            <input type="file"
                name="photo"
                id="photo"
                class="form-control"
                onchange="previewImage(this)">

            <!-- Image Preview (Shows avatar if no image is selected) -->
            <div id="imagePreview" class="mt-2">
                {{--  <p class="mb-1">@lang('messages.image-preview')</p>  --}}
                <img id="previewImg"
                    src="{{ isset($dealer) && $dealer->photo ? asset('storage/' . $dealer->photo) : asset('images/No-Image-Placeholder.svg.png') }}"
                    alt=""
                    class="img-thumbnail"
                    style="max-height: 200px;">
            </div>

            @error('photo')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>


        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-sm btn-primary">
                @if(isset($group) && $dealer->id)
                    @lang('messages.update')
                @else
                    @lang('messages.save')
                @endif
            </button>
            <a href="{{ route('dealers.index') }}" class="btn btn-sm btn-alt-primary">@lang('messages.go-to-list')</a>
        </div>
    </div>
</div>
