@extends('layouts.backend')

@section('content')
<div class="block block-rounded col-md-12">
    <div class="block-header block-header-default">
        <h3 class="block-title">@lang('messages.create-user')</h3>
    </div>
    <div class="block-content block-content-full">
        <div class="row">
            <div class="col-sm-6 col-md-6 mx-auto">
                <form action="{{ route('users.store') }}"  enctype="multipart/form-data" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label" for="name_en">@lang('messages.name') (EN)<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name_en" maxlength="100" name="name_en" value="{{ old('name_en') }}">
                        @error('name_en')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label d-block text-end" for="name_ur"> نام (اردو)<span class="text-danger">*</span></label>
                        <input type="text" class="form-control keyboardInput" maxlength="100" id="name_ur" dir="rtl" name="name_ur" value="{{ old('name_ur') }}" autocomplete="off">
                        @error('name_ur')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label" for="father_name_en">@lang('messages.father-name') (EN) <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="father_name_en" name="father_name_en" maxlength="100" value="{{ old('father_name_en') }}" >
                        @error('father_name_en')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label d-block text-end" for="father_name_ur">
                            والد کا نام (اردو) <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control keyboardInput" id="father_name_ur" maxlength="100" dir="rtl" name="father_name_ur" value="{{ old('father_name_ur') }}"  autocomplete="off">
                        @error('father_name_ur')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Shared keyboard container -->
                    {{--  <div id="keyboard" class="simple-keyboard mt-2" style="display: none;"></div>  --}}

                    <div class="mb-3">
                        <label>@lang('messages.email')<span class="text-danger">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" maxlength="100" class="form-control">
                        @error('email')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label>@lang('messages.password')<span class="text-danger">*</span></label>
                        <input type="password" name="password" maxlength="10" class="form-control">
                        @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label>@lang('messages.confirm-password')<span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" maxlength="10" class="form-control">
                        @error('password_confirmation')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{--  <div class="form-group mb-4">
                        <label for="avatar">@lang('messages.user-photo')</label><br>
                        <input type="file" name="avatar" class="form-control-file">
                        @error('avatar')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>  --}}

                    <div class="form-group mb-4">
                        {{--  <label for="avatar">@lang('messages.avatar')</label><br>  --}}

                        <!-- File Upload Input -->
                        <input type="file"
                            name="avatar"
                            id="avatar"
                            class="form-control"
                            onchange="previewImage(this)">

                        <!-- Image Preview (Shows avatar if no image is selected) -->
                        <div id="imagePreview" class="mt-2">
                            {{--  <p class="mb-1">@lang('messages.image-preview')</p>  --}}
                            <img id="previewImg"
                                src="{{ isset($user) && $user->avatar ? asset('storage/' . $$user->avatar) : asset('images/No-Image-Placeholder.svg.png') }}"
                                alt=""
                                class="img-thumbnail"
                                style="max-height: 200px;">
                        </div>

                        @error('avatar')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-sm btn-primary">@lang('messages.save')</button>
                        <a href="{{ route('users.index') }}" class="btn btn-sm btn-alt-primary">@lang('messages.go-to-list')</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
