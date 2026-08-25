@extends('payroll::layouts.payroll')


@section('content')

    @php
        $isUrdu = app()->getLocale() === 'ur';
    @endphp


    <div class="content" dir="{{ $isUrdu ? 'rtl' : 'ltr' }}">


        {{-- ============================================================ --}}
        {{-- PAGE HEADER --}}
        {{-- ============================================================ --}}

        <div class="block block-rounded">

            <div class="block-content">

                <div class="row align-items-center">

                    <div class="col-md-8">

                        <h2 class="mb-1">

                            <i class="fa fa-clock me-1"></i>

                            {{ $isUrdu ? 'اوور ٹائم رپورٹ' : 'Overtime Report' }}

                        </h2>


                        <p class="text-muted mb-0">

                            {{ \Carbon\Carbon::parse($fromDate)->format('d-m-Y') }}

                            -

                            {{ \Carbon\Carbon::parse($toDate)->format('d-m-Y') }}

                        </p>

                    </div>


                    <div class="col-md-4 text-end no-print">

                        <button type="button" onclick="window.print()" class="btn btn-primary">

                            <i class="fa fa-print me-1"></i>

                            {{ $isUrdu ? 'پرنٹ' : 'Print' }}

                        </button>

                    </div>

                </div>

            </div>

        </div>


        {{-- ============================================================ --}}
        {{-- FILTER --}}
        {{-- ============================================================ --}}

        <div class="no-print">

            @include('payroll::reports.overtime.filters')

        </div>


        {{-- ============================================================ --}}
        {{-- SUMMARY CARDS --}}
        {{-- ============================================================ --}}

        <div class="row">

            {{-- Total Overtime Records --}}
            <div class="col-md-4">

                <div class="block block-rounded">

                    <div class="block-content text-center">

                        <div class="fs-sm text-muted">

                            {{ $isUrdu ? 'اوور ٹائم ریکارڈز' : 'Overtime Records' }}

                        </div>

                        <div class="fs-2 fw-bold">

                            {{ $totalRecords }}

                        </div>

                    </div>

                </div>

            </div>


            {{-- Total Overtime Minutes --}}
            <div class="col-md-4">

                <div class="block block-rounded">

                    <div class="block-content text-center">

                        <div class="fs-sm text-muted">

                            {{ $isUrdu ? 'کل اوور ٹائم منٹ' : 'Total Overtime Minutes' }}

                        </div>

                        <div class="fs-2 fw-bold">

                            {{ number_format($totalOvertimeMinutes) }}

                        </div>

                    </div>

                </div>

            </div>


            {{-- Total Overtime Hours --}}
            <div class="col-md-4">

                <div class="block block-rounded">

                    <div class="block-content text-center">

                        <div class="fs-sm text-muted">

                            {{ $isUrdu ? 'کل اوور ٹائم' : 'Total Overtime' }}

                        </div>

                        <div class="fs-2 fw-bold">

                            {{ $totalOvertimeHours }}
                            {{ $isUrdu ? 'گھنٹے' : 'Hours' }}

                            {{ $remainingMinutes }}
                            {{ $isUrdu ? 'منٹ' : 'Minutes' }}

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ============================================================ --}}
        {{-- EMPLOYEE SUMMARY --}}
        {{-- ============================================================ --}}

        <div class="block block-rounded">

            <div class="block-header block-header-default">

                <h3 class="block-title">

                    {{ $isUrdu ? 'ملازم وار اوور ٹائم خلاصہ' : 'Employee Overtime Summary' }}

                </h3>

            </div>


            <div class="block-content">

                <div class="table-responsive">

                    <table class="table table-bordered table-striped table-vcenter">

                        <thead>

                            <tr>

                                <th class="text-center">
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

                                <th class="text-center">
                                    {{ $isUrdu ? 'اوور ٹائم دن' : 'OT Days' }}
                                </th>

                                <th class="text-center">
                                    {{ $isUrdu ? 'کل منٹ' : 'Total Minutes' }}
                                </th>

                                <th class="text-center">
                                    {{ $isUrdu ? 'کل گھنٹے' : 'Total Hours' }}
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse($employeeSummary as $index => $summary)
                                @php

                                    $employee = $summary['employee'];

                                    $nameEn = $employee
                                        ? trim(($employee->first_name_en ?? '') . ' ' . ($employee->last_name_en ?? ''))
                                        : '-';

                                    $nameUr = $employee
                                        ? trim(($employee->first_name_ur ?? '') . ' ' . ($employee->last_name_ur ?? ''))
                                        : '-';

                                    $employeeName = $isUrdu ? ($nameUr ?: $nameEn) : ($nameEn ?: $nameUr);

                                    $department = $employee?->department;

                                    $designation = $employee?->designation;
                                @endphp


                                <tr>

                                    <td class="text-center">

                                        {{ $index + 1 }}

                                    </td>


                                    <td>

                                        {{ $employeeName }}

                                    </td>


                                    <td>

                                        {{ $department
                                            ? ($isUrdu
                                                ? ($department->title_ur ?:
                                                    $department->title_en)
                                                : ($department->title_en ?:
                                                    $department->title_ur))
                                            : '-' }}

                                    </td>


                                    <td>

                                        {{ $designation
                                            ? ($isUrdu
                                                ? ($designation->title_ur ?:
                                                    $designation->title_en)
                                                : ($designation->title_en ?:
                                                    $designation->title_ur))
                                            : '-' }}

                                    </td>


                                    <td class="text-center">

                                        {{ $summary['days'] }}

                                    </td>


                                    <td class="text-center">

                                        {{ number_format($summary['minutes']) }}

                                    </td>


                                    <td class="text-center fw-bold">

                                        {{ $summary['hours'] }}
                                        h
                                        {{ $summary['remaining_minutes'] }}
                                        m

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="7" class="text-center text-muted py-4">

                                        {{ $isUrdu ? 'کوئی اوور ٹائم ریکارڈ موجود نہیں ہے۔' : 'No overtime records found.' }}

                                    </td>

                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>


        {{-- ============================================================ --}}
        {{-- DAILY DETAIL --}}
        {{-- ============================================================ --}}

        <div class="block block-rounded">

            <div class="block-header block-header-default">

                <h3 class="block-title">

                    {{ $isUrdu ? 'اوور ٹائم کی تفصیل' : 'Overtime Details' }}

                </h3>

            </div>


            <div class="block-content">

                <div class="table-responsive">

                    <table class="table table-bordered table-striped table-vcenter">

                        <thead>

                            <tr>

                                <th class="text-center">
                                    #
                                </th>

                                <th>
                                    {{ $isUrdu ? 'تاریخ' : 'Date' }}
                                </th>

                                <th>
                                    {{ $isUrdu ? 'ملازم' : 'Employee' }}
                                </th>

                                <th>
                                    {{ $isUrdu ? 'شعبہ' : 'Department' }}
                                </th>

                                <th>
                                    {{ $isUrdu ? 'شفٹ' : 'Shift' }}
                                </th>

                                <th>
                                    {{ $isUrdu ? 'چیک اِن' : 'Check In' }}
                                </th>

                                <th>
                                    {{ $isUrdu ? 'چیک آؤٹ' : 'Check Out' }}
                                </th>

                                <th class="text-center">
                                    {{ $isUrdu ? 'اوور ٹائم منٹ' : 'Overtime Minutes' }}
                                </th>

                                <th class="text-center">
                                    {{ $isUrdu ? 'اوور ٹائم' : 'Overtime' }}
                                </th>

                                <th>
                                    {{ $isUrdu ? 'اسٹیٹس' : 'Status' }}
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse($attendances as $index => $attendance)
                                @php

                                    $employee = $attendance->employee;

                                    $nameEn = $employee
                                        ? trim(($employee->first_name_en ?? '') . ' ' . ($employee->last_name_en ?? ''))
                                        : '-';

                                    $nameUr = $employee
                                        ? trim(($employee->first_name_ur ?? '') . ' ' . ($employee->last_name_ur ?? ''))
                                        : '-';

                                    $employeeName = $isUrdu ? ($nameUr ?: $nameEn) : ($nameEn ?: $nameUr);

                                    $department = $employee?->department;

                                    $shift = $employee?->shift;

                                    $minutes = (int) $attendance->overtime_minutes;

                                    $hours = intdiv($minutes, 60);

                                    $remaining = $minutes % 60;
                                @endphp


                                <tr>

                                    <td class="text-center">

                                        {{ $index + 1 }}

                                    </td>


                                    <td>

                                        {{ \Carbon\Carbon::parse($attendance->date)->format('d-m-Y') }}

                                    </td>


                                    <td>

                                        {{ $employeeName }}

                                    </td>


                                    <td>

                                        {{ $department
                                            ? ($isUrdu
                                                ? ($department->title_ur ?:
                                                    $department->title_en)
                                                : ($department->title_en ?:
                                                    $department->title_ur))
                                            : '-' }}

                                    </td>


                                    <td>

                                        {{ $shift
                                            ? ($isUrdu
                                                ? ($shift->shift_name_ur ?:
                                                    $shift->shift_name_en)
                                                : ($shift->shift_name_en ?:
                                                    $shift->shift_name_ur))
                                            : '-' }}

                                    </td>


                                    <td>

                                        {{ $attendance->check_in ? \Carbon\Carbon::parse($attendance->check_in)->format('h:i A') : '-' }}

                                    </td>


                                    <td>

                                        {{ $attendance->check_out ? \Carbon\Carbon::parse($attendance->check_out)->format('h:i A') : '-' }}

                                    </td>


                                    <td class="text-center">

                                        {{ $minutes }}

                                    </td>


                                    <td class="text-center fw-bold">

                                        {{ $hours }}h
                                        {{ $remaining }}m

                                    </td>


                                    <td>

                                        @if ($attendance->status)
                                            <span class="badge bg-info">

                                                {{ ucfirst(str_replace('_', ' ', $attendance->status)) }}

                                            </span>
                                        @else
                                            -
                                        @endif

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="10" class="text-center text-muted py-4">

                                        {{ $isUrdu ? 'کوئی ریکارڈ موجود نہیں ہے۔' : 'No overtime records found.' }}

                                    </td>

                                </tr>
                            @endforelse

                        </tbody>


                        @if ($attendances->count())
                            <tfoot>

                                <tr class="fw-bold">

                                    <td colspan="7">

                                        {{ $isUrdu ? 'کل اوور ٹائم' : 'TOTAL OVERTIME' }}

                                    </td>


                                    <td class="text-center">

                                        {{ number_format($totalOvertimeMinutes) }}

                                    </td>


                                    <td class="text-center">

                                        {{ $totalOvertimeHours }}h
                                        {{ $remainingMinutes }}m

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


    @push('css')
        <style>
            @media print {

                .no-print,
                .sidebar,
                .nav-main,
                .header,
                .header-navbar {
                    display: none !important;
                }

                .content {
                    width: 100% !important;
                    max-width: 100% !important;
                    padding: 0 !important;
                    margin: 0 !important;
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
                    padding: 4px !important;
                }

                @page {
                    size: A4 landscape;
                    margin: 8mm;
                }

            }
        </style>
    @endpush

@endsection
