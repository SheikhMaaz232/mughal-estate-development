<?php

namespace Modules\Payroll\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Payroll\App\Http\Requests\StoreLeaveRequestRequest;
use Modules\Payroll\App\Http\Requests\UpdateLeaveRequestRequest;
use Modules\Payroll\App\Models\Employee;
use Modules\Payroll\App\Models\LeaveRequest;
use Modules\Payroll\App\Models\LeaveType;

class LeaveRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = LeaveRequest::with(['employee', 'leaveType', 'approver']);

        // Filter by status if provided
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Filter by employee if provided
        if ($request->has('employee_id') && $request->employee_id !== '') {
            $query->where('employee_id', $request->employee_id);
        }

        $leaveRequests = $query->latest()->paginate(10);
        $employees = Employee::orderBy('first_name_en')->get();
        $statuses = [
            'pending' => 'payroll::messages.pending',
            'approved' => 'payroll::messages.approved',
            'rejected' => 'payroll::messages.rejected'
        ];

        return view('payroll::registration.leave-requests.index', compact('leaveRequests', 'employees', 'statuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = Employee::with('department', 'designation')->orderBy('first_name_en')->get();
        $leaveTypes = LeaveType::orderBy('title_en')->get();

        return view('payroll::registration.leave-requests.create', compact('employees', 'leaveTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLeaveRequestRequest $request)
    {
        LeaveRequest::create($request->validated());

        return redirect()->route('payroll.leave-requests.index')
            ->with('success', __('messages.record-saved'));
    }

    /**
     * Display the specified resource.
     */
    public function show(LeaveRequest $leaveRequest)
    {
        $leaveRequest->load(['employee', 'leaveType', 'approver']);
        return view('payroll::registration.leave-requests.show', compact('leaveRequest'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LeaveRequest $leaveRequest)
    {
        // Only allow editing pending requests
        if (!$leaveRequest->isPending()) {
            return redirect()->route('payroll.leave-requests.index')
                ->with('error', __('messages.cannot-edit-non-pending'));
        }

        $employees = Employee::with('department', 'designation')->orderBy('first_name_en')->get();
        $leaveTypes = LeaveType::orderBy('title_en')->get();

        return view('payroll::registration.leave-requests.edit', compact('leaveRequest', 'employees', 'leaveTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLeaveRequestRequest $request, LeaveRequest $leaveRequest)
    {
        // Only allow updating pending requests
        if (!$leaveRequest->isPending()) {
            return redirect()->route('payroll.leave-requests.index')
                ->with('error', __('messages.cannot-edit-non-pending'));
        }

        $leaveRequest->update($request->validated());

        return redirect()->route('payroll.leave-requests.index')
            ->with('success', __('messages.record-updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LeaveRequest $leaveRequest)
    {
        // Only allow deleting pending requests
        if (!$leaveRequest->isPending()) {
            return redirect()->route('payroll.leave-requests.index')
                ->with('error', __('messages.cannot-delete-non-pending'));
        }

        $leaveRequest->delete();

        return redirect()->route('payroll.leave-requests.index')
            ->with('success', __('messages.record-deleted'));
    }

    /**
     * Approve a leave request.
     */
    public function approve(Request $request, LeaveRequest $leaveRequest)
    {
        if (!$leaveRequest->isPending()) {
            return redirect()->route('payroll.leave-requests.index')
                ->with('error', __('messages.cannot-approve-non-pending'));
        }

        $validated = $request->validate([
            'approval_remarks' => 'nullable|string|max:1000',
        ]);

        $leaveRequest->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approval_remarks' => $validated['approval_remarks'] ?? null,
            'approved_at' => now(),
        ]);

        // Update employee leave balance used days when a leave request is approved.
        $leaveRequest->employee->leaveBalances()
            ->firstOrCreate(
                ['leave_type_id' => $leaveRequest->leave_type_id],
                ['total_days' => 0, 'used_days' => 0]
            )
            ->increment('used_days', $leaveRequest->days);

        return redirect()->route('payroll.leave-requests.index')
            ->with('success', __('messages.leave-request-approved'));
    }

    /**
     * Reject a leave request.
     */
    public function reject(Request $request, LeaveRequest $leaveRequest)
    {
        if (!$leaveRequest->isPending()) {
            return redirect()->route('payroll.leave-requests.index')
                ->with('error', __('messages.cannot-reject-non-pending'));
        }

        $validated = $request->validate([
            'approval_remarks' => 'required|string|max:1000',
        ]);

        $leaveRequest->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'approval_remarks' => $validated['approval_remarks'],
            'approved_at' => now(),
        ]);

        return redirect()->route('payroll.leave-requests.index')
            ->with('success', __('messages.leave-request-rejected'));
    }
}
