@extends('layouts.backend')

@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h1 class="h3 fw-bold mb-3">@lang('messages.tenders')</h1>
                    @if ($constructionSiteId)
                        @php
                            $site = \App\Models\ConstructionSite::find($constructionSiteId);
                        @endphp
                        @if ($site)
                            <h2 class="fs-base lh-base fw-medium text-muted mb-0">
                                @lang('messages.for') {{ App::getLocale() === 'ur' ? $site->name_ur : $site->name_en }}
                            </h2>
                        @endif
                    @else
                        <h2 class="fs-base lh-base fw-medium text-muted mb-0">@lang('messages.list-of-all-tenders')</h2>
                    @endif
                </div>
                @if ($constructionSiteId)
                    <a href="{{ route('tenders.create', ['id' => $constructionSiteId]) }}"
                        class="btn btn-sm btn-primary">@lang('messages.add-new')</a>
                @else
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                        data-bs-target="#selectSiteModal">@lang('messages.add-new')</button>
                @endif
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

        <form method="GET" action="{{ route('tenders.index') }}">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="construction_site_id" class="form-label">@lang('messages.construction-site')</label>
                    <select name="construction_site_id" id="construction_site_id" class="form-control form-select select2">
                        <option value="">@lang('messages.all')</option>
                        @foreach ($constructionSites as $site)
                            <option value="{{ $site->id }}"
                                {{ request('construction_site_id') == $site->id ? 'selected' : '' }}>
                                {{ App::getLocale() === 'ur' ? $site->name_ur : $site->name_en }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="status" class="form-label">@lang('messages.status')</label>
                    <select name="status" id="status" class="form-control form-select">
                        <option value="">@lang('messages.all')</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>@lang('messages.draft')
                        </option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>@lang('messages.approved')
                        </option>
                        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>
                            @lang('messages.in-progress')</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>
                            @lang('messages.completed')</option>
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="search" class="form-label">@lang('messages.search')</label>
                    <input type="text" class="form-control" name="search" placeholder="@lang('messages.search-by-name')"
                        value="{{ request('search') }}">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <button class="btn btn-primary" type="submit">@lang('messages.search')</button>
                    @if (request()->hasAny(['search', 'construction_site_id', 'status']))
                        <a href="{{ route('tenders.index') }}" class="btn btn-secondary">@lang('messages.clear')</a>
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
                                <th>@lang('messages.title')</th>
                                <th>@lang('messages.construction-site')</th>
                                <th>@lang('messages.work-type')</th>
                                <th>@lang('messages.estimated-cost')</th>
                                <th>@lang('messages.status')</th>
                                <th>@lang('messages.start-date')</th>
                                <th>@lang('messages.end-date')</th>
                                <th class="text-center">@lang('messages.actions')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tendersListing as $tender)
                                <tr>
                                    <td>{{ $tender->id }}</td>
                                    <td>
                                        <strong>{{ App::getLocale() === 'ur' ? $tender->title_ur : $tender->title_en }}</strong>
                                    </td>

                                    <td>
                                        {{ App::getLocale() === 'ur'
                                            ? $tender->constructionSite?->name_ur ?? '-'
                                            : $tender->constructionSite?->name_en ?? '-' }}
                                    </td>


                                    <td>{{ $tender->work_type ?? '-' }}</td>
                                    <td>{{ $tender->estimated_cost ? number_format($tender->estimated_cost, 2) : '-' }}
                                    </td>
                                    <td>
                                        @if ($tender->status === 'draft')
                                            <span class="badge bg-secondary">@lang('messages.draft')</span>
                                        @elseif ($tender->status === 'approved')
                                            <span class="badge bg-info">@lang('messages.approved')</span>
                                        @elseif ($tender->status === 'in_progress')
                                            <span class="badge bg-warning">@lang('messages.in-progress')</span>
                                        @elseif ($tender->status === 'completed')
                                            <span class="badge bg-success">@lang('messages.completed')</span>
                                        @endif
                                    </td>
                                    <td>{{ $tender->start_date ? $tender->start_date->format('d-m-Y') : '-' }}</td>
                                    <td>{{ $tender->end_date ? $tender->end_date->format('d-m-Y') : '-' }}</td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('tenders.show', $tender->id) }}"
                                                class="btn btn-sm btn-alt-info" title="@lang('messages.view')">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <a href="{{ route('tenders.edit', $tender->id) }}"
                                                class="btn btn-sm btn-alt-primary" title="@lang('messages.edit')">
                                                <i class="fa fa-pencil-alt"></i>
                                            </a>
                                            <form action="{{ route('tenders.destroy', $tender->id) }}" method="POST"
                                                class="d-inline" onsubmit="return confirm('@lang('messages.confirm-delete')');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-alt-danger"
                                                    title="@lang('messages.delete')">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            {{-- <tr>
                                    <td colspan="9" class="text-center text-muted py-4">
                                        @lang('messages.no-records-found')
                                    </td>
                                </tr>
                            @endforeach --}}
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $tendersListing->links() }}
                </div>

            </div>
        </div>
    </div>

    <!-- Modal to Select Construction Site -->
    <div class="modal fade" id="selectSiteModal" tabindex="-1" aria-labelledby="selectSiteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="selectSiteModalLabel">@lang('messages.select-construction-site')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="selectSiteForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="modalConstructionSiteId" class="form-label">@lang('messages.construction-site') <span
                                    class="text-danger">*</span></label>
                            <select id="modalConstructionSiteId" class="form-control form-select" required>
                                <option value="">@lang('messages.select-construction-site')</option>
                                @foreach ($constructionSites as $site)
                                    <option value="{{ $site->id }}">
                                        {{ App::getLocale() === 'ur' ? $site->name_ur : $site->name_en }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">@lang('messages.cancel')</button>
                        <button type="button" class="btn btn-primary"
                            onclick="proceedToCreate()">@lang('messages.proceed')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function proceedToCreate() {
            const siteId = document.getElementById('modalConstructionSiteId').value;
            if (siteId) {
                const baseUrl = "{{ route('tenders.create', ['id' => '__id__']) }}".replace('__id__', siteId);
                window.location.href = baseUrl;
            } else {
                alert("@lang('messages.select-construction-site')");
            }
        }
    </script>
@endsection
