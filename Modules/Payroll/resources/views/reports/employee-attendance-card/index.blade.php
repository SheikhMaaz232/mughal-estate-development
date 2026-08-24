@extends('payroll::layouts.payroll')


@section('content')

    @php

        $isUrdu = app()->getLocale() === 'ur';

        /*
    |--------------------------------------------------------------------------
    | Month Names
    |--------------------------------------------------------------------------
    */

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

        /*
    |--------------------------------------------------------------------------
    | Employee Name
    |--------------------------------------------------------------------------
    */

        $employeeName = '';

        if ($employee) {
            $employeeName = $isUrdu
                ? trim(($employee->first_name_ur ?? '') . ' ' . ($employee->last_name_ur ?? ''))
                : trim(($employee->first_name_en ?? '') . ' ' . ($employee->last_name_en ?? ''));
        }

        /*
    |--------------------------------------------------------------------------
    | Status Labels
    |--------------------------------------------------------------------------
    */

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

            'not_recorded' => [
                'en' => 'Not Recorded',
                'ur' => 'ریکارڈ موجود نہیں',
            ],
        ];

        /*
    |--------------------------------------------------------------------------
    | Urdu Day Names
    |--------------------------------------------------------------------------
    */

        $dayNames = [
            'Monday' => 'پیر',

            'Tuesday' => 'منگل',

            'Wednesday' => 'بدھ',

            'Thursday' => 'جمعرات',

            'Friday' => 'جمعہ',

            'Saturday' => 'ہفتہ',

            'Sunday' => 'اتوار',
        ];
    @endphp


    <div class="content" dir="{{ $isUrdu ? 'rtl' : 'ltr' }}">


        {{-- ================================================================ --}}
        {{-- FILTER --}}
        {{-- ================================================================ --}}

        @include('payroll::reports.employee-attendance-card.filters')


        @if ($employee)
            {{-- ============================================================ --}}
            {{-- ATTENDANCE CARD --}}
            {{-- ============================================================ --}}

            <div class="block block-rounded">


                {{-- ======================================================== --}}
                {{-- CARD HEADER --}}
                {{-- ======================================================== --}}

                <div class="block-header block-header-default">

                    <h3 class="block-title">

                        <i class="fa fa-id-card me-1"></i>

                        {{ $isUrdu ? 'ملازم حاضری کارڈ' : 'Employee Attendance Card' }}

                    </h3>


                    <div>

                        <strong>

                            {{ $months[$month] }}

                            {{ $year }}

                        </strong>

                    </div>

                </div>


                {{-- ======================================================== --}}
                {{-- EMPLOYEE INFORMATION --}}
                {{-- ======================================================== --}}

                <div class="block-content">

                    <div class="row mb-4">


                        {{-- Employee --}}
                        <div class="col-md-4">

                            <div class="border rounded p-3 h-100">

                                <small class="text-muted">

                                    {{ $isUrdu ? 'ملازم' : 'Employee' }}

                                </small>

                                <h4 class="mb-0 mt-1">

                                    {{ $employeeName ?: '-' }}

                                </h4>

                            </div>

                        </div>


                        {{-- Employee ID --}}
                        <div class="col-md-2">

                            <div class="border rounded p-3 h-100">

                                <small class="text-muted">

                                    {{ $isUrdu ? 'ملازم نمبر' : 'Employee ID' }}

                                </small>

                                <h5 class="mb-0 mt-1">

                                    {{ $employee->id }}

                                </h5>

                            </div>

                        </div>


                        {{-- Device ID --}}
                        <div class="col-md-2">

                            <div class="border rounded p-3 h-100">

                                <small class="text-muted">

                                    {{ $isUrdu ? 'ڈیوائس آئی ڈی' : 'Device ID' }}

                                </small>

                                <h5 class="mb-0 mt-1">

                                    {{ $employee->device_user_id ?: '-' }}

                                </h5>

                            </div>

                        </div>


                        {{-- Department --}}
                        <div class="col-md-2">

                            <div class="border rounded p-3 h-100">

                                <small class="text-muted">

                                    {{ $isUrdu ? 'شعبہ' : 'Department' }}

                                </small>

                                <h6 class="mb-0 mt-1">

                                    @if ($employee->department)
                                        {{ $isUrdu ? $employee->department->title_ur : $employee->department->title_en }}
                                    @else
                                        -
                                    @endif

                                </h6>

                            </div>

                        </div>


                        {{-- Designation --}}
                        <div class="col-md-2">

                            <div class="border rounded p-3 h-100">

                                <small class="text-muted">

                                    {{ $isUrdu ? 'عہدہ' : 'Designation' }}

                                </small>

                                <h6 class="mb-0 mt-1">

                                    @if ($employee->designation)
                                        {{ $isUrdu ? $employee->designation->title_ur : $employee->designation->title_en }}
                                    @else
                                        -
                                    @endif

                                </h6>

                            </div>

                        </div>

                    </div>


                    {{-- ==================================================== --}}
                    {{-- SHIFT --}}
                    {{-- ==================================================== --}}

                    <div class="row mb-4">

                        <div class="col-md-4">

                            <strong>

                                {{ $isUrdu ? 'شفٹ:' : 'Shift:' }}

                            </strong>


                            @if ($employee->shift)
                                {{ $isUrdu ? $employee->shift->shift_name_ur : $employee->shift->shift_name_en }}

                                <small class="text-muted">

                                    (
                                    {{ \Carbon\Carbon::parse($employee->shift->start_time)->format('h:i A') }}

                                    -

                                    {{ \Carbon\Carbon::parse($employee->shift->end_time)->format('h:i A') }}
                                    )

                                </small>
                            @else
                                -
                            @endif

                        </div>


                        <div class="col-md-4">

                            <strong>

                                {{ $isUrdu ? 'مدت:' : 'Period:' }}

                            </strong>

                            {{ $startDate->format('d-m-Y') }}

                            -

                            {{ $endDate->format('d-m-Y') }}

                        </div>


                        <div class="col-md-4">

                            <strong>

                                {{ $isUrdu ? 'حاضری فیصد:' : 'Attendance:' }}

                            </strong>

                            {{ number_format($summary['attendance_percentage'], 2) }}%

                        </div>

                    </div>


                    {{-- ==================================================== --}}
                    {{-- SUMMARY --}}
                    {{-- ==================================================== --}}

                    <div class="row">


                        {{-- Present --}}
                        <div class="col-md-2">

                            <div class="block block-rounded border">

                                <div class="block-content block-content-full">

                                    <div class="text-muted">

                                        {{ $isUrdu ? 'حاضر' : 'Present' }}

                                    </div>

                                    <div class="fs-3 fw-bold">

                                        {{ $summary['present_days'] }}

                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- Absent --}}
                        <div class="col-md-2">

                            <div class="block block-rounded border">

                                <div class="block-content block-content-full">

                                    <div class="text-muted">

                                        {{ $isUrdu ? 'غیر حاضر' : 'Absent' }}

                                    </div>

                                    <div class="fs-3 fw-bold">

                                        {{ $summary['absent_days'] }}

                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- Leave --}}
                        <div class="col-md-2">

                            <div class="block block-rounded border">

                                <div class="block-content block-content-full">

                                    <div class="text-muted">

                                        {{ $isUrdu ? 'چھٹی' : 'Leave' }}

                                    </div>

                                    <div class="fs-3 fw-bold">

                                        {{ $summary['leave_days'] }}

                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- Late --}}
                        <div class="col-md-2">

                            <div class="block block-rounded border">

                                <div class="block-content block-content-full">

                                    <div class="text-muted">

                                        {{ $isUrdu ? 'تاخیر منٹ' : 'Late Minutes' }}

                                    </div>

                                    <div class="fs-3 fw-bold">

                                        {{ number_format($summary['late_minutes']) }}

                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- Overtime --}}
                        <div class="col-md-2">

                            <div class="block block-rounded border">

                                <div class="block-content block-content-full">

                                    <div class="text-muted">

                                        {{ $isUrdu ? 'اوور ٹائم' : 'Overtime' }}

                                    </div>

                                    <div class="fs-3 fw-bold">

                                        {{ number_format($summary['overtime_minutes']) }}

                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- Working Hours --}}
                        <div class="col-md-2">

                            <div class="block block-rounded border">

                                <div class="block-content block-content-full">

                                    <div class="text-muted">

                                        {{ $isUrdu ? 'کام کے گھنٹے' : 'Working Hours' }}

                                    </div>

                                    <div class="fs-3 fw-bold">

                                        {{ $summary['working_hours'] }}h
                                        {{ str_pad($summary['working_remaining_minutes'], 2, '0', STR_PAD_LEFT) }}m

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- ==================================================== --}}
                    {{-- DAILY ATTENDANCE TABLE --}}
                    {{-- ==================================================== --}}

                    <div class="table-responsive">

                        <table class="table table-bordered table-vcenter table-hover">

                            <thead>

                                <tr>

                                    <th>
                                        #
                                    </th>

                                    <th>
                                        {{ $isUrdu ? 'تاریخ' : 'Date' }}
                                    </th>

                                    <th>
                                        {{ $isUrdu ? 'دن' : 'Day' }}
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
                                        {{ $isUrdu ? 'تاخیر' : 'Late' }}
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

                                @foreach ($days as $index => $day)
                                    @php

                                        $status = $day['status'];

                                        $statusText =
                                            $statusLabels[$status][$isUrdu ? 'ur' : 'en'] ??
                                            ucfirst(str_replace('_', ' ', $status));

                                        $isMissing = $status === 'not_recorded';

                                    @endphp


                                    <tr
                                        class="
                                        {{ $day['is_weekend'] ? 'table-secondary' : '' }}
                                    ">


                                        {{-- Number --}}
                                        <td>

                                            {{ $index + 1 }}

                                        </td>


                                        {{-- Date --}}
                                        <td>

                                            {{ $day['date']->format('d-m-Y') }}

                                        </td>


                                        {{-- Day --}}
                                        <td>

                                            {{ $isUrdu ? $dayNames[$day['day_name']] ?? $day['day_name'] : $day['day_name'] }}

                                        </td>


                                        {{-- Check In --}}
                                        <td>

                                            @if ($day['check_in'])
                                                {{ \Carbon\Carbon::parse($day['check_in'])->format('h:i A') }}
                                            @else
                                                -
                                            @endif

                                        </td>


                                        {{-- Check Out --}}
                                        <td>

                                            @if ($day['check_out'])
                                                {{ \Carbon\Carbon::parse($day['check_out'])->format('h:i A') }}
                                            @else
                                                -
                                            @endif

                                        </td>


                                        {{-- Working Hours --}}
                                        <td>

                                            @if ($day['working_minutes'] > 0)
                                                {{ $day['working_hours'] }}h
                                                {{ str_pad($day['remaining_minutes'], 2, '0', STR_PAD_LEFT) }}m
                                            @else
                                                -
                                            @endif

                                        </td>


                                        {{-- Late --}}
                                        <td>

                                            {{ $day['late_minutes'] }}

                                            @if ($day['late_minutes'] > 0)
                                                min
                                            @endif

                                        </td>


                                        {{-- Early Leave --}}
                                        <td>

                                            {{ $day['early_leave_minutes'] }}

                                            @if ($day['early_leave_minutes'] > 0)
                                                min
                                            @endif

                                        </td>


                                        {{-- Overtime --}}
                                        <td>

                                            {{ $day['overtime_minutes'] }}

                                            @if ($day['overtime_minutes'] > 0)
                                                min
                                            @endif

                                        </td>


                                        {{-- Status --}}
                                        <td>

                                            @if ($isMissing)
                                                <span class="badge bg-secondary">

                                                    {{ $statusText }}

                                                </span>
                                            @elseif($status === 'present')
                                                <span class="badge bg-success">

                                                    {{ $statusText }}

                                                </span>
                                            @elseif($status === 'absent')
                                                <span class="badge bg-danger">

                                                    {{ $statusText }}

                                                </span>
                                            @elseif($status === 'leave')
                                                <span class="badge bg-info">

                                                    {{ $statusText }}

                                                </span>
                                            @elseif($status === 'holiday')
                                                <span class="badge bg-primary">

                                                    {{ $statusText }}

                                                </span>
                                            @elseif($status === 'half_day')
                                                <span class="badge bg-warning">

                                                    {{ $statusText }}

                                                </span>
                                            @else
                                                <span class="badge bg-secondary">

                                                    {{ $statusText }}

                                                </span>
                                            @endif

                                        </td>


                                        {{-- Source --}}
                                        <td>

                                            @if ($day['attendance'])
                                                @if ($day['is_manual'])
                                                    <span class="badge bg-warning">

                                                        {{ $isUrdu ? 'دستی' : 'Manual' }}

                                                    </span>
                                                @else
                                                    <span class="badge bg-success">

                                                        {{ $isUrdu ? 'ڈیوائس' : 'Device' }}

                                                    </span>
                                                @endif
                                            @else
                                                -
                                            @endif

                                        </td>

                                    </tr>
                                @endforeach

                            </tbody>


                            {{-- ================================================= --}}
                            {{-- TOTAL --}}
                            {{-- ================================================= --}}

                            <tfoot>

                                <tr class="fw-bold">

                                    <td colspan="6" class="text-end">

                                        {{ $isUrdu ? 'کل' : 'Total' }}

                                    </td>


                                    <td>

                                        {{ number_format($summary['late_minutes']) }}

                                    </td>


                                    <td>

                                        {{ number_format($summary['early_leave_minutes']) }}

                                    </td>


                                    <td>

                                        {{ number_format($summary['overtime_minutes']) }}

                                    </td>


                                    <td colspan="2">

                                        {{ number_format($summary['attendance_percentage'], 2) }}%

                                    </td>

                                </tr>

                            </tfoot>

                        </table>

                    </div>

                </div>

            </div>
        @else
            {{-- ============================================================ --}}
            {{-- NO EMPLOYEE SELECTED --}}
            {{-- ============================================================ --}}

            <div class="block block-rounded">

                <div class="block-content text-center py-5">

                    <i class="fa fa-user-circle fa-4x text-muted mb-4"></i>


                    <h3>

                        {{ $isUrdu ? 'ملازم منتخب کریں' : 'Select an Employee' }}

                    </h3>


                    <p class="text-muted">

                        {{ $isUrdu
                            ? 'حاضری کارڈ دیکھنے کے لیے اوپر سے ملازم اور مہینہ منتخب کریں۔'
                            : 'Select an employee and month above to view the attendance card.' }}

                    </p>

                </div>

            </div>
        @endif

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
                    font-size: 9px !important;
                }

                .table th,
                .table td {
                    padding: 4px !important;
                }

                @page {
                    size: A4 portrait;
                    margin: 8mm;
                }

            }
        </style>
    @endpush

@endsection
