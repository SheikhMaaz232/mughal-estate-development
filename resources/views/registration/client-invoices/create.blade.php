@extends('layouts.backend')

@section('content')

    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h1 class="h3 fw-bold mb-0">@lang('messages.create-client-invoice')</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <h4 class="alert-heading">@lang('messages.validation-errors')</h4>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form action="{{ route('client-invoices.store') }}" method="POST" class="block block-rounded">
            @csrf

            <div class="block-header block-header-default">
                <h3 class="block-title">@lang('messages.invoice-information')</h3>
            </div>

            <div class="block-content block-content-full">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="tender_id">@lang('messages.tender') <span
                                class="text-danger">*</span></label>
                        <select name="tender_id" id="tender_id"
                            class="form-select select2 @error('tender_id') is-invalid @enderror" required>
                            <option value="">@lang('messages.select-tender')</option>
                            @foreach ($tenders as $tender)
                                <option value="{{ $tender->id }}" {{ old('tender_id') == $tender->id ? 'selected' : '' }}>
                                    {{ app()->getLocale() === 'ur' ? $tender->title_ur : $tender->title_en }}
                                </option>
                            @endforeach
                        </select>
                        @error('tender_id')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            @lang('messages.client')
                            <span class="text-danger">*</span>
                        </label>

                        <select name="client_id" id="client_id" class="form-select">
                        </select>
                    </div>


                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label" for="invoice_no">@lang('messages.invoice-no') <span
                                class="text-danger">*</span></label>
                        <input type="text" name="invoice_no" id="invoice_no"
                            class="form-control @error('invoice_no') is-invalid @enderror"
                            value="{{ old('invoice_no', $nextId) }}" readonly>
                        @error('invoice_no')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label" for="invoice_date">@lang('messages.invoice-date') <span
                                class="text-danger">*</span></label>
                        <input type="date" name="invoice_date" id="invoice_date"
                            class="form-control @error('invoice_date') is-invalid @enderror"
                            value="{{ old('invoice_date', date('Y-m-d')) }}" required>
                        @error('invoice_date')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label" for="amount">@lang('messages.amount') <span
                                class="text-danger">*</span></label>
                        <input type="number" name="amount" id="amount"
                            class="form-control @error('amount') is-invalid @enderror" step="0.01" min="0"
                            value="{{ old('amount') }}" required>
                        @error('amount')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="remarks_en">@lang('messages.remarks') @lang('messages.english')</label>
                        <textarea name="remarks_en" id="remarks_en" class="form-control @error('remarks_en') is-invalid @enderror"
                            rows="3">{{ old('remarks_en') }}</textarea>
                        @error('remarks_en')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="remarks_ur">@lang('messages.remarks') @lang('messages.urdu')</label>
                        <textarea name="remarks_ur" id="remarks_ur" class="form-control @error('remarks_ur') is-invalid @enderror"
                            rows="3">{{ old('remarks_ur') }}</textarea>
                        @error('remarks_ur')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="block-content block-content-full bg-body-light">
                <button type="submit" class="btn btn-success">
                    <i class="fa fa-save me-1"></i> @lang('messages.create')
                </button>
                <a href="{{ route('client-invoices.index') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left me-1"></i> @lang('messages.back')
                </a>
            </div>
        </form>
    </div>

@endsection
