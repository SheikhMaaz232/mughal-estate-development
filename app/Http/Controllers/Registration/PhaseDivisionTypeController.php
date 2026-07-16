<?php

namespace App\Http\Controllers\Registration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Registration\UpdatePhaseDivisionTypeRequest;
use App\Http\Requests\Registration\StorePhaseDivisionTypeRequest;
use App\Models\PhaseDivisionType;

class PhaseDivisionTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $phaseTypes = PhaseDivisionType::latest()->paginate(10);
        return view('registration.phase_division_types.index', compact('phaseTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('registration.phase_division_types.create');

    }

    /**
     * Store a newly created resource in storage.
     */
       public function store(StorePhaseDivisionTypeRequest $request)
    {
        PhaseDivisionType::create($request->all());

        return redirect()->route('phase-types.index')
            ->with('success', __('messages.record-saved'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PhaseDivisionType $phaseType)
    {
        return view('registration.phase_division_types.edit', compact('phaseType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePhaseDivisionTypeRequest $request, PhaseDivisionType $phaseType)
    {
        $phaseType->update($request->all());

        return redirect()->route('phase-types.index')
            ->with('success', __('messages.record-updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PhaseDivisionType $phaseType)
    {
        $phaseType->delete();

        return redirect()->route('phase-types.index')
            ->with('success', __('messages.record-deleted'));
    }
}
