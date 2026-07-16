<?php

namespace Modules\Payroll\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Payroll\App\Http\Requests\StoreGradeRequest;
use Modules\Payroll\App\Http\Requests\UpdateGradeRequest;
use Modules\Payroll\App\Models\Grade;

class GradeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $grades = Grade::latest()->paginate(10);
        return view('payroll::registration.grades.index', compact('grades'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('payroll::registration.grades.create');

    }

    /**
     * Store a newly created resource in storage.
     */
       public function store(StoreGradeRequest $request)
        {
            Grade::create($request->all());

            return redirect()->route('payroll.grades.index')
                ->with('success', __('messages.record-saved'));
        }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Grade $grade)
    {
        return view('payroll::registration.grades.edit', compact('grade'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGradeRequest $request, Grade $grade)
    {
        $grade->update($request->all());

        return redirect()->route('payroll.grades.index')
            ->with('success', __('messages.record-updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Grade $grade)
    {
        $grade->delete();

        return redirect()->route('payroll.grades.index')
            ->with('success', __('messages.record-deleted'));
    }
}
