@extends('layouts.backend')

{{-- Styles --}}
@section('content')
    <div class="block block-rounded col-md-12">
        <div class="block-header block-header-default">
            <h3 class="block-title">@lang('messages.add-control-heads')</h3>
        </div>
        <div class="block-content block-content-full">
            <form action="{{ route('control-heads.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-lg-4">
                        <p class="fs-sm text-muted">
                            @lang('messages.both-names')
                        </p>
                    </div>
                    <div class="col-lg-8 col-xl-5">

                        <div class="col-md-6 mb-3">
                            <label for="main_head_id">@lang('messages.main-heads')</label>
                            <select name="main_head_id" id="main_head_id"
                                class="form-control form-select @error('main_head_id') is-invalid @enderror">
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
                            <a href="{{ route('control-heads.index') }}" class="btn btn-dark">@lang('messages.go-to-list')</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script src="{{ asset('js/detail-account.js') }}"></script>
@endsection
