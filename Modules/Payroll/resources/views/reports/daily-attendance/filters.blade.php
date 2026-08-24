@php
    $locale = app()->getLocale();
    $isUrdu = $locale === 'ur';
@endphp

<div class="card mb-4">

    <div class="card-header">
        <strong>
            {{ $isUrdu ? 'رپورٹ فلٹرز' : 'Report Filters' }}
        </strong>
    </div>

    <div class="card-body">

        <form
            method="GET"
            action="{{ route('payroll.reports.daily-attendance') }}"
        >

            <div class="row g-3">

                {{-- Date --}}
                <div class="col-md-3">

                    <label class="form-label">
                        {{ $isUrdu ? 'تاریخ' : 'Date' }}
                    </label>

                    <input
                        type="date"
                        name="date"
                        value="{{ request('date', $date ?? now()->format('Y-m-d')) }}"
                        class="form-control"
                    >

                </div>


                {{-- Employee --}}
                <div class="col-md-3">

                    <label class="form-label">
                        {{ $isUrdu ? 'ملازم' : 'Employee' }}
                    </label>

                    <select
                        name="employee_id"
                        class="form-select"
                    >

                        <option value="">
                            {{ $isUrdu ? 'تمام ملازمین' : 'All Employees' }}
                        </option>

                        @foreach($employees as $employee)

                            @php
                                $employeeName = $isUrdu
                                    ? trim(
                                        ($employee->first_name_ur ?? '') . ' ' .
                                        ($employee->last_name_ur ?? '')
                                    )
                                    : trim(
                                        ($employee->first_name_en ?? '') . ' ' .
                                        ($employee->last_name_en ?? '')
                                    );
                            @endphp

                            <option
                                value="{{ $employee->id }}"
                                @selected(request('employee_id') == $employee->id)
                            >
                                {{ $employeeName }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- Department --}}
                <div class="col-md-3">

                    <label class="form-label">
                        {{ $isUrdu ? 'شعبہ' : 'Department' }}
                    </label>

                    <select
                        name="department_id"
                        class="form-select"
                    >

                        <option value="">
                            {{ $isUrdu ? 'تمام شعبے' : 'All Departments' }}
                        </option>

                        @foreach($departments as $department)

                            <option
                                value="{{ $department->id }}"
                                @selected(request('department_id') == $department->id)
                            >
                                {{ $isUrdu
                                    ? $department->title_ur
                                    : $department->title_en
                                }}
                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- Status --}}
                <div class="col-md-3">

                    <label class="form-label">
                        {{ $isUrdu ? 'حاضری کی حالت' : 'Attendance Status' }}
                    </label>

                    <select
                        name="status"
                        class="form-select"
                    >

                        <option value="">
                            {{ $isUrdu ? 'تمام حالتیں' : 'All Statuses' }}
                        </option>

                        @foreach($statuses as $item)

                            <option
                                value="{{ $item }}"
                                @selected(request('status') === $item)
                            >
                                @if($isUrdu)

                                    @switch($item)

                                        @case('present')
                                            حاضر
                                            @break

                                        @case('absent')
                                            غیر حاضر
                                            @break

                                        @case('late')
                                            تاخیر سے
                                            @break

                                        @case('half_day')
                                            آدھا دن
                                            @break

                                        @case('leave')
                                            چھٹی
                                            @break

                                        @case('holiday')
                                            تعطیل
                                            @break

                                        @case('manual')
                                            دستی
                                            @break

                                        @default
                                            {{ $item }}

                                    @endswitch

                                @else

                                    {{ ucwords(str_replace('_', ' ', $item)) }}

                                @endif
                            </option>

                        @endforeach

                    </select>

                </div>

            </div>


            {{-- Buttons --}}
            <div class="mt-3 d-flex gap-2">

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    <i class="fas fa-search"></i>

                    {{ $isUrdu ? 'تلاش کریں' : 'Search' }}
                </button>


                <a
                    href="{{ route('payroll.reports.daily-attendance') }}"
                    class="btn btn-secondary"
                >
                    <i class="fas fa-redo"></i>

                    {{ $isUrdu ? 'ری سیٹ' : 'Reset' }}
                </a>


                <button
                    type="button"
                    onclick="window.print()"
                    class="btn btn-outline-secondary"
                >
                    <i class="fas fa-print"></i>

                    {{ $isUrdu ? 'پرنٹ' : 'Print' }}
                </button>

            </div>

        </form>

    </div>

</div>
