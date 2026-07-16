@extends('payroll::layouts.payroll')

@section('content')
<div class="block block-rounded">
    <div class="block-header block-header-default">
        <h3 class="block-title">@lang('payroll::messages.holiday-details')</h3>
        <div class="block-options">
            <a href="{{ route('payroll.holidays.index') }}" class="btn btn-sm btn-alt-primary">
                @lang('messages.go-to-list')
            </a>
        </div>
    </div>
    <div class="block-content block-content-full">
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label fw-bold">@lang('messages.name') (EN)</label>
                    <p class="form-control-plaintext">{{ $holiday->name_en }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">@lang('messages.name') (اردو)</label>
                    <p class="form-control-plaintext" dir="rtl">{{ $holiday->name_ur }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">@lang('messages.date')</label>
                    <p class="form-control-plaintext">{{ $holiday->date?->format('d-m-Y') }}</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label fw-bold">@lang('payroll::messages.holiday-type')</label>
                    <p class="form-control-plaintext">{{ $holiday->holidayType?->{'title_' . app()->getLocale()} ?? $holiday->holidayType?->title_en ?? '-' }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">@lang('payroll::messages.paid')</label>
                    <p class="form-control-plaintext">
                        <span class="badge bg-{{ $holiday->is_paid ? 'success' : 'secondary' }}">
                            {{ $holiday->is_paid ? __('payroll::messages.paid') : __('payroll::messages.unpaid') }}
                        </span>
                    </p>
                </div>
            </div>
        </div>
        <div class="d-flex gap-2 mt-3">
            <a href="{{ route('payroll.holidays.edit', $holiday->id) }}" class="btn btn-sm btn-primary">
                @lang('messages.edit')
            </a>
            <a href="{{ route('payroll.holidays.index') }}" class="btn btn-sm btn-alt-secondary">
                @lang('messages.back')
            </a>
        </div>
    </div>
</div>
@endsection
