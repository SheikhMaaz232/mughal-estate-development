@extends('payroll::layouts.payroll')


@section('content')

@php

    $isUrdu = app()->getLocale() === 'ur';

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

    $monthNumber = (int) $month;

@endphp


<div
    class="content"
    dir="{{ $isUrdu ? 'rtl' : 'ltr' }}"
>


    {{-- ================================================================ --}}
    {{-- FILTER --}}
    {{-- ================================================================ --}}

    @include(
        'payroll::reports.employee-payslip.filters'
    )


    {{-- ================================================================ --}}
    {{-- NO PAYROLL --}}
    {{-- ================================================================ --}}

    @if($employeeId && !$payroll)

        <div class="block block-rounded">

            <div class="block-content text-center py-5">

                <i
                    class="fa fa-file-invoice-dollar fa-3x text-muted mb-3"
                ></i>


                <h3>

                    {{ $isUrdu
                        ? 'پے سلپ نہیں ملی'
                        : 'Payslip Not Found'
                    }}

                </h3>


                <p class="text-muted">

                    {{ $isUrdu
                        ? 'منتخب ملازم کے لیے منتخب مہینے کا پے رول ریکارڈ موجود نہیں ہے۔'
                        : 'No payroll record exists for this employee for the selected month.'
                    }}

                </p>

            </div>

        </div>

    @endif


    {{-- ================================================================ --}}
    {{-- PAYSLIP --}}
    {{-- ================================================================ --}}

    @if($payroll)

        @php

            $employee = $payroll->employee;

            $employeeNameEn = trim(
                ($employee->first_name_en ?? '') .
                ' ' .
                ($employee->last_name_en ?? '')
            );

            $employeeNameUr = trim(
                ($employee->first_name_ur ?? '') .
                ' ' .
                ($employee->last_name_ur ?? '')
            );


            $employeeName = $isUrdu
                ? ($employeeNameUr ?: $employeeNameEn)
                : ($employeeNameEn ?: $employeeNameUr);


            $department = $employee->department;

            $designation = $employee->designation;

            $shift = $employee->shift;


            /*
            |--------------------------------------------------------------------------
            | Total Deductions
            |--------------------------------------------------------------------------
            */

            $totalDeductions =
                (float) $payroll->absence_deduction_amount
                +
                (float) $payroll->late_early_deduction_amount
                +
                (float) $payroll->deduction_adjustment;


            /*
            |--------------------------------------------------------------------------
            | Total Earnings
            |--------------------------------------------------------------------------
            */

            $totalEarnings =
                (float) $payroll->basic_salary
                +
                (float) $payroll->overtime_amount
                +
                (float) $payroll->allowance_adjustment;


            /*
            |--------------------------------------------------------------------------
            | Overtime
            |--------------------------------------------------------------------------
            */

            $overtimeMinutes =
                (int) $payroll->total_overtime_minutes;

            $overtimeHours =
                intdiv(
                    $overtimeMinutes,
                    60
                );

            $overtimeRemaining =
                $overtimeMinutes % 60;

        @endphp


        <div class="block block-rounded payslip-container">


            {{-- ======================================================== --}}
            {{-- HEADER --}}
            {{-- ======================================================== --}}

            <div class="block-content">


                <div class="row align-items-center mb-4">

                    <div class="col-4">

                        @if(
                            !empty($employee->profile_picture)
                        )

                            <img
                                src="{{ asset(
                                    'storage/' .
                                    $employee->profile_picture
                                ) }}"
                                alt="Employee"
                                style="
                                    width:80px;
                                    height:80px;
                                    object-fit:cover;
                                    border-radius:50%;
                                "
                            >

                        @endif

                    </div>


                    <div class="col-4 text-center">

                        <h2 class="fw-bold mb-1">

                            {{ $isUrdu
                                ? 'تنخواہ کی پرچی'
                                : 'SALARY SLIP'
                            }}

                        </h2>


                        <div class="text-muted">

                            {{ $months[$monthNumber] }}

                            {{ $year }}

                        </div>

                    </div>


                    <div class="col-4 text-end">

                        @if($payroll->is_finalized)

                            <span class="badge bg-success">

                                {{ $isUrdu
                                    ? 'حتمی شدہ'
                                    : 'FINALIZED'
                                }}

                            </span>

                        @else

                            <span class="badge bg-warning">

                                {{ $isUrdu
                                    ? 'مسودہ'
                                    : 'DRAFT'
                                }}

                            </span>

                        @endif

                    </div>

                </div>


                <hr>


                {{-- ==================================================== --}}
                {{-- EMPLOYEE INFORMATION --}}
                {{-- ==================================================== --}}

                <div class="row mb-4">


                    <div class="col-md-6">

                        <table class="table table-sm">

                            <tr>

                                <th width="40%">

                                    {{ $isUrdu
                                        ? 'ملازم'
                                        : 'Employee'
                                    }}

                                </th>

                                <td>

                                    {{ $employeeName }}

                                </td>

                            </tr>


                            <tr>

                                <th>

                                    {{ $isUrdu
                                        ? 'ملازم نمبر'
                                        : 'Employee ID'
                                    }}

                                </th>

                                <td>

                                    {{ $employee->id }}

                                </td>

                            </tr>


                            <tr>

                                <th>

                                    {{ $isUrdu
                                        ? 'ڈیوائس ID'
                                        : 'Device ID'
                                    }}

                                </th>

                                <td>

                                    {{ $employee->device_user_id ?: '-' }}

                                </td>

                            </tr>


                            <tr>

                                <th>

                                    {{ $isUrdu
                                        ? 'شناختی کارڈ'
                                        : 'CNIC'
                                    }}

                                </th>

                                <td>

                                    {{ $employee->cnic ?: '-' }}

                                </td>

                            </tr>

                        </table>

                    </div>


                    <div class="col-md-6">

                        <table class="table table-sm">

                            <tr>

                                <th width="40%">

                                    {{ $isUrdu
                                        ? 'شعبہ'
                                        : 'Department'
                                    }}

                                </th>

                                <td>

                                    @if($department)

                                        {{ $isUrdu
                                            ? $department->title_ur
                                            : $department->title_en
                                        }}

                                    @else

                                        -

                                    @endif

                                </td>

                            </tr>


                            <tr>

                                <th>

                                    {{ $isUrdu
                                        ? 'عہدہ'
                                        : 'Designation'
                                    }}

                                </th>

                                <td>

                                    @if($designation)

                                        {{ $isUrdu
                                            ? $designation->title_ur
                                            : $designation->title_en
                                        }}

                                    @else

                                        -

                                    @endif

                                </td>

                            </tr>


                            <tr>

                                <th>

                                    {{ $isUrdu
                                        ? 'شفٹ'
                                        : 'Shift'
                                    }}

                                </th>

                                <td>

                                    @if($shift)

                                        {{ $isUrdu
                                            ? $shift->shift_name_ur
                                            : $shift->shift_name_en
                                        }}

                                    @else

                                        -

                                    @endif

                                </td>

                            </tr>


                            <tr>

                                <th>

                                    {{ $isUrdu
                                        ? 'شمولیت'
                                        : 'Joining Date'
                                    }}

                                </th>

                                <td>

                                    {{ $employee->joining_date
                                        ? \Carbon\Carbon::parse(
                                            $employee->joining_date
                                        )->format('d-m-Y')
                                        : '-'
                                    }}

                                </td>

                            </tr>

                        </table>

                    </div>

                </div>


                {{-- ==================================================== --}}
                {{-- ATTENDANCE SUMMARY --}}
                {{-- ==================================================== --}}

                <h5 class="fw-bold border-bottom pb-2">

                    {{ $isUrdu
                        ? 'حاضری کا خلاصہ'
                        : 'Attendance Summary'
                    }}

                </h5>


                <div class="row mb-4">


                    <div class="col-md-3">

                        <div class="border rounded p-3 text-center">

                            <small class="text-muted">

                                {{ $isUrdu
                                    ? 'مہینے کے دن'
                                    : 'Days in Month'
                                }}

                            </small>

                            <h4 class="mb-0">

                                {{ $payroll->days_in_month }}

                            </h4>

                        </div>

                    </div>


                    <div class="col-md-3">

                        <div class="border rounded p-3 text-center">

                            <small class="text-muted">

                                {{ $isUrdu
                                    ? 'کام کے دن'
                                    : 'Worked Days'
                                }}

                            </small>

                            <h4 class="mb-0">

                                {{ $payroll->total_worked_days }}

                            </h4>

                        </div>

                    </div>


                    <div class="col-md-3">

                        <div class="border rounded p-3 text-center">

                            <small class="text-muted">

                                {{ $isUrdu
                                    ? 'غیر حاضری'
                                    : 'Absent Days'
                                }}

                            </small>

                            <h4 class="mb-0">

                                {{ $payroll->total_absent_days }}

                            </h4>

                        </div>

                    </div>


                    <div class="col-md-3">

                        <div class="border rounded p-3 text-center">

                            <small class="text-muted">

                                {{ $isUrdu
                                    ? 'چھٹی کے دن'
                                    : 'Leave Days'
                                }}

                            </small>

                            <h4 class="mb-0">

                                {{ $payroll->total_leave_days }}

                            </h4>

                        </div>

                    </div>

                </div>


                <div class="row mb-4">


                    <div class="col-md-3">

                        <div class="border rounded p-3 text-center">

                            <small class="text-muted">

                                {{ $isUrdu
                                    ? 'چھٹی کے دن'
                                    : 'Holiday Days'
                                }}

                            </small>

                            <h5 class="mb-0">

                                {{ $payroll->total_holiday_days }}

                            </h5>

                        </div>

                    </div>


                    <div class="col-md-3">

                        <div class="border rounded p-3 text-center">

                            <small class="text-muted">

                                {{ $isUrdu
                                    ? 'تاخیر منٹ'
                                    : 'Late Minutes'
                                }}

                            </small>

                            <h5 class="mb-0">

                                {{ $payroll->total_late_minutes }}

                            </h5>

                        </div>

                    </div>


                    <div class="col-md-3">

                        <div class="border rounded p-3 text-center">

                            <small class="text-muted">

                                {{ $isUrdu
                                    ? 'جلدی جانے کے منٹ'
                                    : 'Early Leave Minutes'
                                }}

                            </small>

                            <h5 class="mb-0">

                                {{ $payroll->total_early_leave_minutes }}

                            </h5>

                        </div>

                    </div>


                    <div class="col-md-3">

                        <div class="border rounded p-3 text-center">

                            <small class="text-muted">

                                {{ $isUrdu
                                    ? 'اوور ٹائم'
                                    : 'Overtime'
                                }}

                            </small>

                            <h5 class="mb-0">

                                {{ $overtimeHours }}h
                                {{ str_pad(
                                    $overtimeRemaining,
                                    2,
                                    '0',
                                    STR_PAD_LEFT
                                ) }}m

                            </h5>

                        </div>

                    </div>

                </div>


                {{-- ==================================================== --}}
                {{-- EARNINGS --}}
                {{-- ==================================================== --}}

                <div class="row">


                    <div class="col-md-6">


                        <h5 class="fw-bold border-bottom pb-2">

                            {{ $isUrdu
                                ? 'آمدنی'
                                : 'Earnings'
                            }}

                        </h5>


                        <table class="table table-bordered">


                            <tr>

                                <td>

                                    {{ $isUrdu
                                        ? 'بنیادی تنخواہ'
                                        : 'Basic Salary'
                                    }}

                                </td>

                                <td class="text-end">

                                    {{ number_format(
                                        $payroll->basic_salary,
                                        2
                                    ) }}

                                </td>

                            </tr>


                            <tr>

                                <td>

                                    {{ $isUrdu
                                        ? 'اوور ٹائم'
                                        : 'Overtime Amount'
                                    }}

                                </td>

                                <td class="text-end">

                                    {{ number_format(
                                        $payroll->overtime_amount,
                                        2
                                    ) }}

                                </td>

                            </tr>


                            <tr>

                                <td>

                                    {{ $isUrdu
                                        ? 'الاؤنس ایڈجسٹمنٹ'
                                        : 'Allowance Adjustment'
                                    }}

                                </td>

                                <td class="text-end">

                                    {{ number_format(
                                        $payroll->allowance_adjustment,
                                        2
                                    ) }}

                                </td>

                            </tr>


                            <tr class="fw-bold">

                                <td>

                                    {{ $isUrdu
                                        ? 'کل آمدنی'
                                        : 'Total Earnings'
                                    }}

                                </td>

                                <td class="text-end">

                                    {{ number_format(
                                        $totalEarnings,
                                        2
                                    ) }}

                                </td>

                            </tr>

                        </table>

                    </div>


                    {{-- ================================================= --}}
                    {{-- DEDUCTIONS --}}
                    {{-- ================================================= --}}

                    <div class="col-md-6">


                        <h5 class="fw-bold border-bottom pb-2">

                            {{ $isUrdu
                                ? 'کٹوتیاں'
                                : 'Deductions'
                            }}

                        </h5>


                        <table class="table table-bordered">


                            <tr>

                                <td>

                                    {{ $isUrdu
                                        ? 'غیر حاضری کٹوتی'
                                        : 'Absence Deduction'
                                    }}

                                </td>

                                <td class="text-end">

                                    {{ number_format(
                                        $payroll->absence_deduction_amount,
                                        2
                                    ) }}

                                </td>

                            </tr>


                            <tr>

                                <td>

                                    {{ $isUrdu
                                        ? 'تاخیر / جلدی جانے کی کٹوتی'
                                        : 'Late / Early Leave Deduction'
                                    }}

                                </td>

                                <td class="text-end">

                                    {{ number_format(
                                        $payroll->late_early_deduction_amount,
                                        2
                                    ) }}

                                </td>

                            </tr>


                            <tr>

                                <td>

                                    {{ $isUrdu
                                        ? 'دیگر کٹوتی'
                                        : 'Deduction Adjustment'
                                    }}

                                </td>

                                <td class="text-end">

                                    {{ number_format(
                                        $payroll->deduction_adjustment,
                                        2
                                    ) }}

                                </td>

                            </tr>


                            <tr class="fw-bold">

                                <td>

                                    {{ $isUrdu
                                        ? 'کل کٹوتیاں'
                                        : 'Total Deductions'
                                    }}

                                </td>

                                <td class="text-end">

                                    {{ number_format(
                                        $totalDeductions,
                                        2
                                    ) }}

                                </td>

                            </tr>

                        </table>

                    </div>

                </div>


                {{-- ==================================================== --}}
                {{-- SALARY CALCULATION --}}
                {{-- ==================================================== --}}

                <div class="row mt-3">


                    <div class="col-md-6">

                        <table class="table table-bordered">


                            <tr>

                                <th>

                                    {{ $isUrdu
                                        ? 'روزانہ شرح'
                                        : 'Daily Rate'
                                    }}

                                </th>

                                <td class="text-end">

                                    {{ number_format(
                                        $payroll->daily_rate,
                                        2
                                    ) }}

                                </td>

                            </tr>


                            <tr>

                                <th>

                                    {{ $isUrdu
                                        ? 'فی منٹ شرح'
                                        : 'Minute Rate'
                                    }}

                                </th>

                                <td class="text-end">

                                    {{ number_format(
                                        $payroll->minute_rate,
                                        4
                                    ) }}

                                </td>

                            </tr>

                        </table>

                    </div>


                    <div class="col-md-6">

                        <table class="table table-bordered">


                            <tr>

                                <th>

                                    {{ $isUrdu
                                        ? 'مجموعی تنخواہ'
                                        : 'Gross Salary'
                                    }}

                                </th>

                                <td class="text-end fw-bold">

                                    {{ number_format(
                                        $payroll->gross_salary,
                                        2
                                    ) }}

                                </td>

                            </tr>


                            <tr class="table-active">

                                <th>

                                    {{ $isUrdu
                                        ? 'خالص تنخواہ'
                                        : 'NET SALARY'
                                    }}

                                </th>

                                <td class="text-end fw-bold fs-5">

                                    {{ number_format(
                                        $payroll->net_salary,
                                        2
                                    ) }}

                                </td>

                            </tr>

                        </table>

                    </div>

                </div>


                {{-- ==================================================== --}}
                {{-- FOOTER --}}
                {{-- ==================================================== --}}

                <div class="row mt-5">


                    <div class="col-md-4 text-center">

                        <div class="border-top pt-2">

                            {{ $isUrdu
                                ? 'ملازم کے دستخط'
                                : 'Employee Signature'
                            }}

                        </div>

                    </div>


                    <div class="col-md-4 text-center">

                        <div class="border-top pt-2">

                            {{ $isUrdu
                                ? 'اکاؤنٹس'
                                : 'Accounts'
                            }}

                        </div>

                    </div>


                    <div class="col-md-4 text-center">

                        <div class="border-top pt-2">

                            {{ $isUrdu
                                ? 'مجاز افسر'
                                : 'Authorized Officer'
                            }}

                        </div>

                    </div>

                </div>


                @if($payroll->finalized_at)

                    <div class="text-center text-muted mt-4">

                        {{ $isUrdu
                            ? 'حتمی تاریخ'
                            : 'Finalized At'
                        }}:

                        {{ $payroll->finalized_at->format(
                            'd-m-Y h:i A'
                        ) }}

                    </div>

                @endif


                {{-- ==================================================== --}}
                {{-- PRINT BUTTON --}}
                {{-- ==================================================== --}}

                <div class="text-center mt-4 no-print">

                    <button
                        type="button"
                        onclick="window.print()"
                        class="btn btn-primary"
                    >

                        <i class="fa fa-print me-1"></i>

                        {{ $isUrdu
                            ? 'پے سلپ پرنٹ کریں'
                            : 'Print Payslip'
                        }}

                    </button>

                </div>

            </div>

        </div>

    @endif

</div>


{{-- ================================================================ --}}
{{-- PRINT CSS --}}
{{-- ================================================================ --}}

@push('css')

<style>

.payslip-container {
    background: #fff;
}

@media print {

    body {
        background: #fff !important;
    }

    .no-print {
        display: none !important;
    }

    .sidebar,
    .nav-main,
    .header-navbar,
    .header,
    form {
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
        margin: 0 !important;
    }

    .table {
        font-size: 11px !important;
    }

    .table th,
    .table td {
        padding: 5px !important;
    }

    @page {
        size: A4 portrait;
        margin: 10mm;
    }

}

</style>

@endpush

@endsection