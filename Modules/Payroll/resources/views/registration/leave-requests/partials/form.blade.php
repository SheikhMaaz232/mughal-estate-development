@csrf

@if (isset($leaveRequest))
    @method('PUT')
@endif

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="employee_id" class="form-label">@lang('payroll::messages.employee')</label>
        <select name="employee_id" id="employee_id" class="form-select" required>
            <option value="">@lang('payroll::messages.select')</option>
            @foreach ($employees as $employee)
                <option value="{{ $employee->id }}"
                    {{ old('employee_id', @$leaveRequest->employee_id ?? '') == $employee->id ? 'selected' : '' }}>
                    {{ $employee->{'first_name_' . app()->getLocale()} }}
                    {{ $employee->{'last_name_' . app()->getLocale()} }}
                    @if ($employee->designation)
                        <br><small
                            class="text-muted">({{ $employee->designation->{'title_' . app()->getLocale()} }})</small>
                    @endif
                </option>
            @endforeach
        </select>
        @error('employee_id')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="leave_type_id" class="form-label">@lang('payroll::messages.leave-type')</label>
        <select name="leave_type_id" id="leave_type_id" class="form-select" required>
            <option value="">@lang('payroll::messages.select')</option>
            @foreach ($leaveTypes as $leaveType)
                <option value="{{ $leaveType->id }}"
                    {{ old('leave_type_id', @$leaveRequest->leave_type_id ?? '') == $leaveType->id ? 'selected' : '' }}>
                    {{ $leaveType->{'title_' . app()->getLocale()} }}
                </option>
            @endforeach
        </select>
        @error('leave_type_id')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="start_date" class="form-label">@lang('payroll::messages.start-date')</label>
        <input type="date" name="start_date" id="start_date" class="form-control"
            value="{{ old('start_date', @$leaveRequest->start_date?->format('Y-m-d') ?? '') }}" required>
        @error('start_date')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="end_date" class="form-label">@lang('payroll::messages.end-date')</label>
        <input type="date" name="end_date" id="end_date" class="form-control"
            value="{{ old('end_date', @$leaveRequest->end_date?->format('Y-m-d') ?? '') }}" required>
        @error('end_date')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-12 mb-3">
        <label for="reason" class="form-label">@lang('payroll::messages.reason')</label>
        <textarea name="reason" id="reason" class="form-control" rows="4" placeholder="@lang('payroll::messages.reason')"
            maxlength="1000">{{ old('reason', @$leaveRequest->reason ?? '') }}</textarea>
        <small class="text-muted">@lang('payroll::messages.max-1000-chars')</small>
        @error('reason')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="d-flex gap-2">
    <button type="submit" class="btn btn-sm btn-primary">
        @if (isset($leaveRequest) && $leaveRequest->id)
            @lang('messages.update')
        @else
            @lang('messages.save')
        @endif
    </button>
    <a href="{{ route('payroll.leave-requests.index') }}" class="btn btn-sm btn-alt-primary">@lang('messages.go-to-list')</a>
</div>
