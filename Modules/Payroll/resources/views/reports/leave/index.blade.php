@extends('payroll::layouts.payroll')

@section('content')

@php
    $isUrdu = app()->getLocale() === 'ur';
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

                        <i class="fa fa-calendar-minus me-1"></i>

                        {{ $isUrdu
                            ? 'چھٹیوں کی رپورٹ'
                            : 'Leave Report'
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

                        {{ $isUrdu ? 'پرنٹ' : 'Print' }}

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
            'payroll::reports.leave.filters'
        )

    </div>


    {{-- ========================================================= --}}
    {{-- SUMMARY CARDS --}}
    {{-- ========================================================= --}}

    <div class="row">

        {{-- Total Requests --}}
        <div class="col-md-3">

            <div class="block block-rounded">

                <div class="block-content text-center">

                    <div class="fs-sm text-muted">

                        {{ $isUrdu
                            ? 'کل درخواستیں'
                            : 'Total Requests'
                        }}

                    </div>

                    <div class="fs-2 fw-bold">

                        {{ number_format($totalRequests) }}

                    </div>

                </div>

            </div>

        </div>


        {{-- Total Leave Days --}}
        <div class="col-md-3">

            <div class="block block-rounded">

                <div class="block-content text-center">

                    <div class="fs-sm text-muted">

                        {{ $isUrdu
                            ? 'کل چھٹی کے دن'
                            : 'Total Leave Days'
                        }}

                    </div>

                    <div class="fs-2 fw-bold">

                        {{ number_format($totalLeaveDays) }}

                    </div>

                </div>

            </div>

        </div>


        {{-- Employees --}}
        <div class="col-md-3">

            <div class="block block-rounded">

                <div class="block-content text-center">

                    <div class="fs-sm text-muted">

                        {{ $isUrdu
                            ? 'ملازمین'
                            : 'Employees'
                        }}

                    </div>

                    <div class="fs-2 fw-bold">

                        {{ number_format($totalEmployees) }}

                    </div>

                </div>

            </div>

        </div>


        {{-- Approved Days --}}
        <div class="col-md-3">

            <div class="block block-rounded">

                <div class="block-content text-center">

                    <div class="fs-sm text-muted">

                        {{ $isUrdu
                            ? 'منظور شدہ دن'
                            : 'Approved Days'
                        }}

                    </div>

                    <div class="fs-2 fw-bold text-success">

                        {{ number_format($approvedDays) }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- STATUS SUMMARY --}}
    {{-- ========================================================= --}}

    <div class="block block-rounded">

        <div class="block-header block-header-default">

            <h3 class="block-title">

                {{ $isUrdu
                    ? 'چھٹیوں کا اسٹیٹس خلاصہ'
                    : 'Leave Status Summary'
                }}

            </h3>

        </div>


        <div class="block-content">

            <div class="table-responsive">

                <table class="table table-bordered table-striped">

                    <thead>

                        <tr>

                            <th>
                                {{ $isUrdu ? 'اسٹیٹس' : 'Status' }}
                            </th>

                            <th class="text-center">
                                {{ $isUrdu ? 'درخواستیں' : 'Requests' }}
                            </th>

                            <th class="text-center">
                                {{ $isUrdu ? 'دن' : 'Days' }}
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        <tr>

                            <td>

                                <span class="badge bg-success">

                                    {{ $isUrdu
                                        ? 'منظور شدہ'
                                        : 'Approved'
                                    }}

                                </span>

                            </td>

                            <td class="text-center">

                                {{ number_format(
                                    $approvedRequests
                                ) }}

                            </td>

                            <td class="text-center">

                                {{ number_format(
                                    $approvedDays
                                ) }}

                            </td>

                        </tr>


                        <tr>

                            <td>

                                <span class="badge bg-warning">

                                    {{ $isUrdu
                                        ? 'زیر التواء'
                                        : 'Pending'
                                    }}

                                </span>

                            </td>

                            <td class="text-center">

                                {{ number_format(
                                    $pendingRequests
                                ) }}

                            </td>

                            <td class="text-center">

                                {{ number_format(
                                    $pendingDays
                                ) }}

                            </td>

                        </tr>


                        <tr>

                            <td>

                                <span class="badge bg-danger">

                                    {{ $isUrdu
                                        ? 'مسترد'
                                        : 'Rejected'
                                    }}

                                </span>

                            </td>

                            <td class="text-center">

                                {{ number_format(
                                    $rejectedRequests
                                ) }}

                            </td>

                            <td class="text-center">

                                {{ number_format(
                                    $rejectedDays
                                ) }}

                            </td>

                        </tr>

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- EMPLOYEE WISE SUMMARY --}}
    {{-- ========================================================= --}}

    <div class="block block-rounded">

        <div class="block-header block-header-default">

            <h3 class="block-title">

                {{ $isUrdu
                    ? 'ملازم وار چھٹیوں کا خلاصہ'
                    : 'Employee-wise Leave Summary'
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
                                    ? 'درخواستیں'
                                    : 'Requests'
                                }}
                            </th>

                            <th class="text-center">
                                {{ $isUrdu
                                    ? 'کل دن'
                                    : 'Total Days'
                                }}
                            </th>

                            <th class="text-center">
                                {{ $isUrdu
                                    ? 'منظور شدہ'
                                    : 'Approved'
                                }}
                            </th>

                            <th class="text-center">
                                {{ $isUrdu
                                    ? 'زیر التواء'
                                    : 'Pending'
                                }}
                            </th>

                            <th class="text-center">
                                {{ $isUrdu
                                    ? 'مسترد'
                                    : 'Rejected'
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

                                $nameEn = $employee
                                    ? trim(
                                        ($employee->first_name_en ?? '') .
                                        ' ' .
                                        ($employee->last_name_en ?? '')
                                    )
                                    : '-';

                                $nameUr = $employee
                                    ? trim(
                                        ($employee->first_name_ur ?? '') .
                                        ' ' .
                                        ($employee->last_name_ur ?? '')
                                    )
                                    : '-';

                                $employeeName = $isUrdu
                                    ? ($nameUr ?: $nameEn)
                                    : ($nameEn ?: $nameUr);

                            @endphp

                            <tr>

                                <td class="text-center">
                                    {{ $index + 1 }}
                                </td>

                                <td>
                                    {{ $employeeName }}
                                </td>

                                <td>

                                    {{ $employee?->department
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

                                    {{ $employee?->designation
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

                                <td class="text-center">

                                    {{ number_format(
                                        $summary['requests']
                                    ) }}

                                </td>

                                <td class="text-center fw-bold">

                                    {{ number_format(
                                        $summary['days']
                                    ) }}

                                </td>

                                <td class="text-center text-success">

                                    {{ number_format(
                                        $summary['approved_days']
                                    ) }}

                                </td>

                                <td class="text-center text-warning">

                                    {{ number_format(
                                        $summary['pending_days']
                                    ) }}

                                </td>

                                <td class="text-center text-danger">

                                    {{ number_format(
                                        $summary['rejected_days']
                                    ) }}

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="9"
                                    class="text-center text-muted py-4"
                                >

                                    {{ $isUrdu
                                        ? 'کوئی چھٹی کا ریکارڈ نہیں ملا۔'
                                        : 'No leave records found.'
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
    {{-- LEAVE TYPE SUMMARY --}}
    {{-- ========================================================= --}}

    <div class="block block-rounded">

        <div class="block-header block-header-default">

            <h3 class="block-title">

                {{ $isUrdu
                    ? 'چھٹی کی قسم وار خلاصہ'
                    : 'Leave Type Summary'
                }}

            </h3>

        </div>


        <div class="block-content">

            <div class="table-responsive">

                <table
                    class="table table-bordered table-striped"
                >

                    <thead>

                        <tr>

                            <th class="text-center">
                                #
                            </th>

                            <th>
                                {{ $isUrdu
                                    ? 'چھٹی کی قسم'
                                    : 'Leave Type'
                                }}
                            </th>

                            <th class="text-center">
                                {{ $isUrdu
                                    ? 'درخواستیں'
                                    : 'Requests'
                                }}
                            </th>

                            <th class="text-center">
                                {{ $isUrdu
                                    ? 'کل دن'
                                    : 'Total Days'
                                }}
                            </th>

                            <th class="text-center">
                                {{ $isUrdu
                                    ? 'منظور شدہ دن'
                                    : 'Approved Days'
                                }}
                            </th>

                            <th class="text-center">
                                {{ $isUrdu
                                    ? 'زیر التواء دن'
                                    : 'Pending Days'
                                }}
                            </th>

                            <th class="text-center">
                                {{ $isUrdu
                                    ? 'مسترد دن'
                                    : 'Rejected Days'
                                }}
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse(
                            $leaveTypeSummary
                            as $index => $summary
                        )

                            @php
                                $leaveType =
                                    $summary['leave_type'];
                            @endphp

                            <tr>

                                <td class="text-center">
                                    {{ $index + 1 }}
                                </td>

                                <td>

                                    {{ $leaveType
                                        ? (
                                            $isUrdu
                                                ? (
                                                    $leaveType->title_ur
                                                    ?? $leaveType->title_en
                                                )
                                                : (
                                                    $leaveType->title_en
                                                    ?? $leaveType->title_ur
                                                )
                                        )
                                        : '-'
                                    }}

                                </td>

                                <td class="text-center">

                                    {{ number_format(
                                        $summary['requests']
                                    ) }}

                                </td>

                                <td class="text-center fw-bold">

                                    {{ number_format(
                                        $summary['days']
                                    ) }}

                                </td>

                                <td class="text-center text-success">

                                    {{ number_format(
                                        $summary['approved_days']
                                    ) }}

                                </td>

                                <td class="text-center text-warning">

                                    {{ number_format(
                                        $summary['pending_days']
                                    ) }}

                                </td>

                                <td class="text-center text-danger">

                                    {{ number_format(
                                        $summary['rejected_days']
                                    ) }}

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="7"
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
                    ? 'چھٹی کی تفصیل'
                    : 'Leave Details'
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
                                {{ $isUrdu ? 'ملازم' : 'Employee' }}
                            </th>

                            <th>
                                {{ $isUrdu ? 'چھٹی کی قسم' : 'Leave Type' }}
                            </th>

                            <th>
                                {{ $isUrdu ? 'شروع' : 'Start Date' }}
                            </th>

                            <th>
                                {{ $isUrdu ? 'اختتام' : 'End Date' }}
                            </th>

                            <th class="text-center">
                                {{ $isUrdu ? 'دن' : 'Days' }}
                            </th>

                            <th>
                                {{ $isUrdu ? 'اسٹیٹس' : 'Status' }}
                            </th>

                            <th>
                                {{ $isUrdu ? 'وجہ' : 'Reason' }}
                            </th>

                            <th>
                                {{ $isUrdu ? 'منظور کنندہ' : 'Approved By' }}
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse(
                            $leaveRequests
                            as $index => $leave
                        )

                            @php

                                $employee =
                                    $leave->employee;

                                $nameEn = $employee
                                    ? trim(
                                        ($employee->first_name_en ?? '') .
                                        ' ' .
                                        ($employee->last_name_en ?? '')
                                    )
                                    : '-';

                                $nameUr = $employee
                                    ? trim(
                                        ($employee->first_name_ur ?? '') .
                                        ' ' .
                                        ($employee->last_name_ur ?? '')
                                    )
                                    : '-';

                                $employeeName = $isUrdu
                                    ? ($nameUr ?: $nameEn)
                                    : ($nameEn ?: $nameUr);

                            @endphp

                            <tr>

                                <td class="text-center">

                                    {{ $index + 1 }}

                                </td>


                                <td>

                                    {{ $employeeName }}

                                </td>


                                <td>

                                    {{ $leave->leaveType
                                        ? (
                                            $isUrdu
                                                ? (
                                                    $leave->leaveType->title_ur
                                                    ?? $leave->leaveType->title_en
                                                )
                                                : (
                                                    $leave->leaveType->title_en
                                                    ?? $leave->leaveType->title_ur
                                                )
                                        )
                                        : '-'
                                    }}

                                </td>


                                <td>

                                    {{ $leave->start_date
                                        ? $leave->start_date->format('d-m-Y')
                                        : '-'
                                    }}

                                </td>


                                <td>

                                    {{ $leave->end_date
                                        ? $leave->end_date->format('d-m-Y')
                                        : '-'
                                    }}

                                </td>


                                <td class="text-center fw-bold">

                                    {{ number_format(
                                        $leave->report_days
                                    ) }}

                                </td>


                                <td>

                                    @if($leave->status === 'approved')

                                        <span class="badge bg-success">

                                            {{ $isUrdu
                                                ? 'منظور شدہ'
                                                : 'Approved'
                                            }}

                                        </span>

                                    @elseif($leave->status === 'pending')

                                        <span class="badge bg-warning">

                                            {{ $isUrdu
                                                ? 'زیر التواء'
                                                : 'Pending'
                                            }}

                                        </span>

                                    @elseif($leave->status === 'rejected')

                                        <span class="badge bg-danger">

                                            {{ $isUrdu
                                                ? 'مسترد'
                                                : 'Rejected'
                                            }}

                                        </span>

                                    @else

                                        <span class="badge bg-secondary">

                                            {{ ucfirst(
                                                $leave->status
                                            ) }}

                                        </span>

                                    @endif

                                </td>


                                <td>

                                    {{ $leave->reason ?: '-' }}

                                </td>


                                <td>

                                    @if($leave->approvedBy)

                                        {{ $leave->approvedBy->name }}

                                    @else

                                        -

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="9"
                                    class="text-center text-muted py-4"
                                >

                                    {{ $isUrdu
                                        ? 'کوئی چھٹی کا ریکارڈ موجود نہیں ہے۔'
                                        : 'No leave records found.'
                                    }}

                                </td>

                            </tr>

                        @endforelse

                    </tbody>


                    @if($leaveRequests->count())

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
                                        $totalLeaveDays
                                    ) }}

                                </td>

                                <td colspan="3">

                                    {{ $isUrdu
                                        ? 'درخواستیں: '
                                        : 'Requests: '
                                    }}

                                    {{ number_format(
                                        $totalRequests
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