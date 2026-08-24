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

    $currentYear = now()->year;
@endphp


<div class="block block-rounded">

    <div class="block-header block-header-default">

        <h3 class="block-title">

            <i class="fa fa-filter me-1"></i>

            {{ $isUrdu
                ? 'رپورٹ فلٹرز'
                : 'Report Filters'
            }}

        </h3>

    </div>


    <div class="block-content">

        <form
            method="GET"
            action="{{ route('payroll.reports.employee-attendance-card') }}"
        >

            <div class="row">


                {{-- Employee --}}
                <div class="col-md-6">

                    <div class="mb-4">

                        <label class="form-label">

                            {{ $isUrdu
                                ? 'ملازم'
                                : 'Employee'
                            }}

                            <span class="text-danger">*</span>

                        </label>


                        <select
                            name="employee_id"
                            class="form-select"
                            required
                        >

                            <option value="">

                                {{ $isUrdu
                                    ? 'ملازم منتخب کریں'
                                    : 'Select Employee'
                                }}

                            </option>


                            @foreach($employees as $employeeOption)

                                @php

                                    $employeeName = $isUrdu
                                        ? trim(
                                            ($employeeOption->first_name_ur ?? '') .
                                            ' ' .
                                            ($employeeOption->last_name_ur ?? '')
                                        )
                                        : trim(
                                            ($employeeOption->first_name_en ?? '') .
                                            ' ' .
                                            ($employeeOption->last_name_en ?? '')
                                        );

                                @endphp


                                <option
                                    value="{{ $employeeOption->id }}"
                                    @selected(
                                        request('employee_id')
                                        == $employeeOption->id
                                    )
                                >

                                    {{ $employeeName }}

                                    @if($employeeOption->device_user_id)

                                        —
                                        {{ $employeeOption->device_user_id }}

                                    @endif

                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>


                {{-- Year --}}
                <div class="col-md-3">

                    <div class="mb-4">

                        <label class="form-label">

                            {{ $isUrdu
                                ? 'سال'
                                : 'Year'
                            }}

                        </label>


                        <select
                            name="year"
                            class="form-select"
                        >

                            @for(
                                $yearOption = $currentYear - 5;
                                $yearOption <= $currentYear + 1;
                                $yearOption++
                            )

                                <option
                                    value="{{ $yearOption }}"
                                    @selected(
                                        $year == $yearOption
                                    )
                                >

                                    {{ $yearOption }}

                                </option>

                            @endfor

                        </select>

                    </div>

                </div>


                {{-- Month --}}
                <div class="col-md-3">

                    <div class="mb-4">

                        <label class="form-label">

                            {{ $isUrdu
                                ? 'مہینہ'
                                : 'Month'
                            }}

                        </label>


                        <select
                            name="month"
                            class="form-select"
                        >

                            @foreach($months as $monthNumber => $monthName)

                                <option
                                    value="{{ $monthNumber }}"
                                    @selected(
                                        $month == $monthNumber
                                    )
                                >

                                    {{ $monthName }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>

            </div>


            <div class="mb-4">

                <button
                    type="submit"
                    class="btn btn-primary"
                >

                    <i class="fa fa-search me-1"></i>

                    {{ $isUrdu
                        ? 'رپورٹ دیکھیں'
                        : 'Generate Report'
                    }}

                </button>


                <a
                    href="{{ route('payroll.reports.employee-attendance-card') }}"
                    class="btn btn-alt-secondary"
                >

                    <i class="fa fa-refresh me-1"></i>

                    {{ $isUrdu
                        ? 'ری سیٹ'
                        : 'Reset'
                    }}

                </a>


                @if($employee)

                    <button
                        type="button"
                        onclick="window.print()"
                        class="btn btn-alt-secondary"
                    >

                        <i class="fa fa-print me-1"></i>

                        {{ $isUrdu
                            ? 'پرنٹ'
                            : 'Print'
                        }}

                    </button>

                @endif

            </div>

        </form>

    </div>

</div>