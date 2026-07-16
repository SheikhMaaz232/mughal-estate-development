@extends('layouts.backend')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-md-6">
                <h1 class="h3">@lang('messages.work_progress')</h1>
            </div>
            <div class="col-md-6 text-end">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#selectTenderModal">
                    <i class="bi bi-plus-circle"></i> @lang('messages.add_work_progress')
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
                        <label class="form-label">@lang('messages.tender')</label>
                        <select name="tenderId" class="form-select select2">
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
                        <label class="form-label">@lang('messages.work_order')</label>
                        <select name="work_order_id" class="form-select select2">
                            <option value="">@lang('messages.all')</option>
                            @foreach ($workOrders as $order)
                                <option value="{{ $order->id }}"
                                    {{ request('work_order_id') == $order->id ? 'selected' : '' }}>
                                    {{ App::getLocale() === 'ur' ? $order->description_ur ?? '-' : $order->description_en ?? '-' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">@lang('messages.search')</label>
                        <input type="text" name="search" class="form-control" placeholder="@lang('messages.description')"
                            value="{{ request('search') }}">
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-info">@lang('messages.search')</button>
                        <a href="{{ route('work-progress.index') }}" class="btn btn-secondary">@lang('messages.reset')</a>
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
                            <th>@lang('messages.date')</th>
                            <th>@lang('messages.tender')</th>
                            <th>@lang('messages.work_order')</th>
                            <th>@lang('messages.description')</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($progresses as $progress)
                            <tr>
                                <td><strong>{{ $progress->id }}</strong></td>
                                <td>{{ \Carbon\Carbon::parse($progress->date)->format('d M Y') }}</td>
                                <td>
                                    {{ App::getLocale() === 'ur' ? $progress->workOrder->tender?->title_ur ?? '-' : $progress->workOrder->tender?->title_en ?? '-' }}
                                </td>

                                <td>
                                    {{ App::getLocale() === 'ur' ? $progress->workOrder->description_ur ?? '-' : $progress->workOrder->description_en ?? '-' }}
                                </td>
                                <td>
                                    {{ App::getLocale() === 'ur' ? $progress->description_ur ?? '-' : $progress->description_en ?? '-' }}
                                </td>
                                <td>

                                    <a href="{{ route('work-progress.show', $progress->id) }}"
                                        class="btn btn-sm btn-alt-info" title="@lang('messages.view')">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a href="{{ route('work-progress.edit', $progress->id) }}"
                                        class="btn btn-sm btn-alt-warning" title="@lang('messages.edit')">
                                        <i class="fa fa-pencil-alt"></i>
                                    </a>
                                    <form action="{{ route('work-progress.destroy', $progress->id) }}" method="POST"
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
                                    <p class="text-muted">@lang('messages.no_work_progress_found')</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $progresses->links() }}
        </div>
    </div>

    <!-- Create Work Order Modal -->
    <div class="modal fade" id="selectTenderModal" tabindex="-1" role="dialog" aria-labelledby="selectTenderModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="selectTenderModalLabel">@lang('messages.add_work_progress')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="workOrderSelectionForm">
                    <div class="modal-body">
                        <!-- Step 1: Select Tender -->
                        <div class="mb-3">
                            <label for="modalTenderId" class="form-label">@lang('messages.select_tender') <span
                                    class="text-danger">*</span></label>
                            <select name="tender_id" id="modalTenderId" class="form-control form-select" required>
                                <option value="">@lang('messages.select_tender')</option>
                                @php
                                    $tenders = \App\Models\Tender::all();
                                @endphp
                                @foreach ($tenders as $tender)
                                    <option value="{{ $tender->id }}">
                                        {{ App::getLocale() === 'ur' ? $tender->title_ur ?? '-' : $tender->title_en ?? '-' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Step 2: Work Order List -->
                        <div id="workOrderListContainer" style="display: none;">
                            <label class="form-label">@lang('messages.select_work_order') <span class="text-danger">*</span></label>
                            <div id="workOrderList" class="row">
                                <!-- Work Orders will be loaded here -->
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
                            data-bs-dismiss="modal">@lang('messages.cancel')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        const currentLang = "{{ app()->getLocale() }}"; // en / ur
    </script>
    <script>
        $(document).ready(function() {

            // Initialize Select2 inside modal
            $('#selectTenderModal').on('shown.bs.modal', function() {
                $('#modalTenderId').select2({
                    placeholder: "Select a Tender",
                    allowClear: true,
                    width: '100%',
                    dropdownParent: $('#selectTenderModal')
                });
            });

            // Handle tender change (Select2 compatible)
            $('#modalTenderId').on('change', function() {

                const tenderId = $(this).val();
                const container = document.getElementById('workOrderListContainer');
                const list = document.getElementById('workOrderList');
                const loadingSpinner = document.getElementById('loadingSpinner');

                if (!tenderId) {
                    container.style.display = 'none';
                    list.innerHTML = '';
                    return;
                }

                loadingSpinner.style.display = 'block';
                list.innerHTML = '';

                //  Pass language to backend
                fetch(`/work-orders/get-by-tender/${tenderId}?lang=${currentLang}`)
                    .then(response => response.json())
                    .then(data => {
                        loadingSpinner.style.display = 'none';
                        list.innerHTML = '';

                        if (data.length === 0) {
                            list.innerHTML = `
                            <div class="col-12">
                                <p class="text-muted text-center">
                                    ${currentLang === 'ur' ? 'اس ٹینڈر کے لیے کوئی ورک آرڈر موجود نہیں' : 'No Work Orders available for this tender'}
                                </p>
                            </div>`;
                        } else {

                            data.forEach(order => {

                                const card = document.createElement('div');
                                card.className = 'col-md-6 mb-3';

                                card.innerHTML = `
                                <div class="card h-100" style="cursor: pointer; border: 2px solid #e9ecef;">
                                    <div class="card-body">

                                        <h6 class="card-title">${order.title ?? 'Work Order #' + order.id}</h6>

                                        <small class="text-muted d-block mb-2">
                                            <strong>${currentLang === 'ur' ? 'حیثیت' : 'Status'}:</strong>
                                            ${order.status ?? 'N/A'}
                                        </small>

                                        <small class="text-muted d-block">
                                            <strong>${currentLang === 'ur' ? 'رقم' : 'Amount'}:</strong>
                                            ${
                                                order.amount
                                                ? parseFloat(order.amount).toLocaleString('en-US', {
                                                    minimumFractionDigits: 2,
                                                    maximumFractionDigits: 2
                                                })
                                                : '0.00'
                                            }
                                        </small>

                                        <a href="/work-progress/create?work_order_id=${order.id}"
                                           class="btn btn-sm btn-primary w-100 mt-2">
                                            ${currentLang === 'ur' ? 'دیکھیں / منتخب کریں' : 'View / Select'}
                                        </a>

                                    </div>
                                </div>
                            `;

                                list.appendChild(card);
                            });
                        }

                        //  RTL support for Urdu
                        container.style.direction = currentLang === 'ur' ? 'rtl' : 'ltr';
                        container.style.display = 'block';
                    })
                    .catch(error => {
                        loadingSpinner.style.display = 'none';
                        console.error('Error:', error);

                        list.innerHTML = `
                        <div class="col-12">
                            <p class="text-danger text-center">
                                ${currentLang === 'ur' ? 'ڈیٹا لوڈ کرنے میں خرابی' : 'Error loading Work Orders'}
                            </p>
                        </div>`;
                        container.style.display = 'block';
                    });
            });

        });
    </script>
@endsection
