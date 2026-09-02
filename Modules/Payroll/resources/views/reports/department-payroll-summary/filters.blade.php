<div class="block block-rounded">

    <div class="block-header block-header-default">

        <h3 class="block-title">

            <i class="fa fa-filter me-1"></i>

            @lang('payroll::messages.filters')

        </h3>

    </div>


    <div class="block-content">

        <form
            method="GET"
            action="{{ route('payroll.reports.department-payroll-summary') }}"
        >

            <div class="row">


                {{-- Month --}}
                <div class="col-md-3">

                    <div class="mb-4">

                        <label
                            class="form-label"
                            for="month"
                        >

                            @lang('payroll::messages.month')

                        </label>

                        <input
                            type="month"
                            id="month"
                            name="month"
                            value="{{ $month }}"
                            class="form-control"
                        >

                    </div>

                </div>


                {{-- Employee --}}
                <div class="col-md-3">

                    <div class="mb-4">

                        <label
                            class="form-label"
                            for="employee_id"
                        >

                            @lang('payroll::messages.employee')

                        </label>

                        <select
                            name="employee_id"
                            id="employee_id"
                            class="form-select"
                        >

                            <option value="">

                                @lang('payroll::messages.all_employees')

                            </option>

                            @foreach($employees as $employee)

                                <option
                                    value="{{ $employee->id }}"
                                    @selected(
                                        $employeeId == $employee->id
                                    )
                                >

                                    {{ $employee->first_name_en }}
                                    {{ $employee->last_name_en }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>


                {{-- Department --}}
                <div class="col-md-3">

                    <div class="mb-4">

                        <label
                            class="form-label"
                            for="department_id"
                        >

                            @lang('payroll::messages.department')

                        </label>

                        <select
                            name="department_id"
                            id="department_id"
                            class="form-select"
                        >

                            <option value="">

                                @lang('payroll::messages.all_departments')

                            </option>

                            @foreach($departments as $department)

                                <option
                                    value="{{ $department->id }}"
                                    @selected(
                                        $departmentId == $department->id
                                    )
                                >

                                    {{ $department->title_en }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>


                {{-- Designation --}}
                <div class="col-md-3">

                    <div class="mb-4">

                        <label
                            class="form-label"
                            for="designation_id"
                        >

                            @lang('payroll::messages.designation')

                        </label>

                        <select
                            name="designation_id"
                            id="designation_id"
                            class="form-select"
                        >

                            <option value="">

                                @lang('payroll::messages.all_designations')

                            </option>

                            @foreach($designations as $designation)

                                <option
                                    value="{{ $designation->id }}"
                                    @selected(
                                        $designationId == $designation->id
                                    )
                                >

                                    {{ $designation->title_en }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>


                {{-- Shift --}}
                <div class="col-md-3">

                    <div class="mb-4">

                        <label
                            class="form-label"
                            for="shift_id"
                        >

                            @lang('payroll::messages.shift')

                        </label>

                        <select
                            name="shift_id"
                            id="shift_id"
                            class="form-select"
                        >

                            <option value="">

                                @lang('payroll::messages.all_shifts')

                            </option>

                            @foreach($shifts as $shift)

                                <option
                                    value="{{ $shift->id }}"
                                    @selected(
                                        $shiftId == $shift->id
                                    )
                                >

                                    {{ $shift->shift_name_en }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>


                {{-- Buttons --}}
                <div class="col-md-9">

                    <div class="mb-4 pt-4">

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >

                            <i class="fa fa-search me-1"></i>

                            @lang('payroll::messages.generate_report')

                        </button>


                        <a
                            href="{{ route('payroll.reports.department-payroll-summary') }}"
                            class="btn btn-alt-secondary"
                        >

                            <i class="fa fa-refresh me-1"></i>

                            @lang('payroll::messages.reset')

                        </a>

                    </div>

                </div>

            </div>

        </form>

    </div>

</div>
