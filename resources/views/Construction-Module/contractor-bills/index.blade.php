@extends('layouts.backend')

@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h1 class="h3 fw-bold mb-3">@lang('messages.contractor-bills')</h1>
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">@lang('messages.manage-contractor-bills')</h2>
                </div>
                <div class="col-md-6 text-end">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#selectTenderModal">
                        <i class="bi bi-plus-circle"></i> @lang('messages.add-new')
                    </button>
                </div>
                {{-- <a href="{{ route('contractor-bills.create') }}" class="btn btn-sm btn-primary">@lang('messages.add-new')</a> --}}
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

        <form method="GET" action="{{ route('contractor-bills.index') }}">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="search" class="form-label">@lang('messages.search')</label>
                    <input type="text" class="form-control" name="search" placeholder="@lang('messages.search-by-bill-number')"
                        value="{{ request('search') }}">
                </div>

                <div class="col-md-3 mb-3">
                    <label for="status" class="form-label">@lang('messages.status')</label>
                    <select name="status" id="status" class="form-control form-select">
                        <option value="">@lang('messages.all')</option>
                        @foreach ($statuses as $status)
                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                {{ __('messages.' . $status) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 mb-3">
                    <label for="tender_id" class="form-label">@lang('messages.tender')</label>
                    <select name="tender_id" id="tender_id" class="form-control form-select">
                        <option value="">@lang('messages.all')</option>
                        @foreach ($tenders as $tender)
                            <option value="{{ $tender->id }}" {{ request('tender_id') == $tender->id ? 'selected' : '' }}>
                                {{ App::getLocale() === 'ur' ? $tender->title_ur : $tender->title_en }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 mb-3">
                    <label for="from_date" class="form-label">@lang('messages.from-date')</label>
                    <input type="date" class="form-control" name="from_date" value="{{ request('from_date') }}">
                </div>

                <div class="col-md-3 mb-3">
                    <label for="to_date" class="form-label">@lang('messages.to-date')</label>
                    <input type="date" class="form-control" name="to_date" value="{{ request('to_date') }}">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <button class="btn btn-primary" type="submit">@lang('messages.search')</button>
                    @if (request()->hasAny(['search', 'status', 'tender_id', 'from_date', 'to_date']))
                        <a href="{{ route('contractor-bills.index') }}" class="btn btn-secondary">@lang('messages.clear')</a>
                    @endif
                </div>
            </div>
        </form>

        <div class="block block-rounded">
            <div class="block-content block-content-full">

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>@lang('messages.bill-number')</th>
                                <th>@lang('messages.date')</th>
                                <th>@lang('messages.tender')</th>
                                <th>@lang('messages.contractor')</th>
                                <th>@lang('messages.amount')</th>
                                <th>@lang('messages.status')</th>
                                <th>@lang('messages.paid-amount')</th>
                                <th>@lang('messages.actions')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bills as $bill)
                                <tr>
                                    <td><strong>{{ $bill->bill_no }}</strong></td>
                                    <td>{{ $bill->bill_date->format('Y-m-d') }}</td>
                                    <td> {{ App::getLocale() === 'ur' ? $bill->tender->title_ur : $bill->tender->title_en }}
                                    </td>
                                    <td> {{ App::getLocale() === 'ur' ? $bill->contractorAccount->party->name_ur : $bill->contractorAccount->party->name_en }}
                                    </td>
                                    <td class="text-right">
                                        <strong>{{ number_format($bill->total_amount, 2) }}</strong>
                                    </td>
                                    <td>
                                        @if ($bill->status === 'draft')
                                            <span class="badge bg-secondary">@lang('messages.draft')</span>
                                        @elseif($bill->status === 'verified')
                                            <span class="badge bg-info">@lang('messages.verified')</span>
                                        @elseif($bill->status === 'partial_paid')
                                            <span class="badge bg-primary">@lang('messages.partial-paid')</span>
                                        @elseif($bill->status === 'paid')
                                            <span class="badge bg-success">@lang('messages.paid')</span>
                                        @else
                                            <span class="badge bg-danger">@lang('messages.cancelled')</span>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        {{ number_format($bill->total_amount - $bill->getRemainigAmount(), 2) }}
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('contractor-bills.show', $bill->id) }}" class="btn btn-info"
                                                title="@lang('messages.view')">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            @if ($bill->canEdit())
                                                <a href="{{ route('contractor-bills.edit', $bill->id) }}"
                                                    class="btn btn-warning" title="@lang('messages.edit')">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endif

                                            @if ($bill->status === 'draft')
                                                <form action="{{ route('contractor-bills.verify', $bill->id) }}"
                                                    method="POST" style="display:inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success"
                                                        title="@lang('messages.verify')">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            <a href="{{ route('contractor-bills.print', $bill->id) }}"
                                                class="btn btn-secondary" title="@lang('messages.print')" target="_blank">
                                                <i class="fas fa-print"></i>
                                            </a>

                                            @if ($bill->canEdit())
                                                <form action="{{ route('contractor-bills.destroy', $bill->id) }}"
                                                    method="POST" style="display:inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger"
                                                        title="@lang('messages.delete')"
                                                        onclick="return confirm('@lang('messages.confirm-delete')')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        <i class="fas fa-inbox"></i> @lang('messages.no-records-found')
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <nav aria-label="Page navigation" class="mt-3">
                    {{ $bills->render() }}
                </nav>
            </div>
        </div>
    </div>
    <div class="modal fade" id="selectTenderModal" tabindex="-1" role="dialog"
        aria-labelledby="selectTenderModalLabel" aria-hidden="true">
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
            console.log('Current Language:', currentLang);

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

                                        <a href="/contractor-bills/create?work_order_id=${order.id}"
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
