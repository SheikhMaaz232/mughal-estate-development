@extends('layouts.backend')

@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">@lang('messages.list-of-registry-letter')</h2>
                </div>
                {{-- <a href="{{ route('registry-order.create') }}" class="btn btn-sm btn-primary">@lang('messages.add-registry-letter')</a> --}}
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

        <form method="GET" action="{{ route('registry-order.index') }}">
            <div class="row">
                <div class="col-lg-6 mb-3">
                    <label for="main_head_id">@lang('messages.date')</label>
                    <input type="date" value="{{ request('date') }}" name="date" id="date" class="form-control"
                        placeholder="@lang('messages.booking_date')">
                </div>

                <div class="col-lg-6">
                    <label for="search">@lang('messages.booking_application_no')</label>
                    <input type="number" step="any" min="0" onwheel="this.blur()" class="form-control"
                        name="booking_application_no" placeholder="@lang('messages.booking_application_no')"
                        value="{{ request('booking_application_no') }}">
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 mb-3">
                    <label for="party_id">@lang('messages.party')</label>
                    <select name="party_id" id="party_id"
                        class="form-control form-select select2 @error('party_id') is-invalid @enderror">
                        <option value="">@lang('messages.select-an-option')</option>
                        @foreach ($searchParties as $searchParty)
                            <option value="{{ $searchParty->id }}"
                                {{ collect(request('party_id'))->contains($searchParty->id) ? 'selected' : '' }}>
                                {{ App::getLocale() === 'ur' ? $searchParty->name_ur ?? '-' : $searchParty->name_en ?? '-' }}
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; -
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $searchParty->cnic_no ?? 'N/A' }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                - {{ $searchParty->contact_number_1 ?? 'N/A' }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; -
                                {{ App::getLocale() === 'ur' ? $searchParty->cast->title_ur ?? '-' : $searchParty->cast->title_en ?? '-' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-6">
                    <label for="project_id">@lang('messages.projects')</label>
                    <select name="project_id" id="project_id"
                        class="form-control form-select select2 @error('project_id') is-invalid @enderror" multiple>
                        @foreach ($projects as $project)
                            <option value="{{ $project->id }}"
                                {{ collect(request('project_id'))->contains($project->id) ? 'selected' : '' }}>
                                {{ App::getLocale() === 'ur' ? $project->name_ur ?? '-' : $project->name_en ?? '-' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 mb-3">
                    <label for="search">@lang('messages.unit_no')</label>
                    <input type="text" class="form-control" name="unit_no" placeholder="@lang('messages.unit_no')"
                        value="{{ request('unit_no') }}">
                </div>

            </div>
            <div class="row">
                <div class="col-lg-6 mb-3">
                </div>

                <div class="col-lg-6" style="margin-top: 25px;">
                    <button class="btn btn-primary" type="submit">@lang('messages.search')</button>

                    @if (request()->hasAny(['search', 'project_id', 'party_id', 'date', 'booking_application_no']))
                        <a href="{{ route('registry-order.index') }}" class="btn btn-secondary">@lang('messages.clear')</a>
                    @endif
                </div>
            </div>
        </form>
        <div class="block block-rounded">
            <div class="block-content block-content-full">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">@lang('messages.letter_no')</th>
                            <th>@lang('messages.file_no')</th>
                            <th>@lang('messages.project')</th>
                            <th>@lang('messages.unit')</th>
                            <th>@lang('messages.nominated_party')</th>
                            <th>@lang('messages.relations')</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($registryOrdersListing as $index => $registryOrderListing)
                            <tr>
                                <td class="text-center"> {{ $index + 1 }}</td>
                                <td>{{ $registryOrderListing->booking->form_no }}</td>
                                <td>
                                    {{ App::getLocale() === 'ur' ? $registryOrderListing->booking->project->name_ur ?? '-' : $registryOrderListing->booking->project->name_en ?? '-' }}
                                </td>
                                <td>
                                    {{ App::getLocale() === 'ur' ? $registryOrderListing->booking->product->name_ur ?? '-' : $registryOrderListing->booking->product->name_en ?? '-' }}
                                </td>
                                <td>
                                    {{ App::getLocale() === 'ur' ? $registryOrderListing->party->name_ur ?? '-' : $registryOrderListing->party->name_en ?? '-' }}
                                </td>
                                <td>
                                    @if ($registryOrderListing->relation === 'file_owner')
                                        @lang('messages.file_owner')
                                    @elseif ($registryOrderListing->relation === 'nominee')
                                        @lang('messages.nominee')
                                    @elseif ($registryOrderListing->relation === 'blood_relation')
                                        @lang('messages.blood_relation')
                                    @elseif ($registryOrderListing->relation === 'third_party')
                                        @lang('messages.third_party')
                                    @else
                                        -
                                    @endif
                                </td>

                                <td class="text-center">
                                    <div class="btn-group">

                                        <a href="{{ route('registry-order.edit', $registryOrderListing->id) }}"
                                            class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled"
                                            data-bs-toggle="tooltip" aria-label="Edit sub Head"
                                            data-bs-original-title="Edit sub Head"> <i
                                                class="fa fa-fw fa-pencil-alt"></i></a>

                                        <form method="POST"
                                            action="{{ route('registry-order.destroy', $registryOrderListing->id) }}"
                                            class="d-inline-block delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                class="btn btn-sm btn-alt-danger js-bs-tooltip-enabled btn-delete"
                                                data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
                                                <i class="fa fa-fw fa-times text-danger"></i>
                                            </button>

                                        </form>
                                        <a href="{{ route('registry-order.show', $registryOrderListing->id) }}"
                                            class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled"
                                            data-bs-toggle="tooltip" aria-label="View registryOrderListing"
                                            data-bs-original-title="View registryOrderListing">
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
                    {{ $registryOrdersListing->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
