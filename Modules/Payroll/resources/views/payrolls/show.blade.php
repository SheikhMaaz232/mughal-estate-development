@extends('payroll::layouts.payroll')

@section('content')
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
            <div class="flex-grow-1">
                <h1 class="h3 fw-bold mb-3">@lang('payroll::messages.payroll-detail')</h1>
                <h2 class="fs-base lh-base fw-medium text-muted mb-0">@lang('payroll::messages.payroll-record-for', ['name' => $payroll->employee->first_name_en . ' ' . $payroll->employee->last_name_en])</h2>
            </div>
            <a href="{{ route('payroll.payrolls.edit', $payroll) }}" class="btn btn-sm btn-primary">@lang('payroll::messages.edit-payroll')</a>
        </div>
    </div>
</div>
<div class="content">
    <div class="block block-rounded">
        <div class="block-content">
            <dl class="row">
                <dt class="col-sm-4">@lang('payroll::messages.employee')</dt>
                <dd class="col-sm-8">{{ $payroll->employee->first_name_en }} {{ $payroll->employee->last_name_en }}</dd>

                <dt class="col-sm-4">@lang('payroll::messages.month')</dt>
                <dd class="col-sm-8">{{ $payroll->month }}</dd>

                <dt class="col-sm-4">@lang('payroll::messages.status')</dt>
                <dd class="col-sm-8">
                    @if ($payroll->is_finalized)
                        <span class="badge bg-success">@lang('payroll::messages.finalized')</span>
                    @else
                        <span class="badge bg-warning text-dark">@lang('payroll::messages.draft')</span>
                    @endif
                </dd>

                <dt class="col-sm-4">@lang('payroll::messages.basic-salary')</dt>
                <dd class="col-sm-8">{{ number_format($payroll->basic_salary, 2) }}</dd>

                <dt class="col-sm-4">@lang('payroll::messages.gross-salary')</dt>
                <dd class="col-sm-8">{{ number_format($payroll->gross_salary, 2) }}</dd>

                <dt class="col-sm-4">@lang('payroll::messages.net-salary')</dt>
                <dd class="col-sm-8">{{ number_format($payroll->net_salary, 2) }}</dd>

                <dt class="col-sm-4">@lang('payroll::messages.allowances')</dt>
                <dd class="col-sm-8">{{ number_format($payroll->allowance_adjustment, 2) }}</dd>

                <dt class="col-sm-4">@lang('payroll::messages.deductions')</dt>
                <dd class="col-sm-8">{{ number_format($payroll->deduction_adjustment, 2) }}</dd>

                <dt class="col-sm-4">@lang('payroll::messages.worked-days')</dt>
                <dd class="col-sm-8">{{ $payroll->total_worked_days }}</dd>

                <dt class="col-sm-4">@lang('payroll::messages.leave-days')</dt>
                <dd class="col-sm-8">{{ $payroll->total_leave_days }}</dd>

                <dt class="col-sm-4">@lang('payroll::messages.holiday-days')</dt>
                <dd class="col-sm-8">{{ $payroll->total_holiday_days }}</dd>

                <dt class="col-sm-4">@lang('payroll::messages.absent-days')</dt>
                <dd class="col-sm-8">{{ $payroll->total_absent_days }}</dd>

                <dt class="col-sm-4">@lang('payroll::messages.late-minutes')</dt>
                <dd class="col-sm-8">{{ $payroll->total_late_minutes }}</dd>

                <dt class="col-sm-4">@lang('payroll::messages.early-leave-minutes')</dt>
                <dd class="col-sm-8">{{ $payroll->total_early_leave_minutes }}</dd>

                <dt class="col-sm-4">@lang('payroll::messages.overtime-minutes')</dt>
                <dd class="col-sm-8">{{ $payroll->total_overtime_minutes }}</dd>
            </dl>
        </div>
    </div>
</div>
@endsection
