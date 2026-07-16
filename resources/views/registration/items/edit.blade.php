@extends('layouts.backend')

@section('content')
    <div class="block block-rounded col-md-12">
        <div class="block-header block-header-default">
            <h3 class="block-title">@lang('messages.edit-item')</h3>
        </div>
        <div class="block-content block-content-full">

            <form action="{{ route('itemRegistration.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="main_head_id">@lang('messages.main-heads')</label>
                        <select name="main_head_id" id="main-head"
                            class="form-control select2 form-select @error('main_head_id') is-invalid @enderror">
                            <option value="">@lang('messages.select-main-heads')</option>
                            @foreach ($mainHeads as $mainHead)
                                <option value="{{ $mainHead->id }}"
                                    {{ isset($item) && $item->main_head_id == $mainHead->id ? 'selected' : '' }}>
                                    {{ App::getLocale() === 'ur' ? $mainHead->name_ur ?? '-' : $mainHead->name_en ?? '-' }}
                                </option>
                            @endforeach
                        </select>

                        @error('main_head_id')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="control_head_id">@lang('messages.control-heads')</label>
                        <select name="control_head_id" id="control-head"
                            class="form-control select2 form-select @error('control_head_id') is-invalid @enderror">
                            <option value="">@lang('messages.select-control-heads')</option>

                            @foreach ($controlHeads as $controlHead)
                                <option value="{{ $controlHead->id }}"
                                    {{ isset($item) && $item->control_head_id == $controlHead->id ? 'selected' : '' }}>
                                    {{ App::getLocale() === 'ur' ? $controlHead->name_ur ?? '-' : $controlHead->name_en ?? '-' }}
                                </option>
                            @endforeach
                        </select>
                        @error('control_head_id')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="sub_head_id">@lang('messages.sub-heads')</label>
                        <select name="sub_head_id" id="sub-head"
                            class="form-control select2 form-select @error('sub_head_id') is-invalid @enderror">
                            <option value="">@lang('messages.select-sub-heads')</option>
                            @foreach ($subHeads as $subHead)
                                <option value="{{ $subHead->id }}"
                                    {{ isset($item) && $item->sub_head_id == $subHead->id ? 'selected' : '' }}>
                                    {{ App::getLocale() === 'ur' ? $subHead->name_ur ?? '-' : $subHead->name_en ?? '-' }}
                                </option>
                            @endforeach
                        </select>
                        @error('sub_head_id')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="sub_sub_head_id">@lang('messages.sub-sub-heads')</label>
                        <select name="sub_sub_head_id" id="sub-sub-head"
                            class="form-control select2 form-select @error('sub_sub_head_id') is-invalid @enderror">
                            <option value="">@lang('messages.select-sub-sub-heads')</option>
                            @foreach ($subSubHeads as $subSubHead)
                                <option value="{{ $subSubHead->id }}"
                                    {{ isset($item) && $item->sub_sub_head_id == $subSubHead->id ? 'selected' : '' }}>
                                    {{ App::getLocale() === 'ur' ? $subSubHead->name_ur ?? '-' : $subSubHead->name_en ?? '-' }}
                                </option>
                            @endforeach
                        </select>

                        @error('sub_sub_head_id')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="sub_sub_sub_head_id">@lang('messages.sub-sub-sub-heads')</label>
                        <select name="sub_sub_sub_head_id" id="sub-sub-sub-head"
                            class="form-control select2 form-select @error('sub_sub_sub_head_id') is-invalid @enderror">
                            <option value="">@lang('messages.select-sub-sub-sub-heads')</option>
                            @foreach ($subSubSubHeads as $subSubSubHead)
                                <option value="{{ $subSubSubHead->id }}"
                                    {{ isset($item) && $item->sub_sub_sub_head_id == $subSubSubHead->id ? 'selected' : '' }}>
                                    {{ App::getLocale() === 'ur' ? $subSubSubHead->name_ur ?? '-' : $subSubSubHead->name_en ?? '-' }}
                                </option>
                            @endforeach
                        </select>

                        @error('sub_sub_sub_head_id')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="measurement_unit_id">@lang('messages.unit')</label>
                        <select name="measurement_unit_id" id="measurement_unit_id"
                            class="form-control form-select select2 @error('measurement_unit_id') is-invalid @enderror">
                            <option value="">@lang('messages.main_party')</option>
                            @foreach ($units as $unit)
                                <option value="{{ $unit->id }}"
                                    {{ (old('measurement_unit_id') ?? $item->measurement_unit_id) == $unit->id ? 'selected' : '' }}>
                                    {{ App::getLocale() === 'ur' ? $unit->name_ur ?? '-' : $unit->name_en ?? '-' }}
                                </option>
                            @endforeach
                        </select>
                        @error('measurement_unit_id')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name_en" class="form-label">@lang('messages.name') @lang('messages.english')</label>
                        <input type="text" class="form-control" id="name_en" name="name_en"
                            value="{{ old('name_en', $item->name_en) }}" required autocomplete="off">
                        @error('name_en')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="name_ur" class="form-label">@lang('messages.name') @lang('messages.urdu')</label>
                        <input type="text" class="form-control input-urdu" id="name_ur" name="name_ur"
                            value="{{ old('name_ur', $item->name_ur) }}" required autocomplete="off">
                        @error('name_ur')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">

                        <div class="form-group mb-3">

                            <label for="image">@lang('messages.image')</label><br>

                            <!-- File Upload Input -->
                            <input type="file" name="item_image" id="item_image" class="form-control"
                                onchange="previewImage(this)">

                            <!-- Image Preview (Shows avatar if no image is selected) -->
                            <div id="imagePreview" class="mt-2">
                                {{--  <p class="mb-1">@lang('messages.image-preview')</p>  --}}
                                <img id="previewImg"
                                    src="{{ isset($item) && $item->item_image ? asset('storage/' . $item->item_image) : asset('images/No-Image-Placeholder.svg.png') }}"
                                    alt="" class="img-thumbnail" style="max-height: 200px;">
                            </div>

                            @error('item_image')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">@lang('messages.update')</button>
                        <a href="{{ route('itemRegistration.index') }}" class="btn btn-secondary">@lang('messages.go-to-list')</a>
                    </div>
                </div>
        </div>
        </form>
    </div>
    </div>
    <script>
        window.translations = {
            pleaseSelect: "@lang('messages.select-control-heads')",
            noData: "@lang('messages.no-control-head-found')",
            errorTitle: "@lang('messages.error-title')",
            errorText: "@lang('messages.control-head-fetch-failed')",
            loading: "@lang('messages.loading')",
            selectSubHead: "@lang('messages.select-sub-head')",
            selectSubSubHead: "@lang('messages.select-sub-sub-heads')",
            noSubHeads: "@lang('messages.no-sub-head-found')",
            subHeaderrorTitle: "@lang('messages.subHeaderror-title')",
            subHeaderrorText: "@lang('messages.sub-head-fetch-failed')",
        };
    </script>

    <script>
        var config = {
            routes: {
                getControlHeads: "{{ route('get.control.head.account', ['mainHead' => ':id']) }}",
                getSubHeads: "{{ route('get.sub.head.account', ['controlHead' => ':id']) }}",
                getSubSubHeads: "{{ route('get.sub.sub.head.account', ['subHead' => ':id']) }}",
                getDetailAccountCode: "{{ route('get.detail.account', ['code' => ':id']) }}",
            }
        };
    </script>

    <script src="{{ asset('js/detail-account.js') }}"></script>
@endsection
