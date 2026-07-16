@extends('layouts.backend')

@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h1 class="h3 fw-bold mb-3">@lang('messages.construction-sites')</h1>
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">@lang('messages.list-of-all-construction-sites')</h2>
                </div>
                <a href="{{ route('construction-sites.create') }}" class="btn btn-sm btn-primary">@lang('messages.add-new')</a>
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

        <form method="GET" action="{{ route('construction-sites.index') }}">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="company_id" class="form-label">@lang('messages.company')</label>
                    <select name="company_id" id="company_id" class="form-control form-select select2">
                        <option value="">@lang('messages.all')</option>
                        @foreach ($companies ?? [] as $company)
                            <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                                {{ App::getLocale() === 'ur' ? $company->name_ur : $company->name_en }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="project_id" class="form-label">@lang('messages.project')</label>
                    <select name="project_id" id="project_id" class="form-control form-select select2">
                        <option value="">@lang('messages.all')</option>
                        @foreach ($projects ?? [] as $project)
                            <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>
                                {{ App::getLocale() === 'ur' ? $project->name_ur : $project->name_en }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="status" class="form-label">@lang('messages.status')</label>
                    <select name="status" id="status" class="form-control form-select">
                        <option value="">@lang('messages.all')</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>@lang('messages.pending')</option>
                        <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>@lang('messages.ongoing')</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>@lang('messages.completed')</option>
                        <option value="on-hold" {{ request('status') == 'on-hold' ? 'selected' : '' }}>@lang('messages.on-hold')</option>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="search" class="form-label">@lang('messages.search')</label>
                    <input type="text" class="form-control" name="search" placeholder="@lang('messages.search-by-name')" value="{{ request('search') }}">
                </div>

                <div class="col-md-3 mb-3">
                    <button class="btn btn-primary mt-4" type="submit">@lang('messages.search')</button>
                    @if (request()->hasAny(['search', 'company_id', 'project_id', 'status']))
                        <a href="{{ route('construction-sites.index') }}" class="btn btn-secondary mt-4">@lang('messages.clear')</a>
                    @endif
                </div>
            </div>
        </form>

        <div class="block block-rounded">
            <div class="block-content block-content-full">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-light">
                            <tr>
                                <th>@lang('messages.id')</th>
                                <th>@lang('messages.name')</th>
                                <th>@lang('messages.company')</th>
                                <th>@lang('messages.project')</th>
                                <th>@lang('messages.status')</th>
                                <th>@lang('messages.start-date')</th>
                                <th>@lang('messages.end-date')</th>
                                <th class="text-center">@lang('messages.actions')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($sites as $site)
                                <tr>
                                    <td>{{ $site->id }}</td>
                                    <td>
                                        <strong>{{ App::getLocale() === 'ur' ? $site->name_ur : $site->name_en }}</strong>
                                    </td>
                                    <td>{{ App::getLocale() === 'ur' ? $site->company->name_ur : $site->company->name_en }}</td>
                                    <td>{{ App::getLocale() === 'ur' ? $site->project->name_ur : $site->project->name_en }}</td>
                                    <td>
                                        @if ($site->status === 'pending')
                                            <span class="badge bg-warning">@lang('messages.pending')</span>
                                        @elseif ($site->status === 'ongoing')
                                            <span class="badge bg-info">@lang('messages.ongoing')</span>
                                        @elseif ($site->status === 'completed')
                                            <span class="badge bg-success">@lang('messages.completed')</span>
                                        @elseif ($site->status === 'on-hold')
                                            <span class="badge bg-danger">@lang('messages.on-hold')</span>
                                        @endif
                                    </td>
                                    <td>{{ $site->estimated_start_date ? $site->estimated_start_date->format('d-m-Y') : '-' }}</td>
                                    <td>{{ $site->estimated_end_date ? $site->estimated_end_date->format('d-m-Y') : '-' }}</td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('construction-sites.show', $site->id) }}" class="btn btn-sm btn-alt-info" title="@lang('messages.view')">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <a href="{{ route('construction-sites.edit', $site->id) }}" class="btn btn-sm btn-alt-primary" title="@lang('messages.edit')">
                                                <i class="fa fa-pencil-alt"></i>
                                            </a>
                                            <a href="{{ route('tenders.create', ['id' => $site->id]) }}" class="btn btn-sm btn-alt-success" title="@lang('messages.manage-tenders')">
                                                <i class="fa fa-list"></i>
                                            </a>
                                            <form action="{{ route('construction-sites.destroy', $site->id) }}" method="POST" class="d-inline" onsubmit="return confirm('@lang('messages.confirm-delete')');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-alt-danger" title="@lang('messages.delete')">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        @lang('messages.no-records-found')
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($sites->total() > 0)
                    <div class="d-flex justify-content-center mt-4">
                        {{ $sites->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
