@extends('payroll::layouts.payroll')

@section('content')

@php
    $isUrdu = app()->getLocale() === 'ur';

    $formatMinutes = function ($minutes) use ($isUrdu) {

        $minutes = (int) $minutes;

        $hours = intdiv($minutes, 60);

        $remainingMinutes = $minutes % 60;

        if ($isUrdu) {
            return $hours . ' گھنٹے ' .
                $remainingMinutes . ' منٹ';
        }

        return $hours . ' Hours ' .
            $remainingMinutes . ' Minutes';
    };
@endphp


<div
    class="content"
    dir="{{ $isUrdu ? 'rtl' : 'ltr' }}"
>


    {{-- ========================================================== --}}
    {{-- HEADER --}}
    {{-- ========================================================== --}}

    <div class="block block-rounded">

        <div class="block-content">

            <div class="row align-items-center">

                <div class="col-md-8">

                    <h2 class="mb-1">

                        <i class="fa fa-building me-1"></i>

                        {{ $isUrdu
                            ? 'شعبہ وار حاضری کا خلاصہ'
                            : 'Department-wise Attendance Summary'
                        }}

                    </h2>


                    <p class="text-muted mb-0">

                        {{ \Carbon\Carbon::parse(
                            $fromDate
                        )->format('d-m-Y') }}

                        -

                        {{ \Carbon\Carbon::parse(
                            $toDate
                        )->format('d-m-Y') }}

                        |

                        {{ $totalPeriodDays }}

                        {{ $isUrdu
                            ? 'دن'
                            : 'Days'
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


    {{-- ========================================================== --}}
    {{-- FILTER --}}
    {{-- ========================================================== --}}

    <div class="no-print">

        @include(
            'payroll::reports.department-attendance-summary.filters'
        )

    </div>


    {{-- ========================================================== --}}
    {{-- SUMMARY CARDS --}}
    {{-- ========================================================== --}}

    <div class="row">

        {{-- Departments --}}
        <div class="col-md-3">

            <div class="block block-rounded">

                <div class="block-content text-center">

                    <div class="text-muted">

                        {{ $isUrdu
                            ? 'کل شعبے'
                            : 'Departments'
                        }}

                    </div>

                    <div class="fs-2 fw-bold">

                        {{ number_format(
                            $totalDepartments
                        ) }}

                    </div>

                </div>

            </div>

        </div>


        {{-- Employees --}}
        <div class="col-md-3">

            <div class="block block-rounded">

                <div class="block-content text-center">

                    <div class="text-muted">

                        {{ $isUrdu
                            ? 'کل ملازمین'
                            : 'Employees'
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

                    <div class="text-muted">

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


        {{-- Percentage --}}
        <div class="col-md-3">

            <div class="block block-rounded">

                <div class="block-content text-center">

                    <div class="text-muted">

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


    {{-- ========================================================== --}}
    {{-- SECONDARY SUMMARY --}}
    {{-- ========================================================== --}}

    <div class="row">


        {{-- Absent --}}
        <div class="col-md-3">

            <div class="block block-rounded">

                <div class="block-content text-center">

                    <div class="text-muted">

                        {{ $isUrdu
                            ? 'غیر حاضر دن'
                            : 'Absent Days'
                        }}

                    </div>

                    <div class="fs-3 fw-bold text-danger">

                        {{ number_format(
                            $totalAbsentDays
                        ) }}

                    </div>

                </div>

            </div>

        </div>


        {{-- Leave --}}
        <div class="col-md-3">

            <div class="block block-rounded">

                <div class="block-content text-center">

                    <div class="text-muted">

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


        {{-- Late --}}
        <div class="col-md-3">

            <div class="block block-rounded">

                <div class="block-content text-center">

                    <div class="text-muted">

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


        {{-- Overtime --}}
        <div class="col-md-3">

            <div class="block block-rounded">

                <div class="block-content text-center">

                    <div class="text-muted">

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


    {{-- ========================================================== --}}
    {{-- TABLE --}}
    {{-- ========================================================== --}}

    <div class="block block-rounded">

        <div class="block-header block-header-default">

            <h3 class="block-title">

                {{ $isUrdu
                    ? 'شعبہ وار رپورٹ'
                    : 'Department-wise Report'
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
                                    ? 'شعبہ'
                                    : 'Department'
                                }}
                            </th>

                            <th class="text-center">
                                {{ $isUrdu
                                    ? 'ملازمین'
                                    : 'Employees'
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
                                    ? 'تعطیل'
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
                            $departmentSummary
                            as $index => $summary
                        )

                            @php
                                $department =
                                    $summary['department'];
                            @endphp

                            <tr>

                                <td class="text-center">

                                    {{ $index + 1 }}

                                </td>


                                <td>

                                    {{ $isUrdu
                                        ? (
                                            $department->title_ur
                                            ?: $department->title_en
                                        )
                                        : (
                                            $department->title_en
                                            ?: $department->title_ur
                                        )
                                    }}

                                </td>


                                <td class="text-center">

                                    {{ number_format(
                                        $summary['employee_count']
                                    ) }}

                                </td>


                                <td class="text-center">

                                    {{ number_format(
                                        $summary['period_days']
                                    ) }}

                                </td>


                                <td class="text-center fw-bold text-success">

                                    {{ number_format(
                                        $summary['present_days']
                                    ) }}

                                </td>


                                <td class="text-center fw-bold text-danger">

                                    {{ number_format(
                                        $summary['absent_days']
                                    ) }}

                                </td>


                                <td class="text-center">

                                    {{ number_format(
                                        $summary['leave_days']
                                    ) }}

                                </td>


                                <td class="text-center">

                                    {{ number_format(
                                        $summary['holiday_days']
                                    ) }}

                                </td>


                                <td class="text-center fw-bold">

                                    {{ number_format(
                                        $summary['attendance_percentage'],
                                        2
                                    ) }}%

                                </td>


                                <td class="text-center">

                                    {{ number_format(
                                        $summary['late_days']
                                    ) }}

                                </td>


                                <td class="text-center">

                                    {{ number_format(
                                        $summary['total_late_minutes']
                                    ) }}

                                </td>


                                <td class="text-center">

                                    {{ number_format(
                                        $summary['early_leave_days']
                                    ) }}

                                </td>


                                <td class="text-center">

                                    {{ number_format(
                                        $summary['total_early_leave_minutes']
                                    ) }}

                                </td>


                                <td class="text-center">

                                    {{ number_format(
                                        $summary['overtime_days']
                                    ) }}

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
                                    colspan="15"
                                    class="text-center text-muted py-4"
                                >

                                    {{ $isUrdu
                                        ? 'کوئی ریکارڈ نہیں ملا۔'
                                        : 'No records found.'
                                    }}

                                </td>

                            </tr>

                        @endforelse

                    </tbody>


                    {{-- ================================================= --}}
                    {{-- TOTAL --}}
                    {{-- ================================================= --}}

                    @if($departmentSummary->count())

                        <tfoot>

                            <tr class="fw-bold">

                                <td colspan="2">

                                    {{ $isUrdu
                                        ? 'کل'
                                        : 'TOTAL'
                                    }}

                                </td>


                                <td class="text-center">

                                    {{ number_format(
                                        $totalEmployees
                                    ) }}

                                </td>


                                <td class="text-center">

                                    {{ number_format(
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
        font-size: 8px !important;
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
