@extends('payroll::layouts.payroll')

@section('content')

@php
    $locale = app()->getLocale();
    $isUrdu = $locale === 'ur';
@endphp

<div
    class="container-fluid"
    dir="{{ $isUrdu ? 'rtl' : 'ltr' }}"
>

    {{-- Report Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>

            <h3 class="mb-1">
                {{ $isUrdu
                    ? 'روزانہ حاضری رپورٹ'
                    : 'Daily Attendance Report'
                }}
            </h3>

            <div class="text-muted">

                {{ $isUrdu ? 'تاریخ:' : 'Date:' }}

                {{ \Carbon\Carbon::parse($date)->format('d-m-Y') }}

            </div>

        </div>

    </div>


    {{-- FILTER --}}
    @include('payroll::reports.daily-attendance.filters')


    {{-- SUMMARY CARDS --}}
    <div class="row g-3 mb-4">

        {{-- Total --}}
        <div class="col-md-3">

            <div class="card shadow-sm">

                <div class="card-body">

                    <div class="text-muted">
                        {{ $isUrdu ? 'کل ریکارڈ' : 'Total Records' }}
                    </div>

                    <h4 class="mb-0">
                        {{ number_format($summary['total']) }}
                    </h4>

                </div>

            </div>

        </div>


        {{-- Present --}}
        <div class="col-md-3">

            <div class="card shadow-sm">

                <div class="card-body">

                    <div class="text-muted">
                        {{ $isUrdu ? 'حاضر' : 'Present' }}
                    </div>

                    <h4 class="mb-0">
                        {{ number_format($summary['present']) }}
                    </h4>

                </div>

            </div>

        </div>


        {{-- Absent --}}
        <div class="col-md-3">

            <div class="card shadow-sm">

                <div class="card-body">

                    <div class="text-muted">
                        {{ $isUrdu ? 'غیر حاضر' : 'Absent' }}
                    </div>

                    <h4 class="mb-0">
                        {{ number_format($summary['absent']) }}
                    </h4>

                </div>

            </div>

        </div>


        {{-- Late --}}
        <div class="col-md-3">

            <div class="card shadow-sm">

                <div class="card-body">

                    <div class="text-muted">
                        {{ $isUrdu ? 'تاخیر کے منٹ' : 'Late Minutes' }}
                    </div>

                    <h4 class="mb-0">
                        {{ number_format($summary['late_minutes']) }}
                    </h4>

                </div>

            </div>

        </div>

    </div>


    {{-- REPORT TABLE --}}
    <div class="card shadow-sm">

        <div class="card-header">

            <strong>
                {{ $isUrdu
                    ? 'حاضری کی تفصیل'
                    : 'Attendance Details'
                }}
            </strong>

        </div>


        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-bordered table-hover mb-0">

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

                            <th>
                                {{ $isUrdu ? 'شفٹ' : 'Shift' }}
                            </th>

                            <th>
                                {{ $isUrdu ? 'تاریخ' : 'Date' }}
                            </th>

                            <th>
                                {{ $isUrdu ? 'حاضری' : 'Check In' }}
                            </th>

                            <th>
                                {{ $isUrdu ? 'چھٹی' : 'Check Out' }}
                            </th>

                            <th>
                                {{ $isUrdu ? 'کام کے گھنٹے' : 'Working Hours' }}
                            </th>

                            <th>
                                {{ $isUrdu ? 'تاخیر' : 'Late' }}
                            </th>

                            <th>
                                {{ $isUrdu ? 'جلدی چھٹی' : 'Early Leave' }}
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

                        @forelse($attendances as $index => $attendance)

                            @php

                                $employee = $attendance->employee;

                                $employeeName = $isUrdu
                                    ? trim(
                                        ($employee->first_name_ur ?? '') . ' ' .
                                        ($employee->last_name_ur ?? '')
                                    )
                                    : trim(
                                        ($employee->first_name_en ?? '') . ' ' .
                                        ($employee->last_name_en ?? '')
                                    );

                                $department = $employee->department;

                                $designation = $employee->designation;

                                $shift = $employee->shift;

                                $workingMinutes = 0;

                                if (
                                    $attendance->check_in &&
                                    $attendance->check_out
                                ) {

                                    $checkIn = \Carbon\Carbon::parse(
                                        $attendance->check_in
                                    );

                                    $checkOut = \Carbon\Carbon::parse(
                                        $attendance->check_out
                                    );

                                    $workingMinutes =
                                        $checkIn->diffInMinutes($checkOut);
                                }

                                $workingHours =
                                    intdiv($workingMinutes, 60);

                                $remainingMinutes =
                                    $workingMinutes % 60;

                                $statusLabel = match($attendance->status) {

                                    'present' =>
                                        $isUrdu ? 'حاضر' : 'Present',

                                    'absent' =>
                                        $isUrdu ? 'غیر حاضر' : 'Absent',

                                    'late' =>
                                        $isUrdu ? 'تاخیر سے' : 'Late',

                                    'half_day' =>
                                        $isUrdu ? 'آدھا دن' : 'Half Day',

                                    'leave' =>
                                        $isUrdu ? 'چھٹی' : 'Leave',

                                    'holiday' =>
                                        $isUrdu ? 'تعطیل' : 'Holiday',

                                    'manual' =>
                                        $isUrdu ? 'دستی' : 'Manual',

                                    default =>
                                        $attendance->status,
                                };

                            @endphp


                            <tr>

                                <td>
                                    {{ $attendances->firstItem() + $index }}
                                </td>


                                <td>
                                    <strong>
                                        {{ $employeeName ?: '-' }}
                                    </strong>
                                </td>


                                <td>
                                    {{ $department
                                        ? ($isUrdu
                                            ? $department->title_ur
                                            : $department->title_en)
                                        : '-'
                                    }}
                                </td>


                                <td>
                                    {{ $designation
                                        ? ($isUrdu
                                            ? $designation->title_ur
                                            : $designation->title_en)
                                        : '-'
                                    }}
                                </td>


                                <td>
                                    {{ $shift
                                        ? ($isUrdu
                                            ? $shift->shift_name_ur
                                            : $shift->shift_name_en)
                                        : '-'
                                    }}
                                </td>


                                <td>
                                    {{ \Carbon\Carbon::parse(
                                        $attendance->date
                                    )->format('d-m-Y') }}
                                </td>


                                <td>
                                    {{ $attendance->check_in ?: '-' }}
                                </td>


                                <td>
                                    {{ $attendance->check_out ?: '-' }}
                                </td>


                                <td>

                                    @if($workingMinutes > 0)

                                        {{ $workingHours }}h
                                        {{ str_pad(
                                            $remainingMinutes,
                                            2,
                                            '0',
                                            STR_PAD_LEFT
                                        ) }}m

                                    @else

                                        -

                                    @endif

                                </td>


                                <td>
                                    {{ number_format(
                                        $attendance->late_minutes
                                    ) }}
                                </td>


                                <td>
                                    {{ number_format(
                                        $attendance->early_leave_minutes
                                    ) }}
                                </td>


                                <td>
                                    {{ number_format(
                                        $attendance->overtime_minutes
                                    ) }}
                                </td>


                                <td>
                                    {{ $statusLabel }}
                                </td>


                                <td>
                                    {{ $attendance->is_manual
                                        ? ($isUrdu
                                            ? 'دستی'
                                            : 'Manual')
                                        : ($isUrdu
                                            ? 'ڈیوائس'
                                            : 'Device')
                                    }}
                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="14"
                                    class="text-center py-5"
                                >

                                    {{ $isUrdu
                                        ? 'اس تاریخ کے لیے کوئی حاضری ریکارڈ نہیں ملا۔'
                                        : 'No attendance records found.'
                                    }}

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        @if($attendances->hasPages())

            <div class="card-footer">

                {{ $attendances->links() }}

            </div>

        @endif

    </div>

</div>

@endsection
