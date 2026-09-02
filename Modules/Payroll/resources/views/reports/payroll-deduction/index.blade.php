@extends('payroll::layouts.payroll')

@section('content')

<div class="content">

    {{-- ========================================================= --}}
    {{-- PAGE HEADER --}}
    {{-- ========================================================= --}}

    <div class="block block-rounded">

        <div class="block-header block-header-default">

            <h3 class="block-title">
                @if(app()->getLocale() == 'ur')
                    تنخواہ کٹوتی رپورٹ
                @else
                    Payroll Deduction Report
                @endif
            </h3>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- FILTER --}}
    {{-- ========================================================= --}}

    <div class="block block-rounded">

        <div class="block-header block-header-default">

            <h3 class="block-title">

                @if(app()->getLocale() == 'ur')
                    فلٹر
                @else
                    Filters
                @endif

            </h3>

        </div>


        <div class="block-content">

            <form
                method="GET"
                action="{{ route('payroll.reports.payroll-deduction') }}"
            >

                <div class="row">


                    {{-- MONTH --}}

                    <div class="col-md-3 mb-4">

                        <label
                            class="form-label"
                            for="month"
                        >

                            @if(app()->getLocale() == 'ur')
                                مہینہ
                            @else
                                Month
                            @endif

                        </label>

                        <input
                            type="month"
                            name="month"
                            id="month"
                            class="form-control"
                            value="{{ $selectedMonth }}"
                        >

                    </div>


                    {{-- EMPLOYEE --}}

                    <div class="col-md-3 mb-4">

                        <label
                            class="form-label"
                            for="employee_id"
                        >

                            @if(app()->getLocale() == 'ur')
                                ملازم
                            @else
                                Employee
                            @endif

                        </label>

                        <select
                            name="employee_id"
                            id="employee_id"
                            class="form-select"
                        >

                            <option value="">

                                @if(app()->getLocale() == 'ur')
                                    تمام ملازمین
                                @else
                                    All Employees
                                @endif

                            </option>


                            @foreach($employees as $employee)

                                <option
                                    value="{{ $employee->id }}"
                                    {{ $employeeId == $employee->id ? 'selected' : '' }}
                                >

                                    {{ $employee->first_name_en }}
                                    {{ $employee->last_name_en }}

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- DEPARTMENT --}}

                    <div class="col-md-3 mb-4">

                        <label
                            class="form-label"
                            for="department_id"
                        >

                            @if(app()->getLocale() == 'ur')
                                شعبہ
                            @else
                                Department
                            @endif

                        </label>

                        <select
                            name="department_id"
                            id="department_id"
                            class="form-select"
                        >

                            <option value="">

                                @if(app()->getLocale() == 'ur')
                                    تمام شعبے
                                @else
                                    All Departments
                                @endif

                            </option>


                            @foreach($departments as $department)

                                <option
                                    value="{{ $department->id }}"
                                    {{ $departmentId == $department->id ? 'selected' : '' }}
                                >

                                    @if(app()->getLocale() == 'ur')

                                        {{ $department->title_ur }}

                                    @else

                                        {{ $department->title_en }}

                                    @endif

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- DESIGNATION --}}

                    <div class="col-md-3 mb-4">

                        <label
                            class="form-label"
                            for="designation_id"
                        >

                            @if(app()->getLocale() == 'ur')
                                عہدہ
                            @else
                                Designation
                            @endif

                        </label>

                        <select
                            name="designation_id"
                            id="designation_id"
                            class="form-select"
                        >

                            <option value="">

                                @if(app()->getLocale() == 'ur')
                                    تمام عہدے
                                @else
                                    All Designations
                                @endif

                            </option>


                            @foreach($designations as $designation)

                                <option
                                    value="{{ $designation->id }}"
                                    {{ $designationId == $designation->id ? 'selected' : '' }}
                                >

                                    @if(app()->getLocale() == 'ur')

                                        {{ $designation->title_ur }}

                                    @else

                                        {{ $designation->title_en }}

                                    @endif

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- SHIFT --}}

                    <div class="col-md-3 mb-4">

                        <label
                            class="form-label"
                            for="shift_id"
                        >

                            @if(app()->getLocale() == 'ur')
                                شفٹ
                            @else
                                Shift
                            @endif

                        </label>

                        <select
                            name="shift_id"
                            id="shift_id"
                            class="form-select"
                        >

                            <option value="">

                                @if(app()->getLocale() == 'ur')
                                    تمام شفٹیں
                                @else
                                    All Shifts
                                @endif

                            </option>


                            @foreach($shifts as $shift)

                                <option
                                    value="{{ $shift->id }}"
                                    {{ $shiftId == $shift->id ? 'selected' : '' }}
                                >

                                    @if(app()->getLocale() == 'ur')

                                        {{ $shift->shift_name_ur }}

                                    @else

                                        {{ $shift->shift_name_en }}

                                    @endif

                                </option>

                            @endforeach

                        </select>

                    </div>


                    {{-- BUTTONS --}}

                    <div class="col-md-6 mb-4 d-flex align-items-end">

                        <button
                            type="submit"
                            class="btn btn-primary me-2"
                        >

                            <i class="fa fa-search me-1"></i>

                            @if(app()->getLocale() == 'ur')
                                رپورٹ دیکھیں
                            @else
                                Generate Report
                            @endif

                        </button>


                        <a
                            href="{{ route('payroll.reports.payroll-deduction') }}"
                            class="btn btn-secondary"
                        >

                            <i class="fa fa-refresh me-1"></i>

                            @if(app()->getLocale() == 'ur')
                                ری سیٹ
                            @else
                                Reset
                            @endif

                        </a>

                    </div>

                </div>

            </form>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- SUMMARY CARDS --}}
    {{-- ========================================================= --}}

    <div class="row">


        {{-- EMPLOYEES --}}

        <div class="col-md-3">

            <div class="block block-rounded">

                <div class="block-content">

                    <div class="fs-sm fw-semibold text-uppercase text-muted">

                        @if(app()->getLocale() == 'ur')
                            ملازمین
                        @else
                            Employees
                        @endif

                    </div>

                    <div class="fs-2 fw-bold">

                        {{ number_format($totalEmployees) }}

                    </div>

                </div>

            </div>

        </div>


        {{-- ABSENCE DEDUCTION --}}

        <div class="col-md-3">

            <div class="block block-rounded">

                <div class="block-content">

                    <div class="fs-sm fw-semibold text-uppercase text-muted">

                        @if(app()->getLocale() == 'ur')
                            غیر حاضری کٹوتی
                        @else
                            Absence Deduction
                        @endif

                    </div>

                    <div class="fs-2 fw-bold">

                        {{ number_format($totalAbsenceDeduction, 2) }}

                    </div>

                </div>

            </div>

        </div>


        {{-- LATE EARLY --}}

        <div class="col-md-3">

            <div class="block block-rounded">

                <div class="block-content">

                    <div class="fs-sm fw-semibold text-uppercase text-muted">

                        @if(app()->getLocale() == 'ur')
                            تاخیر / جلدی جانے کی کٹوتی
                        @else
                            Late / Early Deduction
                        @endif

                    </div>

                    <div class="fs-2 fw-bold">

                        {{ number_format($totalLateEarlyDeduction, 2) }}

                    </div>

                </div>

            </div>

        </div>


        {{-- TOTAL DEDUCTIONS --}}

        <div class="col-md-3">

            <div class="block block-rounded">

                <div class="block-content">

                    <div class="fs-sm fw-semibold text-uppercase text-muted">

                        @if(app()->getLocale() == 'ur')
                            کل کٹوتیاں
                        @else
                            Total Deductions
                        @endif

                    </div>

                    <div class="fs-2 fw-bold">

                        {{ number_format($totalDeductions, 2) }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- REPORT TABLE --}}
    {{-- ========================================================= --}}

    <div class="block block-rounded">

        <div class="block-header block-header-default">

            <h3 class="block-title">

                @if(app()->getLocale() == 'ur')

                    {{ $monthName }} - تنخواہ کٹوتی

                @else

                    {{ $monthName }} - Payroll Deduction

                @endif

            </h3>

        </div>


        <div class="block-content">

            <div class="table-responsive">

                <table class="table table-bordered table-striped table-vcenter">

                    <thead>

                        <tr>

                            <th>#</th>

                            <th>

                                @if(app()->getLocale() == 'ur')
                                    ملازم
                                @else
                                    Employee
                                @endif

                            </th>

                            <th>

                                @if(app()->getLocale() == 'ur')
                                    شعبہ
                                @else
                                    Department
                                @endif

                            </th>

                            <th>

                                @if(app()->getLocale() == 'ur')
                                    عہدہ
                                @else
                                    Designation
                                @endif

                            </th>

                            <th class="text-end">

                                @if(app()->getLocale() == 'ur')
                                    بنیادی تنخواہ
                                @else
                                    Basic Salary
                                @endif

                            </th>

                            <th class="text-end">

                                @if(app()->getLocale() == 'ur')
                                    غیر حاضری
                                @else
                                    Absence Deduction
                                @endif

                            </th>

                            <th class="text-end">

                                @if(app()->getLocale() == 'ur')
                                    تاخیر / جلدی
                                @else
                                    Late / Early
                                @endif

                            </th>

                            <th class="text-end">

                                @if(app()->getLocale() == 'ur')
                                    ایڈجسٹمنٹ
                                @else
                                    Deduction Adjustment
                                @endif

                            </th>

                            <th class="text-end">

                                @if(app()->getLocale() == 'ur')
                                    کل کٹوتی
                                @else
                                    Total Deduction
                                @endif

                            </th>

                            <th class="text-end">

                                @if(app()->getLocale() == 'ur')
                                    مجموعی تنخواہ
                                @else
                                    Gross Salary
                                @endif

                            </th>

                            <th class="text-end">

                                @if(app()->getLocale() == 'ur')
                                    خالص تنخواہ
                                @else
                                    Net Salary
                                @endif

                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($payrolls as $index => $payroll)

                            @php

                                $employee = $payroll->employee;

                                $department = optional(
                                    $employee
                                )->department;

                                $designation = optional(
                                    $employee
                                )->designation;

                            @endphp


                            <tr>

                                <td>
                                    {{ $index + 1 }}
                                </td>


                                <td>

                                    @if(app()->getLocale() == 'ur')

                                        {{ $employee->first_name_ur }}
                                        {{ $employee->last_name_ur }}

                                    @else

                                        {{ $employee->first_name_en }}
                                        {{ $employee->last_name_en }}

                                    @endif

                                </td>


                                <td>

                                    @if(app()->getLocale() == 'ur')

                                        {{ optional($department)->title_ur ?? '-' }}

                                    @else

                                        {{ optional($department)->title_en ?? '-' }}

                                    @endif

                                </td>


                                <td>

                                    @if(app()->getLocale() == 'ur')

                                        {{ optional($designation)->title_ur ?? '-' }}

                                    @else

                                        {{ optional($designation)->title_en ?? '-' }}

                                    @endif

                                </td>


                                <td class="text-end">

                                    {{ number_format(
                                        (float) $payroll->basic_salary,
                                        2
                                    ) }}

                                </td>


                                <td class="text-end">

                                    {{ number_format(
                                        (float) $payroll->absence_deduction_amount,
                                        2
                                    ) }}

                                </td>


                                <td class="text-end">

                                    {{ number_format(
                                        (float) $payroll->late_early_deduction_amount,
                                        2
                                    ) }}

                                </td>


                                <td class="text-end">

                                    {{ number_format(
                                        (float) $payroll->deduction_adjustment,
                                        2
                                    ) }}

                                </td>


                                <td class="text-end fw-bold">

                                    {{ number_format(
                                        (float) $payroll->total_deduction,
                                        2
                                    ) }}

                                </td>


                                <td class="text-end">

                                    {{ number_format(
                                        (float) $payroll->gross_salary,
                                        2
                                    ) }}

                                </td>


                                <td class="text-end fw-bold">

                                    {{ number_format(
                                        (float) $payroll->net_salary,
                                        2
                                    ) }}

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="11"
                                    class="text-center py-5"
                                >

                                    <i class="fa fa-info-circle fa-2x mb-2"></i>

                                    <div>

                                        @if(app()->getLocale() == 'ur')

                                            اس ماہ کے لیے کوئی کٹوتی ریکارڈ نہیں ملی۔

                                        @else

                                            No deduction records found for the selected month.

                                        @endif

                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>


                    @if($payrolls->count() > 0)

                        <tfoot>

                            <tr class="fw-bold">

                                <td colspan="4" class="text-end">

                                    @if(app()->getLocale() == 'ur')
                                        کل
                                    @else
                                        Total
                                    @endif

                                </td>


                                <td class="text-end">

                                    {{ number_format(
                                        $totalBasicSalary,
                                        2
                                    ) }}

                                </td>


                                <td class="text-end">

                                    {{ number_format(
                                        $totalAbsenceDeduction,
                                        2
                                    ) }}

                                </td>


                                <td class="text-end">

                                    {{ number_format(
                                        $totalLateEarlyDeduction,
                                        2
                                    ) }}

                                </td>


                                <td class="text-end">

                                    {{ number_format(
                                        $totalDeductionAdjustment,
                                        2
                                    ) }}

                                </td>


                                <td class="text-end">

                                    {{ number_format(
                                        $totalDeductions,
                                        2
                                    ) }}

                                </td>


                                <td class="text-end">

                                    {{ number_format(
                                        $totalGrossSalary,
                                        2
                                    ) }}

                                </td>


                                <td class="text-end">

                                    {{ number_format(
                                        $totalNetSalary,
                                        2
                                    ) }}

                                </td>

                            </tr>

                        </tfoot>

                    @endif

                </table>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- DEPARTMENT SUMMARY --}}
    {{-- ========================================================= --}}

    <div class="block block-rounded">

        <div class="block-header block-header-default">

            <h3 class="block-title">

                @if(app()->getLocale() == 'ur')
                    شعبہ وار کٹوتی کا خلاصہ
                @else
                    Department-wise Deduction Summary
                @endif

            </h3>

        </div>


        <div class="block-content">

            <div class="table-responsive">

                <table class="table table-bordered table-striped">

                    <thead>

                        <tr>

                            <th>#</th>

                            <th>

                                @if(app()->getLocale() == 'ur')
                                    شعبہ
                                @else
                                    Department
                                @endif

                            </th>

                            <th class="text-end">

                                @if(app()->getLocale() == 'ur')
                                    ملازمین
                                @else
                                    Employees
                                @endif

                            </th>

                            <th class="text-end">

                                @if(app()->getLocale() == 'ur')
                                    بنیادی تنخواہ
                                @else
                                    Basic Salary
                                @endif

                            </th>

                            <th class="text-end">

                                @if(app()->getLocale() == 'ur')
                                    غیر حاضری
                                @else
                                    Absence
                                @endif

                            </th>

                            <th class="text-end">

                                @if(app()->getLocale() == 'ur')
                                    تاخیر / جلدی
                                @else
                                    Late / Early
                                @endif

                            </th>

                            <th class="text-end">

                                @if(app()->getLocale() == 'ur')
                                    ایڈجسٹمنٹ
                                @else
                                    Adjustment
                                @endif

                            </th>

                            <th class="text-end">

                                @if(app()->getLocale() == 'ur')
                                    کل کٹوتی
                                @else
                                    Total Deduction
                                @endif

                            </th>

                            <th class="text-end">

                                @if(app()->getLocale() == 'ur')
                                    خالص تنخواہ
                                @else
                                    Net Salary
                                @endif

                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($departmentSummary as $index => $summary)

                            <tr>

                                <td>
                                    {{ $index + 1 }}
                                </td>


                                <td>

                                    @if(app()->getLocale() == 'ur')

                                        {{ optional($summary['department'])->title_ur ?? 'غیر مقرر' }}

                                    @else

                                        {{ optional($summary['department'])->title_en ?? 'Unassigned' }}

                                    @endif

                                </td>


                                <td class="text-end">

                                    {{ number_format(
                                        $summary['employees']
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
                                        $summary['deduction_adjustment'],
                                        2
                                    ) }}

                                </td>


                                <td class="text-end fw-bold">

                                    {{ number_format(
                                        $summary['total_deductions'],
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
                                    colspan="9"
                                    class="text-center py-4"
                                >

                                    @if(app()->getLocale() == 'ur')

                                        کوئی ڈیٹا دستیاب نہیں۔

                                    @else

                                        No data available.

                                    @endif

                                </td>

                            </tr>

                        @endforelse

                    </tbody>


                    @if($payrolls->count() > 0)

                        <tfoot>

                            <tr class="fw-bold">

                                <td colspan="2" class="text-end">

                                    @if(app()->getLocale() == 'ur')
                                        کل
                                    @else
                                        Total
                                    @endif

                                </td>

                                <td class="text-end">

                                    {{ number_format(
                                        $totalEmployees
                                    ) }}

                                </td>

                                <td class="text-end">

                                    {{ number_format(
                                        $totalBasicSalary,
                                        2
                                    ) }}

                                </td>

                                <td class="text-end">

                                    {{ number_format(
                                        $totalAbsenceDeduction,
                                        2
                                    ) }}

                                </td>

                                <td class="text-end">

                                    {{ number_format(
                                        $totalLateEarlyDeduction,
                                        2
                                    ) }}

                                </td>

                                <td class="text-end">

                                    {{ number_format(
                                        $totalDeductionAdjustment,
                                        2
                                    ) }}

                                </td>

                                <td class="text-end">

                                    {{ number_format(
                                        $totalDeductions,
                                        2
                                    ) }}

                                </td>

                                <td class="text-end">

                                    {{ number_format(
                                        $totalNetSalary,
                                        2
                                    ) }}

                                </td>

                            </tr>

                        </tfoot>

                    @endif

                </table>

            </div>

        </div>

    </div>

</div>

@endsection
