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

        <form method="GET" action="{{ route('payroll.reports.salary-register') }}">

            <div class="row">

                {{-- Year --}}
                <div class="col-md-2">

                    <div class="mb-4">

                        <label class="form-label">

                            {{ $isUrdu ? 'سال' : 'Year' }}

                        </label>


                        <select name="year" class="form-select">

                            @for ($yearOption = $currentYear - 5; $yearOption <= $currentYear + 1; $yearOption++)
                                <option value="{{ $yearOption }}" @selected($year == $yearOption)>

                                    {{ $yearOption }}

                                </option>
                            @endfor

                        </select>

                    </div>

                </div>


                {{-- Month --}}
                <div class="col-md-2">

                    <div class="mb-4">

                        <label class="form-label">

                            {{ $isUrdu ? 'مہینہ' : 'Month' }}

                        </label>


                        <select name="month" class="form-select">

                            @foreach ($months as $monthNumber => $monthName)
                                <option
                                    value="{{ str_pad($monthNumber, 2, '0', STR_PAD_LEFT) }}"
                                    @selected((int) $month === $monthNumber)>

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


                        <select name="employee_id" class="form-select">

                            <option value="">

                                {{ $isUrdu ? 'تمام ملازمین' : 'All Employees' }}

                            </option>


                            @foreach ($employees as $employeeOption)
                                @php

                                    $employeeName = $isUrdu
                                        ? trim(
                                            ($employeeOption->first_name_ur ?? '') .
                                                ' ' .
                                                ($employeeOption->last_name_ur ?? ''),
                                        )
                                        : trim(
                                            ($employeeOption->first_name_en ?? '') .
                                                ' ' .
                                                ($employeeOption->last_name_en ?? ''),
                                        );
                                @endphp


                                <option value="{{ $employeeOption->id }}" @selected((string) $employeeId === (string) $employeeOption->id)>

                                    {{ $employeeName }}

                                    @if ($employeeOption->device_user_id)
                                        —
                                        {{ $employeeOption->device_user_id }}
                                    @endif

                                </option>
                            @endforeach

                        </select>

                    </div>

                </div>


                {{-- Department --}}
                <div class="col-md-2">

                    <div class="mb-4">

                        <label class="form-label">

                            {{ $isUrdu ? 'شعبہ' : 'Department' }}

                        </label>


                        <select name="department_id" class="form-select">

                            <option value="">

                                {{ $isUrdu ? 'تمام شعبے' : 'All Departments' }}

                            </option>


                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}" @selected((string) $departmentId === (string) $department->id)>

                                    {{ $isUrdu ? $department->title_ur : $department->title_en }}

                                </option>
                            @endforeach

                        </select>

                    </div>

                </div>


                {{-- Finalized --}}
                <div class="col-md-3">

                    <div class="mb-4">

                        <label class="form-label">

                            {{ $isUrdu ? 'حتمی حالت' : 'Finalization Status' }}

                        </label>


                        <select name="finalized" class="form-select">

                            <option value="">

                                {{ $isUrdu ? 'تمام' : 'All' }}

                            </option>


                            <option value="1" @selected($finalized === '1' || $finalized === 1)>

                                {{ $isUrdu ? 'حتمی شدہ' : 'Finalized' }}

                            </option>


                            <option value="0" @selected($finalized === '0' || $finalized === 0)>

                                {{ $isUrdu ? 'غیر حتمی' : 'Not Finalized' }}

                            </option>

                        </select>

                    </div>

                </div>

            </div>


            {{-- Designation --}}
            <div class="row">

                <div class="col-md-3">

                    <div class="mb-4">

                        <label class="form-label">

                            {{ $isUrdu ? 'عہدہ' : 'Designation' }}

                        </label>


                        <select name="designation_id" class="form-select">

                            <option value="">

                                {{ $isUrdu ? 'تمام عہدے' : 'All Designations' }}

                            </option>


                            @foreach ($designations as $designation)
                                <option value="{{ $designation->id }}" @selected((string) $designationId === (string) $designation->id)>

                                    {{ $isUrdu ? $designation->title_ur : $designation->title_en }}

                                </option>
                            @endforeach

                        </select>

                    </div>

                </div>

            </div>


            {{-- Buttons --}}
            <div class="mb-4">

                <button type="submit" class="btn btn-primary">

                    <i class="fa fa-search me-1"></i>

                    {{ $isUrdu ? 'رپورٹ دیکھیں' : 'Generate Report' }}

                </button>


                <a href="{{ route('payroll.reports.salary-register') }}" class="btn btn-alt-secondary">

                    <i class="fa fa-refresh me-1"></i>

                    {{ $isUrdu ? 'ری سیٹ' : 'Reset' }}

                </a>


                <button type="button" onclick="window.print()" class="btn btn-alt-secondary">

                    <i class="fa fa-print me-1"></i>

                    {{ $isUrdu ? 'پرنٹ' : 'Print' }}

                </button>

            </div>

        </form>

    </div>

</div>
