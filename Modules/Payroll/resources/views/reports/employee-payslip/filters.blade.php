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
                ? 'پے سلپ فلٹر'
                : 'Payslip Filter'
            }}

        </h3>

    </div>


    <div class="block-content">

        <form
            method="GET"
            action="{{ route('payroll.reports.employee-payslip') }}"
        >

            <div class="row">

                {{-- Year --}}
                <div class="col-md-3">

                    <label class="form-label">

                        {{ $isUrdu ? 'سال' : 'Year' }}

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


                {{-- Month --}}
                <div class="col-md-3">

                    <label class="form-label">

                        {{ $isUrdu ? 'مہینہ' : 'Month' }}

                    </label>

                    <select
                        name="month"
                        class="form-select"
                    >

                        @foreach($months as $monthNumber => $monthName)

                            <option
                                value="{{ str_pad(
                                    $monthNumber,
                                    2,
                                    '0',
                                    STR_PAD_LEFT
                                ) }}"
                                @selected(
                                    (int) $month === $monthNumber
                                )
                            >

                                {{ $monthName }}

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- Employee --}}
                <div class="col-md-4">

                    <label class="form-label">

                        {{ $isUrdu
                            ? 'ملازم'
                            : 'Employee'
                        }}

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


                        @foreach($employees as $employee)

                            @php

                                $nameEn = trim(
                                    ($employee->first_name_en ?? '') .
                                    ' ' .
                                    ($employee->last_name_en ?? '')
                                );

                                $nameUr = trim(
                                    ($employee->first_name_ur ?? '') .
                                    ' ' .
                                    ($employee->last_name_ur ?? '')
                                );

                            @endphp


                            <option
                                value="{{ $employee->id }}"
                                @selected(
                                    (string) $employeeId
                                    === (string) $employee->id
                                )
                            >

                                {{ $isUrdu
                                    ? ($nameUr ?: $nameEn)
                                    : ($nameEn ?: $nameUr)
                                }}

                                @if($employee->device_user_id)
                                    - {{ $employee->device_user_id }}
                                @endif

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- Generate --}}
                <div class="col-md-2 d-flex align-items-end">

                    <div class="mb-4 w-100">

                        <button
                            type="submit"
                            class="btn btn-primary w-100"
                        >

                            <i class="fa fa-search me-1"></i>

                            {{ $isUrdu
                                ? 'دیکھیں'
                                : 'View'
                            }}

                        </button>

                    </div>

                </div>

            </div>


            <div class="mb-3">

                <a
                    href="{{ route(
                        'payroll.reports.employee-payslip'
                    ) }}"
                    class="btn btn-alt-secondary"
                >

                    <i class="fa fa-refresh me-1"></i>

                    {{ $isUrdu
                        ? 'ری سیٹ'
                        : 'Reset'
                    }}

                </a>

            </div>

        </form>

    </div>

</div>