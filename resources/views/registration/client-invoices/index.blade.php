@extends('layouts.backend')

@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h1 class="h3 fw-bold mb-0">@lang('messages.client-invoices')</h1>
                </div>
                <nav class="flex-shrink-0 mt-3 mt-sm-0 ms-sm-3">
                    <a href="{{ route('client-invoices.create') }}" class="btn btn-sm btn-alt-primary">
                        <i class="fa fa-plus me-1"></i> @lang('messages.create-invoice')
                    </a>
                </nav>
            </div>
        </div>
    </div>

    <div class="content">
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <h4 class="alert-heading">@lang('messages.error')</h4>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Filter Section -->
        <div class="block block-rounded mb-2">
            <div class="block-content block-content-full">
                <form method="GET" action="{{ route('client-invoices.index') }}" class="row g-3 mb-3">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" placeholder="@lang('messages.search')"
                            value="{{ request('search') }}">
                    </div>

                    <div class="col-md-2">
                        <select name="tender_id" class="form-select">
                            <option value="">@lang('messages.all-tenders')</option>
                            @foreach ($tenders as $tender)
                                <option value="{{ $tender->id }}"
                                    {{ request('tender_id') == $tender->id ? 'selected' : '' }}>
                                    {{ app()->getLocale() === 'ur' ? $tender->title_ur : $tender->title_en }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <select name="status" class="form-select">
                            <option value="">@lang('messages.all-statuses')</option>
                            @foreach ($statuses as $status)
                                <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                    @lang('messages.status-' . $status)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                    </div>

                    <div class="col-md-2">
                        <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                    </div>

                    <div class="col-md-1">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Invoices Table -->
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">@lang('messages.client-invoices')</h3>
            </div>

            <div class="block-content block-content-full">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>@lang('messages.invoice-no')</th>
                                <th>@lang('messages.tender')</th>
                                <th>@lang('messages.client')</th>
                                <th>@lang('messages.invoice-date')</th>
                                <th>@lang('messages.amount')</th>
                                <th>@lang('messages.status')</th>
                                <th>@lang('messages.created-by')</th>
                                <th>@lang('messages.action')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($invoices as $invoice)
                                <tr>
                                    <td class="fw-bold">{{ $invoice->invoice_no }}</td>
                                    <td>
                                        {{ app()->getLocale() === 'ur' ? $invoice->tender->title_ur : $invoice->tender->title_en }}
                                    </td>
                                    <td>{{ $invoice->client?->name_en }}</td>
                                    <td>{{ $invoice->invoice_date->format('d M Y') }}</td>
                                    <td class="text-end">{{ number_format($invoice->amount, 2) }}</td>
                                    <td>
                                        <span
                                            class="badge bg-{{ $invoice->status === 'verified' ? 'success' : ($invoice->status === 'draft' ? 'warning' : 'secondary') }}">
                                            @lang('messages.status-' . $invoice->status)
                                        </span>
                                    </td>
                                    <td>{{ app()->getLocale() === 'ur' ? $invoice->createdByUser?->name_ur : $invoice->createdByUser?->name_en }}</td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('client-invoices.show', $invoice->id) }}"
                                                class="btn btn-sm btn-info" title="@lang('messages.view')">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            @if ($invoice->canBeEdited())
                                                <a href="{{ route('client-invoices.edit', $invoice->id) }}"
                                                    class="btn btn-sm btn-warning" title="@lang('messages.edit')">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                            @endif
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

                @if ($invoices->hasPages())
                    <div class="mt-3">
                        {{ $invoices->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
