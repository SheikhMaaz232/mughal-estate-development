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
            action="{{ route('payroll.reports.department-attendance-summary') }}"
        >

            <div class="row">

                {{-- From Date --}}
                <div class="col-md-3">

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
                <div class="col-md-3">

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


                {{-- Department --}}
                <div class="col-md-4">

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


                {{-- Buttons --}}
                <div class="col-md-2 d-flex align-items-end">

                    <div class="mb-3">

                        <button
                            type="submit"
                            class="btn btn-primary"
                        >

                            <i class="fa fa-search me-1"></i>

                            {{ $isUrdu
                                ? 'دیکھیں'
                                : 'View'
                            }}

                        </button>


                        <a
                            href="{{ route('payroll.reports.department-attendance-summary') }}"
                            class="btn btn-alt-secondary"
                        >

                            <i class="fa fa-refresh"></i>

                        </a>

                    </div>

                </div>

            </div>

        </form>

    </div>

</div>
