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

            {{ $isUrdu ? 'رپورٹ فلٹرز' : 'Report Filters' }}

        </h3>

    </div>


    <div class="block-content">

        <form
            method="GET"
            action="{{ route('payroll.reports.monthly-attendance') }}"
        >

            <div class="row">

                {{-- Year --}}
                <div class="col-md-3">

                    <div class="mb-4">

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
                                    @selected($year == $yearOption)
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

                            {{ $isUrdu ? 'مہینہ' : 'Month' }}

                        </label>

                        <select
                            name="month"
                            class="form-select"
                        >

                            @foreach($months as $monthNumber => $monthName)

                                <option
                                    value="{{ $monthNumber }}"
                                    @selected($month == $monthNumber)
                                >
                                    {{ $monthName }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>


                {{-- Employee --}}
                <div class="col-md-3">

                    <div class="mb-4">

                        <label class="form-label">

                            {{ $isUrdu ? 'ملازم' : 'Employee' }}

                        </label>

                        <select
                            name="employee_id"
                            class="form-select js-select2"
                            data-placeholder="{{ $isUrdu ? 'تمام ملازمین' : 'All Employees' }}"
                        >

                            <option value=""></option>

                            @foreach($employees as $employee)

                                @php

                                    $name = $isUrdu
                                        ? trim(
                                            ($employee->first_name_ur ?? '') .
                                            ' ' .
                                            ($employee->last_name_ur ?? '')
                                        )
                                        : trim(
                                            ($employee->first_name_en ?? '') .
                                            ' ' .
                                            ($employee->last_name_en ?? '')
                                        );

                                @endphp

                                <option
                                    value="{{ $employee->id }}"
                                    @selected(request('employee_id') == $employee->id)
                                >
                                    {{ $name }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>


                {{-- Department --}}
                <div class="col-md-3">

                    <div class="mb-4">

                        <label class="form-label">

                            {{ $isUrdu ? 'شعبہ' : 'Department' }}

                        </label>

                        <select
                            name="department_id"
                            class="form-select"
                        >

                            <option value="">

                                {{ $isUrdu
                                    ? 'تمام شعبے'
                                    : 'All Departments'
                                }}

                            </option>

                            @foreach($departments as $department)

                                <option
                                    value="{{ $department->id }}"
                                    @selected(
                                        request('department_id')
                                        == $department->id
                                    )
                                >

                                    {{ $isUrdu
                                        ? $department->title_ur
                                        : $department->title_en
                                    }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>


                {{-- Status --}}
                <div class="col-md-3">

                    <div class="mb-4">

                        <label class="form-label">

                            {{ $isUrdu
                                ? 'حاضری کی حالت'
                                : 'Attendance Status'
                            }}

                        </label>

                        <select
                            name="status"
                            class="form-select"
                        >

                            <option value="">

                                {{ $isUrdu
                                    ? 'تمام حالتیں'
                                    : 'All Statuses'
                                }}

                            </option>

                            @foreach($statuses as $status)

                                @php

                                    $statusLabels = [

                                        'present' => [
                                            'en' => 'Present',
                                            'ur' => 'حاضر',
                                        ],

                                        'absent' => [
                                            'en' => 'Absent',
                                            'ur' => 'غیر حاضر',
                                        ],

                                        'late' => [
                                            'en' => 'Late',
                                            'ur' => 'تاخیر سے',
                                        ],

                                        'half_day' => [
                                            'en' => 'Half Day',
                                            'ur' => 'آدھا دن',
                                        ],

                                        'leave' => [
                                            'en' => 'Leave',
                                            'ur' => 'چھٹی',
                                        ],

                                        'holiday' => [
                                            'en' => 'Holiday',
                                            'ur' => 'تعطیل',
                                        ],

                                        'manual' => [
                                            'en' => 'Manual',
                                            'ur' => 'دستی',
                                        ],

                                    ];

                                @endphp

                                <option
                                    value="{{ $status }}"
                                    @selected(request('status') === $status)
                                >

                                    {{ $statusLabels[$status][$isUrdu ? 'ur' : 'en'] ?? $status }}

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

                    {{ $isUrdu ? 'رپورٹ دیکھیں' : 'Generate Report' }}

                </button>


                <a
                    href="{{ route('payroll.reports.monthly-attendance') }}"
                    class="btn btn-alt-secondary"
                >

                    <i class="fa fa-refresh me-1"></i>

                    {{ $isUrdu ? 'ری سیٹ' : 'Reset' }}

                </a>


                <button
                    type="button"
                    onclick="window.print()"
                    class="btn btn-alt-secondary"
                >

                    <i class="fa fa-print me-1"></i>

                    {{ $isUrdu ? 'پرنٹ' : 'Print' }}

                </button>

            </div>

        </form>

    </div>

</div>