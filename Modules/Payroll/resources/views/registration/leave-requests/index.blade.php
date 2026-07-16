@extends('payroll::layouts.payroll')

@section('content')
    <div class="bg-body-light">
        <div class="content content-full">
            <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center py-2">
                <div class="flex-grow-1">
                    <h1 class="h3 fw-bold mb-3">@lang('payroll::messages.leave-requests-management')</h1>
                    <h2 class="fs-base lh-base fw-medium text-muted mb-0">@lang('payroll::messages.list-of-leave-requests')</h2>
                </div>
                <a href="{{ route('payroll.leave-requests.create') }}" class="btn btn-sm btn-primary">@lang('payroll::messages.add-new')</a>
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

        <!-- Filters -->
        <div class="block block-rounded mb-3">
            <div class="block-content block-content-full">
                <form method="GET" action="{{ route('payroll.leave-requests.index') }}" class="row g-3">
                    <div class="col-md-6">
                        <label for="employee_id" class="form-label">@lang('payroll::messages.employee')</label>
                        <select name="employee_id" id="employee_id" class="form-select select2 "
                            data-placeholder="@lang('payroll::messages.select-employee')">
                            <option value="">@lang('payroll::messages.select')</option>
                            @foreach ($employees as $employee)
                                <option value="{{ $employee->id }}"
                                    {{ request('employee_id') == $employee->id ? 'selected' : '' }}>
                                    {{ $employee->{'first_name_' . app()->getLocale()} }}{{ $employee->{'last_name_' . app()->getLocale()} }}
                                    @if ($employee->designation)
                                        <br><small
                                            class="text-muted">({{ $employee->designation->{'title_' . app()->getLocale()} }})</small>
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="status" class="form-label">@lang('payroll::messages.status')</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">@lang('payroll::messages.select')</option>
                            @foreach ($statuses as $value => $label)
                                <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>
                                     {{ __($label) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-sm btn-primary">@lang('payroll::messages.filter')</button>
                        <a href="{{ route('payroll.leave-requests.index') }}"
                            class="btn btn-sm btn-alt-secondary">@lang('payroll::messages.reset')</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="block block-rounded">
            <div class="block-content block-content-full">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-vcenter">
                        <thead>
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th style="width: 25%;">@lang('payroll::messages.employee')</th>
                                <th>@lang('payroll::messages.leave-type')</th>
                                <th style="width: 15%;">@lang('payroll::messages.start-date')</th>
                                <th style="width: 15%;">@lang('payroll::messages.end-date')</th>
                                <th style="width: 80px;">@lang('payroll::messages.days')</th>
                                <th style="width: 100px;">@lang('payroll::messages.status')</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($leaveRequests as $leaveRequest)
                                <tr>
                                    <td>{{ $leaveRequest->id }}</td>
                                    <td>
                                        <strong>{{ $leaveRequest->employee->{'first_name_' . app()->getLocale()} }}{{ $leaveRequest->employee->{'last_name_' . app()->getLocale()} }}</strong><br>
                                        <small
                                            class="text-muted">{{ $leaveRequest->employee->designation->{'title_' . app()->getLocale()} ?? 'N/A' }}</small>
                                    </td>
                                    <td>{{ $leaveRequest->leaveType->{'title_' . app()->getLocale()} }}</td>
                                    <td>{{ $leaveRequest->start_date->format('d-m-Y') }}</td>
                                    <td>{{ $leaveRequest->end_date->format('d-m-Y') }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-info">{{ $leaveRequest->days }}</span>
                                    </td>
                                    <td class="text-center">
                                        @if ($leaveRequest->status === 'pending')
                                            <span class="badge bg-warning">@lang('payroll::messages.pending')</span>
                                        @elseif ($leaveRequest->status === 'approved')
                                            <span class="badge bg-success">@lang('payroll::messages.approved')</span>
                                        @else
                                            <span class="badge bg-danger">@lang('payroll::messages.rejected')</span>
                                        @endif
                                    </td>
                                    {{-- <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <!-- View Button -->
                                            <a href="{{ route('payroll.leave-requests.show', $leaveRequest->id) }}"
                                                class="btn btn-alt-secondary" title="View Details">
                                                <i class="fa fa-fw fa-eye"></i>
                                            </a>

                                            <!-- Edit Button (only for pending) -->
                                            @if ($leaveRequest->isPending())
                                                <a href="{{ route('payroll.leave-requests.edit', $leaveRequest->id) }}"
                                                    class="btn btn-alt-secondary" title="Edit">
                                                    <i class="fa fa-fw fa-pencil-alt"></i>
                                                </a>

                                                <!-- Delete Form -->
                                                <form method="POST"
                                                    action="{{ route('payroll.leave-requests.destroy', $leaveRequest->id) }}"
                                                    class="d-inline-block">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-alt-secondary btn-delete"
                                                        title="Delete">
                                                        <i class="fa fa-fw fa-times"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            <!-- Approval Actions (only for pending) -->
                                            @if ($leaveRequest->isPending())
                                                <button type="button" class="btn btn-alt-success" data-bs-toggle="modal"
                                                    data-bs-target="#approveModal{{ $leaveRequest->id }}" title="Approve">
                                                    <i class="fa fa-fw fa-check"></i>
                                                </button>
                                                <button type="button" class="btn btn-alt-danger" data-bs-toggle="modal"
                                                    data-bs-target="#rejectModal{{ $leaveRequest->id }}" title="Reject">
                                                    <i class="fa fa-fw fa-ban"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td> --}}

                                    <td>
                                        <!-- First Row -->
                                        <div class="d-flex gap-1 mb-1">
                                            <!-- View -->
                                            <a href="{{ route('payroll.leave-requests.show', $leaveRequest->id) }}"
                                                class="btn btn-sm text-white " style="background-color:#6c757d;"
                                                title="View Details">
                                                <i class="fa fa-eye"></i>
                                            </a>

                                            <!-- Edit -->
                                            @if ($leaveRequest->isPending())
                                                <a href="{{ route('payroll.leave-requests.edit', $leaveRequest->id) }}"
                                                    class="btn btn-sm text-white " style="background-color:#0d6efd;"
                                                    title="Edit">
                                                    <i class="fa fa-pencil-alt"></i>
                                                </a>
                                            @endif
                                        </div>

                                        <!-- Second Row -->
                                        @if ($leaveRequest->isPending())
                                            <div class="d-flex gap-1">
                                                <!-- Approve -->
                                                <button type="button" class="btn btn-sm text-white "
                                                    style="background-color:#198754;" data-bs-toggle="modal"
                                                    data-bs-target="#approveModal{{ $leaveRequest->id }}" title="Approve">
                                                    <i class="fa fa-check"></i>
                                                </button>

                                                <!-- Reject -->
                                                <button type="button" class="btn btn-sm text-white "
                                                    style="background-color:#dc3545;" data-bs-toggle="modal"
                                                    data-bs-target="#rejectModal{{ $leaveRequest->id }}" title="Reject">
                                                    <i class="fa fa-ban"></i>
                                                </button>
                                            </div>
                                        @endif

                                        <!-- Delete Button -->
                                        @if ($leaveRequest->isPending())
                                            <div class="mt-1">
                                                <form method="POST"
                                                    action="{{ route('payroll.leave-requests.destroy', $leaveRequest->id) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-sm text-white btn-delete"
                                                        style="background-color:#dc3545;" title="Delete" data-bs-toggle="modal"
                                                        data-bs-target="#confirmDeleteModal">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </td>
                                </tr>

                                <!-- Approve Modal -->
                                @if ($leaveRequest->isPending())
                                    <div class="modal fade" id="approveModal{{ $leaveRequest->id }}" tabindex="-1"
                                        aria-labelledby="approveModalLabel{{ $leaveRequest->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title"
                                                        id="approveModalLabel{{ $leaveRequest->id }}">@lang('payroll::messages.approve-leave-request')
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <form
                                                    action="{{ route('payroll.leave-requests.approve', $leaveRequest->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="approval_remarks"
                                                                class="form-label">@lang('payroll::messages.approval-remarks')</label>
                                                            <textarea name="approval_remarks" id="approval_remarks" class="form-control" rows="3"
                                                                placeholder="@lang('payroll::messages.approval-remarks')"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">@lang('payroll::messages.cancel')</button>
                                                        <button type="submit"
                                                            class="btn btn-success">@lang('payroll::messages.approve')</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Reject Modal -->
                                    <div class="modal fade" id="rejectModal{{ $leaveRequest->id }}" tabindex="-1"
                                        aria-labelledby="rejectModalLabel{{ $leaveRequest->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="rejectModalLabel{{ $leaveRequest->id }}">
                                                        @lang('payroll::messages.reject-leave-request')</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <form
                                                    action="{{ route('payroll.leave-requests.reject', $leaveRequest->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="rejection_remarks"
                                                                class="form-label">@lang('payroll::messages.rejection-reason')</label>
                                                            <textarea name="approval_remarks" id="rejection_remarks" class="form-control" rows="3"
                                                                placeholder="@lang('payroll::messages.rejection-reason')" required></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">@lang('messages.cancel')</button>
                                                        <button type="submit"
                                                            class="btn btn-danger">@lang('payroll::messages.reject')</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-5">@lang('payroll::messages.no-records-found')</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-3">
                    {{ $leaveRequests->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">@lang('payroll::messages.confirm-delete')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @lang('payroll::messages.are-you-sure-delete')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('payroll::messages.cancel')</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">@lang('payroll::messages.delete')</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.btn-delete');
            let currentForm = null;

            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    currentForm = this.closest('form');
                });
            });

            const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
            if (confirmDeleteBtn) {
                confirmDeleteBtn.addEventListener('click', function() {
                    if (currentForm) {
                        currentForm.submit();
                    }
                });
            }
        });
    </script>
@endsection
