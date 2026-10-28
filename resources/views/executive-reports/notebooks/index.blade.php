@extends('layouts.backend')

@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <h1 class="h3 fw-bold mb-0">@lang('menu.notebooks')</h1>
            </div>
        </div>
    </div>

    <div class="content">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('executive-reports.notebooks.index') }}" method="GET" class="block block-rounded">
            <div class="block-content">
                <div class="row g-2 align-items-end">
                    <div class="col-md-8">
                        <label for="notebook-search" class="form-label">@lang('messages.search')</label>
                        <input id="notebook-search" name="search" type="search" class="form-control"
                            value="{{ $search }}" placeholder="@lang('messages.search_notebooks_placeholder')">
                    </div>
                    <div class="col-md-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">@lang('messages.search')</button>
                        @if ($search !== '')
                            <a href="{{ route('executive-reports.notebooks.index') }}"
                                class="btn btn-secondary">@lang('messages.clear')</a>
                        @endif
                    </div>
                </div>
            </div>
        </form>

        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h2 class="block-title">@lang('messages.create_notebook')</h2>
            </div>
            <div class="block-content">
                <form action="{{ route('executive-reports.notebooks.store') }}" method="POST" class="row g-3">
                    @csrf
                    <div class="col-md-5">
                        <label for="name_en" class="form-label">@lang('messages.name') (@lang('messages.english'))</label>
                        <input id="name_en" name="name_en" type="text" class="form-control"
                            value="{{ old('name_en') }}" maxlength="255" required>
                    </div>
                    <div class="col-md-5">
                        <label for="name_ur" class="form-label">@lang('messages.name') (@lang('messages.urdu'))</label>
                        <input id="name_ur" name="name_ur" type="text" class="form-control" dir="rtl"
                            value="{{ old('name_ur') }}" maxlength="255" required>
                    </div>
                    <div class="col-md-2">
                        <label for="color" class="form-label">@lang('messages.color')</label>
                        <input id="color" name="color" type="color" class="form-control form-control-color w-100"
                            value="{{ old('color', '#fff3cd') }}" required>
                    </div>
                    <div class="col-md-12">
                        <label for="description" class="form-label">@lang('messages.description')</label>
                        <textarea id="description" name="description" class="form-control" rows="2" maxlength="5000">{{ old('description') }}</textarea>
                    </div>

                    <div class="col-12 mb-3">
                        <button type="submit" class="btn btn-primary">@lang('messages.create_notebook')</button>
                    </div>
                </form>
            </div>
        </div>

        @if ($notebooks->isEmpty())
            <div class="alert alert-info">@lang('messages.no_notebooks')</div>
        @else
            <div class="row g-3">
                @foreach ($notebooks as $notebook)
                    <div class="col-md-6 col-xl-4">
                        <div class="block block-rounded h-100 mb-0" style="border-top: 5px solid {{ $notebook->color }}">
                            <div class="block-header block-header-default">
                                <h2 class="block-title" dir="{{ app()->getLocale() === 'ur' ? 'rtl' : 'ltr' }}">
                                    {{ app()->getLocale() === 'ur' ? ($notebook->name_ur ?: $notebook->name_en) : $notebook->name_en }}
                                </h2>
                            </div>
                            <div class="block-content">
                                <div class="mb-3">
                                    {{-- <p class="text-muted mb-2">
                                        {{ $notebook->description
                                            ? \Illuminate\Support\Str::limit($notebook->description, 120)
                                            : '—' }}
                                    </p> --}}
                                    @if ($notebook->description)
                                        <button type="button" class="btn btn-sm btn-alt-secondary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#notebook-description-{{ $notebook->id }}">
                                            @lang('messages.view_description')
                                        </button>
                                    @endif
                                </div>

                                <form id="update-notebook-{{ $notebook->id }}"
                                    action="{{ route('executive-reports.notebooks.update', $notebook) }}" method="POST"
                                    class="row g-2">
                                    @csrf
                                    @method('PUT')
                                    <div class="col-12">
                                        <label for="name-en-{{ $notebook->id }}" class="form-label">@lang('messages.name') (@lang('messages.english'))</label>
                                        <input id="name-en-{{ $notebook->id }}" name="name_en" type="text"
                                            class="form-control" value="{{ $notebook->name_en }}" maxlength="255" required>
                                    </div>
                                    <div class="col-12">
                                        <label for="name-ur-{{ $notebook->id }}" class="form-label">@lang('messages.name') (@lang('messages.urdu'))</label>
                                        <input id="name-ur-{{ $notebook->id }}" name="name_ur" type="text"
                                            class="form-control" dir="rtl" value="{{ old('name_ur', $notebook->name_ur) }}"
                                            maxlength="255" required>
                                    </div>
                                    <div class="col-12">
                                        <label for="description-{{ $notebook->id }}" class="form-label">@lang('messages.description')</label>
                                        <textarea id="description-{{ $notebook->id }}" name="description" class="form-control" rows="2"
                                            maxlength="5000">{{ $notebook->description }}</textarea>
                                    </div>
                                    <div class="col-12">
                                        <label for="color-{{ $notebook->id }}" class="form-label">@lang('messages.color')</label>
                                        <input id="color-{{ $notebook->id }}" name="color" type="color"
                                            class="form-control form-control-color" value="{{ $notebook->color }}" required>
                                    </div>
                                </form>
                                <div class="col-12 d-flex gap-2 mb-3">
                                    <button type="submit" form="update-notebook-{{ $notebook->id }}"
                                        class="btn btn-sm btn-primary">@lang('messages.update')</button>
                                    <form action="{{ route('executive-reports.notebooks.destroy', $notebook) }}"
                                        method="POST"
                                        onsubmit="return confirm('{{ __('messages.confirm_delete_notebook') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">@lang('messages.delete')</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if ($notebook->description)
                        <div class="modal fade" id="notebook-description-{{ $notebook->id }}" tabindex="-1"
                            aria-labelledby="notebook-description-title-{{ $notebook->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="notebook-description-title-{{ $notebook->id }}">
                                            @lang('messages.description') — {{ app()->getLocale() === 'ur' ? ($notebook->name_ur ?: $notebook->name_en) : $notebook->name_en }}
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="@lang('messages.close')"></button>
                                    </div>
                                    <div class="modal-body" style="white-space: pre-wrap;">{{ $notebook->description }}</div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">@lang('messages.close')</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif
    </div>
@endsection
