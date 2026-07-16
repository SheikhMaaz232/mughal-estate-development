<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="form-group mb-2">
            <label for="group_code">@lang('messages.group-code')</label>
            <input type="text" name="group_code" class="form-control" maxlength="100" value="{{ old('group_code', $group->group_code ?? '') }}" autocomplete="off">
            @error('group_code')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group mb-4">
            <label for="name">@lang('messages.name') (EN)</label>
            <input type="text" name="name_en" class="form-control" maxlength="100" value="{{ old('name_eng', $group->name_eng ?? '') }}" autocomplete="off">
            @error('name_eng')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-4 text-end">
            <label for="name_ur">@lang('messages.name') (اردو)</label>
            <input type="text" name="name_ur" class="form-control keyboardInput" maxlength="100" id="name_ur" dir="rtl" data-keyboard-id="keyboard-name-ur" value="{{ old('name_ur', $group->name_ur ?? '') }}" autocomplete="off">
            <div id="keyboard-name-ur" class="simple-keyboard mt-2 keyboard-container" style="display: none;"></div>
            @error('name_ur')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-4">
            <label for="description_eng">@lang('messages.description') (EN)</label>
            <textarea name="description_en" class="form-control" autocomplete="off">{{ old('description_eng', $group->description_eng ?? '') }}</textarea>
            @error('description_eng')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-4 text-end">
            <label for="description_ur">@lang('messages.description') (اردو)</label>
            <textarea name="description_ur" class="form-control keyboardInput" id="description_ur" dir="rtl" data-keyboard-id="keyboard-description-ur" autocomplete="off">{{ old('description_ur', $group->description_ur ?? '') }}</textarea>
            <div id="keyboard-description-ur" class="simple-keyboard mt-2 keyboard-container" style="display: none;"></div>
            @error('description_ur')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-4">
            <label for="address_eng">@lang('messages.address') (EN)</label>
            <input type="text" name="address_en" class="form-control" maxlength="200" value="{{ old('address_eng', $group->address_eng ?? '') }}" autocomplete="off">
            @error('address_eng')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-4 text-end">
            <label for="address_ur">@lang('messages.address') (اردو)</label>
            <input type="text" name="address_ur" class="form-control keyboardInput" maxlength="100" dir="rtl" id="address_ur" data-keyboard-id="keyboard-address-ur" value="{{ old('address_ur', $group->address_ur ?? '') }}" autocomplete="off">
            <div id="keyboard-address-ur" class="simple-keyboard mt-2 keyboard-container" style="display: none;"></div>
            @error('address_ur')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group mb-4">
            <label for="image">@lang('messages.group-logo')</label>
            <input type="file" name="image" class="form-control-file">
            @error('image')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-sm btn-primary">
                @if(isset($group) && $group->id)
                    @lang('messages.update')
                @else
                    @lang('messages.save')
                @endif
            </button>
            <a href="{{ route('groups.index') }}" class="btn btn-sm btn-alt-primary">@lang('messages.go-to-list')</a>
        </div>
    </div>
</div>
