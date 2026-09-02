@extends('payroll::layouts.payroll')

@section('content')

@php
    $isUrdu = app()->getLocale() === 'ur';

    $formatMinutes = function ($minutes) use ($isUrdu) {

        $minutes = (int) $minutes;

        $hours = intdiv($minutes, 60);

        $remaining = $minutes % 60;

        if ($isUrdu) {
            return $hours . ' گھنٹے ' . $remaining . ' منٹ';
        }

        return $hours . ' Hours ' . $remaining . ' Minutes';
    };
@endphp


<div
    class="content"
    dir="{{ $isUrdu ? 'rtl' : 'ltr' }}"
>


    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <div class="block block-rounded">

        <div class="block-content">

            <div class="row align-items-center">

                <div class="col-md-8">

                    <h2 class="mb-1">

                        <i class="fa fa-users me-1"></i>

                        {{ $isUrdu
                            ? 'ملازمین کی حاضری کا خلاصہ'
                            : 'Employee Attendance Summary Report'
                        }}

                    </h2>

                    <p class="text-muted mb-0">

                        {{ \Carbon\Carbon::parse($fromDate)->format('d-m-Y') }}

                        -

                        {{ \Carbon\Carbon::parse($toDate)->format('d-m-Y') }}

                        |

                        {{ $isUrdu
                            ? $totalPeriodDays . ' دن'
                            : $totalPeriodDays . ' Days'
                        }}

                    </p>

                </div>


                <div class="col-md-4 text-end no-print">

                    <button
                        type="button"
                        onclick="window.print()"
                        class="btn btn-primary"
                    >

                        <i class="fa fa-print me-1"></i>

                        {{ $isUrdu
                            ? 'پرنٹ'
                            : 'Print'
                        }}

                    </button>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- FILTERS --}}
    {{-- ========================================================= --}}

    <div class="no-print">

        @include(
            'payroll::reports.employee-attendance-summary.filters'
        )

    </div>


    {{-- ========================================================= --}}
    {{-- SUMMARY CARDS --}}
    {{-- ========================================================= --}}

    <div class="row">


        {{-- Employees --}}
        <div class="col-md-3">

            <div class="block block-rounded">

                <div class="block-content text-center">

                    <div class="text-muted fs-sm">

                        {{ $isUrdu
                            ? 'کل ملازمین'
                            : 'Total Employees'
                        }}

                    </div>

                    <div class="fs-2 fw-bold">

                        {{ number_format(
                            $totalEmployees
                        ) }}

                    </div>

                </div>

            </div>

        </div>


        {{-- Present --}}
        <div class="col-md-3">

            <div class="block block-rounded">

                <div class="block-content text-center">

                    <div class="text-muted fs-sm">

                        {{ $isUrdu
                            ? 'حاضر دن'
                            : 'Present Days'
                        }}

                    </div>

                    <div class="fs-2 fw-bold text-success">

                        {{ number_format(
                            $totalPresentDays
                        ) }}

                    </div>

                </div>

            </div>

        </div>


        {{-- Absent --}}
        <div class="col-md-3">

            <div class="block block-rounded">

                <div class="block-content text-center">

                    <div class="text-muted fs-sm">

                        {{ $isUrdu
                            ? 'غیر حاضر دن'
                            : 'Absent Days'
                        }}

                    </div>

                    <div class="fs-2 fw-bold text-danger">

                        {{ number_format(
                            $totalAbsentDays
                        ) }}

                    </div>

                </div>

            </div>

        </div>


        {{-- Attendance Percentage --}}
        <div class="col-md-3">

            <div class="block block-rounded">

                <div class="block-content text-center">

                    <div class="text-muted fs-sm">

                        {{ $isUrdu
                            ? 'حاضری کا تناسب'
                            : 'Attendance Percentage'
                        }}

                    </div>

                    <div class="fs-2 fw-bold">

                        {{ number_format(
                            $overallAttendancePercentage,
                            2
                        ) }}%

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- ADDITIONAL SUMMARY --}}
    {{-- ========================================================= --}}

    <div class="row">


        <div class="col-md-3">

            <div class="block block-rounded">

                <div class="block-content text-center">

                    <div class="text-muted fs-sm">

                        {{ $isUrdu
                            ? 'چھٹی کے دن'
                            : 'Leave Days'
                        }}

                    </div>

                    <div class="fs-3 fw-bold">

                        {{ number_format(
                            $totalLeaveDays
                        ) }}

                    </div>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="block block-rounded">

                <div class="block-content text-center">

                    <div class="text-muted fs-sm">

                        {{ $isUrdu
                            ? 'تاخیر کے منٹس'
                            : 'Late Minutes'
                        }}

                    </div>

                    <div class="fs-3 fw-bold">

                        {{ number_format(
                            $totalLateMinutes
                        ) }}

                    </div>

                    <small class="text-muted">

                        {{ $formatMinutes(
                            $totalLateMinutes
                        ) }}

                    </small>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="block block-rounded">

                <div class="block-content text-center">

                    <div class="text-muted fs-sm">

                        {{ $isUrdu
                            ? 'جلدی چھٹی کے منٹس'
                            : 'Early Leave Minutes'
                        }}

                    </div>

                    <div class="fs-3 fw-bold">

                        {{ number_format(
                            $totalEarlyLeaveMinutes
                        ) }}

                    </div>

                    <small class="text-muted">

                        {{ $formatMinutes(
                            $totalEarlyLeaveMinutes
                        ) }}

                    </small>

                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="block block-rounded">

                <div class="block-content text-center">

                    <div class="text-muted fs-sm">

                        {{ $isUrdu
                            ? 'اوور ٹائم منٹس'
                            : 'Overtime Minutes'
                        }}

                    </div>

                    <div class="fs-3 fw-bold">

                        {{ number_format(
                            $totalOvertimeMinutes
                        ) }}

                    </div>

                    <small class="text-muted">

                        {{ $formatMinutes(
                            $totalOvertimeMinutes
                        ) }}

                    </small>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- EMPLOYEE SUMMARY TABLE --}}
    {{-- ========================================================= --}}

    <div class="block block-rounded">

        <div class="block-header block-header-default">

            <h3 class="block-title">

                {{ $isUrdu
                    ? 'ملازم وار حاضری کا خلاصہ'
                    : 'Employee-wise Attendance Summary'
                }}

            </h3>

        </div>


        <div class="block-content">

            <div class="table-responsive">

                <table
                    class="table table-bordered table-striped table-vcenter"
                >

                    <thead>

                        <tr>

                            <th class="text-center">
                                #
                            </th>

                            <th>
                                {{ $isUrdu
                                    ? 'ملازم'
                                    : 'Employee'
                                }}
                            </th>

                            <th>
                                {{ $isUrdu
                                    ? 'شعبہ'
                                    : 'Department'
                                }}
                            </th>

                            <th>
                                {{ $isUrdu
                                    ? 'عہدہ'
                                    : 'Designation'
                                }}
                            </th>

                            <th>
                                {{ $isUrdu
                                    ? 'شفٹ'
                                    : 'Shift'
                                }}
                            </th>

                            <th class="text-center">
                                {{ $isUrdu
                                    ? 'کل دن'
                                    : 'Period Days'
                                }}
                            </th>

                            <th class="text-center">
                                {{ $isUrdu
                                    ? 'حاضر'
                                    : 'Present'
                                }}
                            </th>

                            <th class="text-center">
                                {{ $isUrdu
                                    ? 'غیر حاضر'
                                    : 'Absent'
                                }}
                            </th>

                            <th class="text-center">
                                {{ $isUrdu
                                    ? 'چھٹی'
                                    : 'Leave'
                                }}
                            </th>

                            <th class="text-center">
                                {{ $isUrdu
                                    ? 'چھٹی کے دن'
                                    : 'Holiday'
                                }}
                            </th>

                            <th class="text-center">
                                {{ $isUrdu
                                    ? 'حاضری %'
                                    : 'Attendance %'
                                }}
                            </th>

                            <th class="text-center">
                                {{ $isUrdu
                                    ? 'تاخیر دن'
                                    : 'Late Days'
                                }}
                            </th>

                            <th class="text-center">
                                {{ $isUrdu
                                    ? 'تاخیر منٹس'
                                    : 'Late Minutes'
                                }}
                            </th>

                            <th class="text-center">
                                {{ $isUrdu
                                    ? 'جلدی چھٹی دن'
                                    : 'Early Leave Days'
                                }}
                            </th>

                            <th class="text-center">
                                {{ $isUrdu
                                    ? 'جلدی چھٹی منٹس'
                                    : 'Early Leave Minutes'
                                }}
                            </th>

                            <th class="text-center">
                                {{ $isUrdu
                                    ? 'اوور ٹائم دن'
                                    : 'OT Days'
                                }}
                            </th>

                            <th class="text-center">
                                {{ $isUrdu
                                    ? 'اوور ٹائم منٹس'
                                    : 'OT Minutes'
                                }}
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse(
                            $employeeSummary
                            as $index => $summary
                        )

                            @php

                                $employee =
                                    $summary['employee'];

                            @endphp

                            <tr>

                                <td class="text-center">

                                    {{ $index + 1 }}

                                </td>


                                <td>

                                    {{ $isUrdu
                                        ? (
                                            $summary['name_ur']
                                            ?: $summary['name_en']
                                        )
                                        : (
                                            $summary['name_en']
                                            ?: $summary['name_ur']
                                        )
                                    }}

                                </td>


                                <td>

                                    {{ $employee->department
                                        ? (
                                            $isUrdu
                                                ? (
                                                    $employee->department->title_ur
                                                    ?: $employee->department->title_en
                                                )
                                                : (
                                                    $employee->department->title_en
                                                    ?: $employee->department->title_ur
                                                )
                                        )
                                        : '-'
                                    }}

                                </td>


                                <td>

                                    {{ $employee->designation
                                        ? (
                                            $isUrdu
                                                ? (
                                                    $employee->designation->title_ur
                                                    ?: $employee->designation->title_en
                                                )
                                                : (
                                                    $employee->designation->title_en
                                                    ?: $employee->designation->title_ur
                                                )
                                        )
                                        : '-'
                                    }}

                                </td>


                                <td>

                                    {{ $employee->shift
                                        ? (
                                            $isUrdu
                                                ? (
                                                    $employee->shift->shift_name_ur
                                                    ?: $employee->shift->shift_name_en
                                                )
                                                : (
                                                    $employee->shift->shift_name_en
                                                    ?: $employee->shift->shift_name_ur
                                                )
                                        )
                                        : '-'
                                    }}

                                </td>


                                <td class="text-center">

                                    {{ $summary['total_period_days'] }}

                                </td>


                                <td class="text-center fw-bold text-success">

                                    {{ $summary['present_days'] }}

                                </td>


                                <td class="text-center fw-bold text-danger">

                                    {{ $summary['absent_days'] }}

                                </td>


                                <td class="text-center">

                                    {{ $summary['leave_days'] }}

                                </td>


                                <td class="text-center">

                                    {{ $summary['holiday_days'] }}

                                </td>


                                <td class="text-center fw-bold">

                                    {{ number_format(
                                        $summary['attendance_percentage'],
                                        2
                                    ) }}%

                                </td>


                                <td class="text-center">

                                    {{ $summary['late_days'] }}

                                </td>


                                <td class="text-center">

                                    {{ number_format(
                                        $summary['total_late_minutes']
                                    ) }}

                                </td>


                                <td class="text-center">

                                    {{ $summary['early_leave_days'] }}

                                </td>


                                <td class="text-center">

                                    {{ number_format(
                                        $summary['total_early_leave_minutes']
                                    ) }}

                                </td>


                                <td class="text-center">

                                    {{ $summary['overtime_days'] }}

                                </td>


                                <td class="text-center">

                                    {{ number_format(
                                        $summary['total_overtime_minutes']
                                    ) }}

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="17"
                                    class="text-center text-muted py-4"
                                >

                                    {{ $isUrdu
                                        ? 'کوئی ملازم یا حاضری کا ریکارڈ نہیں ملا۔'
                                        : 'No employees or attendance records found.'
                                    }}

                                </td>

                            </tr>

                        @endforelse

                    </tbody>


                    @if($employeeSummary->count())

                        <tfoot>

                            <tr class="fw-bold">

                                <td colspan="5">

                                    {{ $isUrdu
                                        ? 'کل'
                                        : 'TOTAL'
                                    }}

                                </td>


                                <td class="text-center">

                                    {{ number_format(
                                        $totalEmployees *
                                        $totalPeriodDays
                                    ) }}

                                </td>


                                <td class="text-center text-success">

                                    {{ number_format(
                                        $totalPresentDays
                                    ) }}

                                </td>


                                <td class="text-center text-danger">

                                    {{ number_format(
                                        $totalAbsentDays
                                    ) }}

                                </td>


                                <td class="text-center">

                                    {{ number_format(
                                        $totalLeaveDays
                                    ) }}

                                </td>


                                <td class="text-center">

                                    {{ number_format(
                                        $totalHolidayDays
                                    ) }}

                                </td>


                                <td class="text-center">

                                    {{ number_format(
                                        $overallAttendancePercentage,
                                        2
                                    ) }}%

                                </td>


                                <td class="text-center">

                                    {{ number_format(
                                        $totalLateDays
                                    ) }}

                                </td>


                                <td class="text-center">

                                    {{ number_format(
                                        $totalLateMinutes
                                    ) }}

                                </td>


                                <td class="text-center">

                                    {{ number_format(
                                        $totalEarlyLeaveDays
                                    ) }}

                                </td>


                                <td class="text-center">

                                    {{ number_format(
                                        $totalEarlyLeaveMinutes
                                    ) }}

                                </td>


                                <td class="text-center">

                                    {{ number_format(
                                        $totalOvertimeDays
                                    ) }}

                                </td>


                                <td class="text-center">

                                    {{ number_format(
                                        $totalOvertimeMinutes
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
        font-size: 7px !important;
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
