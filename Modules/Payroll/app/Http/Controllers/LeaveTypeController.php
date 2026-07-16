<?php

namespace Modules\Payroll\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Payroll\App\Http\Requests\StoreLeaveTypesRequest;
use Modules\Payroll\App\Http\Requests\UpdateLeaveTypesRequest;
use Modules\Payroll\App\Models\LeaveType;

class LeaveTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $leaveTypes = LeaveType::latest()->paginate(10);
        return view('payroll::registration.leave-types.index', compact('leaveTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('payroll::registration.leave-types.create');

    }

    /**
     * Store a newly created resource in storage.
     */
       public function store(StoreLeaveTypesRequest $request)
        {
            LeaveType::create($request->all());

            return redirect()->route('payroll.leave-types.index')
                ->with('success', __('messages.record-saved'));
        }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LeaveType $leaveType)
    {
        return view('payroll::registration.leave-types.edit', compact('leaveType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLeaveTypesRequest $request, LeaveType $leaveType)
    {
        $leaveType->update($request->all());

        return redirect()->route('payroll.leave-types.index')
            ->with('success', __('messages.record-updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LeaveType $leaveType)
    {
        $leaveType->delete();

        return redirect()->route('payroll.leave-types.index')
            ->with('success', __('messages.record-deleted'));
    }
}
