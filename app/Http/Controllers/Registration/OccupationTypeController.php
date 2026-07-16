<?php

namespace App\Http\Controllers\Registration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Registration\StoreOccupationTypeRequest;
use App\Http\Requests\Registration\UpdateOccupationTypeRequest;
use App\Models\OccupationType;

class OccupationTypeController extends Controller
{
    public function index()
    {
        $occupationTypes = OccupationType::latest()->paginate(10);
        return view('registration.occupation-types.index', compact('occupationTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('registration.occupation-types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOccupationTypeRequest $request)
    {
        OccupationType::create($request->all());

        return redirect()->route('occupation-types.index')
            ->with('success',  __('messages.record-saved'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OccupationType $occupationType)
    {
        return view('registration.occupation-types.edit', compact('occupationType'));
    }

    /**
     * Update the specified resource in storage.
     */
   public function update(UpdateOccupationTypeRequest $request, OccupationType $occupationType)
    {
        $occupationType->update($request->all());

        return redirect()->route('occupation-types.index')
            ->with('success', __('messages.record-updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OccupationType $occupationType)
    {
        $occupationType->delete();

        return redirect()->route('occupation-types.index')
            ->with('success', __('messages.record-deleted'));
    }
}
