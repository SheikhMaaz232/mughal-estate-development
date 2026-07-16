@extends('layouts.backend')

@section('content')
    <div class="block block-rounded col-md-12">
        <div class="block-header block-header-default">
            <h3 class="block-title">@lang('messages.edit-sub-heads')</h3>
        </div>
        <div class="block-content block-content-full">

            <form action="{{ route('sub-heads.update', $subHeads->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-lg-4">
                        <p class="fs-sm text-muted">
                            @lang('messages.both-sub-head-names')
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
                                        {{ isset($subHeads) && $subHeads->main_head_id == $mainHead->id ? 'selected' : '' }}>
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
                                @foreach ($controlHeads as $controlHead)
                                    <option value="{{ $controlHead->id }}"
                                        {{ isset($subHeads) && $subHeads->control_head_id == $controlHead->id ? 'selected' : '' }}>
                                        {{ App::getLocale() === 'ur' ? $controlHead->name_ur ?? '-' : $controlHead->name_en ?? '-' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('control_head_id')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="name_en" class="form-label">@lang('messages.name') @lang('messages.english')</label>
                            <input type="text" class="form-control" id="name_en" name="name_en"
                                value="{{ old('name_en', $subHeads->name_en) }}" required autocomplete="off">
                            @error('name_en')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="name_ur" class="form-label">@lang('messages.name') @lang('messages.urdu')</label>
                            <input type="text" class="form-control input-urdu" id="name_ur" name="name_ur"
                                value="{{ old('name_ur', $subHeads->name_ur) }}" required autocomplete="off">
                            @error('name_ur')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">@lang('messages.update')</button>
                        <a href="{{ route('sub-heads.index') }}" class="btn btn-secondary">@lang('messages.go-to-list')</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>
        window.customTranslations = {
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
    <script src="{{ asset('js/jquery/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('js/jquery/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/detail-account.js') }}"></script>
    <script>
        var config = {
            routes: {
                getControlHeads: "{{ route('get.control.head.account', ['mainHead' => ':id']) }}", // uses named route
                getSubHeads: "{{ route('get.sub.head.account', ['controlHead' => ':id']) }}"
            }
        };
    </script>
@endsection
