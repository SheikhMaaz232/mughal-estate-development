@extends('layouts.backend')

@section('content')
    <div class="block block-rounded col-md-12">
        <div class="block-header block-header-default">
            <h3 class="block-title">@lang('messages.add-sub-sub-sub-heads')</h3>
        </div>
        <div class="block-content block-content-full">
            <form action="{{ route('sub-sub-sub-heads.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-lg-4">
                        <p class="fs-sm text-muted">
                            @lang('messages.both-sub-sub-sub-head-names')
                        </p>
                    </div>
                    <div class="col-lg-8 col-xl-5">

                        <div class="col-md-12 mb-3">
                            <label for="main_head_id">@lang('messages.main-heads')</label>
                            <select name="main_head_id" id="main-head"
                                class="form-control form-select @error('main_head_id') is-invalid @enderror main-head">
                                <option value="">@lang('messages.select-main-heads')</option>
                                @foreach ($mainHeads as $mainHead)
                                    <option value="{{ $mainHead->id }}"
                                        {{ old('main_head_id') == $mainHead->id ? 'selected' : '' }}>
                                        {{ App::getLocale() === 'ur' ? $mainHead->name_ur ?? '-' : $mainHead->name_en ?? '-' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('main_head_id')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="control_head_id">@lang('messages.control-heads')</label>
                            <select name="control_head_id" id="control-head" data-old="{{ old('control_head_id') }}"
                                class="form-control form-select select2 @error('control_head_id') is-invalid @enderror">
                                <option value="">@lang('messages.select-control-heads')</option>

                            </select>
                            @error('control_head_id')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="sub_head_id">@lang('messages.sub-heads')</label>
                            <select name="sub_head_id" id="sub-head" data-old="{{ old('sub_head_id') }}"
                                class="form-control form-select select2 @error('sub_head_id') is-invalid @enderror">
                                <option value="">@lang('messages.select-sub-heads')</option>

                            </select>
                            @error('sub_head_id')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="sub_sub_head_id">@lang('messages.sub-sub-heads')</label>
                            <select name="sub_sub_head_id" id="sub-sub-head" data-old="{{ old('sub_sub_head_id') }}"
                                class="form-control form-select select2 @error('sub_sub_head_id') is-invalid @enderror">
                                <option value="">@lang('messages.select-sub-sub-heads')</option>

                            </select>
                            @error('sub_sub_head_id')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="project_id">@lang('messages.projects')</label>
                            <select name="project_id[]" id="project_id"
                                class="form-control select2 custom-select form-select @error('project_id') is-invalid @enderror" multiple>
                                <option value="">@lang('messages.select-project')</option>
                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}"
                                        {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                        {{ App::getLocale() === 'ur' ? $project->name_ur ?? '-' : $project->name_en ?? '-' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('project_id')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="name_en">@lang('messages.name') @lang('messages.english')</label>
                            <input type="text" class="form-control" id="name_en" name="name_en"
                                placeholder="@lang('messages.enter-name-english')" autocomplete="off" value="{{ old('name_en') }}">
                            @error('name_en')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="name_ur">@lang('messages.name') @lang('messages.urdu')</label>
                            <input type="text" class="form-control input-urdu keyboardInput" id="name_ur"
                                name="name_ur" placeholder="نام درج کریں" autocomplete="off" value="{{ old('name_ur') }}">
                            @error('name_ur')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">@lang('messages.save')</button>
                            <a href="{{ route('sub-sub-sub-heads.index') }}" class="btn btn-dark">@lang('messages.go-to-list')</a>
                        </div>
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
