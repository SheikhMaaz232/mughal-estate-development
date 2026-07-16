@extends('layouts.backend')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-md-6">
                <h1 class="h3">@lang('messages.work_orders')</h1>
            </div>
            <div class="col-md-6 text-end">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#selectTenderModal">
                    <i class="bi bi-plus-circle"></i> @lang('messages.create-work-order')
                </button>
            </div>
        </div>

        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ $message }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($message = Session::get('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ $message }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Filter Section -->
        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('work-orders.index') }}" method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">@lang('messages.construction-site')</label>
                        <select name="constructionSiteId" class="form-select">
                            <option value="">@lang('messages.all')</option>
                            @foreach (\App\Models\ConstructionSite::all() as $site)
                                <option value="{{ $site->id }}"
                                    {{ request('constructionSiteId') == $site->id ? 'selected' : '' }}>
                                    {{ App::getLocale() === 'ur' ? $site->name_ur ?? '-' : $site->name_en ?? '-' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">@lang('messages.tender')</label>
                        <select name="tenderId" class="form-select">
                            <option value="">@lang('messages.all')</option>
                            @foreach (\App\Models\Tender::all() as $tender)
                                <option value="{{ $tender->id }}"
                                    {{ request('tenderId') == $tender->id ? 'selected' : '' }}>
                                    {{ App::getLocale() === 'ur' ? $tender->title_ur ?? '-' : $tender->title_en ?? '-' }}

                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">@lang('messages.status')</label>
                        <select name="status" class="form-select">
                            <option value="">@lang('messages.all')</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                                @lang('messages.pending')
                            </option>
                            <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>
                                @lang('messages.in_progress')
                            </option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>
                                @lang('messages.completed')
                            </option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">@lang('messages.search')</label>
                        <input type="text" name="search" class="form-control" placeholder="@lang('messages.description')"
                            value="{{ request('search') }}">
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-info">@lang('messages.search')</button>
                        <a href="{{ route('work-orders.index') }}" class="btn btn-secondary">@lang('messages.reset')</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Work Orders Table -->
        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>@lang('messages.id')</th>
                            <th>@lang('messages.construction-site')</th>
                            <th>@lang('messages.tender')</th>
                            <th>@lang('messages.start-date')</th>
                            <th>@lang('messages.end-date')</th>
                            <th>@lang('messages.total_amount')</th>
                            <th>@lang('messages.status')</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($workOrdersListing as $order)
                            <tr>
                                <td><strong>{{ $order->id }}</strong></td>
                                <td>
                                    {{ App::getLocale() === 'ur'
                                        ? $order->constructionSite?->name_ur ?? '-'
                                        : $order->constructionSite?->name_en ?? '-' }}
                                </td>
                                <td>
                                    {{ App::getLocale() === 'ur' ? $order->tender?->title_ur ?? '-' : $order->tender?->title_en ?? '-' }}
                                </td>
                                <td>{{ $order->start_date->format('d-m-Y') }}</td>
                                <td>{{ $order->end_date->format('d-m-Y') }}</td>
                                <td>{{ number_format($order->total_amount, 2) }}</td>
                                <td>
                                    <span
                                        class="badge bg-{{ $order->status == 'completed' ? 'success' : ($order->status == 'in_progress' ? 'warning' : 'secondary') }}">
                                        {{ __('messages.' . $order->status) }}
                                    </span>
                                </td>
                                <td>

                                    <a href="{{ route('work-orders.show', $order->id) }}" class="btn btn-sm btn-alt-info"
                                        title="@lang('messages.view')">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a href="{{ route('work-orders.edit', $order->id) }}"
                                        class="btn btn-sm btn-alt-warning" title="@lang('messages.edit')">
                                        <i class="fa fa-pencil-alt"></i>
                                    </a>
                                    <form action="{{ route('work-orders.destroy', $order->id) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('{{ __('Are you sure?') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-alt-danger">
                                            <i class="fa fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <p class="text-muted">{{ __('No work orders found') }}</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $workOrdersListing->links() }}
        </div>
    </div>

    <!-- Create Work Order Modal -->
    <div class="modal fade" id="selectTenderModal" tabindex="-1" role="dialog" aria-labelledby="selectTenderModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="selectTenderModalLabel">{{ __('Create Work Order') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="workOrderSelectionForm">
                    <div class="modal-body">
                        <!-- Step 1: Select Tender -->
                        <div class="mb-3">
                            <label for="modalTenderId" class="form-label">{{ __('Select Tender') }} <span
                                    class="text-danger">*</span></label>
                            <select name="tender_id" id="modalTenderId" class="form-control form-select" required>
                                <option value="">{{ __('Select a Tender') }}</option>
                                @php
                                    $tenders = \App\Models\Tender::all();
                                @endphp
                                @foreach ($tenders as $tender)
                                    <option value="{{ $tender->id }}">
                                        {{ $tender->title_en }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Step 2: BOQ List (shown after tender selection) -->
                        <div id="boqListContainer" style="display: none;">
                            <label class="form-label">{{ __('Select BOQ') }} <span class="text-danger">*</span></label>
                            <div id="boqList" class="row">
                                <!-- BOQs will be loaded here via AJAX -->
                            </div>
                        </div>

                        <div id="loadingSpinner" style="display: none;" class="text-center py-3">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Load BOQs when tender is selected
        document.getElementById('modalTenderId').addEventListener('change', function() {
            const tenderId = this.value;
            const boqListContainer = document.getElementById('boqListContainer');
            const boqList = document.getElementById('boqList');
            const loadingSpinner = document.getElementById('loadingSpinner');

            if (!tenderId) {
                boqListContainer.style.display = 'none';
                boqList.innerHTML = '';
                return;
            }

            // Show loading spinner
            loadingSpinner.style.display = 'block';
            boqList.innerHTML = '';

            // Fetch BOQs for the selected tender
            fetch(`/boq-masters/get-by-tender/${tenderId}`)
                .then(response => response.json())
                .then(data => {
                    loadingSpinner.style.display = 'none';
                    boqList.innerHTML = '';

                    if (data.length === 0) {
                        boqList.innerHTML =
                            '<div class="col-12"><p class="text-muted text-center">No BOQs available for this tender</p></div>';
                    } else {
                        data.forEach(boq => {
                            const boqCard = document.createElement('div');
                            boqCard.className = 'col-md-6 mb-3';
                            boqCard.innerHTML = `
                            <div class="card h-100" style="cursor: pointer; border: 2px solid #e9ecef;"
                                 onmouseover="this.style.borderColor='#0d6efd'; this.style.boxShadow='0 0.5rem 1rem rgba(0,0,0,0.15)'"
                                 onmouseout="this.style.borderColor='#e9ecef'; this.style.boxShadow='none'">
                                <div class="card-body">
                                    <h6 class="card-title">${boq.title_en}</h6>
                                    <small class="text-muted d-block mb-2">
                                        <strong>Items:</strong> ${boq.items_count}
                                    </small>
                                    <small class="text-muted d-block">
                                        <strong>Amount:</strong> ${parseFloat(boq.total_amount).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}
                                    </small>
                                    <a href="/work-orders/create?boq_id=${boq.id}" class="btn btn-sm btn-primary w-100 mt-2">
                                        Select & Create
                                    </a>
                                </div>
                            </div>
                        `;
                            boqList.appendChild(boqCard);
                        });
                    }

                    boqListContainer.style.display = 'block';
                })
                .catch(error => {
                    loadingSpinner.style.display = 'none';
                    console.error('Error fetching BOQs:', error);
                    boqList.innerHTML =
                        '<div class="col-12"><p class="text-danger text-center">Error loading BOQs</p></div>';
                    boqListContainer.style.display = 'block';
                });
        });
    </script>
@endsection
