@extends('layouts.backend')

@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">@lang('messages.list-of-sub-heads')</h2>
                </div>
                <a href="{{ route('sub-heads.create') }}" class="btn btn-sm btn-primary">@lang('messages.add-sub-heads')</a>
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
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form method="GET" action="{{ route('sub-heads.index') }}">
            <div class="row">
                <div class="col-lg-6 mb-3">
                    <label for="main_head_id">@lang('messages.main-heads')</label>
                    <select name="main_head_id[]" id="main_head_id"
                        class="form-control form-select select2 @error('main_head_id') is-invalid @enderror" multiple
                        placeholder="@lang('messages.select-main-heads')">
                        @foreach ($mainHeads as $mainHead)
                            <option value="{{ $mainHead->id }}"
                                {{ is_array(request('main_head_id')) && in_array($mainHead->id, request('main_head_id')) ? 'selected' : '' }}>
                                {{ App::getLocale() === 'ur' ? $mainHead->name_ur ?? '-' : $mainHead->name_en ?? '-' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-6 mb-3">
                    <label for="control_head_id">@lang('messages.control-heads')</label>
                    <select name="control_head_id[]" id="control_head_id"
                        class="form-control form-select select2 @error('control_head_id') is-invalid @enderror" multiple
                        placeholder="@lang('messages.select-control-heads')">
                        @foreach ($searchControlHeads as $controlHead)
                            <option value="{{ $controlHead->id }}"
                                {{ is_array(request('control_head_id')) && in_array($controlHead->id, request('control_head_id')) ? 'selected' : '' }}>
                                {{ App::getLocale() === 'ur' ? $controlHead->name_ur ?? '-' : $controlHead->name_en ?? '-' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6 mb-3">
                    <label >@lang('messages.name')</label>
                    <input type="text" class="form-control" name="search" placeholder="@lang('messages.search-SubHead')"
                        value="{{ request('search') }}">
                </div>
                <div class="col-lg-6 mb-3 text-end" style="margin-top: 25px;">
                    <button class="btn btn-primary" type="submit">@lang('messages.search')</button>

                    @if (request()->hasAny(['search', 'main_head_id', 'control_head_id']))
                        <a href="{{ route('sub-heads.index') }}" class="btn btn-secondary">@lang('messages.clear')</a>
                    @endif
                </div>
            </div>
        </form>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th>@lang('messages.main-heads')</th>
                    <th>@lang('messages.control-heads')</th>
                    <th>@lang('messages.name') (EN)</th>
                    <th>@lang('messages.name') (UR)</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($subHeads as $index => $subHead)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>
                            {{ App::getLocale() === 'ur' ? $subHead->mainHead->name_ur ?? '-' : $subHead->mainHead->name_en ?? '-' }}
                        </td>
                        <td>
                            {{ App::getLocale() === 'ur' ? $subHead->controlHead->name_ur ?? '-' : $subHead->controlHead->name_en ?? '-' }}
                        </td>
                        <td>{{ $subHead->name_en }}</td>
                        <td>{{ $subHead->name_ur }}</td>
                        <td class="text-center">
                            <div class="btn-group">

                                <a href="{{ route('sub-heads.edit', $subHead->id) }}"
                                    class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled" data-bs-toggle="tooltip"
                                    aria-label="Edit sub Head" data-bs-original-title="Edit sub Head"> <i
                                        class="fa fa-fw fa-pencil-alt"></i></a>

                                <form method="POST" action="{{ route('sub-heads.destroy', $subHead->id) }}"
                                    class="d-inline-block delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                        class="btn btn-sm btn-alt-danger js-bs-tooltip-enabled btn-delete"
                                        data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
                                        <i class="fa fa-fw fa-times text-danger"></i>
                                    </button>

                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $subHeads->links() }}
        </div>
    </div>
@endsection
