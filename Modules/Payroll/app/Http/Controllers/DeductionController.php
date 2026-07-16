<?php

namespace Modules\Payroll\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Payroll\App\Http\Requests\StoreDeductionRequest;
use Modules\Payroll\App\Http\Requests\UpdateDeductionRequest;
use Modules\Payroll\App\Models\Deduction;

class DeductionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $deductions = Deduction::latest()->paginate(10);
        return view('payroll::registration.deductions.index', compact('deductions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('payroll::registration.deductions.create');

    }

    /**
     * Store a newly created resource in storage.
     */
       public function store(StoreDeductionRequest $request)
        {
            Deduction::create($request->all());

            return redirect()->route('payroll.deductions.index')
                ->with('success', __('messages.record-saved'));
        }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Deduction $deduction)
    {
        return view('payroll::registration.deductions.edit', compact('deduction'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDeductionRequest $request, Deduction $deduction)
    {
        $deduction->update($request->all());

        return redirect()->route('payroll.deductions.index')
            ->with('success', __('messages.record-updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Deduction $deduction)
    {
        $deduction->delete();

        return redirect()->route('payroll.deductions.index')
            ->with('success', __('messages.record-deleted'));
    }
}
