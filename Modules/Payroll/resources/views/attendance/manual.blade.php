@extends('payroll::layouts.payroll')

@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h1 class="h3 fw-bold mb-3">@lang('payroll::messages.manual-attendance')</h1>
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">
                        @lang('payroll::messages.mark-manual-attendance')
                    </h2>
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

                <form method="POST" action="{{ route('payroll.attendance.manual.store') }}">
                    @csrf

                    <div class="row">

                        <!-- Date -->
                        <div class="col-md-3 mb-3">
                            <label>@lang('payroll::messages.date')</label>
                            <input type="date" name="date" class="form-control" required>
                        </div>

                        <!-- Check In -->
                        <div class="col-md-3 mb-3">
                            <label>@lang('payroll::messages.check-in')</label>
                            <input type="time" name="check_in" class="form-control">
                        </div>

                        <!-- Check Out -->
                        <div class="col-md-3 mb-3">
                            <label>@lang('payroll::messages.check-out')</label>
                            <input type="time" name="check_out" class="form-control">
                        </div>

                        <!-- Status -->
                        <div class="col-md-3 mb-3">
                            <label>@lang('messages.status')</label>

                            <select name="status" class="form-control @error('status') is-invalid @enderror">
                                <option value="present">@lang('payroll::messages.present')</option>
                                <option value="absent">@lang('payroll::messages.absent')</option>
                                <option value="late">@lang('payroll::messages.late')</option>
                                <option value="half_day">@lang('payroll::messages.half_day')</option>
                                <option value="leave">@lang('payroll::messages.leave')</option>
                                <option value="holiday">@lang('payroll::messages.holiday')</option>
                                <option value="manual">@lang('payroll::messages.manual')</option>
                            </select>

                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>

                    <hr>

                    <h5 class="mb-3">@lang('payroll::messages.select-employees')</h5>

                    <div class="row">
                        @foreach ($employees as $emp)
                            <div class="col-md-3 mb-2">
                                <label class="d-block">
                                    <input type="checkbox" name="employees[]" value="{{ $emp->id }}">
                                    {{ $emp->{'first_name_' . app()->getLocale()} }}
                                    {{ $emp->{'last_name_' . app()->getLocale()} }}
                                </label>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-success">
                            @lang('messages.save')
                        </button>

                        <a href="{{ route('payroll.attendance.index') }}" class="btn btn-secondary">
                            @lang('messages.go-to-list')
                        </a>
                    </div>

                </form>

            </div>
        </div>
    </div>
@endsection
