@extends('layouts.backend')

@section('content')
    <div class="block block-rounded col-md-12">
        <div class="block-header block-header-default">
            <h3 class="block-title">Edit Control Head</h3>
        </div>
        <div class="block-content block-content-full">

            <form action="{{ route('control-heads.update', $controlHeads->id) }}" method="POST">
                @csrf
                @method('PUT')
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
                        @foreach ($mainHeads as $controlHead)
                            <option value="{{ $controlHead->id }}"
                                {{ isset($controlHeads) && $controlHeads->main_head_id == $controlHead->id ? 'selected' : '' }}>
                                {{ App::getLocale() === 'ur' ? $controlHead->name_ur ?? '-' : $controlHead->name_en ?? '-' }}
                            </option>
                        @endforeach
                    </select>

                    @error('main_head_id')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label for="name_en" class="form-label">@lang('messages.name') @lang('messages.english')</label>
                    <input type="text" class="form-control" id="name_en" name="name_en"
                        value="{{ old('name_en', $controlHeads->name_en) }}" required autocomplete="off">
                    @error('name_en')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="name_ur" class="form-label">@lang('messages.name') @lang('messages.urdu')</label>
                    <input type="text" class="form-control input-urdu" id="name_ur" name="name_ur"
                        value="{{ old('name_ur', $controlHeads->name_ur) }}" required autocomplete="off">
                    @error('name_ur')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">@lang('messages.update')</button>
                <a href="{{ route('control-heads.index') }}" class="btn btn-secondary">@lang('messages.go-to-list')</a>
            </form>
        </div>
    </div>
    <script src="{{ asset('js/detail-account.js') }}"></script>
@endsection
