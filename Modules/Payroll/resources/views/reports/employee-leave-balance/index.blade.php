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

                        <i class="fa fa-calendar-check me-1"></i>

                        {{ $isUrdu
                            ? 'ملازم کی چھٹی کا بیلنس'
                            : 'Employee Leave Balance Report'
                        }}

                    </h2>

                    <p class="text-muted mb-0">

                        {{ $isUrdu
                            ? 'سال'
                            : 'Year'
                        }}

                        :

                        {{ $year }}

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
    {{-- FILTER --}}
    {{-- ========================================================= --}}

    <div class="no-print">

        @include(
            'payroll::reports.employee-leave-balance.filters'
        )

    </div>


    {{-- ========================================================= --}}
    {{-- SUMMARY --}}
    {{-- ========================================================= --}}

    <div class="row">


        {{-- Employees --}}
        <div class="col-md-3">

            <div class="block block-rounded">

                <div class="block-content text-center">

                    <div class="text-muted">

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


        {{-- Allocated --}}
        <div class="col-md-3">

            <div class="block block-rounded">

                <div class="block-content text-center">

                    <div class="text-muted">

                        {{ $isUrdu
                            ? 'مختص چھٹی'
                            : 'Allocated Leave'
                        }}

                    </div>

                    <div class="fs-2 fw-bold">

                        {{ number_format(
                            $totalAllocatedDays
                        ) }}

                    </div>

                    <small>

                        {{ $isUrdu
                            ? 'دن'
                            : 'Days'
                        }}

                    </small>

                </div>

            </div>

        </div>


        {{-- Used --}}
        <div class="col-md-3">

            <div class="block block-rounded">

                <div class="block-content text-center">

                    <div class="text-muted">

                        {{ $isUrdu
                            ? 'استعمال شدہ'
                            : 'Used Leave'
                        }}

                    </div>

                    <div class="fs-2 fw-bold">

                        {{ number_format(
                            $totalUsedDays
                        ) }}

                    </div>

                    <small>

                        {{ $isUrdu
                            ? 'دن'
                            : 'Days'
                        }}

                    </small>

                </div>

            </div>

        </div>


        {{-- Remaining --}}
        <div class="col-md-3">

            <div class="block block-rounded">

                <div class="block-content text-center">

                    <div class="text-muted">

                        {{ $isUrdu
                            ? 'باقی چھٹی'
                            : 'Remaining Leave'
                        }}

                    </div>

                    <div class="fs-2 fw-bold">

                        {{ number_format(
                            $totalRemainingDays
                        ) }}

                    </div>

                    <small>

                        {{ $isUrdu
                            ? 'دن'
                            : 'Days'
                        }}

                    </small>

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
                    ? 'ملازم وار چھٹی کا خلاصہ'
                    : 'Employee-wise Leave Balance'
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
                                    ? 'چھٹی کی اقسام'
                                    : 'Leave Types'
                                }}
                            </th>

                            <th class="text-center">
                                {{ $isUrdu
                                    ? 'مختص'
                                    : 'Allocated'
                                }}
                            </th>

                            <th class="text-center">
                                {{ $isUrdu
                                    ? 'استعمال'
                                    : 'Used'
                                }}
                            </th>

                            <th class="text-center">
                                {{ $isUrdu
                                    ? 'باقی'
                                    : 'Remaining'
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

                                    @if($employee)

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
                                            ? $employee->department->title_ur
                                            : $employee->department->title_en
                                        }}

                                    @else

                                        -

                                    @endif

                                </td>


                                <td>

                                    @if(
                                        $employee &&
                                        $employee->designation
                                    )

                                        {{ $isUrdu
                                            ? $employee->designation->title_ur
                                            : $employee->designation->title_en
                                        }}

                                    @else

                                        -

                                    @endif

                                </td>


                                <td class="text-center">

                                    {{ number_format(
                                        $summary['leave_types']
                                    ) }}

                                </td>


                                <td class="text-center">

                                    {{ number_format(
                                        $summary['allocated_days']
                                    ) }}

                                </td>


                                <td class="text-center">

                                    {{ number_format(
                                        $summary['used_days']
                                    ) }}

                                </td>


                                <td class="text-center fw-bold">

                                    {{ number_format(
                                        $summary['remaining_days']
                                    ) }}

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="8"
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
    {{-- LEAVE TYPE SUMMARY --}}
    {{-- ========================================================= --}}

    <div class="block block-rounded">

        <div class="block-header block-header-default">

            <h3 class="block-title">

                {{ $isUrdu
                    ? 'چھٹی کی قسم کا خلاصہ'
                    : 'Leave Type Summary'
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
                                {{ $isUrdu
                                    ? 'چھٹی کی قسم'
                                    : 'Leave Type'
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
                                    ? 'مختص'
                                    : 'Allocated'
                                }}
                            </th>

                            <th class="text-center">
                                {{ $isUrdu
                                    ? 'استعمال'
                                    : 'Used'
                                }}
                            </th>

                            <th class="text-center">
                                {{ $isUrdu
                                    ? 'باقی'
                                    : 'Remaining'
                                }}
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse(
                            $leaveTypeSummary
                            as $summary
                        )

                            @php
                                $leaveType =
                                    $summary['leave_type'];
                            @endphp

                            <tr>

                                <td>

                                    @if($leaveType)

                                        {{ $isUrdu
                                            ? $leaveType->title_ur
                                            : $leaveType->title_en
                                        }}

                                    @else

                                        -

                                    @endif

                                </td>


                                <td class="text-center">

                                    {{ number_format(
                                        $summary['employees']
                                    ) }}

                                </td>


                                <td class="text-center">

                                    {{ number_format(
                                        $summary['allocated_days']
                                    ) }}

                                </td>


                                <td class="text-center">

                                    {{ number_format(
                                        $summary['used_days']
                                    ) }}

                                </td>


                                <td class="text-center fw-bold">

                                    {{ number_format(
                                        $summary['remaining_days']
                                    ) }}

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="5"
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
                    ? 'چھٹی کے بیلنس کی تفصیل'
                    : 'Leave Balance Details'
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
                                    ? 'چھٹی کی قسم'
                                    : 'Leave Type'
                                }}
                            </th>

                            <th class="text-center">
                                {{ $isUrdu
                                    ? 'مختص'
                                    : 'Allocated'
                                }}
                            </th>

                            <th class="text-center">
                                {{ $isUrdu
                                    ? 'استعمال'
                                    : 'Used'
                                }}
                            </th>

                            <th class="text-center">
                                {{ $isUrdu
                                    ? 'باقی'
                                    : 'Remaining'
                                }}
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse(
                            $leaveBalances
                            as $index => $balance
                        )

                            <tr>

                                <td>

                                    {{ $index + 1 }}

                                </td>


                                <td>

                                    @if($balance->employee)

                                        {{ $balance->employee->first_name_en }}
                                        {{ $balance->employee->last_name_en }}

                                    @else

                                        -

                                    @endif

                                </td>


                                <td>

                                    @if(
                                        $balance->employee &&
                                        $balance->employee->department
                                    )

                                        {{ $isUrdu
                                            ? $balance->employee->department->title_ur
                                            : $balance->employee->department->title_en
                                        }}

                                    @else

                                        -

                                    @endif

                                </td>


                                <td>

                                    @if(
                                        $balance->employee &&
                                        $balance->employee->designation
                                    )

                                        {{ $isUrdu
                                            ? $balance->employee->designation->title_ur
                                            : $balance->employee->designation->title_en
                                        }}

                                    @else

                                        -

                                    @endif

                                </td>


                                <td>

                                    @if($balance->leaveType)

                                        {{ $isUrdu
                                            ? $balance->leaveType->title_ur
                                            : $balance->leaveType->title_en
                                        }}

                                    @else

                                        -

                                    @endif

                                </td>


                                <td class="text-center">

                                    {{ number_format(
                                        $balance->allocated_days
                                    ) }}

                                </td>


                                <td class="text-center">

                                    {{ number_format(
                                        $balance->used_days
                                    ) }}

                                </td>


                                <td class="text-center fw-bold">

                                    {{ number_format(
                                        $balance->remaining_days
                                    ) }}

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="8"
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
