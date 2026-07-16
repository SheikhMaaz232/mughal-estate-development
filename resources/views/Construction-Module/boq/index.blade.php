@extends('layouts.backend')

@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h1 class="h3 fw-bold mb-3">@lang('messages.boq')</h1>
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">@lang('messages.list-of-all-boq')</h2>
                </div>
                @if ($constructionSiteId && $tenderId)
                    <a href="{{ route('boq-masters.create', ['tenderId' => $tenderId]) }}"
                        class="btn btn-sm btn-primary">@lang('messages.add-new')</a>
                @else
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                        data-bs-target="#selectTenderModal">@lang('messages.add-new')</button>
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

        <form method="GET" action="{{ route('boq-masters.index') }}">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="constructionSiteId" class="form-label">@lang('messages.construction-site')</label>
                    <select name="constructionSiteId" id="constructionSiteId" class="form-control form-select select2">
                        <option value="">@lang('messages.all')</option>
                        @foreach ($constructionSites ?? [] as $site)
                            <option value="{{ $site->id }}"
                                {{ request('constructionSiteId') == $site->id ? 'selected' : '' }}>
                                {{ App::getLocale() === 'ur' ? $site->name_ur : $site->name_en }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="tenderId" class="form-label">@lang('messages.tender')</label>
                    <select name="tenderId" id="tenderId" class="form-control form-select select2">
                        <option value="">@lang('messages.all')</option>
                        @foreach ($tenders ?? [] as $tender)
                            <option value="{{ $tender->id }}" {{ request('tenderId') == $tender->id ? 'selected' : '' }}>
                                {{ App::getLocale() === 'ur' ? $tender->title_ur : $tender->title_en }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="search" class="form-label">@lang('messages.search')</label>
                    <input type="text" class="form-control" name="search" placeholder="@lang('messages.search-by-name')"
                        value="{{ request('search') }}">
                </div>

                <div class="col-md-3 mb-3">
                    <button class="btn btn-primary mt-4" type="submit">@lang('messages.search')</button>
                    @if (request()->hasAny(['search', 'constructionSiteId', 'tenderId']))
                        <a href="{{ route('boq-masters.index') }}" class="btn btn-secondary mt-4">@lang('messages.clear')</a>
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
                                <th>@lang('messages.tender')</th>
                                <th>@lang('messages.total_amount')</th>
                                <th class="text-center">@lang('messages.actions')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($boqs as $boq)
                                <tr>
                                    <td>{{ $boq->id }}</td>
                                    <td>
                                        <strong>{{ App::getLocale() === 'ur' ? $boq->title_ur : $boq->title_en }}</strong>
                                    </td>
                                    <td>{{ App::getLocale() === 'ur' ? $boq->constructionSite->name_ur : $boq->constructionSite->name_en }}
                                    </td>
                                    <td>{{ App::getLocale() === 'ur' ? $boq->tender->title_ur : $boq->tender->title_en }}
                                    </td>
                                    <td>{{ number_format($boq->total_amount, 2) }}</td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('boq-masters.show', $boq->id) }}"
                                                class="btn btn-sm btn-alt-info" title="@lang('messages.view')">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <a href="{{ route('boq-masters.edit', $boq->id) }}"
                                                class="btn btn-sm btn-alt-primary" title="@lang('messages.edit')">
                                                <i class="fa fa-pencil-alt"></i>
                                            </a>
                                            <form action="{{ route('boq-masters.destroy', $boq->id) }}" method="POST"
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
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        @lang('messages.no-records-found')
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($boqs->total() > 0)
                    <div class="d-flex justify-content-center mt-4">
                        {{ $boqs->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Create BOQ Modal -->
    <div class="modal fade" id="selectTenderModal" tabindex="-1" role="dialog" aria-labelledby="selectTenderModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="selectTenderModalLabel">@lang('messages.select-tender')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="boqSelectionForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="modalTenderId" class="form-label">@lang('messages.tender')</label>
                            <select name="tender_id" id="modalTenderId" class="form-control form-select select2"
                                required>
                                <option value="">@lang('messages.select-option')</option>
                                @foreach ($tenders as $tender)
                                    <option value="{{ $tender->id }}">
                                        {{ App::getLocale() === 'ur' ? $tender->title_ur : $tender->title_en }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">@lang('messages.cancel')</button>
                        <button type="button" class="btn btn-primary"
                            onclick="proceedToCreateBOQ()">@lang('messages.proceed')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function proceedToCreateBOQ() {
            const tenderId = document.getElementById('modalTenderId').value;
            if (tenderId) {
                const baseUrl = "{{ route('boq-masters.create', ['id' => '__id__']) }}".replace('__id__', tenderId);

                window.location.href = baseUrl;
            } else {
                alert("@lang('messages.select-tender')");
            }
        }
    </script>
@endsection
