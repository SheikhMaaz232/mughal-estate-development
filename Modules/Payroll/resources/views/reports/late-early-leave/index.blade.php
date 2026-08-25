@extends('payroll::layouts.payroll')


@section('content')

@php

    $isUrdu = app()->getLocale() === 'ur';

@endphp


<div
    class="content"
    dir="{{ $isUrdu ? 'rtl' : 'ltr' }}"
>

    {{-- ============================================================ --}}
    {{-- PAGE HEADER --}}
    {{-- ============================================================ --}}

    <div class="block block-rounded">

        <div class="block-content">

            <div class="row align-items-center">

                <div class="col-md-8">

                    <h2 class="mb-1">

                        <i class="fa fa-clock me-1"></i>

                        {{ $isUrdu
                            ? 'تاخیر اور جلدی جانے کی رپورٹ'
                            : 'Late & Early Leave Report'
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


    {{-- ============================================================ --}}
    {{-- FILTER --}}
    {{-- ============================================================ --}}

    <div class="no-print">

        @include(
            'payroll::reports.late-early-leave.filters'
        )

    </div>


    {{-- ============================================================ --}}
    {{-- SUMMARY --}}
    {{-- ============================================================ --}}

    <div class="row">

        {{-- Total Records --}}
        <div class="col-md-3">

            <div class="block block-rounded">

                <div class="block-content text-center">

                    <div class="fs-sm text-muted">

                        {{ $isUrdu
                            ? 'کل ریکارڈز'
                            : 'Total Records'
                        }}

                    </div>

                    <div class="fs-2 fw-bold">

                        {{ $totalRecords }}

                    </div>

                </div>

            </div>

        </div>


        {{-- Late Records --}}
        <div class="col-md-3">

            <div class="block block-rounded">

                <div class="block-content text-center">

                    <div class="fs-sm text-muted">

                        {{ $isUrdu
                            ? 'تاخیر والے ریکارڈز'
                            : 'Late Records'
                        }}

                    </div>

                    <div class="fs-2 fw-bold">

                        {{ $lateRecords }}

                    </div>

                </div>

            </div>

        </div>


        {{-- Early Leave Records --}}
        <div class="col-md-3">

            <div class="block block-rounded">

                <div class="block-content text-center">

                    <div class="fs-sm text-muted">

                        {{ $isUrdu
                            ? 'جلدی جانے والے ریکارڈز'
                            : 'Early Leave Records'
                        }}

                    </div>

                    <div class="fs-2 fw-bold">

                        {{ $earlyLeaveRecords }}

                    </div>

                </div>

            </div>

        </div>


        {{-- Total Exception --}}
        <div class="col-md-3">

            <div class="block block-rounded">

                <div class="block-content text-center">

                    <div class="fs-sm text-muted">

                        {{ $isUrdu
                            ? 'کل اضافی منٹ'
                            : 'Total Exception Minutes'
                        }}

                    </div>

                    <div class="fs-2 fw-bold">

                        {{ $totalExceptionMinutes }}

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- ============================================================ --}}
    {{-- REPORT TABLE --}}
    {{-- ============================================================ --}}

    <div class="block block-rounded">

        <div class="block-header block-header-default">

            <h3 class="block-title">

                {{ $isUrdu
                    ? 'رپورٹ کی تفصیل'
                    : 'Report Details'
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
                                    ? 'تاریخ'
                                    : 'Date'
                                }}

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


                            <th>

                                {{ $isUrdu
                                    ? 'ان'
                                    : 'Check In'
                                }}

                            </th>


                            <th>

                                {{ $isUrdu
                                    ? 'آؤٹ'
                                    : 'Check Out'
                                }}

                            </th>


                            <th class="text-center">

                                {{ $isUrdu
                                    ? 'تاخیر (منٹ)'
                                    : 'Late (Min)'
                                }}

                            </th>


                            <th class="text-center">

                                {{ $isUrdu
                                    ? 'جلدی جانا (منٹ)'
                                    : 'Early Leave (Min)'
                                }}

                            </th>


                            <th class="text-center">

                                {{ $isUrdu
                                    ? 'کل (منٹ)'
                                    : 'Total (Min)'
                                }}

                            </th>


                            <th>

                                {{ $isUrdu
                                    ? 'اسٹیٹس'
                                    : 'Status'
                                }}

                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($attendances as $index => $attendance)

                            @php

                                $employee = $attendance->employee;

                                $nameEn = $employee
                                    ? trim(
                                        ($employee->first_name_en ?? '')
                                        . ' '
                                        . ($employee->last_name_en ?? '')
                                    )
                                    : '-';

                                $nameUr = $employee
                                    ? trim(
                                        ($employee->first_name_ur ?? '')
                                        . ' '
                                        . ($employee->last_name_ur ?? '')
                                    )
                                    : '-';


                                $employeeName = $isUrdu
                                    ? ($nameUr ?: $nameEn)
                                    : ($nameEn ?: $nameUr);


                                $department = $employee?->department;

                                $designation = $employee?->designation;

                                $shift = $employee?->shift;


                                $rowTotal =
                                    (int) $attendance->late_minutes
                                    +
                                    (int) $attendance->early_leave_minutes;

                            @endphp


                            <tr>

                                <td class="text-center">

                                    {{ $index + 1 }}

                                </td>


                                <td>

                                    {{ \Carbon\Carbon::parse(
                                        $attendance->date
                                    )->format('d-m-Y') }}

                                </td>


                                <td>

                                    {{ $employeeName }}

                                </td>


                                <td>

                                    @if($department)

                                        {{ $isUrdu
                                            ? ($department->title_ur
                                                ?: $department->title_en)
                                            : ($department->title_en
                                                ?: $department->title_ur)
                                        }}

                                    @else

                                        -

                                    @endif

                                </td>


                                <td>

                                    @if($designation)

                                        {{ $isUrdu
                                            ? ($designation->title_ur
                                                ?: $designation->title_en)
                                            : ($designation->title_en
                                                ?: $designation->title_ur)
                                        }}

                                    @else

                                        -

                                    @endif

                                </td>


                                <td>

                                    @if($shift)

                                        {{ $isUrdu
                                            ? ($shift->shift_name_ur
                                                ?: $shift->shift_name_en)
                                            : ($shift->shift_name_en
                                                ?: $shift->shift_name_ur)
                                        }}

                                    @else

                                        -

                                    @endif

                                </td>


                                <td>

                                    {{ $attendance->check_in
                                        ? \Carbon\Carbon::parse(
                                            $attendance->check_in
                                        )->format('h:i A')
                                        : '-'
                                    }}

                                </td>


                                <td>

                                    {{ $attendance->check_out
                                        ? \Carbon\Carbon::parse(
                                            $attendance->check_out
                                        )->format('h:i A')
                                        : '-'
                                    }}

                                </td>


                                <td class="text-center">

                                    {{ (int) $attendance->late_minutes }}

                                </td>


                                <td class="text-center">

                                    {{ (int) $attendance->early_leave_minutes }}

                                </td>


                                <td class="text-center fw-bold">

                                    {{ $rowTotal }}

                                </td>


                                <td>

                                    @if($attendance->status)

                                        <span class="badge bg-info">

                                            {{ ucfirst(
                                                str_replace(
                                                    '_',
                                                    ' ',
                                                    $attendance->status
                                                )
                                            ) }}

                                        </span>

                                    @else

                                        -

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="12"
                                    class="text-center text-muted py-4"
                                >

                                    {{ $isUrdu
                                        ? 'کوئی ریکارڈ موجود نہیں ہے۔'
                                        : 'No records found.'
                                    }}

                                </td>

                            </tr>

                        @endforelse

                    </tbody>


                    @if($attendances->count() > 0)

                        <tfoot>

                            <tr class="fw-bold">

                                <td colspan="8">

                                    {{ $isUrdu
                                        ? 'کل'
                                        : 'TOTAL'
                                    }}

                                </td>


                                <td class="text-center">

                                    {{ $totalLateMinutes }}

                                </td>


                                <td class="text-center">

                                    {{ $totalEarlyLeaveMinutes }}

                                </td>


                                <td class="text-center">

                                    {{ $totalExceptionMinutes }}

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
        margin-bottom: 10px !important;
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