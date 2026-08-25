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
            action="{{ route('payroll.reports.absentee') }}"
        >

            <div class="row">

                {{-- From Date --}}
                <div class="col-md-3">

                    <div class="mb-3">

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

                    <div class="mb-3">

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


                {{-- Department --}}
                <div class="col-md-3">

                    <div class="mb-3">

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
                                        ? (
                                            $department->title_ur
                                            ?: $department->title_en
                                        )
                                        : (
                                            $department->title_en
                                            ?: $department->title_ur
                                        )
                                    }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>


                {{-- Designation --}}
                <div class="col-md-3">

                    <div class="mb-3">

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
                                        ? (
                                            $designation->title_ur
                                            ?: $designation->title_en
                                        )
                                        : (
                                            $designation->title_en
                                            ?: $designation->title_ur
                                        )
                                    }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>


                {{-- Employee --}}
                <div class="col-md-4">

                    <div class="mb-3">

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
                                        (string) $employeeId ===
                                        (string) $employee->id
                                    )
                                >

                                    {{ $isUrdu
                                        ? ($nameUr ?: $nameEn)
                                        : ($nameEn ?: $nameUr)
                                    }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>


                {{-- Shift --}}
                <div class="col-md-4">

                    <div class="mb-3">

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
                                    ? 'تمام شفٹس'
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
                                        ? (
                                            $shift->shift_name_ur
                                            ?: $shift->shift_name_en
                                        )
                                        : (
                                            $shift->shift_name_en
                                            ?: $shift->shift_name_ur
                                        )
                                    }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>

            </div>


            {{-- Buttons --}}
            <div class="mb-3">

                <button
                    type="submit"
                    class="btn btn-primary"
                >

                    <i class="fa fa-search me-1"></i>

                    {{ $isUrdu
                        ? 'رپورٹ دیکھیں'
                        : 'View Report'
                    }}

                </button>


                <a
                    href="{{ route('payroll.reports.absentee') }}"
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