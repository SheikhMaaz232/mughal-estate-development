@extends('payroll::layouts.payroll')

@section('content')

    <div class="content">

        {{-- ========================================================= --}}
        {{-- PAGE HEADER --}}
        {{-- ========================================================= --}}

        <div class="block block-rounded">

            <div class="block-header block-header-default">

                <h3 class="block-title">

                    <i class="fa fa-building me-1"></i>

                    @lang('payroll::messages.department-wise-payroll-summary-report')

                </h3>


                <div class="block-options no-print">

                    <button type="button" class="btn btn-sm btn-alt-primary" onclick="window.print()">

                        <i class="fa fa-print me-1"></i>

                        @lang('payroll::messages.print')

                    </button>

                </div>

            </div>


            <div class="block-content">

                <div class="row">

                    <div class="col-md-6">

                        <strong>

                            @lang('payroll::messages.period')

                        </strong>

                        {{ $monthName }}

                    </div>


                    <div class="col-md-6 text-end">

                        <strong>

                            @lang('payroll::messages.generated_on')

                        </strong>

                        {{ now()->format('d-m-Y h:i A') }}

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- FILTER --}}
        {{-- ========================================================= --}}

        <div class="no-print">

            @lang('payroll::messages.filters')

        </div>


        {{-- ========================================================= --}}
        {{-- SUMMARY --}}
        {{-- ========================================================= --}}

        <div class="row">

            <div class="col-md-3">

                <div class="block block-rounded">

                    <div class="block-content text-center">

                        <div class="fs-sm text-muted">

                            @lang('payroll::messages.total_employees')

                        </div>

                        <div class="fs-2 fw-bold">

                            {{ number_format($totalEmployees) }}

                        </div>

                    </div>

                </div>

            </div>


            <div class="col-md-3">

                <div class="block block-rounded">

                    <div class="block-content text-center">

                        <div class="fs-sm text-muted">

                            @lang('payroll::messages.worked_days')

                        </div>

                        <div class="fs-2 fw-bold">

                            {{ number_format($totalWorkedDays) }}

                        </div>

                    </div>

                </div>

            </div>


            <div class="col-md-3">

                <div class="block block-rounded">

                    <div class="block-content text-center">

                        <div class="fs-sm text-muted">

                            @lang('payroll::messages.overtime')

                        </div>

                        <div class="fs-2 fw-bold">

                            {{ $totalOvertimeHours }}h
                            {{ $remainingOvertimeMinutes }}m

                        </div>

                    </div>

                </div>

            </div>


            <div class="col-md-3">

                <div class="block block-rounded">

                    <div class="block-content text-center">

                        <div class="fs-sm text-muted">

                            @lang('payroll::messages.net_salary')

                        </div>

                        <div class="fs-2 fw-bold">

                            {{ number_format($totalNetSalary, 2) }}

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- DEPARTMENT SUMMARY --}}
        {{-- ========================================================= --}}

        <div class="block block-rounded">

            <div class="block-header block-header-default">

                <h3 class="block-title">

                    @lang('payroll::messages.department_summary')

                </h3>

            </div>


            <div class="block-content">

                <div class="table-responsive">

                    <table class="table table-bordered table-striped table-vcenter">

                        <thead>

                            <tr>

                                <th>
                                    #
                                </th>

                                <th>
                                    @lang('payroll::messages.department')
                                </th>

                                <th class="text-center">
                                    @lang('payroll::messages.employees')
                                </th>

                                <th class="text-center">
                                    @lang('payroll::messages.worked_days')
                                </th>

                                <th class="text-center">
                                    @lang('payroll::messages.absent_days')
                                </th>

                                <th class="text-center">
                                    @lang('payroll::messages.leave_days')
                                </th>

                                <th class="text-center">
                                    @lang('payroll::messages.holiday_days')
                                </th>

                                <th class="text-center">
                                    @lang('payroll::messages.late_minutes')
                                </th>

                                <th class="text-center">
                                    @lang('payroll::messages.early_leave_minutes')
                                </th>

                                <th class="text-center">
                                    @lang('payroll::messages.overtime')
                                </th>

                                <th class="text-end">
                                    @lang('payroll::messages.basic_salary')
                                </th>

                                <th class="text-end">
                                    @lang('payroll::messages.gross_salary')
                                </th>

                                <th class="text-end">
                                    @lang('payroll::messages.net_salary')
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse($departmentSummary
                                as $index => $summary)
                                <tr>

                                    <td>

                                        {{ $index + 1 }}

                                    </td>


                                    <td>

                                        @if ($summary['department'])
                                            {{ $summary['department']->title_en }}
                                        @else
                                            @lang('payroll::messages.no_department')
                                        @endif

                                    </td>


                                    <td class="text-center">

                                        {{ number_format($summary['employees']) }}

                                    </td>


                                    <td class="text-center">

                                        {{ number_format($summary['worked_days']) }}

                                    </td>


                                    <td class="text-center">

                                        {{ number_format($summary['absent_days']) }}

                                    </td>


                                    <td class="text-center">

                                        {{ number_format($summary['leave_days']) }}

                                    </td>


                                    <td class="text-center">

                                        {{ number_format($summary['holiday_days']) }}

                                    </td>


                                    <td class="text-center">

                                        {{ number_format($summary['late_minutes']) }}

                                    </td>


                                    <td class="text-center">

                                        {{ number_format($summary['early_leave_minutes']) }}

                                    </td>


                                    <td class="text-center">

                                        @php

                                            $hours = intdiv((int) $summary['overtime_minutes'], 60);

                                            $minutes = $summary['overtime_minutes'] % 60;
                                        @endphp

                                        {{ $hours }}h
                                        {{ $minutes }}m

                                    </td>


                                    <td class="text-end">

                                        {{ number_format($summary['basic_salary'], 2) }}

                                    </td>


                                    <td class="text-end">

                                        {{ number_format($summary['gross_salary'], 2) }}

                                    </td>


                                    <td class="text-end fw-bold">

                                        {{ number_format($summary['net_salary'], 2) }}

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="13" class="text-center py-4">

                                        @lang('payroll::messages.no_records')

                                    </td>

                                </tr>
                            @endforelse

                        </tbody>


                        <tfoot>

                            <tr class="fw-bold">

                                <td colspan="2">

                                    @lang('payroll::messages.total')

                                </td>


                                <td class="text-center">

                                    {{ number_format($totalEmployees) }}

                                </td>


                                <td class="text-center">

                                    {{ number_format($totalWorkedDays) }}

                                </td>


                                <td class="text-center">

                                    {{ number_format($totalAbsentDays) }}

                                </td>


                                <td class="text-center">

                                    {{ number_format($totalLeaveDays) }}

                                </td>


                                <td class="text-center">

                                    {{ number_format($totalHolidayDays) }}

                                </td>


                                <td class="text-center">

                                    {{ number_format($totalLateMinutes) }}

                                </td>


                                <td class="text-center">

                                    {{ number_format($totalEarlyLeaveMinutes) }}

                                </td>


                                <td class="text-center">

                                    {{ $totalOvertimeHours }}h
                                    {{ $remainingOvertimeMinutes }}m

                                </td>


                                <td class="text-end">

                                    {{ number_format($totalBasicSalary, 2) }}

                                </td>


                                <td class="text-end">

                                    {{ number_format($totalGrossSalary, 2) }}

                                </td>


                                <td class="text-end">

                                    {{ number_format($totalNetSalary, 2) }}

                                </td>

                            </tr>

                        </tfoot>

                    </table>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- SALARY SUMMARY --}}
        {{-- ========================================================= --}}

        <div class="block block-rounded">

            <div class="block-header block-header-default">

                <h3 class="block-title">

                    @lang('payroll::messages.salary_summary')

                </h3>

            </div>


            <div class="block-content">

                <div class="table-responsive">

                    <table class="table table-bordered">

                        <tbody>

                            <tr>

                                <th>

                                    @lang('payroll::messages.basic_salary')

                                </th>

                                <td class="text-end">

                                    {{ number_format($totalBasicSalary, 2) }}

                                </td>

                            </tr>


                            <tr>

                                <th>

                                    @lang('payroll::messages.absence_deduction')

                                </th>

                                <td class="text-end">

                                    {{ number_format($totalAbsenceDeduction, 2) }}

                                </td>

                            </tr>


                            <tr>

                                <th>

                                    @lang('payroll::messages.late_early_deduction')

                                </th>

                                <td class="text-end">

                                    {{ number_format($totalLateEarlyDeduction, 2) }}

                                </td>

                            </tr>


                            <tr>

                                <th>

                                    @lang('payroll::messages.overtime_amount')

                                </th>

                                <td class="text-end">

                                    {{ number_format($totalOvertimeAmount, 2) }}

                                </td>

                            </tr>


                            <tr>

                                <th>

                                    @lang('payroll::messages.allowance_adjustment')

                                </th>

                                <td class="text-end">

                                    {{ number_format($totalAllowanceAdjustment, 2) }}

                                </td>

                            </tr>


                            <tr>

                                <th>

                                    @lang('payroll::messages.deduction_adjustment')

                                </th>

                                <td class="text-end">

                                    {{ number_format($totalDeductionAdjustment, 2) }}

                                </td>

                            </tr>


                            <tr class="fw-bold">

                                <th>

                                    @lang('payroll::messages.gross_salary')

                                </th>

                                <td class="text-end">

                                    {{ number_format($totalGrossSalary, 2) }}

                                </td>

                            </tr>


                            <tr class="fw-bold">

                                <th>

                                    @lang('payroll::messages.net_salary')

                                </th>

                                <td class="text-end">

                                    {{ number_format($totalNetSalary, 2) }}

                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>


    @push('css')
        <style>
            @media print {

                .no-print {
                    display: none !important;
                }

                .content {
                    width: 100% !important;
                    max-width: 100% !important;
                    margin: 0 !important;
                    padding: 0 !important;
                }

                .block {
                    border: 0 !important;
                    box-shadow: none !important;
                }

                .table {
                    font-size: 8px !important;
                }

                .table th,
                .table td {
                    padding: 3px !important;
                }

                @page {
                    size: A3 landscape;
                    margin: 6mm;
                }
            }
        </style>
    @endpush

@endsection
