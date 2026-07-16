@extends('layouts.backend')

@section('content')
    <div class="block block-rounded col-md-12">
        <div class="block-header block-header-default">
            <h3 class="block-title">@lang('messages.edit-sub-sub-sub-heads')</h3>
        </div>
        <div class="block-content block-content-full">

            <form action="{{ route('sub-sub-sub-heads.update', $subSubSubHead->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-lg-4">
                        <p class="fs-sm text-muted">
                            @lang('messages.both-sub-sub-sub-head-names')
                        </p>
                    </div>
                    <div class="col-lg-8 col-xl-5">

                        <div class="col-md-6 mb-3">
                            <label for="main_head_id">@lang('messages.main-heads')</label>
                            <select name="main_head_id" id="main-head"
                                class="form-control form-select @error('main_head_id') is-invalid @enderror">
                                <option value="">@lang('messages.select-main-heads')</option>
                                @foreach ($mainHeads as $mainHead)
                                    <option value="{{ $mainHead->id }}"
                                        {{ isset($subSubSubHead) && $subSubSubHead->main_head_id == $mainHead->id ? 'selected' : '' }}>
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
                                class="form-control form-select select2 @error('control_head_id') is-invalid @enderror">
                                <option value="">@lang('messages.select-control-heads')</option>

                                @foreach ($controlHeads as $controlHead)
                                    <option value="{{ $controlHead->id }}"
                                        {{ isset($subSubSubHead) && $subSubSubHead->control_head_id == $controlHead->id ? 'selected' : '' }}>
                                        {{ App::getLocale() === 'ur' ? $controlHead->name_ur ?? '-' : $controlHead->name_en ?? '-' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('control_head_id')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="sub_head_id">@lang('messages.sub-heads')</label>
                            <select name="sub_head_id" id="sub-head"
                                class="form-control form-select select2 @error('sub_head_id') is-invalid @enderror">
                                <option value="">@lang('messages.select-sub-heads')</option>
                                @foreach ($subHeads as $subHead)
                                    <option value="{{ $subHead->id }}"
                                        {{ isset($subSubSubHead) && $subSubSubHead->sub_head_id == $subHead->id ? 'selected' : '' }}>
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
                                class="form-control form-select select2 @error('sub_sub_head_id') is-invalid @enderror">
                                <option value="">@lang('messages.select-sub-sub-heads')</option>
                                @foreach ($subSubHeads as $subSubHead)
                                    <option value="{{ $subSubHead->id }}"
                                        {{ isset($subSubSubHead) && $subSubSubHead->sub_sub_head_id == $subSubHead->id ? 'selected' : '' }}>
                                        {{ App::getLocale() === 'ur' ? $subSubHead->name_ur ?? '-' : $subSubHead->name_en ?? '-' }}
                                    </option>
                                @endforeach
                            </select>

                            @error('sub_sub_head_id')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="project_id">@lang('messages.projects')</label>
                            <select name="project_id" id="project_id"
                                class="form-control select2 form-select @error('project_id') is-invalid @enderror">
                                <option value="">@lang('messages.select-project')</option>
                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}"
                                        {{ (old('project_id') ?? $subSubSubHead->project_id) == $project->id ? 'selected' : '' }}>
                                        {{ App::getLocale() === 'ur' ? $project->name_ur ?? '-' : $project->name_en ?? '-' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('project_id')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="name_en" class="form-label">@lang('messages.name') @lang('messages.english')</label>
                            <input type="text" class="form-control" id="name_en" name="name_en"
                                value="{{ old('name_en', $subSubSubHead->name_en) }}" required autocomplete="off">
                            @error('name_en')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="name_ur" class="form-label">@lang('messages.name') @lang('messages.urdu')</label>
                            <input type="text" class="form-control input-urdu" id="name_ur" name="name_ur"
                                value="{{ old('name_ur', $subSubSubHead->name_ur) }}" required autocomplete="off">
                            @error('name_ur')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">@lang('messages.update')</button>
                        <a href="{{ route('sub-sub-sub-heads.index') }}" class="btn btn-secondary">@lang('messages.go-to-list')</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>
        window.customTranslations = {
            pleaseSelect: "{{ __('messages.select-control-heads') }}",
            noData: "{{ __('messages.no-control-head-found') }}",
            errorTitle: "{{ __('messages.error-title') }}",
            errorText: "{{ __('messages.control-head-fetch-failed') }}",
            loading: "{{ __('messages.loading') }}",
            selectSubHead: "{{ __('messages.select-sub-head') }}",
            selectSubSubHead: "{{ __('messages.select-sub-sub-heads') }}",
            selectSubSubSubHead: "{{ __('messages.select-sub-sub-sub-heads') }}",
            noSubHeads: "{{ __('messages.no-sub-head-found') }}",
            noSubSubSubHeads: "{{ __('messages.no-sub-sub-sub-head-found') }}",
            subHeaderrorTitle: "{{ __('messages.subHeaderror-title') }}",
            subHeaderrorText: "{{ __('messages.sub-head-fetch-failed') }}"
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
    <script src="{{ asset('js/subSubSubHead.js') }}"></script>
@endsection
