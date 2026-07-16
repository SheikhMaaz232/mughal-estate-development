<?php

namespace Modules\Payroll\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Registration\UpdateDepartmentRequest;
use Modules\Payroll\App\Models\Designation;
use Modules\Payroll\App\Http\Requests\StoreDesignationRequest;

class DesignationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $designations = Designation::latest()->get();
        return view('payroll::registration.designations.index', compact('designations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('payroll::registration.designations.create');

    }

    /**
     * Store a newly created resource in storage.
     */
       public function store(StoreDesignationRequest $request)
        {
            Designation::create($request->all());

            return redirect()->route('payroll.designations.index')
                ->with('success', __('messages.record-saved'));
        }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Designation $designation)
    {
        return view('payroll::registration.designations.edit', compact('designation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDepartmentRequest $request, Designation $designation)
    {
        $designation->update($request->all());

        return redirect()->route('payroll.designations.index')
            ->with('success', __('messages.record-updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Designation $designation)
    {
        $designation->delete();

        return redirect()->route('payroll.designations.index')
            ->with('success', __('messages.record-deleted'));
    }
}
