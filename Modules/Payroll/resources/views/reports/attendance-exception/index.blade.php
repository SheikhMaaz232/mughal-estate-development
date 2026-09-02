@extends('payroll::layouts.payroll')

@section('content')

@php

    $isUrdu = app()->getLocale() === 'ur';

    $formatMinutes = function ($minutes) use ($isUrdu) {

        $minutes = (int) $minutes;

        $hours = intdiv(
            $minutes,
            60
        );

        $remainingMinutes =
            $minutes % 60;

        if ($isUrdu) {

            return $hours .
                ' گھنٹے ' .
                $remainingMinutes .
                ' منٹ';
        }

        return $hours .
            ' Hours ' .
            $remainingMinutes .
            ' Minutes';
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

                        <i class="fa fa-exclamation-triangle me-1"></i>

                        {{ $isUrdu
                            ? 'حاضری کی استثنائی رپورٹ'
                            : 'Attendance Exception Report'
                        }}

                    </h2>

                    <p class="text-muted mb-0">

                        {{ \Carbon\Carbon::parse($fromDate)->format('d-m-Y') }}

                        -

                        {{ \Carbon\Carbon::parse($toDate)->format('d-m-Y') }}

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
            'payroll::reports.attendance-exception.filters'
        )

    </div>


    {{-- ========================================================= --}}
    {{-- SUMMARY --}}
    {{-- ========================================================= --}}

    <div class="row">


        {{-- Total --}}
        <div class="col-md-3">

            <div class="block block-rounded">

                <div class="block-content text-center">

                    <div class="text-muted">

                        {{ $isUrdu
                            ? 'کل ریکارڈ'
                            : 'Total Exceptions'
                        }}

                    </div>

                    <div class="fs-2 fw-bold">

                        {{ number_format(
                            $totalRecords
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
                            ? 'دیر سے آمد'
                            : 'Late Arrivals'
                        }}

                    </div>

                    <div class="fs-2 fw-bold">

                        {{ number_format(
                            $lateRecords
                        ) }}

                    </div>

                    <small class="text-muted">

                        {{ number_format(
                            $totalLateMinutes
                        ) }}

                        {{ $isUrdu ? 'منٹ' : 'minutes' }}

                    </small>

                </div>

            </div>

        </div>


        {{-- Early --}}
        <div class="col-md-3">

            <div class="block block-rounded">

                <div class="block-content text-center">

                    <div class="text-muted">

                        {{ $isUrdu
                            ? 'جلدی چھٹی'
                            : 'Early Leaves'
                        }}

                    </div>

                    <div class="fs-2 fw-bold">

                        {{ number_format(
                            $earlyLeaveRecords
                        ) }}

                    </div>

                    <small class="text-muted">

                        {{ number_format(
                            $totalEarlyLeaveMinutes
                        ) }}

                        {{ $isUrdu ? 'منٹ' : 'minutes' }}

                    </small>

                </div>

            </div>

        </div>


        {{-- Missing --}}
        <div class="col-md-3">

            <div class="block block-rounded">

                <div class="block-content text-center">

                    <div class="text-muted">

                        {{ $isUrdu
                            ? 'نامکمل پنچ'
                            : 'Missing Punches'
                        }}

                    </div>

                    <div class="fs-2 fw-bold">

                        {{ number_format(
                            $missingPunchRecords
                        ) }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- EMPLOYEE SUMMARY --}}
    {{-- ========================================================= --}}

    <div class="block block-rounded">

        <div class="block-header block-header-default">

            <h3 class="block-title">

                {{ $isUrdu
                    ? 'ملازم وار خلاصہ'
                    : 'Employee-wise Summary'
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

                            <th class="text-center">
                                {{ $isUrdu
                                    ? 'کل'
                                    : 'Exceptions'
                                }}
                            </th>

                            <th class="text-center">
                                {{ $isUrdu
                                    ? 'دیر'
                                    : 'Late'
                                }}
                            </th>

                            <th class="text-center">
                                {{ $isUrdu
                                    ? 'جلدی'
                                    : 'Early'
                                }}
                            </th>

                            <th class="text-center">
                                {{ $isUrdu
                                    ? 'چیک اِن غائب'
                                    : 'Missing In'
                                }}
                            </th>

                            <th class="text-center">
                                {{ $isUrdu
                                    ? 'چیک آؤٹ غائب'
                                    : 'Missing Out'
                                }}
                            </th>

                            <th class="text-center">
                                {{ $isUrdu
                                    ? 'دیر منٹس'
                                    : 'Late Min.'
                                }}
                            </th>

                            <th class="text-center">
                                {{ $isUrdu
                                    ? 'جلدی منٹس'
                                    : 'Early Min.'
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

                                    <strong>

                                        {{ $employee->first_name_en }}
                                        {{ $employee->last_name_en }}

                                    </strong>

                                    @if($employee->device_user_id)

                                        <br>

                                        <small class="text-muted">

                                            ID:
                                            {{ $employee->device_user_id }}

                                        </small>

                                    @endif

                                </td>


                                <td>

                                    @if($employee->department)

                                        {{ $isUrdu
                                            ? (
                                                $employee->department->title_ur
                                                ?: $employee->department->title_en
                                            )
                                            : (
                                                $employee->department->title_en
                                                ?: $employee->department->title_ur
                                            )
                                        }}

                                    @else
                                        -
                                    @endif

                                </td>


                                <td>

                                    @if($employee->designation)

                                        {{ $isUrdu
                                            ? (
                                                $employee->designation->title_ur
                                                ?: $employee->designation->title_en
                                            )
                                            : (
                                                $employee->designation->title_en
                                                ?: $employee->designation->title_ur
                                            )
                                        }}

                                    @else
                                        -
                                    @endif

                                </td>


                                <td class="text-center fw-bold">

                                    {{ number_format(
                                        $summary['records']
                                    ) }}

                                </td>


                                <td class="text-center">

                                    {{ number_format(
                                        $summary['late_records']
                                    ) }}

                                </td>


                                <td class="text-center">

                                    {{ number_format(
                                        $summary['early_leave_records']
                                    ) }}

                                </td>


                                <td class="text-center">

                                    {{ number_format(
                                        $summary['missing_check_in']
                                    ) }}

                                </td>


                                <td class="text-center">

                                    {{ number_format(
                                        $summary['missing_check_out']
                                    ) }}

                                </td>


                                <td class="text-center">

                                    {{ number_format(
                                        $summary['late_minutes']
                                    ) }}

                                </td>


                                <td class="text-center">

                                    {{ number_format(
                                        $summary['early_leave_minutes']
                                    ) }}

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="11"
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

                </table>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- DETAIL --}}
    {{-- ========================================================= --}}

    <div class="block block-rounded">

        <div class="block-header block-header-default">

            <h3 class="block-title">

                {{ $isUrdu
                    ? 'استثنائی تفصیل'
                    : 'Exception Details'
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

                            <th>
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
                                {{ $isUrdu ? 'دیر' : 'Late' }}
                            </th>

                            <th class="text-center">
                                {{ $isUrdu ? 'جلدی' : 'Early Leave' }}
                            </th>

                            <th>
                                {{ $isUrdu
                                    ? 'استثنا'
                                    : 'Exception'
                                }}
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse(
                            $attendances
                            as $index => $attendance
                        )

                            @php
                                $employee =
                                    $attendance->employee;
                            @endphp

                            <tr>

                                <td>
                                    {{ $index + 1 }}
                                </td>


                                <td>

                                    {{ \Carbon\Carbon::parse(
                                        $attendance->date
                                    )->format('d-m-Y') }}

                                </td>


                                <td>

                                    @if($employee)

                                        {{ $employee->first_name_en }}
                                        {{ $employee->last_name_en }}

                                    @else
                                        -
                                    @endif

                                </td>


                                <td>

                                    @if(
                                        $employee &&
                                        $employee->department
                                    )

                                        {{ $isUrdu
                                            ? (
                                                $employee->department->title_ur
                                                ?: $employee->department->title_en
                                            )
                                            : (
                                                $employee->department->title_en
                                                ?: $employee->department->title_ur
                                            )
                                        }}

                                    @else
                                        -
                                    @endif

                                </td>


                                <td>

                                    @if(
                                        $employee &&
                                        $employee->shift
                                    )

                                        {{ $isUrdu
                                            ? (
                                                $employee->shift->shift_name_ur
                                                ?: $employee->shift->shift_name_en
                                            )
                                            : (
                                                $employee->shift->shift_name_en
                                                ?: $employee->shift->shift_name_ur
                                            )
                                        }}

                                    @else
                                        -
                                    @endif

                                </td>


                                <td>

                                    @if($attendance->check_in)

                                        {{ \Carbon\Carbon::parse(
                                            $attendance->check_in
                                        )->format('h:i A') }}

                                    @else

                                        <span class="text-danger">

                                            {{ $isUrdu
                                                ? 'غائب'
                                                : 'Missing'
                                            }}

                                        </span>

                                    @endif

                                </td>


                                <td>

                                    @if($attendance->check_out)

                                        {{ \Carbon\Carbon::parse(
                                            $attendance->check_out
                                        )->format('h:i A') }}

                                    @else

                                        <span class="text-danger">

                                            {{ $isUrdu
                                                ? 'غائب'
                                                : 'Missing'
                                            }}

                                        </span>

                                    @endif

                                </td>


                                <td class="text-center">

                                    @if(
                                        (int) $attendance->late_minutes > 0
                                    )

                                        <span class="badge bg-warning">

                                            {{ $attendance->late_minutes }}

                                            {{ $isUrdu
                                                ? 'منٹ'
                                                : 'min'
                                            }}

                                        </span>

                                    @else

                                        -

                                    @endif

                                </td>


                                <td class="text-center">

                                    @if(
                                        (int) $attendance->early_leave_minutes > 0
                                    )

                                        <span class="badge bg-warning">

                                            {{ $attendance->early_leave_minutes }}

                                            {{ $isUrdu
                                                ? 'منٹ'
                                                : 'min'
                                            }}

                                        </span>

                                    @else

                                        -

                                    @endif

                                </td>


                                <td>

                                    @switch(
                                        $attendance->exception_type
                                    )

                                        @case('late')

                                            {{ $isUrdu
                                                ? 'دیر سے آمد'
                                                : 'Late Arrival'
                                            }}

                                            @break

                                        @case('early_leave')

                                            {{ $isUrdu
                                                ? 'جلدی چھٹی'
                                                : 'Early Leave'
                                            }}

                                            @break

                                        @case('both')

                                            {{ $isUrdu
                                                ? 'دیر سے آمد اور جلدی چھٹی'
                                                : 'Late & Early Leave'
                                            }}

                                            @break

                                        @case('missing_check_in')

                                            {{ $isUrdu
                                                ? 'چیک اِن موجود نہیں'
                                                : 'Missing Check-In'
                                            }}

                                            @break

                                        @case('missing_check_out')

                                            {{ $isUrdu
                                                ? 'چیک آؤٹ موجود نہیں'
                                                : 'Missing Check-Out'
                                            }}

                                            @break

                                        @case('missing_punch')

                                            {{ $isUrdu
                                                ? 'چیک اِن اور چیک آؤٹ موجود نہیں'
                                                : 'Missing Check-In & Check-Out'
                                            }}

                                            @break

                                        @default

                                            {{ $isUrdu
                                                ? 'استثنا'
                                                : 'Exception'
                                            }}

                                    @endswitch

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="10"
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
