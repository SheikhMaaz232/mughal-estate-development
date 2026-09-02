@extends('payroll::layouts.payroll')

@section('title')
    @lang('payroll::messages.department_salary_summary')
@endsection

@section('content')

<div class="content">

    {{-- ==========================================================
        PAGE HEADER
    =========================================================== --}}

    <div class="block block-rounded">

        <div class="block-header block-header-default">

            <h3 class="block-title">

                <i class="fa fa-money-bill-wave me-1"></i>

                @lang('payroll::messages.department_salary_summary')

            </h3>

        </div>

        <div class="block-content">

            {{-- ==================================================
                FILTER FORM
            =================================================== --}}

            <form
                method="GET"
                action="{{ route('payroll.reports.department-salary-summary') }}"
            >

                <div class="row">

                    {{-- Month --}}

                    <div class="col-md-3 mb-3">

                        <label class="form-label">
                            @lang('payroll::messages.month')
                        </label>

                        <input
                            type="month"
                            name="month"
                            value="{{ $month }}"
                            class="form-control"
                        >

                    </div>


                    {{-- Department --}}

                    <div class="col-md-3 mb-3">

                        <label class="form-label">
                            @lang('payroll::messages.department')
                        </label>

                        <select
                            name="department_id"
                            class="form-control"
                        >

                            <option value="">
                                @lang('payroll::messages.all_departments')
                            </option>

                            @foreach($departments as $department)

                                <option
                                    value="{{ $department->id }}"
                                    {{ (string)$departmentId === (string)$department->id ? 'selected' : '' }}
                                >
                                    {{ app()->getLocale() === 'ur'
                                        ? $department->title_ur
                                        : $department->title_en
                                    }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Designation --}}

                    <div class="col-md-3 mb-3">

                        <label class="form-label">
                            @lang('payroll::messages.designation')
                        </label>

                        <select
                            name="designation_id"
                            class="form-control"
                        >

                            <option value="">
                                @lang('payroll::messages.all_designations')
                            </option>

                            @foreach($designations as $designation)

                                <option
                                    value="{{ $designation->id }}"
                                    {{ (string)$designationId === (string)$designation->id ? 'selected' : '' }}
                                >
                                    {{ app()->getLocale() === 'ur'
                                        ? $designation->title_ur
                                        : $designation->title_en
                                    }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Employee --}}

                    <div class="col-md-3 mb-3">

                        <label class="form-label">
                            @lang('payroll::messages.employee')
                        </label>

                        <select
                            name="employee_id"
                            class="form-control"
                        >

                            <option value="">
                                @lang('payroll::messages.all_employees')
                            </option>

                            @foreach($employees as $employee)

                                <option
                                    value="{{ $employee->id }}"
                                    {{ (string)$employeeId === (string)$employee->id ? 'selected' : '' }}
                                >

                                    {{ app()->getLocale() === 'ur'
                                        ? trim(($employee->first_name_ur ?? '') . ' ' . ($employee->last_name_ur ?? ''))
                                        : trim(($employee->first_name_en ?? '') . ' ' . ($employee->last_name_en ?? ''))
                                    }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- Finalized --}}

                    <div class="col-md-3 mb-3">

                        <label class="form-label">
                            @lang('payroll::messages.payroll_status')
                        </label>

                        <select
                            name="finalized"
                            class="form-control"
                        >

                            <option
                                value="all"
                                {{ $finalized === 'all' ? 'selected' : '' }}
                            >
                                @lang('payroll::messages.all')
                            </option>

                            <option
                                value="finalized"
                                {{ $finalized === 'finalized' ? 'selected' : '' }}
                            >
                                @lang('payroll::messages.finalized')
                            </option>

                            <option
                                value="unfinalized"
                                {{ $finalized === 'unfinalized' ? 'selected' : '' }}
                            >
                                @lang('payroll::messages.unfinalized')
                            </option>

                        </select>

                    </div>


                    {{-- Buttons --}}

                    <div class="col-md-9 mb-3 d-flex align-items-end">

                        <button
                            type="submit"
                            class="btn btn-primary me-2"
                        >
                            <i class="fa fa-search me-1"></i>
                            @lang('payroll::messages.filter')
                        </button>


                        <a
                            href="{{ route('payroll.reports.department-salary-summary') }}"
                            class="btn btn-secondary"
                        >
                            <i class="fa fa-refresh me-1"></i>
                            @lang('payroll::messages.reset')
                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- ==========================================================
        SUMMARY CARDS
    =========================================================== --}}

    <div class="row">

        <div class="col-md-3">

            <div class="block block-rounded">

                <div class="block-content">

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

                <div class="block-content">

                    <div class="fs-sm text-muted">
                        @lang('payroll::messages.basic_salary')
                    </div>

                    <div class="fs-2 fw-bold">
                        {{ number_format($totalBasicSalary, 2) }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="block block-rounded">

                <div class="block-content">

                    <div class="fs-sm text-muted">
                        @lang('payroll::messages.gross_salary')
                    </div>

                    <div class="fs-2 fw-bold">
                        {{ number_format($totalGrossSalary, 2) }}
                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="block block-rounded">

                <div class="block-content">

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


    {{-- ==========================================================
        DEPARTMENT SUMMARY
    =========================================================== --}}

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
                                @lang('payroll::messages.department')
                            </th>

                            <th class="text-center">
                                @lang('payroll::messages.employees')
                            </th>

                            <th class="text-end">
                                @lang('payroll::messages.basic_salary')
                            </th>

                            <th class="text-end">
                                @lang('payroll::messages.absence_deduction')
                            </th>

                            <th class="text-end">
                                @lang('payroll::messages.late_early_deduction')
                            </th>

                            <th class="text-end">
                                @lang('payroll::messages.overtime_amount')
                            </th>

                            <th class="text-end">
                                @lang('payroll::messages.allowance_adjustment')
                            </th>

                            <th class="text-end">
                                @lang('payroll::messages.deduction_adjustment')
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

                        @forelse($departmentSummary as $summary)

                            <tr>

                                <td>

                                    @if($summary['department'])

                                        {{ app()->getLocale() === 'ur'
                                            ? $summary['department']->title_ur
                                            : $summary['department']->title_en
                                        }}

                                    @else

                                        @lang('payroll::messages.not_assigned')

                                    @endif

                                </td>


                                <td class="text-center">

                                    {{ number_format(
                                        $summary['employee_count']
                                    ) }}

                                </td>


                                <td class="text-end">

                                    {{ number_format(
                                        $summary['basic_salary'],
                                        2
                                    ) }}

                                </td>


                                <td class="text-end">

                                    {{ number_format(
                                        $summary['absence_deduction'],
                                        2
                                    ) }}

                                </td>


                                <td class="text-end">

                                    {{ number_format(
                                        $summary['late_early_deduction'],
                                        2
                                    ) }}

                                </td>


                                <td class="text-end">

                                    {{ number_format(
                                        $summary['overtime_amount'],
                                        2
                                    ) }}

                                </td>


                                <td class="text-end">

                                    {{ number_format(
                                        $summary['allowance_adjustment'],
                                        2
                                    ) }}

                                </td>


                                <td class="text-end">

                                    {{ number_format(
                                        $summary['deduction_adjustment'],
                                        2
                                    ) }}

                                </td>


                                <td class="text-end fw-semibold">

                                    {{ number_format(
                                        $summary['gross_salary'],
                                        2
                                    ) }}

                                </td>


                                <td class="text-end fw-bold">

                                    {{ number_format(
                                        $summary['net_salary'],
                                        2
                                    ) }}

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="10"
                                    class="text-center text-muted py-4"
                                >

                                    @lang('payroll::messages.no_records_found')

                                </td>

                            </tr>

                        @endforelse

                    </tbody>


                    @if($departmentSummary->count())

                        <tfoot>

                            <tr class="fw-bold">

                                <td>
                                    @lang('payroll::messages.total')
                                </td>

                                <td class="text-center">
                                    {{ number_format($totalEmployees) }}
                                </td>

                                <td class="text-end">
                                    {{ number_format($totalBasicSalary, 2) }}
                                </td>

                                <td class="text-end">
                                    {{ number_format($totalAbsenceDeduction, 2) }}
                                </td>

                                <td class="text-end">
                                    {{ number_format($totalLateEarlyDeduction, 2) }}
                                </td>

                                <td class="text-end">
                                    {{ number_format($totalOvertimeAmount, 2) }}
                                </td>

                                <td class="text-end">
                                    {{ number_format($totalAllowanceAdjustment, 2) }}
                                </td>

                                <td class="text-end">
                                    {{ number_format($totalDeductionAdjustment, 2) }}
                                </td>

                                <td class="text-end">
                                    {{ number_format($totalGrossSalary, 2) }}
                                </td>

                                <td class="text-end">
                                    {{ number_format($totalNetSalary, 2) }}
                                </td>

                            </tr>

                        </tfoot>

                    @endif

                </table>

            </div>

        </div>

    </div>


    {{-- ==========================================================
        EMPLOYEE PAYROLL DETAILS
    =========================================================== --}}

    <div class="block block-rounded">

        <div class="block-header block-header-default">

            <h3 class="block-title">

                @lang('payroll::messages.employee_payroll_details')

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
                                @lang('payroll::messages.employee')
                            </th>

                            <th>
                                @lang('payroll::messages.department')
                            </th>

                            <th>
                                @lang('payroll::messages.designation')
                            </th>

                            <th class="text-end">
                                @lang('payroll::messages.basic_salary')
                            </th>

                            <th class="text-end">
                                @lang('payroll::messages.absence_deduction')
                            </th>

                            <th class="text-end">
                                @lang('payroll::messages.late_early_deduction')
                            </th>

                            <th class="text-end">
                                @lang('payroll::messages.overtime_amount')
                            </th>

                            <th class="text-end">
                                @lang('payroll::messages.gross_salary')
                            </th>

                            <th class="text-end">
                                @lang('payroll::messages.net_salary')
                            </th>

                            <th>
                                @lang('payroll::messages.status')
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($payrolls as $index => $payroll)

                            @php
                                $employee = $payroll->employee;
                            @endphp

                            <tr>

                                <td>
                                    {{ $index + 1 }}
                                </td>


                                <td>

                                    @if($employee)

                                        {{ app()->getLocale() === 'ur'
                                            ? trim(($employee->first_name_ur ?? '') . ' ' . ($employee->last_name_ur ?? ''))
                                            : trim(($employee->first_name_en ?? '') . ' ' . ($employee->last_name_en ?? ''))
                                        }}

                                    @else

                                        @lang('payroll::messages.not_assigned')

                                    @endif

                                </td>


                                <td>

                                    @if($employee && $employee->department)

                                        {{ app()->getLocale() === 'ur'
                                            ? $employee->department->title_ur
                                            : $employee->department->title_en
                                        }}

                                    @else

                                        -

                                    @endif

                                </td>


                                <td>

                                    @if($employee && $employee->designation)

                                        {{ app()->getLocale() === 'ur'
                                            ? $employee->designation->title_ur
                                            : $employee->designation->title_en
                                        }}

                                    @else

                                        -

                                    @endif

                                </td>


                                <td class="text-end">
                                    {{ number_format($payroll->basic_salary, 2) }}
                                </td>


                                <td class="text-end">
                                    {{ number_format($payroll->absence_deduction_amount, 2) }}
                                </td>


                                <td class="text-end">
                                    {{ number_format($payroll->late_early_deduction_amount, 2) }}
                                </td>


                                <td class="text-end">
                                    {{ number_format($payroll->overtime_amount, 2) }}
                                </td>


                                <td class="text-end fw-semibold">
                                    {{ number_format($payroll->gross_salary, 2) }}
                                </td>


                                <td class="text-end fw-bold">
                                    {{ number_format($payroll->net_salary, 2) }}
                                </td>


                                <td>

                                    @if($payroll->is_finalized)

                                        <span class="badge bg-success">

                                            @lang('payroll::messages.finalized')

                                        </span>

                                    @else

                                        <span class="badge bg-warning">

                                            @lang('payroll::messages.unfinalized')

                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="11"
                                    class="text-center text-muted py-4"
                                >

                                    @lang('payroll::messages.no_records_found')

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- ==========================================================
        DEDUCTION / ADJUSTMENT SUMMARY
    =========================================================== --}}

    <div class="block block-rounded">

        <div class="block-header block-header-default">

            <h3 class="block-title">

                @lang('payroll::messages.deduction_adjustment_summary')

            </h3>

        </div>


        <div class="block-content">

            <table class="table table-bordered">

                <tbody>

                    <tr>

                        <td>
                            @lang('payroll::messages.absence_deduction')
                        </td>

                        <td class="text-end">
                            {{ number_format($totalAbsenceDeduction, 2) }}
                        </td>

                    </tr>


                    <tr>

                        <td>
                            @lang('payroll::messages.late_early_deduction')
                        </td>

                        <td class="text-end">
                            {{ number_format($totalLateEarlyDeduction, 2) }}
                        </td>

                    </tr>


                    <tr>

                        <td>
                            @lang('payroll::messages.deduction_adjustment')
                        </td>

                        <td class="text-end">
                            {{ number_format($totalDeductionAdjustment, 2) }}
                        </td>

                    </tr>


                    <tr>

                        <td>
                            @lang('payroll::messages.allowance_adjustment')
                        </td>

                        <td class="text-end">
                            {{ number_format($totalAllowanceAdjustment, 2) }}
                        </td>

                    </tr>


                    <tr>

                        <td>
                            @lang('payroll::messages.overtime_amount')
                        </td>

                        <td class="text-end">
                            {{ number_format($totalOvertimeAmount, 2) }}
                        </td>

                    </tr>

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection
