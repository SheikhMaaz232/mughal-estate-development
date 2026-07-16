@extends('payroll::layouts.payroll')

@section('content')
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
            <div class="flex-grow-1">
                <h1 class="h3 fw-bold mb-3">@lang('payroll::messages.edit-payroll')</h1>
                <h2 class="fs-base lh-base fw-medium text-muted mb-0">@lang('payroll::messages.edit-payroll-description')</h2>
            </div>
            <a href="{{ route('payroll.payrolls.index') }}" class="btn btn-sm btn-secondary">@lang('payroll::messages.back-to-payroll-list')</a>
        </div>
    </div>
</div>
<div class="content">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row gy-4">
        <div class="col-md-6">
            <div class="block block-rounded">
                <div class="block-content">
                    <h2 class="h5 mb-3">@lang('payroll::messages.payroll-summary')</h2>
                    <dl class="row mb-0">
                        <dt class="col-5">@lang('payroll::messages.employee')</dt>
                        <dd class="col-7">{{ $payroll->employee->first_name_en }} {{ $payroll->employee->last_name_en }}</dd>

                        <dt class="col-5">@lang('payroll::messages.month')</dt>
                        <dd class="col-7">{{ $payroll->month }}</dd>

                        <dt class="col-5">@lang('payroll::messages.basic-salary')</dt>
                        <dd class="col-7">{{ number_format($payroll->basic_salary, 2) }}</dd>

                        <dt class="col-5">@lang('payroll::messages.days-in-month')</dt>
                        <dd class="col-7">{{ $payroll->days_in_month }}</dd>

                        <dt class="col-5">@lang('payroll::messages.worked-days')</dt>
                        <dd class="col-7">{{ $payroll->total_worked_days }}</dd>

                        <dt class="col-5">@lang('payroll::messages.leave-days')</dt>
                        <dd class="col-7">{{ $payroll->total_leave_days }}</dd>

                        <dt class="col-5">@lang('payroll::messages.holiday-days')</dt>
                        <dd class="col-7">{{ $payroll->total_holiday_days }}</dd>

                        <dt class="col-5">@lang('payroll::messages.absent-days')</dt>
                        <dd class="col-7">{{ $payroll->total_absent_days }}</dd>

                        <dt class="col-5">@lang('payroll::messages.late-minutes')</dt>
                        <dd class="col-7">{{ $payroll->total_late_minutes }}</dd>

                        <dt class="col-5">@lang('payroll::messages.early-leave-minutes')</dt>
                        <dd class="col-7">{{ $payroll->total_early_leave_minutes }}</dd>

                        <dt class="col-5">@lang('payroll::messages.overtime-minutes')</dt>
                        <dd class="col-7">{{ $payroll->total_overtime_minutes }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="block block-rounded">
                <div class="block-content">
                    <h2 class="h5 mb-3">@lang('payroll::messages.financials')</h2>
                    <dl class="row mb-0">
                        <dt class="col-6">@lang('payroll::messages.daily-rate')</dt>
                        <dd class="col-6">{{ number_format($payroll->daily_rate, 2) }}</dd>

                        <dt class="col-6">@lang('payroll::messages.minute-rate')</dt>
                        <dd class="col-6">{{ number_format($payroll->minute_rate, 4) }}</dd>

                        <dt class="col-6">@lang('payroll::messages.absence-deduction')</dt>
                        <dd class="col-6">{{ number_format($payroll->absence_deduction_amount, 2) }}</dd>

                        <dt class="col-6">@lang('payroll::messages.late-early-deduction')</dt>
                        <dd class="col-6">{{ number_format($payroll->late_early_deduction_amount, 2) }}</dd>

                        <dt class="col-6">@lang('payroll::messages.overtime-amount')</dt>
                        <dd class="col-6">{{ number_format($payroll->overtime_amount, 2) }}</dd>

                        <dt class="col-6">@lang('payroll::messages.gross-salary')</dt>
                        <dd class="col-6">{{ number_format($payroll->gross_salary, 2) }}</dd>

                        <dt class="col-6">@lang('payroll::messages.net-salary')</dt>
                        <dd class="col-6">{{ number_format($payroll->net_salary, 2) }}</dd>

                        <dt class="col-6">@lang('payroll::messages.status')</dt>
                        <dd class="col-6">
                            @if ($payroll->is_finalized)
                                <span class="badge bg-success">@lang('payroll::messages.finalized')</span>
                            @else
                                <span class="badge bg-warning text-dark">@lang('payroll::messages.draft')</span>
                            @endif
                        </dd>

                        @if ($payroll->finalized_at)
                            <dt class="col-6">@lang('payroll::messages.finalized-at')</dt>
                            <dd class="col-6">{{ $payroll->finalized_at->format('Y-m-d H:i') }}</dd>
                        @endif
                    </dl>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="block block-rounded">
                <div class="block-content">
                    <form method="POST" action="{{ route('payroll.payrolls.update', $payroll) }}">
                        @csrf
                        @method('PUT')

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label for="allowance_adjustment" class="form-label">@lang('payroll::messages.allowance-adjustment')</label>
                                <input id="allowance_adjustment" type="number" step="0.01" name="allowance_adjustment" class="form-control @error('allowance_adjustment') is-invalid @enderror" value="{{ old('allowance_adjustment', $payroll->allowance_adjustment) }}">
                                @error('allowance_adjustment')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="deduction_adjustment" class="form-label">@lang('payroll::messages.deduction-adjustment')</label>
                                <input id="deduction_adjustment" type="number" step="0.01" name="deduction_adjustment" class="form-control @error('deduction_adjustment') is-invalid @enderror" value="{{ old('deduction_adjustment', $payroll->deduction_adjustment) }}">
                                @error('deduction_adjustment')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input type="hidden" name="is_finalized" value="0">
                            <input class="form-check-input" type="checkbox" id="is_finalized" name="is_finalized" value="1" @checked(old('is_finalized', $payroll->is_finalized))>
                            <label class="form-check-label" for="is_finalized">@lang('payroll::messages.finalize-payroll')</label>
                        </div>

                        <button type="submit" class="btn btn-primary">@lang('payroll::messages.save-payroll')</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
