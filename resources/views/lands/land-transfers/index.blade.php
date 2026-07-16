@extends('layouts.backend')

@section('content')
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">@lang('messages.land-transfers')</h3>

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
            <form method="GET" action="{{ route('land-transfers.index') }}" class="mb-4">
                <div class="row">
                    <div class="col-md-3">
                        <label for="search" class="form-label">@lang('messages.search')</label>
                        <input type="text" name="search" id="search" class="form-control"
                            value="{{ request('search') }}" placeholder="@lang('messages.search-placeholder')">
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
                    <div class="col-md-3">
                        <label for="registry_type_id" class="form-label">@lang('messages.registry-type')</label>
                        <select name="registry_type_id" id="registry_type_id" class="form-control">
                            <option value="">@lang('messages.all-types')</option>
                            @foreach ($registryTypes as $id => $type)
                                <option value="{{ $id }}"
                                    {{ request('registry_type_id') == $id ? 'selected' : '' }}>
                                    {{ App::getLocale() === 'ur' ? $type->title_ur ?? '-' : $type->title_en ?? '-' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-alt-primary me-2">
                            <i class="fa fa-search me-1"></i> @lang('messages.search')
                        </button>
                        <a href="{{ route('land-transfers.index') }}" class="btn btn-alt-secondary">
                            <i class="fa fa-refresh me-1"></i>
                        </a>
                    </div>
                </div>
            </form>

            <!-- Land Transfers Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-vcenter">
                    <thead>
                        <tr>
                            <th>@lang('messages.id')</th>
                            <th>@lang('messages.transfer-date')</th>
                            <th>@lang('messages.land')</th>
                            <th>@lang('messages.registry-type')</th>
                            <th>@lang('messages.purchaser')</th>
                            <th>@lang('messages.seller')</th>
                            <th>@lang('messages.value')</th>
                            <th>@lang('messages.actions')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($landTransfers as $transfer)
                            <tr>
                                <td>{{ $transfer->id }}</td>
                                <td>{{ $transfer->transfer_date->format('d/m/Y') }}</td>
                                <td>
                                    @if ($transfer->land)
                                        @lang('messages.land#'){{ $transfer->land->id }}
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    {{ App::getLocale() === 'ur' ? $transfer->registryType->title_ur ?? '-' : $transfer->registryType->title_en ?? '-' }}
                                </td>
                                <td>
                                    {{ App::getLocale() === 'ur' ? $transfer->purchaserAccount->name_ur ?? '-' : $transfer->purchaserAccount->name_en ?? '-' }}
                                </td>
                                <td>
                                    {{ App::getLocale() === 'ur' ? $transfer->sellerAccount->name_ur ?? '-' : $transfer->sellerAccount->name_en ?? '-' }}
                                </td>
                                <td>{{ number_format($transfer->value, 2) }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('land-transfers.show', $transfer->id) }}"
                                            class="btn btn-sm btn-alt-primary" title="@lang('messages.view')">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <a href="{{ route('land-transfers.edit', $transfer->id) }}"
                                            class="btn btn-sm btn-alt-info" title="@lang('messages.edit')">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <form action="{{ route('land-transfers.destroy', $transfer->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-alt-danger"
                                                onclick="return confirm('@lang('messages.confirm-delete')')" title="@lang('messages.delete')">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">
                                    <div class="py-4">
                                        <i class="fa fa-inbox fa-3x text-muted"></i>
                                        <p class="mt-2">@lang('messages.no-transfers-found')</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($landTransfers->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $landTransfers->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
