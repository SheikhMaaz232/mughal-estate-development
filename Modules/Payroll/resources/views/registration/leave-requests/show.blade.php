@extends('payroll::layouts.payroll')

@section('content')
<div class="block block-rounded">
    <div class="block-header block-header-default">
        <h3 class="block-title">@lang('payroll::messages.leave-request-details')</h3>
        <div class="block-options">
            <a href="{{ route('payroll.leave-requests.index') }}" class="btn btn-sm btn-alt-primary">
                @lang('messages.go-to-list')
            </a>
        </div>
    </div>
    <div class="block-content block-content-full">
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label fw-bold">@lang('payroll::messages.request-id')</label>
                    <p class="form-control-plaintext">{{ $leaveRequest->id }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">@lang('payroll::messages.employee')</label>
                    <p class="form-control-plaintext">
                         {{ $leaveRequest->employee->{'first_name_' . app()->getLocale()} }}{{ $leaveRequest->employee->{'last_name_' . app()->getLocale()} }}
                        @if($leaveRequest->employee->designation)
                            <br><small class="text-muted">{{ $leaveRequest->employee->designation->{'title_' . app()->getLocale()} }}</small>
                        @endif
                    </p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">@lang('payroll::messages.leave-type')</label>
                    <p class="form-control-plaintext">{{ $leaveRequest->leaveType->{'title_' . app()->getLocale()} }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">@lang('payroll::messages.start-date')</label>
                    <p class="form-control-plaintext">{{ $leaveRequest->start_date->format('d-m-Y') }}</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label fw-bold">@lang('payroll::messages.end-date')</label>
                    <p class="form-control-plaintext">{{ $leaveRequest->end_date->format('d-m-Y') }}</p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">@lang('payroll::messages.days')</label>
                    <p class="form-control-plaintext">
                        <span class="badge bg-info">{{ $leaveRequest->days }} @lang('payroll::messages.days')</span>
                    </p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">@lang('payroll::messages.status')</label>
                    <p class="form-control-plaintext">
                        @if ($leaveRequest->status === 'pending')
                            <span class="badge bg-warning">@lang('payroll::messages.pending')</span>
                        @elseif ($leaveRequest->status === 'approved')
                            <span class="badge bg-success">@lang('payroll::messages.approved')</span>
                        @else
                            <span class="badge bg-danger">@lang('payroll::messages.rejected')</span>
                        @endif
                    </p>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">@lang('payroll::messages.submitted-date')</label>
                    <p class="form-control-plaintext">{{ $leaveRequest->created_at->format('d-m-Y H:i') }}</p>
                </div>
            </div>
        </div>

        @if($leaveRequest->reason)
        <div class="mb-3">
            <label class="form-label fw-bold">@lang('payroll::messages.reason')</label>
            <p class="form-control-plaintext">{{ nl2br(e($leaveRequest->reason)) }}</p>
        </div>
        @endif

        @if($leaveRequest->approver)
        <hr>
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label fw-bold">@lang('payroll::messages.approved-by')</label>
                    <p class="form-control-plaintext">{{ $leaveRequest->approver->{'name_' . app()->getLocale()} }}</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label fw-bold">@lang('payroll::messages.approval-date')</label>
                    <p class="form-control-plaintext">{{ $leaveRequest->approved_at?->format('d-m-Y H:i') }}</p>
                </div>
            </div>
            @if($leaveRequest->approval_remarks)
            <div class="col-md-12">
                <div class="mb-3">
                    <label class="form-label fw-bold">@lang('payroll::messages.approval-remarks')</label>
                    <p class="form-control-plaintext">{{ nl2br(e($leaveRequest->approval_remarks)) }}</p>
                </div>
            </div>
            @endif
        </div>
        @endif

        <div class="d-flex gap-2 mt-3">
            @if ($leaveRequest->isPending())
                <a href="{{ route('payroll.leave-requests.edit', $leaveRequest->id) }}" class="btn btn-sm btn-primary">
                    @lang('messages.edit')
                </a>
                <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#approveModal">
                    @lang('payroll::messages.approve')
                </button>
                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                    @lang('payroll::messages.reject')
                </button>
                <form method="POST" action="{{ route('payroll.leave-requests.destroy', $leaveRequest->id) }}" class="d-inline-block delete-form">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-sm btn-alt-secondary btn-delete">
                        @lang('messages.delete')
                    </button>
                </form>
            @endif
            <a href="{{ route('payroll.leave-requests.index') }}" class="btn btn-sm btn-alt-secondary">
                @lang('messages.go-to-list')
            </a>
        </div>
    </div>
</div>

<!-- Approve Modal -->
@if ($leaveRequest->isPending())
<div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approveModalLabel">@lang('payroll::messages.approve-leave-request')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('payroll.leave-requests.approve', $leaveRequest->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="approval_remarks" class="form-label">@lang('payroll::messages.approval-remarks')</label>
                        <textarea name="approval_remarks" id="approval_remarks" class="form-control" rows="3" placeholder="Optional remarks..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('messages.cancel')</button>
                    <button type="submit" class="btn btn-success">@lang('payroll::messages.approve')</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectModalLabel">@lang('payroll::messages.reject-leave-request')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('payroll.leave-requests.reject', $leaveRequest->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="rejection_remarks" class="form-label">@lang('payroll::messages.rejection-reason')</label>
                        <textarea name="approval_remarks" id="rejection_remarks" class="form-control" rows="3" placeholder="Provide reason for rejection..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('messages.cancel')</button>
                    <button type="submit" class="btn btn-danger">@lang('payroll::messages.reject')</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDeleteModalLabel">@lang('messages.confirm-delete')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @lang('messages.are-you-sure-delete')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('messages.cancel')</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">@lang('messages.delete')</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteButton = document.querySelector('.btn-delete');
        let currentForm = null;

        if (deleteButton) {
            deleteButton.addEventListener('click', function () {
                currentForm = this.closest('form');
                const modal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
                modal.show();
            });
        }

        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
        if (confirmDeleteBtn) {
            confirmDeleteBtn.addEventListener('click', function () {
                if (currentForm) {
                    currentForm.submit();
                }
            });
        }
    });
</script>
@endsection
