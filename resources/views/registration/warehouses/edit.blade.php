@extends('layouts.backend')

@section('content')
<div class="block block-rounded col-md-12">
    <div class="block-header block-header-default">
        <h3 class="block-title">@lang(key: 'messages.edit-warehouse')</h3>
    </div>
    <div class="block-content block-content-full">

        <form action="{{ route('warehouses.update', $warehouse->id) }}" method="POST">
        @csrf
        @method('PUT')
            
            <div class="row">
                <div class="col-md-3 mx-auto">
                    <div class="form-group mb-4">
                        <label for="title_en">@lang(key: 'messages.name') (EN)</label>
                        <input type="text" name="title_en" class="form-control" maxlength="100" value="{{ $warehouse->title_en }}" value="{{ old('title_en', $warehouse->title_en ?? '') }}" autocomplete="off">
                        @error('title_en')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-4 text-end">
                        <label for="title_ur"> @lang('messages.name') (اردو)</label>
                        <input type="text" name="title_ur" class="form-control keyboardInput" maxlength="100" id="title_ur" value="{{ $warehouse->title_ur }}" dir="rtl" data-keyboard-id="keyboard-name-ur" value="{{ old('title_ur', $warehouse->title_ur ?? '') }}" autocomplete="off">
                        <div id="keyboard-name-ur" class="simple-keyboard mt-2 keyboard-container" style="display: none;"></div>
                        @error('title_ur')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-primary">@lang('messages.update')</button>
                <a href="{{ route('warehouses.index') }}" class="btn btn-sm btn-alt-primary">@lang('messages.go-to-list')</a>
            </div>
        </form>
    </div>
</div>
@endsection