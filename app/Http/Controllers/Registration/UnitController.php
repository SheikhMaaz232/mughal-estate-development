<?php

namespace App\Http\Controllers\Registration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Registration\StoreUnitRequest;
use App\Http\Requests\Registration\UpdateUnitRequest;
use App\Models\Unit;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $units = Unit::latest()->paginate(10);
        return view('registration.units.index', compact('units'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('registration.units.create');

    }

    /**
     * Store a newly created resource in storage.
     */
       public function store(StoreUnitRequest $request)
    {
        Unit::create($request->all());

        return redirect()->route('units.index')
            ->with('success', __('messages.record-saved'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Unit $unit)
    {
        return view('registration.units.edit', compact('unit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUnitRequest $request, Unit $unit)
    {
        $unit->update($request->all());

        return redirect()->route('units.index')
            ->with('success', __('messages.record-updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Unit $unit)
    {
        $unit->delete();

        return redirect()->route('units.index')
            ->with('success', __('messages.record-deleted'));
    }
}
