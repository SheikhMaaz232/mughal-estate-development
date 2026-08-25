@extends('payroll::layouts.payroll')


@section('content')

    @php
        $isUrdu = app()->getLocale() === 'ur';
    @endphp


    <div class="content" dir="{{ $isUrdu ? 'rtl' : 'ltr' }}">


        {{-- ========================================================== --}}
        {{-- HEADER --}}
        {{-- ========================================================== --}}

        <div class="block block-rounded">

            <div class="block-content">

                <div class="row align-items-center">

                    <div class="col-md-8">

                        <h2 class="mb-1">

                            <i class="fa fa-user-times me-1"></i>

                            {{ $isUrdu ? 'غیر حاضر ملازمین کی رپورٹ' : 'Absentee Report' }}

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


        {{-- ========================================================== --}}
        {{-- FILTERS --}}
        {{-- ========================================================== --}}

        <div class="no-print">

            @include('payroll::reports.absentee.filters')

        </div>


        {{-- ========================================================== --}}
        {{-- SUMMARY --}}
        {{-- ========================================================== --}}

        <div class="row">

            {{-- Absent Records --}}
            <div class="col-md-4">

                <div class="block block-rounded">

                    <div class="block-content text-center">

                        <div class="fs-sm text-muted">

                            {{ $isUrdu ? 'کل غیر حاضری ریکارڈز' : 'Total Absent Records' }}

                        </div>

                        <div class="fs-2 fw-bold text-danger">

                            {{ number_format($totalAbsentRecords) }}

                        </div>

                    </div>

                </div>

            </div>


            {{-- Absent Days --}}
            <div class="col-md-4">

                <div class="block block-rounded">

                    <div class="block-content text-center">

                        <div class="fs-sm text-muted">

                            {{ $isUrdu ? 'کل غیر حاضر دن' : 'Total Absent Days' }}

                        </div>

                        <div class="fs-2 fw-bold text-danger">

                            {{ number_format($totalAbsentDays) }}

                        </div>

                    </div>

                </div>

            </div>


            {{-- Employees --}}
            <div class="col-md-4">

                <div class="block block-rounded">

                    <div class="block-content text-center">

                        <div class="fs-sm text-muted">

                            {{ $isUrdu ? 'غیر حاضر ملازمین' : 'Employees Absent' }}

                        </div>

                        <div class="fs-2 fw-bold">

                            {{ number_format($totalEmployeesAbsent) }}

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================== --}}
        {{-- EMPLOYEE WISE SUMMARY --}}
        {{-- ========================================================== --}}

        <div class="block block-rounded">

            <div class="block-header block-header-default">

                <h3 class="block-title">

                    {{ $isUrdu ? 'ملازم وار غیر حاضری خلاصہ' : 'Employee-wise Absence Summary' }}

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

                                <th>
                                    {{ $isUrdu ? 'شفٹ' : 'Shift' }}
                                </th>

                                <th class="text-center">
                                    {{ $isUrdu ? 'غیر حاضر دن' : 'Absent Days' }}
                                </th>

                                <th>
                                    {{ $isUrdu ? 'پہلی غیر حاضری' : 'First Absence' }}
                                </th>

                                <th>
                                    {{ $isUrdu ? 'آخری غیر حاضری' : 'Last Absence' }}
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse($employeeSummary
                                as $index => $summary)
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

                                    $shift = $employee?->shift;
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


                                    <td>

                                        {{ $shift
                                            ? ($isUrdu
                                                ? ($shift->shift_name_ur ?:
                                                    $shift->shift_name_en)
                                                : ($shift->shift_name_en ?:
                                                    $shift->shift_name_ur))
                                            : '-' }}

                                    </td>


                                    <td class="text-center">

                                        <span class="badge bg-danger">

                                            {{ $summary['days'] }}

                                        </span>

                                    </td>


                                    <td>

                                        {{ $summary['first_absent_date'] ? \Carbon\Carbon::parse($summary['first_absent_date'])->format('d-m-Y') : '-' }}

                                    </td>


                                    <td>

                                        {{ $summary['last_absent_date'] ? \Carbon\Carbon::parse($summary['last_absent_date'])->format('d-m-Y') : '-' }}

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="8" class="text-center text-muted py-4">

                                        {{ $isUrdu ? 'اس مدت میں کوئی غیر حاضر ملازم نہیں ملا۔' : 'No absent employees found for the selected period.' }}

                                    </td>

                                </tr>
                            @endforelse

                        </tbody>


                        @if ($employeeSummary->count())
                            <tfoot>

                                <tr class="fw-bold">

                                    <td colspan="5">

                                        {{ $isUrdu ? 'کل' : 'TOTAL' }}

                                    </td>

                                    <td class="text-center">

                                        {{ number_format($totalAbsentDays) }}

                                    </td>

                                    <td colspan="2">

                                        {{ $isUrdu ? 'غیر حاضر ملازمین: ' : 'Employees Absent: ' }}

                                        {{ $totalEmployeesAbsent }}

                                    </td>

                                </tr>

                            </tfoot>
                        @endif

                    </table>

                </div>

            </div>

        </div>


        {{-- ========================================================== --}}
        {{-- DATE WISE SUMMARY --}}
        {{-- ========================================================== --}}

        <div class="block block-rounded">

            <div class="block-header block-header-default">

                <h3 class="block-title">

                    {{ $isUrdu ? 'تاریخ وار غیر حاضری خلاصہ' : 'Date-wise Absence Summary' }}

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

                                <th class="text-center">
                                    {{ $isUrdu ? 'غیر حاضر ملازمین' : 'Absent Employees' }}
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse($dateSummary
                                as $index => $summary)
                                <tr>

                                    <td class="text-center">

                                        {{ $index + 1 }}

                                    </td>


                                    <td>

                                        {{ \Carbon\Carbon::parse($summary['date'])->format('d-m-Y') }}

                                    </td>


                                    <td class="text-center">

                                        <span class="badge bg-danger">

                                            {{ $summary['employees'] }}

                                        </span>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="3" class="text-center text-muted py-4">

                                        {{ $isUrdu ? 'کوئی ریکارڈ موجود نہیں ہے۔' : 'No records found.' }}

                                    </td>

                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>


        {{-- ========================================================== --}}
        {{-- DAILY DETAIL --}}
        {{-- ========================================================== --}}

        <div class="block block-rounded">

            <div class="block-header block-header-default">

                <h3 class="block-title">

                    {{ $isUrdu ? 'غیر حاضری کی تفصیل' : 'Absence Details' }}

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
                                    {{ $isUrdu ? 'عہدہ' : 'Designation' }}
                                </th>

                                <th>
                                    {{ $isUrdu ? 'شفٹ' : 'Shift' }}
                                </th>

                                <th>
                                    {{ $isUrdu ? 'اسٹیٹس' : 'Status' }}
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            @forelse($attendances
                                as $index => $attendance)
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

                                    $designation = $employee?->designation;

                                    $shift = $employee?->shift;
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

                                        {{ $designation
                                            ? ($isUrdu
                                                ? ($designation->title_ur ?:
                                                    $designation->title_en)
                                                : ($designation->title_en ?:
                                                    $designation->title_ur))
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

                                        <span class="badge bg-danger">

                                            {{ $isUrdu ? 'غیر حاضر' : 'Absent' }}

                                        </span>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="7" class="text-center text-muted py-4">

                                        {{ $isUrdu ? 'کوئی غیر حاضری کا ریکارڈ موجود نہیں ہے۔' : 'No absence records found.' }}

                                    </td>

                                </tr>
                            @endforelse

                        </tbody>


                        @if ($attendances->count())
                            <tfoot>

                                <tr class="fw-bold">

                                    <td colspan="6">

                                        {{ $isUrdu ? 'کل غیر حاضر دن' : 'TOTAL ABSENT DAYS' }}

                                    </td>

                                    <td>

                                        {{ number_format($totalAbsentDays) }}

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
