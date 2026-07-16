@extends('layouts.backend')

@section('content')
<div class="content">
    <div class="block block-rounded">
        <div class="block-header">
            <h3 class="block-title">@lang('messages.create-permission')</h3>
        </div>
        <div class="block-content">
            <form action="{{ route('permissions.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="form-label">@lang('messages.name')</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-4">
                    <label class="form-label">@lang('messages.name') (EN)</label>
                    <input type="text" name="name_en" class="form-control" required>
                </div>
                <div class="mb-4">
                    <label class="form-label">@lang('messages.name') (UR)</label>
                    <input type="text" name="name_ur" class="form-control keyboardInput" required>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-sm btn-primary">@lang('messages.save')</button>
                    <a href="{{ route('permissions.index') }}" class="btn btn-sm btn-alt-primary">@lang('messages.go-to-list')</a>
                </div>
                {{--  <button type="submit" class="btn btn-primary">@lang('messages.save')</button>
                <a href="{{ route('permissions.index') }}" class="btn btn-secondary">@lang('messages.cancel')</a>  --}}
            </form>
        </div>
    </div>
</div>
@endsection
