@extends('layouts.backend')

@section('content')
    <div class="block block-rounded col-md-12">
        <div class="block-header block-header-default">
            <h3 class="block-title">@lang('messages.add-sub-sub-heads')</h3>
        </div>
        <div class="block-content block-content-full">
            <form action="{{ route('sub-sub-heads.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-lg-4">
                        <p class="fs-sm text-muted">
                            @lang('messages.both-sub-sub-head-names')
                        </p>
                    </div>
                    <div class="col-lg-8 col-xl-5">

                        <div class="col-md-6 mb-3">
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

                        <div class="col-md-6 mb-3">
                            <label for="control_head_id">@lang('messages.control-heads')</label>
                            <select name="control_head_id" id="control-head"
                                class="form-control form-select @error('control_head_id') is-invalid @enderror">
                                <option value="">@lang('messages.select-control-heads')</option>

                                @if (old('control_head_id') && $selectedControlHead)
                                    <option value="{{ $selectedControlHead->id }}" selected>
                                        {{ app()->getLocale() == 'ur' ? $selectedControlHead->name_ur : $selectedControlHead->name_en }}
                                    </option>
                                @endif
                            </select>
                            @error('control_head_id')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="sub_head_id">@lang('messages.sub-heads')</label>
                            <select name="sub_head_id" id="sub-head"
                                class="form-control form-select @error('sub_head_id') is-invalid @enderror">
                                <option value="">@lang('messages.select-sub-heads')</option>

                                @if (old('sub_head_id') && $selectedSubHead)
                                    <option value="{{ $selectedSubHead->id }}" selected>
                                        {{ app()->getLocale() == 'ur' ? $selectedSubHead->name_ur : $selectedSubHead->name_en }}
                                    </option>
                                @endif
                            </select>
                            @error('sub_head_id')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="name_en">@lang('messages.name') @lang('messages.english')</label>
                            <input type="text" class="form-control" id="name_en" name="name_en"
                                placeholder="@lang('messages.enter-name-english')" autocomplete="off" value="{{ old('name_en') }}">
                            @error('name_en')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="name_ur">@lang('messages.name') @lang('messages.urdu')</label>
                            <input type="text" class="form-control input-urdu keyboardInput" id="name_ur"
                                name="name_ur" placeholder="نام درج کریں" autocomplete="off" value="{{ old('name_ur') }}">
                            @error('name_ur')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">@lang('messages.save')</button>
                            <a href="{{ route('sub-sub-heads.index') }}" class="btn btn-dark">@lang('messages.go-to-list')</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        window.customTranslations= {
            pleaseSelect: "@lang('messages.select-control-heads')",
            noData: "@lang('messages.no-control-head-found')",
            errorTitle: "@lang('messages.error-title')",
            errorText: "@lang('messages.control-head-fetch-failed')",
            loading: "@lang('messages.loading')",
            selectSubHead: "@lang('messages.select-sub-head')",
            noSubHeads: "@lang('messages.no-sub-head-found')",
            subHeaderrorTitle: "@lang('messages.subHeaderror-title')",
            subHeaderrorText: "@lang('messages.sub-head-fetch-failed')",
        };
    </script>
    
    <script src="{{ asset('js/detail-account.js') }}"></script>

    <script>
        var config = {
            routes: {
                getControlHeads: "{{ route('get.control.head.account', ['mainHead' => ':id']) }}",
                getSubHeads: "{{ route('get.sub.head.account', ['controlHead' => ':id']) }}"
            }
        };

    </script>
@endsection
