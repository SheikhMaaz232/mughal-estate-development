@extends('layouts.backend')

@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">@lang('messages.list-of-items')</h2>
                </div>
                <a href="{{ route('itemRegistration.create') }}" class="btn btn-sm btn-primary">@lang('messages.add-item')</a>
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

        <form method="GET" action="{{ route('itemRegistration.index') }}">
            <div class="row">
                <div class="col-lg-6 mb-3">
                    <label for="main_head_id">@lang('messages.main-heads')</label>
                    <select name="main_head_id[]" id="main_head_id"
                        class="form-control form-select select2 @error('main_head_id') is-invalid @enderror" multiple>
                        @foreach ($mainHeads as $mainHead)
                            <option value="{{ $mainHead->id }}"
                                {{ collect(request('main_head_id'))->contains($mainHead->id) ? 'selected' : '' }}>
                                {{ App::getLocale() === 'ur' ? $mainHead->name_ur ?? '-' : $mainHead->name_en ?? '-' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-6 mb-3">
                    <label for="control_head_id">@lang('messages.control-heads')</label>
                    <select name="control_head_id[]" id="control_head_id"
                        class="form-control form-select select2 @error('control_head_id') is-invalid @enderror" multiple>
                        @foreach ($searchControlHeads as $controlHead)
                            <option value="{{ $controlHead->id }}"
                                {{ collect(request('control_head_id'))->contains($controlHead->id) ? 'selected' : '' }}>
                                {{ App::getLocale() === 'ur' ? $controlHead->name_ur ?? '-' : $controlHead->name_en ?? '-' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6 mb-3">
                    <label for="sub_head_id">@lang('messages.sub-heads')</label>
                    <select name="sub_head_id[]" id="sub_head_id"
                        class="form-control form-select select2 @error('sub_head_id') is-invalid @enderror" multiple>
                        @foreach ($searchSubHeads as $subHead)
                            <option value="{{ $subHead->id }}"
                                {{ collect(request('sub_head_id'))->contains($subHead->id) ? 'selected' : '' }}>
                                {{ App::getLocale() === 'ur' ? $subHead->name_ur ?? '-' : $subHead->name_en ?? '-' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-6 mb-3">
                    <label for="sub_sub_head_id">@lang('messages.sub-sub-heads')</label>
                    <select name="sub_sub_head_id[]" id="sub_sub_head_id"
                        class="form-control form-select select2 @error('sub_sub_head_id') is-invalid @enderror" multiple>
                        @foreach ($searchSubSubHeads as $subSubHead)
                            <option value="{{ $subSubHead->id }}"
                                {{ collect(request('sub_sub_head_id'))->contains($subSubHead->id) ? 'selected' : '' }}>
                                {{ App::getLocale() === 'ur' ? $subSubHead->name_ur ?? '-' : $subSubHead->name_en ?? '-' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row">

                <div class="col-lg-6 mb-3">
                    <label for="sub_sub_sub_head_id">@lang('messages.sub-sub-sub-heads')</label>
                    <select name="sub_sub_sub_head_id[]" id="sub_sub_sub_head_id"
                        class="form-control form-select select2 @error('sub_sub_sub_head_id') is-invalid @enderror"
                        multiple>
                        @foreach ($searchSubSubSubHeads as $subSubSubHead)
                            <option value="{{ $subSubSubHead->id }}"
                                {{ collect(request('sub_sub_head_id'))->contains($subSubSubHead->id) ? 'selected' : '' }}>
                                {{ App::getLocale() === 'ur' ? $subSubSubHead->name_ur ?? '-' : $subSubSubHead->name_en ?? '-' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-6 mb-3">
                    <label for="search">@lang('messages.name')</label>
                    <input type="text" class="form-control" name="search" placeholder="@lang('messages.search')"
                        value="{{ request('search') }}">
                </div>

                <div class="col-lg-6 mb-3">
                    <button class="btn btn-primary" type="submit">@lang('messages.search')</button>

                    @if (request()->hasAny([
                            'search',
                            'main_head_id',
                            'control_head_id',
                            'sub_head_id',
                            'sub_sub_head_id',
                            'sub_sub_sub_head_id',
                        ]))
                        <a href="{{ route('itemRegistration.index') }}" class="btn btn-secondary">@lang('messages.clear')</a>
                    @endif
                </div>
            </div>
        </form>

        <div class="block block-rounded">
            <div class="block-content block-content-full">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>@lang('messages.main-heads')</th>
                            <th>@lang('messages.control-heads')</th>
                            <th>@lang('messages.sub-heads')</th>
                            <th>@lang('messages.sub-sub-heads')</th>
                            <th>@lang('messages.sub-sub-sub-heads')</th>
                            <th>@lang('messages.unit')</th>
                            <th>@lang('messages.name')</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($itemsListing as $index => $itemListing)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>
                                    {{ App::getLocale() === 'ur' ? $itemListing->mainHead->name_ur ?? '-' : $itemListing->mainHead->name_en ?? '-' }}
                                </td>
                                <td>
                                    {{ App::getLocale() === 'ur' ? $itemListing->controlHead->name_ur ?? '-' : $itemListing->controlHead->name_en ?? '-' }}
                                </td>
                                <td>
                                    {{ App::getLocale() === 'ur' ? $itemListing->subHead->name_ur ?? '-' : $itemListing->subHead->name_en ?? '-' }}
                                </td>
                                <td>
                                    {{ App::getLocale() === 'ur' ? $itemListing->subSubHead->name_ur ?? '-' : $itemListing->subSubHead->name_en ?? '-' }}
                                </td>
                                <td>
                                    {{ App::getLocale() === 'ur' ? $itemListing->subSubSubHead->name_ur ?? '-' : $itemListing->subSubSubHead->name_en ?? '-' }}
                                </td>
                                <td>
                                    {{ App::getLocale() === 'ur' ? $itemListing->measurementUnit->name_ur ?? '-' : $itemListing->measurementUnit->name_en ?? '-' }}
                                </td>
                                <td>
                                    {{ App::getLocale() === 'ur' ? $itemListing->name_ur ?? '-' : $itemListing->name_en ?? '-' }}
                                </td>

                                <td class="text-center">
                                    <div class="btn-group">

                                        <a href="{{ route('itemRegistration.edit', $itemListing->id) }}"
                                            class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled"
                                            data-bs-toggle="tooltip" aria-label="Edit sub Head"
                                            data-bs-original-title="Edit sub Head"> <i
                                                class="fa fa-fw fa-pencil-alt"></i></a>

                                        <form method="POST"
                                            action="{{ route('itemRegistration.destroy', $itemListing->id) }}"
                                            class="d-inline-block delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                class="btn btn-sm btn-alt-danger js-bs-tooltip-enabled btn-delete"
                                                data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
                                                <i class="fa fa-fw fa-times text-danger"></i>
                                            </button>

                                        </form>
                                        <a href="{{ route('itemRegistration.show', $itemListing->id) }}"
                                            class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled"
                                            data-bs-toggle="tooltip" aria-label="View item"
                                            data-bs-original-title="View item">
                                            <i class="fa fa-fw fa-eye"></i>
                                        </a>
    
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $itemsListing->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
