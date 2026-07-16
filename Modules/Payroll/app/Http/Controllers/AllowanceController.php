<?php

namespace Modules\Payroll\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Payroll\App\Models\Allowance;

class AllowanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    
        $allowancesData = Allowance::latest()->paginate(10);
        return view('payroll::registration.allowances.index', compact('allowancesData'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('payroll::registration.allowances.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Allowance::create($request->all());

        return redirect()->route('payroll.allowances.index')
            ->with('success', __('messages.record-saved'));
    }

    /**
     * Show the specified resource.
     */
    public function show(Allowance $allowance)
    {
        return view('payroll::registration.allowances.show', compact('allowance'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Allowance $allowance)
    {
        return view('payroll::registration.allowances.edit', compact('allowance'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Allowance $allowance)
    {
        $allowance->update($request->all());
        return redirect()->route('payroll.allowances.index')
            ->with('success', __('messages.record-updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Allowance $allowance)
    {
        $allowance->delete();
        return redirect()->route('payroll.allowances.index')
            ->with('success', __('messages.record-deleted'));
    }
}
