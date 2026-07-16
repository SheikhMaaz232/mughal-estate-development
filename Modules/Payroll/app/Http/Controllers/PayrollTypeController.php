<?php

namespace Modules\Payroll\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Payroll\App\Http\Requests\StorePayrollTypeRequest;
use Modules\Payroll\App\Http\Requests\UpdatePayrollTypeRequest;
use Modules\Payroll\App\Models\PayrollType;

class PayrollTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payrollTypes = PayrollType::latest()->paginate(10);
        return view('payroll::registration.payroll-types.index', compact('payrollTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('payroll::registration.payroll-types.create');

    }

    /**
     * Store a newly created resource in storage.
     */
       public function store(StorePayrollTypeRequest $request)
        {
            PayrollType::create($request->all());

            return redirect()->route('payroll.payroll-types.index')
                ->with('success', __('messages.record-saved'));
        }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PayrollType $payrollType)
    {
        return view('payroll::registration.payroll-types.edit', compact('payrollType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePayrollTypeRequest $request, PayrollType $payrollType)
    {
        $payrollType->update($request->all());

        return redirect()->route('payroll.payroll-types.index')
            ->with('success', __('messages.record-updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PayrollType $payrollType)
    {
        $payrollType->delete();

        return redirect()->route('payroll.payroll-types.index')
            ->with('success', __('payroll::messages.record-deleted'));
    }
}
