@extends('payroll::layouts.payroll')

@section('content')
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
            <div class="flex-grow-1">
                <h1 class="h3 fw-bold mb-3">@lang('payroll::messages.payroll-management')</h1>
                <h2 class="fs-base lh-base fw-medium text-muted mb-0">@lang('payroll::messages.payroll-management-description')</h2>
            </div>
            <a href="{{ route('payroll.payrolls.create') }}" class="btn btn-sm btn-primary">@lang('payroll::messages.generate-payroll')</a>
        </div>
    </div>
</div>
<div class="content">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="block block-rounded">
        <div class="block-content block-content-full">
            <form method="GET" action="{{ route('payroll.payrolls.index') }}" class="row g-2 mb-4">
                <div class="col-auto">
                    <label for="month" class="form-label">@lang('payroll::messages.month')</label>
                    <input id="month" type="month" name="month" class="form-control" value="{{ old('month', $month) }}">
                </div>
                <div class="col-auto align-self-end">
                    <button type="submit" class="btn btn-secondary mt-1">@lang('payroll::messages.filter')</button>
                    <a href="{{ route('payroll.payrolls.index') }}" class="btn btn-outline-secondary mt-1">@lang('payroll::messages.reset')</a>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-vcenter">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>@lang('payroll::messages.employee')</th>
                            <th>@lang('payroll::messages.month')</th>
                            <th>@lang('payroll::messages.worked-days')</th>
                            <th>@lang('payroll::messages.leave-days')</th>
                            <th>@lang('payroll::messages.holiday-days')</th>
                            <th>@lang('payroll::messages.absent-days')</th>
                            <th>@lang('payroll::messages.late-minutes')</th>
                            <th>@lang('payroll::messages.early-leave-minutes')</th>
                            <th>@lang('payroll::messages.overtime-minutes')</th>
                            <th>@lang('payroll::messages.gross-salary')</th>
                            <th>@lang('payroll::messages.net-salary')</th>
                            <th>@lang('payroll::messages.status')</th>
                            <th class="text-center" style="width: 150px;">@lang('payroll::messages.actions')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($payrolls as $payroll)
                            <tr>
                                <td>{{ $payroll->id }}</td>
                                <td>{{ $payroll->employee->first_name_en }} {{ $payroll->employee->last_name_en }}</td>
                                <td>{{ $payroll->month }}</td>
                                <td>{{ $payroll->total_worked_days }}</td>
                                <td>{{ $payroll->total_leave_days }}</td>
                                <td>{{ $payroll->total_holiday_days }}</td>
                                <td>{{ $payroll->total_absent_days }}</td>
                                <td>{{ $payroll->total_late_minutes }}</td>
                                <td>{{ $payroll->total_early_leave_minutes }}</td>
                                <td>{{ $payroll->total_overtime_minutes }}</td>
                                <td>{{ number_format($payroll->gross_salary, 2) }}</td>
                                <td>{{ number_format($payroll->net_salary, 2) }}</td>
                                <td>
                                    @if ($payroll->is_finalized)
                                        <span class="badge bg-success">@lang('payroll::messages.finalized')</span>
                                    @else
                                        <span class="badge bg-warning text-dark">@lang('payroll::messages.draft')</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="{{ route('payroll.payrolls.show', $payroll) }}" class="btn btn-sm btn-alt-secondary" title="@lang('payroll::messages.view')">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <a href="{{ route('payroll.payrolls.edit', $payroll) }}" class="btn btn-sm btn-alt-secondary" title="@lang('payroll::messages.edit')">
                                            <i class="fa fa-pencil-alt"></i>
                                        </a>
                                        <form method="POST" action="{{ route('payroll.payrolls.destroy', $payroll) }}" class="d-inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-alt-secondary" onclick="return confirm('{{ __('payroll::messages.delete-payroll-confirm') }}')">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="14" class="text-center">@lang('payroll::messages.no-records-found')</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center">
                @if (method_exists($payrolls, 'links'))
                    {{ $payrolls->links() }}
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
