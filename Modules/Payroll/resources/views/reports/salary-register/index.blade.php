@extends('payroll::layouts.payroll')


@section('content')

    @php

        $isUrdu = app()->getLocale() === 'ur';

        $months = [
            1 => $isUrdu ? 'جنوری' : 'January',

            2 => $isUrdu ? 'فروری' : 'February',

            3 => $isUrdu ? 'مارچ' : 'March',

            4 => $isUrdu ? 'اپریل' : 'April',

            5 => $isUrdu ? 'مئی' : 'May',

            6 => $isUrdu ? 'جون' : 'June',

            7 => $isUrdu ? 'جولائی' : 'July',

            8 => $isUrdu ? 'اگست' : 'August',

            9 => $isUrdu ? 'ستمبر' : 'September',

            10 => $isUrdu ? 'اکتوبر' : 'October',

            11 => $isUrdu ? 'نومبر' : 'November',

            12 => $isUrdu ? 'دسمبر' : 'December',
        ];

        $monthNumber = (int) $month;

    @endphp


    <div class="content" dir="{{ $isUrdu ? 'rtl' : 'ltr' }}">


        {{-- ================================================================ --}}
        {{-- FILTER --}}
        {{-- ================================================================ --}}

        @include('payroll::reports.salary-register.filters')


        {{-- ================================================================ --}}
        {{-- REPORT --}}
        {{-- ================================================================ --}}

        <div class="block block-rounded">


            {{-- ============================================================ --}}
            {{-- HEADER --}}
            {{-- ============================================================ --}}

            <div class="block-header block-header-default">

                <div>

                    <h3 class="block-title">

                        <i class="fa fa-money-bill-wave me-1"></i>

                        {{ $isUrdu ? 'ماہانہ پے رول سیلری رجسٹر' : 'Monthly Payroll Salary Register' }}

                    </h3>


                    <small class="text-muted">

                        {{ $months[$monthNumber] }}

                        {{ $year }}

                    </small>

                </div>


                <div>

                    <strong>

                        {{ $payrolls->count() }}

                        {{ $isUrdu ? 'ملازمین' : 'Employees' }}

                    </strong>

                </div>

            </div>


            <div class="block-content">


                {{-- ======================================================== --}}
                {{-- SUMMARY CARDS --}}
                {{-- ======================================================== --}}

                <div class="row mb-4">


                    {{-- Employees --}}
                    <div class="col-md-2">

                        <div class="border rounded p-3">

                            <small class="text-muted">

                                {{ $isUrdu ? 'ملازمین' : 'Employees' }}

                            </small>

                            <div class="fs-3 fw-bold">

                                {{ number_format($summary['employees']) }}

                            </div>

                        </div>

                    </div>


                    {{-- Basic --}}
                    <div class="col-md-2">

                        <div class="border rounded p-3">

                            <small class="text-muted">

                                {{ $isUrdu ? 'بنیادی تنخواہ' : 'Basic Salary' }}

                            </small>

                            <div class="fs-5 fw-bold">

                                {{ number_format($summary['basic_salary'], 2) }}

                            </div>

                        </div>

                    </div>


                    {{-- Gross --}}
                    <div class="col-md-2">

                        <div class="border rounded p-3">

                            <small class="text-muted">

                                {{ $isUrdu ? 'مجموعی تنخواہ' : 'Gross Salary' }}

                            </small>

                            <div class="fs-5 fw-bold">

                                {{ number_format($summary['gross_salary'], 2) }}

                            </div>

                        </div>

                    </div>


                    {{-- Deductions --}}
                    <div class="col-md-3">

                        <div class="border rounded p-3">

                            <small class="text-muted">

                                {{ $isUrdu ? 'کل کٹوتیاں' : 'Total Deductions' }}

                            </small>

                            @php

                                $totalDeductions =
                                    $summary['absence_deduction'] +
                                    $summary['late_early_deduction'] +
                                    $summary['deduction_adjustment'];

                            @endphp

                            <div class="fs-5 fw-bold">

                                {{ number_format($totalDeductions, 2) }}

                            </div>

                        </div>

                    </div>


                    {{-- Net --}}
                    <div class="col-md-3">

                        <div class="border rounded p-3">

                            <small class="text-muted">

                                {{ $isUrdu ? 'خالص تنخواہ' : 'Net Salary' }}

                            </small>

                            <div class="fs-4 fw-bold">

                                {{ number_format($summary['net_salary'], 2) }}

                            </div>

                        </div>

                    </div>

                </div>


                {{-- ======================================================== --}}
                {{-- FINALIZATION SUMMARY --}}
                {{-- ======================================================== --}}

                <div class="row mb-4">

                    <div class="col-md-6">

                        <span class="badge bg-success">

                            {{ $isUrdu ? 'حتمی شدہ' : 'Finalized' }}:

                            {{ $summary['finalized'] }}

                        </span>

                    </div>


                    <div class="col-md-6 text-end">

                        <span class="badge bg-warning">

                            {{ $isUrdu ? 'غیر حتمی' : 'Not Finalized' }}:

                            {{ $summary['not_finalized'] }}

                        </span>

                    </div>

                </div>


                {{-- ======================================================== --}}
                {{-- TABLE --}}
                {{-- ======================================================== --}}

                <div class="table-responsive">

                    <table class="table table-bordered table-hover table-vcenter">

                        <thead>

                            <tr>

                                <th>#</th>

                                <th>
                                    {{ $isUrdu ? 'ملازم' : 'Employee' }}
                                </th>

                                <th>
                                    {{ $isUrdu ? 'شعبہ' : 'Department' }}
                                </th>

                                <th>
                                    {{ $isUrdu ? 'عہدہ' : 'Designation' }}
                                </th>

                                <th class="text-end">
                                    {{ $isUrdu ? 'بنیادی' : 'Basic' }}
                                </th>

                                <th class="text-center">
                                    {{ $isUrdu ? 'کام' : 'Worked' }}
                                </th>

                                <th class="text-center">
                                    {{ $isUrdu ? 'غیر حاضر' : 'Absent' }}
                                </th>

                                <th class="text-center">
                                    {{ $isUrdu ? 'چھٹی' : 'Leave' }}
                                </th>

                                <th class="text-center">
                                    {{ $isUrdu ? 'اوور ٹائم' : 'OT' }}
                                </th>

                                <th class="text-end">
                                    {{ $isUrdu ? 'غیر حاضری کٹوتی' : 'Absence Ded.' }}
                                </th>

                                <th class="text-end">
                                    {{ $isUrdu ? 'تاخیر کٹوتی' : 'Late/Early Ded.' }}
                                </th>

                                <th class="text-end">
                                    {{ $isUrdu ? 'اوور ٹائم رقم' : 'OT Amount' }}
                                </th>

                                <th class="text-end">
                                    {{ $isUrdu ? 'الاؤنس' : 'Allowance' }}
                                </th>

                                <th class="text-end">
                                    {{ $isUrdu ? 'کٹوتی' : 'Deduction' }}
                                </th>

                                <th class="text-end">
                                    {{ $isUrdu ? 'مجموعی' : 'Gross' }}
                                </th>

                                <th class="text-end">
                                    {{ $isUrdu ? 'خالص' : 'Net' }}
                                </th>

                                <th>
                                    {{ $isUrdu ? 'حالت' : 'Status' }}
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse($payrolls as $index => $payroll)
                                @php

                                    $employee = $payroll->employee;

                                    $employeeName = $isUrdu
                                        ? trim(($employee->first_name_ur ?? '') . ' ' . ($employee->last_name_ur ?? ''))
                                        : trim(
                                            ($employee->first_name_en ?? '') . ' ' . ($employee->last_name_en ?? ''),
                                        );

                                    $department = $employee->department;

                                    $designation = $employee->designation;
                                @endphp


                                <tr>


                                    {{-- # --}}
                                    <td>

                                        {{ $index + 1 }}

                                    </td>


                                    {{-- Employee --}}
                                    <td>

                                        <div class="fw-semibold">

                                            {{ $employeeName ?: '-' }}

                                        </div>


                                        <small class="text-muted">

                                            #{{ $employee->id }}

                                            @if ($employee->device_user_id)
                                                |
                                                {{ $employee->device_user_id }}
                                            @endif

                                        </small>

                                    </td>


                                    {{-- Department --}}
                                    <td>

                                        @if ($department)
                                            {{ $isUrdu ? $department->title_ur : $department->title_en }}
                                        @else
                                            -
                                        @endif

                                    </td>


                                    {{-- Designation --}}
                                    <td>

                                        @if ($designation)
                                            {{ $isUrdu ? $designation->title_ur : $designation->title_en }}
                                        @else
                                            -
                                        @endif

                                    </td>


                                    {{-- Basic --}}
                                    <td class="text-end">

                                        {{ number_format((float) $payroll->basic_salary, 2) }}

                                    </td>


                                    {{-- Worked --}}
                                    <td class="text-center">

                                        {{ $payroll->total_worked_days }}

                                    </td>


                                    {{-- Absent --}}
                                    <td class="text-center">

                                        {{ $payroll->total_absent_days }}

                                    </td>


                                    {{-- Leave --}}
                                    <td class="text-center">

                                        {{ $payroll->total_leave_days }}

                                    </td>


                                    {{-- Overtime --}}
                                    <td class="text-center">

                                        @php

                                            $otMinutes = (int) $payroll->total_overtime_minutes;

                                            $otHours = intdiv($otMinutes, 60);

                                            $otRemaining = $otMinutes % 60;
                                        @endphp


                                        {{ $otHours }}h
                                        {{ str_pad($otRemaining, 2, '0', STR_PAD_LEFT) }}m

                                    </td>


                                    {{-- Absence Deduction --}}
                                    <td class="text-end">

                                        {{ number_format((float) $payroll->absence_deduction_amount, 2) }}

                                    </td>


                                    {{-- Late/Early Deduction --}}
                                    <td class="text-end">

                                        {{ number_format((float) $payroll->late_early_deduction_amount, 2) }}

                                    </td>


                                    {{-- Overtime Amount --}}
                                    <td class="text-end">

                                        {{ number_format((float) $payroll->overtime_amount, 2) }}

                                    </td>


                                    {{-- Allowance --}}
                                    <td class="text-end">

                                        {{ number_format((float) $payroll->allowance_adjustment, 2) }}

                                    </td>


                                    {{-- Deduction --}}
                                    <td class="text-end">

                                        {{ number_format((float) $payroll->deduction_adjustment, 2) }}

                                    </td>


                                    {{-- Gross --}}
                                    <td class="text-end fw-semibold">

                                        {{ number_format((float) $payroll->gross_salary, 2) }}

                                    </td>


                                    {{-- Net --}}
                                    <td class="text-end fw-bold">

                                        {{ number_format((float) $payroll->net_salary, 2) }}

                                    </td>


                                    {{-- Status --}}
                                    <td>

                                        @if ($payroll->is_finalized)
                                            <span class="badge bg-success">

                                                {{ $isUrdu ? 'حتمی' : 'Finalized' }}

                                            </span>
                                        @else
                                            <span class="badge bg-warning">

                                                {{ $isUrdu ? 'غیر حتمی' : 'Draft' }}

                                            </span>
                                        @endif

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="17" class="text-center py-5">

                                        <i class="fa fa-file-invoice-dollar fa-3x text-muted mb-3"></i>


                                        <h4>

                                            {{ $isUrdu ? 'پے رول ریکارڈ نہیں ملا' : 'No Payroll Records Found' }}

                                        </h4>


                                        <p class="text-muted mb-0">

                                            {{ $isUrdu
                                                ? 'منتخب مہینے اور فلٹرز کے لیے کوئی پے رول ریکارڈ موجود نہیں ہے۔'
                                                : 'No payroll records were found for the selected month and filters.' }}

                                        </p>

                                    </td>

                                </tr>
                            @endforelse

                        </tbody>


                        {{-- ================================================= --}}
                        {{-- TOTAL --}}
                        {{-- ================================================= --}}

                        @if ($payrolls->count())
                            <tfoot>

                                <tr class="fw-bold">

                                    <td colspan="4" class="text-end">

                                        {{ $isUrdu ? 'کل' : 'TOTAL' }}

                                    </td>


                                    <td class="text-end">

                                        {{ number_format($summary['basic_salary'], 2) }}

                                    </td>


                                    <td class="text-center">

                                        {{ $summary['worked_days'] }}

                                    </td>


                                    <td class="text-center">

                                        {{ $summary['absent_days'] }}

                                    </td>


                                    <td class="text-center">

                                        {{ $summary['leave_days'] }}

                                    </td>


                                    <td class="text-center">

                                        @php

                                            $totalOtMinutes = (int) $summary['overtime_minutes'];

                                            $totalOtHours = intdiv($totalOtMinutes, 60);

                                            $totalOtRemaining = $totalOtMinutes % 60;
                                        @endphp


                                        {{ $totalOtHours }}h
                                        {{ str_pad($totalOtRemaining, 2, '0', STR_PAD_LEFT) }}m

                                    </td>


                                    <td class="text-end">

                                        {{ number_format($summary['absence_deduction'], 2) }}

                                    </td>


                                    <td class="text-end">

                                        {{ number_format($summary['late_early_deduction'], 2) }}

                                    </td>


                                    <td class="text-end">

                                        {{ number_format($summary['overtime_amount'], 2) }}

                                    </td>


                                    <td class="text-end">

                                        {{ number_format($summary['allowance_adjustment'], 2) }}

                                    </td>


                                    <td class="text-end">

                                        {{ number_format($summary['deduction_adjustment'], 2) }}

                                    </td>


                                    <td class="text-end">

                                        {{ number_format($summary['gross_salary'], 2) }}

                                    </td>


                                    <td class="text-end">

                                        {{ number_format($summary['net_salary'], 2) }}

                                    </td>


                                    <td>

                                        -

                                    </td>

                                </tr>

                            </tfoot>
                        @endif

                    </table>

                </div>

            </div>

        </div>

    </div>


    {{-- ================================================================ --}}
    {{-- PRINT CSS --}}
    {{-- ================================================================ --}}

    @push('css')
        <style>
            @media print {

                body {
                    background: #fff !important;
                }

                .sidebar,
                .nav-main,
                .header-navbar,
                form,
                .btn {
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
                    font-size: 7px !important;
                }

                .table th,
                .table td {
                    padding: 3px !important;
                }

                @page {
                    size: A4 landscape;
                    margin: 6mm;
                }

            }
        </style>
    @endpush

@endsection
