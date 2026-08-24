@extends('payroll::layouts.payroll')


@section('content')

    @php
        $isUrdu = app()->getLocale() === 'ur';

        $fromDateDisplay = \Carbon\Carbon::parse($fromDate)->format('d-m-Y');

        $toDateDisplay = \Carbon\Carbon::parse($toDate)->format('d-m-Y');
    @endphp


    <div class="content" dir="{{ $isUrdu ? 'rtl' : 'ltr' }}">


        {{-- ================================================================ --}}
        {{-- REPORT HEADER --}}
        {{-- ================================================================ --}}

        <div class="block block-rounded">

            <div class="block-header block-header-default">

                <h3 class="block-title">

                    <i class="fa fa-list-alt me-1"></i>

                    {{ $isUrdu ? 'تفصیلی حاضری رپورٹ' : 'Attendance Detail Report' }}

                </h3>

            </div>


            <div class="block-content">

                <div class="row">

                    <div class="col-md-6">

                        <h4 class="mb-1">

                            {{ $isUrdu ? 'تفصیلی حاضری رپورٹ' : 'Attendance Detail Report' }}

                        </h4>

                    </div>


                    <div class="col-md-6 text-md-end">

                        <strong>

                            {{ $isUrdu ? 'مدت:' : 'Period:' }}

                        </strong>

                        {{ $fromDateDisplay }}

                        -

                        {{ $toDateDisplay }}

                    </div>

                </div>

            </div>

        </div>


        {{-- ================================================================ --}}
        {{-- FILTER --}}
        {{-- ================================================================ --}}

        @include('payroll::reports.attendance-detail.filters')


        {{-- ================================================================ --}}
        {{-- SUMMARY CARDS --}}
        {{-- ================================================================ --}}

        <div class="row">


            {{-- Total Records --}}
            <div class="col-md-3">

                <div class="block block-rounded">

                    <div class="block-content block-content-full">

                        <div class="text-muted">

                            {{ $isUrdu ? 'کل ریکارڈ' : 'Total Records' }}

                        </div>

                        <div class="fs-2 fw-bold">

                            {{ number_format($summary['total_records']) }}

                        </div>

                    </div>

                </div>

            </div>


            {{-- Present --}}
            <div class="col-md-3">

                <div class="block block-rounded">

                    <div class="block-content block-content-full">

                        <div class="text-muted">

                            {{ $isUrdu ? 'حاضر' : 'Present' }}

                        </div>

                        <div class="fs-2 fw-bold">

                            {{ number_format($summary['present']) }}

                        </div>

                    </div>

                </div>

            </div>


            {{-- Absent --}}
            <div class="col-md-3">

                <div class="block block-rounded">

                    <div class="block-content block-content-full">

                        <div class="text-muted">

                            {{ $isUrdu ? 'غیر حاضر' : 'Absent' }}

                        </div>

                        <div class="fs-2 fw-bold">

                            {{ number_format($summary['absent']) }}

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
        {{-- DETAIL TABLE --}}
        {{-- ================================================================ --}}

        <div class="block block-rounded">

            <div class="block-header block-header-default">

                <h3 class="block-title">

                    {{ $isUrdu ? 'حاضری کی تفصیل' : 'Attendance Details' }}

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
                                    {{ $isUrdu ? 'تاریخ' : 'Date' }}
                                </th>

                                <th>
                                    {{ $isUrdu ? 'دن' : 'Day' }}
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

                                <th>
                                    {{ $isUrdu ? 'کام کے گھنٹے' : 'Working Hours' }}
                                </th>

                                <th>
                                    {{ $isUrdu ? 'تاخیر' : 'Late Min' }}
                                </th>

                                <th>
                                    {{ $isUrdu ? 'جلدی روانگی' : 'Early Leave' }}
                                </th>

                                <th>
                                    {{ $isUrdu ? 'اوور ٹائم' : 'Overtime' }}
                                </th>

                                <th>
                                    {{ $isUrdu ? 'حالت' : 'Status' }}
                                </th>

                                <th>
                                    {{ $isUrdu ? 'ذریعہ' : 'Source' }}
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

                                    $dayNames = [
                                        'Monday' => 'پیر',

                                        'Tuesday' => 'منگل',

                                        'Wednesday' => 'بدھ',

                                        'Thursday' => 'جمعرات',

                                        'Friday' => 'جمعہ',

                                        'Saturday' => 'ہفتہ',

                                        'Sunday' => 'اتوار',
                                    ];

                                    $statusLabels = [
                                        'present' => [
                                            'en' => 'Present',
                                            'ur' => 'حاضر',
                                        ],

                                        'absent' => [
                                            'en' => 'Absent',
                                            'ur' => 'غیر حاضر',
                                        ],

                                        'late' => [
                                            'en' => 'Late',
                                            'ur' => 'تاخیر سے',
                                        ],

                                        'half_day' => [
                                            'en' => 'Half Day',
                                            'ur' => 'آدھا دن',
                                        ],

                                        'leave' => [
                                            'en' => 'Leave',
                                            'ur' => 'چھٹی',
                                        ],

                                        'holiday' => [
                                            'en' => 'Holiday',
                                            'ur' => 'تعطیل',
                                        ],

                                        'manual' => [
                                            'en' => 'Manual',
                                            'ur' => 'دستی',
                                        ],
                                    ];
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


                                    {{-- Date --}}
                                    <td>

                                        {{ \Carbon\Carbon::parse($row['date'])->format('d-m-Y') }}

                                    </td>


                                    {{-- Day --}}
                                    <td>

                                        {{ $isUrdu ? $dayNames[$row['day']] ?? $row['day'] : $row['day'] }}

                                    </td>


                                    {{-- Shift --}}
                                    <td>

                                        @if ($employee->shift)
                                            {{ $isUrdu ? $employee->shift->shift_name_ur : $employee->shift->shift_name_en }}
                                        @else
                                            -
                                        @endif

                                    </td>


                                    {{-- Check In --}}
                                    <td>

                                        @if ($row['check_in'])
                                            {{ \Carbon\Carbon::parse($row['check_in'])->format('h:i A') }}
                                        @else
                                            -
                                        @endif

                                    </td>


                                    {{-- Check Out --}}
                                    <td>

                                        @if ($row['check_out'])
                                            {{ \Carbon\Carbon::parse($row['check_out'])->format('h:i A') }}
                                        @else
                                            -
                                        @endif

                                    </td>


                                    {{-- Working Hours --}}
                                    <td>

                                        @if ($row['working_minutes'] > 0)
                                            {{ $row['working_hours'] }}h
                                            {{ str_pad($row['remaining_minutes'], 2, '0', STR_PAD_LEFT) }}m
                                        @else
                                            -
                                        @endif

                                    </td>


                                    {{-- Late --}}
                                    <td>

                                        @if ($row['late_minutes'] > 0)
                                            {{ number_format($row['late_minutes']) }}
                                        @else
                                            0
                                        @endif

                                    </td>


                                    {{-- Early Leave --}}
                                    <td>

                                        @if ($row['early_leave_minutes'] > 0)
                                            {{ number_format($row['early_leave_minutes']) }}
                                        @else
                                            0
                                        @endif

                                    </td>


                                    {{-- Overtime --}}
                                    <td>

                                        @if ($row['overtime_minutes'] > 0)
                                            {{ number_format($row['overtime_minutes']) }}
                                        @else
                                            0
                                        @endif

                                    </td>


                                    {{-- Status --}}
                                    <td>

                                        @php

                                            $status = $row['status'];

                                        @endphp


                                        <span class="badge bg-secondary">

                                            {{ $statusLabels[$status][$isUrdu ? 'ur' : 'en'] ?? ucfirst(str_replace('_', ' ', $status)) }}

                                        </span>

                                    </td>


                                    {{-- Source --}}
                                    <td>

                                        @if ($row['is_manual'])
                                            <span class="badge bg-warning">

                                                {{ $isUrdu ? 'دستی' : 'Manual' }}

                                            </span>
                                        @else
                                            <span class="badge bg-success">

                                                {{ $isUrdu ? 'ڈیوائس' : 'Device' }}

                                            </span>
                                        @endif

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="15" class="text-center py-5">

                                        <i class="fa fa-calendar-times fa-3x text-muted mb-3"></i>


                                        <h4>

                                            {{ $isUrdu ? 'کوئی حاضری ریکارڈ نہیں ملا' : 'No Attendance Records Found' }}

                                        </h4>


                                        <p class="text-muted">

                                            {{ $isUrdu
                                                ? 'منتخب تاریخ اور فلٹرز کے لیے کوئی ریکارڈ دستیاب نہیں ہے۔'
                                                : 'No attendance records are available for the selected dates and filters.' }}

                                        </p>

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

                                    <td colspan="9" class="text-end">

                                        {{ $isUrdu ? 'کل' : 'Total' }}

                                    </td>


                                    {{-- Working --}}
                                    <td>

                                        {{ $summary['working_hours'] }}h
                                        {{ str_pad($summary['working_remaining_minutes'], 2, '0', STR_PAD_LEFT) }}m

                                    </td>


                                    {{-- Late --}}
                                    <td>

                                        {{ number_format($summary['late_minutes']) }}

                                    </td>


                                    {{-- Early --}}
                                    <td>

                                        {{ number_format($summary['early_leave_minutes']) }}

                                    </td>


                                    {{-- Overtime --}}
                                    <td>

                                        {{ number_format($summary['overtime_minutes']) }}

                                    </td>


                                    {{-- Status --}}
                                    <td>

                                        -

                                    </td>


                                    {{-- Source --}}
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
                form,
                .btn,
                .block-header {
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
                    size: landscape;
                    margin: 5mm;
                }

            }
        </style>
    @endpush

@endsection
