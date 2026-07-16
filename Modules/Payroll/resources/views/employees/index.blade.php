@extends('payroll::layouts.payroll')

@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h1 class="h3 fw-bold mb-3">@lang('payroll::messages.employees-management')</h1>
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">@lang('payroll::messages.list-of-employees')</h2>
                </div>
                <a href="{{ route('payroll.employees.create') }}" class="btn btn-sm btn-primary">@lang('payroll::messages.add-new')</a>
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
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="block block-rounded">
            <div class="block-content block-content-full">
                <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>@lang('messages.photo')</th>
                            <th>@lang('payroll::messages.name') (EN)</th>
                            <th>@lang('payroll::messages.name') (اردو) </th>
                            <th>@lang('messages.father_name')</th>
                            <th>@lang('payroll::messages.cnic') </th>
                            <th style="width: 150px;">@lang('messages.actions')</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($employeesData as $index => $employee)
                            <td>{{ $employee->id }}</td>
                            <td class="text-center">
                                <img src="{{ isset($employee) && $employee->profile_picture ? asset('storage/' . $employee->profile_picture) : asset('images/No-Image-Placeholder.svg.png') }}"
                                    width="50" height="50" style="object-fit: cover;">
                            </td>
                            <td>{{ $employee->first_name_en . ' ' . $employee->last_name_en }}</td>
                            <td>{{ $employee->first_name_ur . ' ' . $employee->last_name_ur }}</td>
                            <td>
                                {{ App::getLocale() === 'ur' ? $employee->father_name_ur ?? '-' : $employee->father_name_en ?? '' }}
                            </td>
                            <td>{{ $employee->cnic }}</td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <!-- Edit Button -->
                                    <a href="{{ route('payroll.employees.edit', $employee->id) }}"
                                        class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled" data-bs-toggle="tooltip"
                                        aria-label="Edit Employee" data-bs-original-title="Edit Employee">
                                        <i class="fa fa-fw fa-pencil-alt"></i>
                                    </a>

                                    <!-- Delete Form -->
                                    <form method="POST" action="{{ route('payroll.employees.destroy', $employee->id) }}"
                                        class="d-inline-block delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                            class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled btn-delete"
                                            data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
                                            <i class="fa fa-fw fa-times"></i>
                                        </button>
                                    </form>

                                    <a href="{{ route('payroll.employees.show', $employee->id) }}"
                                        class="btn btn-sm btn-alt-secondary js-bs-tooltip-enabled" data-bs-toggle="tooltip"
                                        aria-label="View Employee Detail" data-bs-original-title="View Employee Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $employeesData->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
