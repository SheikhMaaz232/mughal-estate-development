@php
    $isUrdu = app()->getLocale() === 'ur';
@endphp

<div class="block block-rounded">

    <div class="block-header block-header-default">

        <h3 class="block-title">

            <i class="fa fa-filter me-1"></i>

            {{ $isUrdu
                ? 'رپورٹ فلٹر'
                : 'Report Filters'
            }}

        </h3>

    </div>

    <div class="block-content">

        <form
            method="GET"
            action="{{ route('payroll.reports.attendance-exception') }}"
        >

            <div class="row g-3">

                {{-- From Date --}}
                <div class="col-md-2">

                    <label class="form-label">

                        {{ $isUrdu
                            ? 'شروع کی تاریخ'
                            : 'From Date'
                        }}

                    </label>

                    <input
                        type="date"
                        name="from_date"
                        value="{{ $fromDate }}"
                        class="form-control"
                    >

                </div>


                {{-- To Date --}}
                <div class="col-md-2">

                    <label class="form-label">

                        {{ $isUrdu
                            ? 'اختتامی تاریخ'
                            : 'To Date'
                        }}

                    </label>

                    <input
                        type="date"
                        name="to_date"
                        value="{{ $toDate }}"
                        class="form-control"
                    >

                </div>


                {{-- Employee --}}
                <div class="col-md-2">

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

                            <option
                                value="{{ $employee->id }}"
                                @selected(
                                    (string) $employeeId ===
                                    (string) $employee->id
                                )
                            >

                                {{ $employee->first_name_en }}
                                {{ $employee->last_name_en }}

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- Department --}}
                <div class="col-md-2">

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
                                    (string) $departmentId ===
                                    (string) $department->id
                                )
                            >

                                {{ $isUrdu
                                    ? ($department->title_ur ?: $department->title_en)
                                    : ($department->title_en ?: $department->title_ur)
                                }}

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- Designation --}}
                <div class="col-md-2">

                    <label class="form-label">

                        {{ $isUrdu
                            ? 'عہدہ'
                            : 'Designation'
                        }}

                    </label>

                    <select
                        name="designation_id"
                        class="form-select"
                    >

                        <option value="">

                            {{ $isUrdu
                                ? 'تمام عہدے'
                                : 'All Designations'
                            }}

                        </option>

                        @foreach($designations as $designation)

                            <option
                                value="{{ $designation->id }}"
                                @selected(
                                    (string) $designationId ===
                                    (string) $designation->id
                                )
                            >

                                {{ $isUrdu
                                    ? ($designation->title_ur ?: $designation->title_en)
                                    : ($designation->title_en ?: $designation->title_ur)
                                }}

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- Shift --}}
                <div class="col-md-2">

                    <label class="form-label">

                        {{ $isUrdu
                            ? 'شفٹ'
                            : 'Shift'
                        }}

                    </label>

                    <select
                        name="shift_id"
                        class="form-select"
                    >

                        <option value="">

                            {{ $isUrdu
                                ? 'تمام شفٹیں'
                                : 'All Shifts'
                            }}

                        </option>

                        @foreach($shifts as $shift)

                            <option
                                value="{{ $shift->id }}"
                                @selected(
                                    (string) $shiftId ===
                                    (string) $shift->id
                                )
                            >

                                {{ $isUrdu
                                    ? ($shift->shift_name_ur ?: $shift->shift_name_en)
                                    : ($shift->shift_name_en ?: $shift->shift_name_ur)
                                }}

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- Exception Type --}}
                <div class="col-md-4">

                    <label class="form-label">

                        {{ $isUrdu
                            ? 'استثنائی قسم'
                            : 'Exception Type'
                        }}

                    </label>

                    <select
                        name="exception_type"
                        class="form-select"
                    >

                        <option
                            value="all"
                            @selected($exceptionType === 'all')
                        >

                            {{ $isUrdu
                                ? 'تمام استثنائی ریکارڈ'
                                : 'All Exceptions'
                            }}

                        </option>


                        <option
                            value="late"
                            @selected($exceptionType === 'late')
                        >

                            {{ $isUrdu
                                ? 'دیر سے آمد'
                                : 'Late Arrival'
                            }}

                        </option>


                        <option
                            value="early_leave"
                            @selected($exceptionType === 'early_leave')
                        >

                            {{ $isUrdu
                                ? 'جلدی چھٹی'
                                : 'Early Leave'
                            }}

                        </option>


                        <option
                            value="both"
                            @selected($exceptionType === 'both')
                        >

                            {{ $isUrdu
                                ? 'دیر سے آمد اور جلدی چھٹی'
                                : 'Late & Early Leave'
                            }}

                        </option>


                        <option
                            value="missing_check_in"
                            @selected($exceptionType === 'missing_check_in')
                        >

                            {{ $isUrdu
                                ? 'چیک اِن موجود نہیں'
                                : 'Missing Check-In'
                            }}

                        </option>


                        <option
                            value="missing_check_out"
                            @selected($exceptionType === 'missing_check_out')
                        >

                            {{ $isUrdu
                                ? 'چیک آؤٹ موجود نہیں'
                                : 'Missing Check-Out'
                            }}

                        </option>


                        <option
                            value="missing_punch"
                            @selected($exceptionType === 'missing_punch')
                        >

                            {{ $isUrdu
                                ? 'نامکمل پنچ'
                                : 'Missing Punch'
                            }}

                        </option>

                    </select>

                </div>

            </div>


            <div class="mt-3">

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
                    href="{{ route('payroll.reports.attendance-exception') }}"
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
