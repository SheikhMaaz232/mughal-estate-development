{{-- resources/views/land-purchase/land-registration/index.blade.php --}}
@extends('layouts.backend')

@section('content')
<div class="block block-rounded">
    <div class="block-header block-header-default">
        <h3 class="block-title">@lang('messages.land-registrations')</h3>
        <div class="block-options">
            <a href="{{ route('lands.create') }}" class="btn btn-sm btn-primary">
                <i class="fa fa-plus me-1"></i> @lang('messages.add-land-registration')
            </a>
        </div>
    </div>

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
    <div class="block-content block-content-full">
    <!-- Search and Filter Form -->
    <form method="GET" action="{{ route('lands.index') }}" class="mb-4">
        <div class="row">
            <div class="col-md-3">
                <label for="search" class="form-label">@lang('messages.search')</label>
                <input type="text" name="search" id="search" class="form-control"
                       value="{{ request('search') }}" placeholder="@lang('messages.search')">
            </div>
            <div class="col-md-3">
                <label for="project_id" class="form-label">@lang('messages.project')</label>
                <select name="project_id" id="project_id" class="form-control">
                    <option value="">@lang('messages.all-projects')</option>
                    @foreach($projects as $id => $project)
                        <option value="{{ $id }}" {{ request('project_id') == $id ? '' : '' }}>
                            {{ App::getLocale() === 'ur' ? $project->name_ur ?? $project->name_en : $project->name_en }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label for="date_from" class="form-label">@lang('messages.from_date')</label>
                <input type="date" name="date_from" id="date_from" class="form-control"
                       value="{{ request('date_from') }}">
            </div>
            <div class="col-md-2">
                <label for="date_to" class="form-label">@lang('messages.to_date')</label>
                <input type="date" name="date_to" id="date_to" class="form-control"
                       value="{{ request('date_to') }}">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-alt-primary me-2">
                    <i class="fa fa-search me-1"></i> @lang('messages.search')
                </button>
                <a href="{{ route('lands.index') }}" class="btn btn-alt-secondary">
                    <i class="fa fa-refresh me-1"></i>
                </a>
            </div>
        </div>
    </form>

    <!-- Rest of your table code -->
</div>

        <!-- Land Registrations Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th>@lang('messages.registry-no')</th>
                        <th>@lang('messages.project')</th>
                        <th>@lang('messages.seller')</th>
                        <th>@lang('messages.buyer')</th>
                        <th>@lang('messages.total-area')</th>
                        <th>@lang('messages.land-amount')</th>
                        <th>@lang('messages.commission')</th>
                        <th>@lang('messages.date')</th>
                        <th>@lang('messages.actions')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($landRegistrations as $registration)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td>
                                @foreach($registration->details as $detail)
                                    @if($detail->registry_no)
                                        <span class="badge bg-primary me-1">{{ $detail->registry_no }}</span>
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                {{ App::getLocale() === 'ur' ? $registration->project->name_ur ?? '-' : $registration->project->name_en ?? '-' }}
                            </td>
                            <td>
                                {{ App::getLocale() === 'ur' ? $registration->sellerAccount->name_ur ?? '-' : $registration->sellerAccount->name_en ?? '-' }}
                            </td>
                            <td>
                                {{ App::getLocale() === 'ur' ? $registration->buyerAccount->name_ur ?? '-' : $registration->buyerAccount->name_en ?? '-' }}
                            </td>
                            <td>
                                @if($registration->total_kanal > 0)
                                    {{ $registration->total_kanal }} @lang('messages.kanal')
                                @endif
                                @if($registration->total_marla > 0)
                                    {{ $registration->total_marla }} @lang('messages.marla')
                                @endif
                            </td>
                            <td class="text-end">{{ $registration->land_amount }}</td>
                            <td class="text-end">{{ $registration->commission_amount }}</td>
                            <td>{{ $registration->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('lands.show', $registration->id) }}"
                                       class="btn btn-sm btn-alt-info" data-bs-toggle="tooltip" title="@lang('messages.view')">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a href="{{ route('lands.edit', $registration->id) }}"
                                       class="btn btn-sm btn-alt-primary" data-bs-toggle="tooltip" title="@lang('messages.edit')">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    <form action="{{ route('lands.destroy', $registration->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-alt-danger"
                                                data-bs-toggle="tooltip" title="@lang('messages.delete')"
                                                onclick="return confirm('@lang('messages.delete-confirm')')">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                        </form>
                                        <a href="{{ route('land-transfers.create', ['land_id' => $registration->id]) }}" class="btn btn-sm btn-alt-warning" title="@lang('messages.transfer-land')">
                                            <i class="fa fa-exchange"></i>
                                        </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center">
                                <div class="py-4">
                                    <i class="fa fa-inbox fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">@lang('messages.no-land-registrations-found')</p>
                                    <a href="{{ route('lands.create') }}" class="btn btn-primary">
                                        @lang('messages.create-first-land-registration')
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($landRegistrations->hasPages())
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted">
                    @lang('messages.showing-from-to', [
                        'from' => $landRegistrations->firstItem(),
                        'to' => $landRegistrations->lastItem(),
                        'total' => $landRegistrations->total()
                    ])
                </div>
                <div>
                    {{ $landRegistrations->links() }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTable
    $('.js-dataTable-full').DataTable({
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, '@lang('messages.all')']],
        autoWidth: false,
        ordering: true,
        order: [[8, 'desc']], // Order by date descending
        language: {
            search: '@lang('messages.search')',
            lengthMenu: '@lang('messages.show-entries')',
            info: '@lang('messages.showing-from-to')',
            infoEmpty: '@lang('messages.showing-0-to-0-of-0')',
            infoFiltered: '@lang('messages.filtered-from-total')',
            zeroRecords: '@lang('messages.no-matching-records')',
            paginate: {
                first: '@lang('messages.first')',
                last: '@lang('messages.last')',
                next: '@lang('messages.next')',
                previous: '@lang('messages.previous')'
            }
        }
    });

    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
});
</script>
@endpush
