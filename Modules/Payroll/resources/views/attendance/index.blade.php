@extends('payroll::layouts.payroll')

@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h1 class="h3 fw-bold mb-3">@lang('payroll::messages.attendance-management')</h1>
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">@lang('payroll::messages.attendance-list')</h2>
                </div>

                <div>
                    <!-- Process Attendance -->
                    <form method="POST" action="{{ route('payroll.attendance.process') }}" class="d-inline-block">
                        @csrf
                        <input type="date" name="date" required class="form-control form-control-sm d-inline-block"
                            style="width: 150px;">
                        <button class="btn btn-sm btn-primary">
                            @lang('payroll::messages.process-attendance')
                        </button>
                    </form>

                    <!-- Manual Attendance -->
                    <a href="{{ route('payroll.attendance.manual') }}" class="btn btn-sm btn-warning">
                        @lang('payroll::messages.manual-attendance')
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="content">

        {{-- SUCCESS --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- ERROR --}}
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="block block-rounded">
            <div class="block-content block-content-full">

                <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>@lang('payroll::messages.employee')</th>
                            <th>@lang('payroll::messages.date')</th>
                            <th>@lang('payroll::messages.check-in')</th>
                            <th>@lang('payroll::messages.check-out')</th>
                            <th>@lang('payroll::messages.late')</th>
                            <th>@lang('payroll::messages.early-leave')</th>
                            <th>@lang('payroll::messages.overtime')</th>
                            <th>@lang('payroll::messages.status')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($attendances as $key => $att)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $att->employee->{'first_name_' . app()->getLocale()} }}{{ $att->employee->{'last_name_' . app()->getLocale()} }}</td>
                                <td>{{ $att->date }}</td>
                                <td>{{ $att->check_in }}</td>
                                <td>{{ $att->check_out }}</td>
                                <td>{{ $att->late_minutes }}</td>
                                <td>{{ $att->early_leave_minutes }}</td>
                                <td>{{ $att->overtime_minutes }}</td>
                                <td>
                                    <span
                                        class="badge
                                @if ($att->status == 'present') bg-success
                                @elseif($att->status == 'absent') bg-danger
                                @elseif($att->status == 'manual') bg-warning
                                @else bg-secondary @endif">
                                        @lang('payroll::messages.' . $att->status)
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $attendances->links() }}
                </div>

            </div>
        </div>
    </div>
@endsection
