@php
    $isUrdu = app()->getLocale() === 'ur';
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
            action="{{ route('payroll.reports.attendance-detail') }}"
        >

            <div class="row">

                {{-- From Date --}}
                <div class="col-md-3">

                    <div class="mb-4">

                        <label class="form-label">

                            {{ $isUrdu
                                ? 'شروع کی تاریخ'
                                : 'From Date'
                            }}

                        </label>

                        <input
                            type="date"
                            name="from_date"
                            class="form-control"
                            value="{{ $fromDate }}"
                        >

                    </div>

                </div>


                {{-- To Date --}}
                <div class="col-md-3">

                    <div class="mb-4">

                        <label class="form-label">

                            {{ $isUrdu
                                ? 'اختتامی تاریخ'
                                : 'To Date'
                            }}

                        </label>

                        <input
                            type="date"
                            name="to_date"
                            class="form-control"
                            value="{{ $toDate }}"
                        >

                    </div>

                </div>


                {{-- Employee --}}
                <div class="col-md-3">

                    <div class="mb-4">

                        <label class="form-label">

                            {{ $isUrdu
                                ? 'ملازم'
                                : 'Employee'
                            }}

                        </label>

                        <select
                            name="employee_id"
                            class="form-select"
                        >

                            <option value="">

                                {{ $isUrdu
                                    ? 'تمام ملازمین'
                                    : 'All Employees'
                                }}

                            </option>


                            @foreach($employees as $employee)

                                @php

                                    $employeeName = $isUrdu
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
                                    @selected(
                                        request('employee_id')
                                        == $employee->id
                                    )
                                >

                                    {{ $employeeName }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>


                {{-- Department --}}
                <div class="col-md-3">

                    <div class="mb-4">

                        <label class="form-label">

                            {{ $isUrdu
                                ? 'شعبہ'
                                : 'Department'
                            }}

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


                            <option
                                value="present"
                                @selected(request('status') === 'present')
                            >
                                {{ $isUrdu ? 'حاضر' : 'Present' }}
                            </option>


                            <option
                                value="absent"
                                @selected(request('status') === 'absent')
                            >
                                {{ $isUrdu ? 'غیر حاضر' : 'Absent' }}
                            </option>


                            <option
                                value="late"
                                @selected(request('status') === 'late')
                            >
                                {{ $isUrdu ? 'تاخیر سے' : 'Late' }}
                            </option>


                            <option
                                value="half_day"
                                @selected(request('status') === 'half_day')
                            >
                                {{ $isUrdu ? 'آدھا دن' : 'Half Day' }}
                            </option>


                            <option
                                value="leave"
                                @selected(request('status') === 'leave')
                            >
                                {{ $isUrdu ? 'چھٹی' : 'Leave' }}
                            </option>


                            <option
                                value="holiday"
                                @selected(request('status') === 'holiday')
                            >
                                {{ $isUrdu ? 'تعطیل' : 'Holiday' }}
                            </option>


                            <option
                                value="manual"
                                @selected(request('status') === 'manual')
                            >
                                {{ $isUrdu ? 'دستی' : 'Manual' }}
                            </option>

                        </select>

                    </div>

                </div>

            </div>


            {{-- Buttons --}}
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
                    href="{{ route('payroll.reports.attendance-detail') }}"
                    class="btn btn-alt-secondary"
                >

                    <i class="fa fa-refresh me-1"></i>

                    {{ $isUrdu
                        ? 'ری سیٹ'
                        : 'Reset'
                    }}

                </a>


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

            </div>

        </form>

    </div>

</div>