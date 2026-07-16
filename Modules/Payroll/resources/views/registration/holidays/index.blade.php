@extends('payroll::layouts.payroll')

@section('content')
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
            <div class="flex-grow-1">
                <h1 class="h3 fw-bold mb-3">@lang('payroll::messages.holidays-management')</h1>
                <h2 class="fs-base lh-base fw-medium text-muted mb-0">@lang('payroll::messages.list-of-holidays')</h2>
            </div>
            <a href="{{ route('payroll.holidays.create') }}" class="btn btn-sm btn-primary">@lang('payroll::messages.add-holiday')</a>
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
            <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>@lang('messages.name') (EN)</th>
                        <th>@lang('messages.name') (اردو)</th>
                        <th>@lang('messages.date')</th>
                        <th>@lang('payroll::messages.holiday-type')</th>
                        <th>@lang('payroll::messages.paid')</th>
                        <th style="width: 160px;">@lang('messages.actions')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($holidays as $holiday)
                        <tr>
                            <td>{{ $holiday->id }}</td>
                            <td>{{ $holiday->name_en }}</td>
                            <td>{{ $holiday->name_ur }}</td>
                            <td>{{ $holiday->date?->format('d-m-Y') }}</td>
                            <td>{{ $holiday->holidayType?->{'title_' . app()->getLocale()} ?? $holiday->holidayType?->title_en ?? '-' }}</td>
                            <td>
                                <span class="badge bg-{{ $holiday->is_paid ? 'success' : 'secondary' }}">
                                    {{ $holiday->is_paid ? __('payroll::messages.paid') : __('payroll::messages.unpaid') }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="{{ route('payroll.holidays.show', $holiday->id) }}" class="btn btn-sm btn-alt-secondary" aria-label="View Holiday">
                                        <i class="fa fa-fw fa-eye"></i>
                                    </a>
                                    <a href="{{ route('payroll.holidays.edit', $holiday->id) }}" class="btn btn-sm btn-alt-secondary" aria-label="Edit Holiday">
                                        <i class="fa fa-fw fa-pencil-alt"></i>
                                    </a>
                                    <form method="POST" action="{{ route('payroll.holidays.destroy', $holiday->id) }}" class="d-inline-block delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-alt-secondary btn-delete" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
                                            <i class="fa fa-fw fa-times"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">@lang('messages.no-records-found')</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="d-flex justify-content-center">
                {{ $holidays->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
