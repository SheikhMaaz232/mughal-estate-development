@extends('layouts.backend')

@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">@lang('messages.list-of-parties')</h2>
                </div>
                <a href="{{ route('parties.create') }}" class="btn btn-sm btn-primary">@lang('messages.add-parties')</a>
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

        <form method="GET" action="{{ route('parties.index') }}">
            <div class="row">
                <div class="col-lg-6 mb-3">
                    <label for="cast_id">@lang('messages.cast')</label>
                    <select name="cast_id[]" id="cast_id"
                        class="form-control form-select select2 @error('cast_id') is-invalid @enderror" multiple>
                        @foreach ($casts as $cast)
                            <option value="{{ $cast->id }}"
                                {{ collect(request('cast_id'))->contains($cast->id) ? 'selected' : '' }}>
                                {{ App::getLocale() === 'ur' ? $cast->title_ur ?? '-' : $cast->title_en ?? '-' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-6 mb-3">
                    <label for="occupation_id">@lang('messages.occupation')</label>
                    <select name="occupation_id[]" id="occupation_id"
                        class="form-control form-select select2 @error('occupation_id') is-invalid @enderror" multiple>
                        @foreach ($occupations as $occupation)
                            <option value="{{ $occupation->id }}"
                                {{ collect(request('occupation_id'))->contains($occupation->id) ? 'selected' : '' }}>
                                {{ App::getLocale() === 'ur' ? $occupation->title_ur ?? '-' : $occupation->title_en ?? '-' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6 mb-3">
                    <label for="residential_status">@lang('messages.residential-Status')</label>
                    <select name="residential_status[]" id="residential_status"
                        class="form-control form-select select2 @error('residential_status') is-invalid @enderror" multiple>
                        @foreach ($residentialStatus as $residential)
                            <option value="{{ $residential->id }}"
                                {{ collect(request('residential_status'))->contains($residential->id) ? 'selected' : '' }}>
                                {{ App::getLocale() === 'ur' ? $residential->title_ur ?? '-' : $residential->title_en ?? '-' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-lg-6 mb-3">
                    <label for="search">@lang('messages.name')</label>
                    <input type="text" class="form-control" name="search" placeholder="@lang('messages.search')"
                        value="{{ request('search') }}">
                </div>

            </div>
            <div class="row">
                <div class="col-lg-6">
                    <button class="btn btn-primary" type="submit">@lang('messages.search')</button>

                    @if (request()->hasAny(['search', 'cast_id', 'occupation_id', 'sub_head_id', 'sub_sub_head_id']))
                        <a href="{{ route('parties.index') }}" class="btn btn-secondary">@lang('messages.clear')</a>
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
                            <th>@lang('messages.name')</th>
                            <th>@lang('messages.father_name')</th>
                            <th>@lang('messages.cnic_no')</th>
                            <th>@lang('messages.contact_no')</th>
                            <th>@lang('messages.cast')</th>
                            <th>@lang('messages.occupation')</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($parties as $index => $party)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>
                                    {{ App::getLocale() === 'ur' ? $party->name_ur ?? '-' : $party->name_en ?? '-' }}
                                </td>
                                <td>
                                    {{ App::getLocale() === 'ur' ? $party->father_name_ur ?? '-' : $party->father_name_en ?? '-' }}
                                </td>
                                <td>{{ $party->cnic_no }}</td>
                                <td>{{ $party->contact_number_1 }}</td>
                                <td>
                                    {{ App::getLocale() === 'ur' ? $party->cast->title_ur ?? '-' : $party->cast->title_en ?? '-' }}
                                </td>
                                <td>
                                    {{ App::getLocale() === 'ur' ? $party->occupation->title_ur ?? '-' : $party->occupation->title_en ?? '-' }}
                                </td>

                                <td class="text-center">
                                    <div class="btn-group">

                                        <a href="{{ route('parties.edit', $party->id) }}"
                                            class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled"
                                            data-bs-toggle="tooltip" aria-label="Edit sub Head"
                                            data-bs-original-title="Edit sub Head"> <i
                                                class="fa fa-fw fa-pencil-alt"></i></a>

                                        <form method="POST" action="{{ route('parties.destroy', $party->id) }}"
                                            class="d-inline-block delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                class="btn btn-sm btn-alt-danger js-bs-tooltip-enabled btn-delete"
                                                data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
                                                <i class="fa fa-fw fa-times text-danger"></i>
                                            </button>

                                        </form>
                                        <a href="{{ route('parties.show', $party->id) }}"
                                            class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled"
                                            data-bs-toggle="tooltip" aria-label="View Party"
                                            data-bs-original-title="View Party">
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
                    {{ $parties->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
