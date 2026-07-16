@extends('layouts.backend')

@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h1 class="h3 fw-bold mb-4">@lang('messages.projects') </h1>
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">@lang('messages.list-of-projects') </h2>
                </div>
                <a href="{{ route('projects.create') }}" class="btn btn-sm btn-primary">@lang('messages.add-new') </a>

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
        <form method="GET" action="{{ route('projects.index') }}">
            <div class="row">

                <div class="col-lg-6 mb-3">
                    <label>@lang('messages.name')</label>
                    <input type="text" class="form-control" name="search" placeholder="@lang('messages.name')"
                        value="{{ $search }}">
                </div>
                <div class="col-lg-3" style="margin-top: 25px">
                    <button class="btn btn-primary" type="submit">@lang('messages.search')</button>

                    @if (request()->has('search'))
                        <a href="{{ route('projects.index') }}" class="btn btn-secondary">
                            @lang('messages.clear')
                        </a>
                    @endif

                </div>
            </div>
        </form>
        <div class="block block-rounded">
            <div class="block-content block-content-full">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>@lang('messages.id') </th>
                            <th>@lang('messages.project-map') </th>
                            <th>@lang('messages.name') (EN)</th>
                            <th>@lang('messages.name') (UR)</th>
                            <th>@lang('messages.marla_in_square_feet') </th>
                            <th>@lang('messages.total_area') </th>
                            <th>@lang('messages.actions') </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($projectsListing as $project)
                            <tr>
                                <td>{{ $project->id }}</td>
                                <td style="padding : 0px !important;">
                                    <img src="{{ isset($project) && $project->project_map ? asset('storage/' . $project->project_map) : asset('images/No-Image-Placeholder.svg.png') }}"
                                        width="140" height="80" style="object-fit: cover;">
                                </td>
                                <td>{{ $project->name_en }}</td>
                                <td>{{ $project->name_ur }}</td>
                                <td>{{ $project->square_feet }}</td>
                                <td>{{ $project->total_area }} @lang('messages.marlas')</td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <!-- Edit Button -->
                                        <a href="{{ route('projects.edit', $project->id) }}"
                                            class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled"
                                            data-bs-toggle="tooltip" aria-label="Edit Project"
                                            data-bs-original-title="Edit Project">
                                            <i class="fa fa-fw fa-pencil-alt"></i>
                                        </a>
                                        <!-- Delete Form -->
                                        <form method="POST" action="{{ route('projects.destroy', $project->id) }}"
                                            class="d-inline-block delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                class="btn btn-sm btn-alt-danger js-bs-tooltip-enabled btn-delete"
                                                data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
                                                <i class="fa fa-fw fa-times"></i>
                                            </button>
                                        </form>
                                        <a href="{{ route('projects.show', $project->id) }}"
                                            class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled"
                                            data-bs-toggle="tooltip" aria-label="View Project"
                                            data-bs-original-title="View Project">
                                            <i class="fa fa-fw fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
