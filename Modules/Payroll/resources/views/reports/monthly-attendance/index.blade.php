@extends('payroll::layouts.payroll')

@section('content')

    @php
        $isUrdu = app()->getLocale() === 'ur';

        $monthName = $startDate->translatedFormat('F');

        $displayMonth = $isUrdu
            ? match ($month) {
                1 => 'جنوری',
                2 => 'فروری',
                3 => 'مارچ',
                4 => 'اپریل',
                5 => 'مئی',
                6 => 'جون',
                7 => 'جولائی',
                8 => 'اگست',
                9 => 'ستمبر',
                10 => 'اکتوبر',
                11 => 'نومبر',
                12 => 'دسمبر',
                default => $monthName,
            }
            : $monthName;
    @endphp


    <div class="content" dir="{{ $isUrdu ? 'rtl' : 'ltr' }}">

        {{-- ================================================================ --}}
        {{-- HEADER --}}
        {{-- ================================================================ --}}

        <div class="block block-rounded">

            <div class="block-header block-header-default">

                <h3 class="block-title">

                    <i class="fa fa-calendar me-1"></i>

                    {{ $isUrdu ? 'ماہانہ حاضری رپورٹ' : 'Monthly Attendance Report' }}

                </h3>

            </div>


            <div class="block-content">

                <div class="row">

                    <div class="col-md-6">

                        <h4 class="mb-1">

                            {{ $isUrdu ? 'ماہانہ حاضری رپورٹ' : 'Monthly Attendance Report' }}

                        </h4>

                        <p class="text-muted">

                            {{ $displayMonth }}
                            {{ $year }}

                        </p>

                    </div>


                    <div class="col-md-6 text-md-end">

                        <strong>

                            {{ $isUrdu ? 'مدت:' : 'Period:' }}

                        </strong>

                        {{ $startDate->format('d-m-Y') }}

                        -

                        {{ $endDate->format('d-m-Y') }}

                    </div>

                </div>

            </div>

        </div>


        {{-- ================================================================ --}}
        {{-- FILTERS --}}
        {{-- ================================================================ --}}

        @include('payroll::reports.monthly-attendance.filters')


        {{-- ================================================================ --}}
        {{-- SUMMARY --}}
        {{-- ================================================================ --}}

        <div class="row">

            {{-- Employees --}}
            <div class="col-md-3">

                <div class="block block-rounded">

                    <div class="block-content block-content-full">

                        <div class="text-muted">

                            {{ $isUrdu ? 'کل ملازمین' : 'Total Employees' }}

                        </div>

                        <div class="fs-2 fw-bold">

                            {{ number_format($summary['employees']) }}

                        </div>

                    </div>

                </div>

            </div>


            {{-- Present --}}
            <div class="col-md-3">

                <div class="block block-rounded">

                    <div class="block-content block-content-full">

                        <div class="text-muted">

                            {{ $isUrdu ? 'حاضری کے دن' : 'Present Days' }}

                        </div>

                        <div class="fs-2 fw-bold">

                            {{ number_format($summary['present_days']) }}

                        </div>

                    </div>

                </div>

            </div>


            {{-- Absent --}}
            <div class="col-md-3">

                <div class="block block-rounded">

                    <div class="block-content block-content-full">

                        <div class="text-muted">

                            {{ $isUrdu ? 'غیر حاضری کے دن' : 'Absent Days' }}

                        </div>

                        <div class="fs-2 fw-bold">

                            {{ number_format($summary['absent_days']) }}

                        </div>

                    </div>

                </div>

            </div>


            {{-- Overtime --}}
            <div class="col-md-3">

                <div class="block block-rounded">

                    <div class="block-content block-content-full">

                        <div class="text-muted">

                            {{ $isUrdu ? 'اوور ٹائم منٹ' : 'Overtime Minutes' }}

                        </div>

                        <div class="fs-2 fw-bold">

                            {{ number_format($summary['overtime_minutes']) }}

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ================================================================ --}}
        {{-- ATTENDANCE REPORT --}}
        {{-- ================================================================ --}}

        <div class="block block-rounded">

            <div class="block-header block-header-default">

                <h3 class="block-title">

                    {{ $isUrdu ? 'ملازمین کی ماہانہ حاضری' : 'Employee Monthly Attendance' }}

                </h3>

            </div>


            <div class="block-content">

                <div class="table-responsive">

                    <table class="table table-bordered table-vcenter table-hover">

                        <thead>

                            <tr>

                                <th>
                                    #
                                </th>

                                <th>
                                    {{ $isUrdu ? 'ملازم' : 'Employee' }}
                                </th>

                                <th>
                                    {{ $isUrdu ? 'شعبہ' : 'Department' }}
                                </th>

                                <th>
                                    {{ $isUrdu ? 'عہدہ' : 'Designation' }}
                                </th>

                                <th>
                                    {{ $isUrdu ? 'کل دن' : 'Records' }}
                                </th>

                                <th>
                                    {{ $isUrdu ? 'حاضر' : 'Present' }}
                                </th>

                                <th>
                                    {{ $isUrdu ? 'غیر حاضر' : 'Absent' }}
                                </th>

                                <th>
                                    {{ $isUrdu ? 'چھٹی' : 'Leave' }}
                                </th>

                                <th>
                                    {{ $isUrdu ? 'تعطیل' : 'Holiday' }}
                                </th>

                                <th>
                                    {{ $isUrdu ? 'آدھا دن' : 'Half Day' }}
                                </th>

                                <th>
                                    {{ $isUrdu ? 'تاخیر' : 'Late Days' }}
                                </th>

                                <th>
                                    {{ $isUrdu ? 'تاخیر منٹ' : 'Late Minutes' }}
                                </th>

                                <th>
                                    {{ $isUrdu ? 'جلدی جانے کے دن' : 'Early Leave Days' }}
                                </th>

                                <th>
                                    {{ $isUrdu ? 'جلدی جانے کے منٹ' : 'Early Leave Minutes' }}
                                </th>

                                <th>
                                    {{ $isUrdu ? 'اوور ٹائم دن' : 'OT Days' }}
                                </th>

                                <th>
                                    {{ $isUrdu ? 'اوور ٹائم منٹ' : 'OT Minutes' }}
                                </th>

                                <th>
                                    {{ $isUrdu ? 'کام کے گھنٹے' : 'Working Hours' }}
                                </th>

                                <th>
                                    {{ $isUrdu ? 'حاضری فیصد' : 'Attendance %' }}
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse($report as $index => $row)
                                @php

                                    $employee = $row['employee'];

                                    $employeeName = $isUrdu
                                        ? trim(($employee->first_name_ur ?? '') . ' ' . ($employee->last_name_ur ?? ''))
                                        : trim(
                                            ($employee->first_name_en ?? '') . ' ' . ($employee->last_name_en ?? ''),
                                        );
                                @endphp


                                <tr>

                                    {{-- # --}}
                                    <td>

                                        {{ $index + 1 }}

                                    </td>


                                    {{-- Employee --}}
                                    <td>

                                        <strong>
                                            {{ $employeeName ?: '-' }}
                                        </strong>

                                    </td>


                                    {{-- Department --}}
                                    <td>

                                        @if ($employee->department)
                                            {{ $isUrdu ? $employee->department->title_ur : $employee->department->title_en }}
                                        @else
                                            -
                                        @endif

                                    </td>


                                    {{-- Designation --}}
                                    <td>

                                        @if ($employee->designation)
                                            {{ $isUrdu ? $employee->designation->title_ur : $employee->designation->title_en }}
                                        @else
                                            -
                                        @endif

                                    </td>


                                    {{-- Records --}}
                                    <td>

                                        {{ number_format($row['total_records']) }}

                                    </td>


                                    {{-- Present --}}
                                    <td>

                                        {{ number_format($row['present_days']) }}

                                    </td>


                                    {{-- Absent --}}
                                    <td>

                                        {{ number_format($row['absent_days']) }}

                                    </td>


                                    {{-- Leave --}}
                                    <td>

                                        {{ number_format($row['leave_days']) }}

                                    </td>


                                    {{-- Holiday --}}
                                    <td>

                                        {{ number_format($row['holiday_days']) }}

                                    </td>


                                    {{-- Half Day --}}
                                    <td>

                                        {{ number_format($row['half_days']) }}

                                    </td>


                                    {{-- Late Days --}}
                                    <td>

                                        {{ number_format($row['late_days']) }}

                                    </td>


                                    {{-- Late Minutes --}}
                                    <td>

                                        {{ number_format($row['late_minutes']) }}

                                    </td>


                                    {{-- Early Leave Days --}}
                                    <td>

                                        {{ number_format($row['early_leave_days']) }}

                                    </td>


                                    {{-- Early Leave Minutes --}}
                                    <td>

                                        {{ number_format($row['early_leave_minutes']) }}

                                    </td>


                                    {{-- Overtime Days --}}
                                    <td>

                                        {{ number_format($row['overtime_days']) }}

                                    </td>


                                    {{-- Overtime Minutes --}}
                                    <td>

                                        {{ number_format($row['overtime_minutes']) }}

                                    </td>


                                    {{-- Working Hours --}}
                                    <td>

                                        {{ $row['working_hours'] }}
                                        h
                                        {{ str_pad($row['working_remaining_minutes'], 2, '0', STR_PAD_LEFT) }}
                                        m

                                    </td>


                                    {{-- Attendance Percentage --}}
                                    <td>

                                        {{ number_format($row['attendance_percentage'], 2) }}%

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="18" class="text-center py-5">

                                        <div class="py-4">

                                            <i class="fa fa-calendar-times fa-3x text-muted mb-3"></i>

                                            <h4>

                                                {{ $isUrdu ? 'کوئی حاضری ریکارڈ نہیں ملا' : 'No Attendance Records Found' }}

                                            </h4>

                                            <p class="text-muted">

                                                {{ $isUrdu
                                                    ? 'منتخب مہینے اور فلٹرز کے لیے کوئی ریکارڈ دستیاب نہیں ہے۔'
                                                    : 'No attendance records are available for the selected month and filters.' }}

                                            </p>

                                        </div>

                                    </td>

                                </tr>
                            @endforelse

                        </tbody>


                        {{-- ================================================= --}}
                        {{-- TOTAL --}}
                        {{-- ================================================= --}}

                        @if ($report->count())
                            <tfoot>

                                <tr class="fw-bold">

                                    <td colspan="4" class="text-end">

                                        {{ $isUrdu ? 'کل' : 'Total' }}

                                    </td>


                                    <td>

                                        {{ number_format($summary['total_records']) }}

                                    </td>


                                    <td>

                                        {{ number_format($summary['present_days']) }}

                                    </td>


                                    <td>

                                        {{ number_format($summary['absent_days']) }}

                                    </td>


                                    <td>

                                        {{ number_format($summary['leave_days']) }}

                                    </td>


                                    <td>

                                        {{ number_format($summary['holiday_days']) }}

                                    </td>


                                    <td>

                                        {{ number_format($summary['half_days']) }}

                                    </td>


                                    <td>

                                        {{ number_format($summary['late_days']) }}

                                    </td>


                                    <td>

                                        {{ number_format($summary['late_minutes']) }}

                                    </td>


                                    <td>

                                        {{ number_format($summary['early_leave_days']) }}

                                    </td>


                                    <td>

                                        {{ number_format($summary['early_leave_minutes']) }}

                                    </td>


                                    <td>

                                        {{ number_format($summary['overtime_days']) }}

                                    </td>


                                    <td>

                                        {{ number_format($summary['overtime_minutes']) }}

                                    </td>


                                    <td>

                                        {{ $summary['working_hours'] }}
                                        h
                                        {{ str_pad($summary['working_remaining_minutes'], 2, '0', STR_PAD_LEFT) }}
                                        m

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

                .no-print,
                .sidebar,
                .nav-main,
                .block-header button,
                form {
                    display: none !important;
                }

                .content {
                    width: 100% !important;
                    max-width: 100% !important;
                }

                .block {
                    border: 0 !important;
                    box-shadow: none !important;
                }

                .table {
                    font-size: 9px !important;
                }

                .table th,
                .table td {
                    padding: 3px !important;
                }

            }
        </style>
    @endpush

@endsection
