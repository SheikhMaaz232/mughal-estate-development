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
            action="{{ route('payroll.reports.employee-leave-balance') }}"
        >

            <div class="row g-3">

                {{-- Year --}}
                <div class="col-md-2">

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
                            $y = now()->year - 5;
                            $y <= now()->year + 1;
                            $y++
                        )

                            <option
                                value="{{ $y }}"
                                @selected($year == $y)
                            >

                                {{ $y }}

                            </option>

                        @endfor

                    </select>

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
                                    ? $department->title_ur
                                    : $department->title_en
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
                                    ? $designation->title_ur
                                    : $designation->title_en
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
                                    ? $shift->shift_name_ur
                                    : $shift->shift_name_en
                                }}

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- Leave Type --}}
                <div class="col-md-2">

                    <label class="form-label">

                        {{ $isUrdu
                            ? 'چھٹی کی قسم'
                            : 'Leave Type'
                        }}

                    </label>

                    <select
                        name="leave_type_id"
                        class="form-select"
                    >

                        <option value="">

                            {{ $isUrdu
                                ? 'تمام اقسام'
                                : 'All Leave Types'
                            }}

                        </option>

                        @foreach($leaveTypes as $leaveType)

                            <option
                                value="{{ $leaveType->id }}"
                                @selected(
                                    (string) $leaveTypeId ===
                                    (string) $leaveType->id
                                )
                            >

                                {{ $isUrdu
                                    ? $leaveType->title_ur
                                    : $leaveType->title_en
                                }}

                            </option>

                        @endforeach

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
                    href="{{ route('payroll.reports.employee-leave-balance') }}"
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
