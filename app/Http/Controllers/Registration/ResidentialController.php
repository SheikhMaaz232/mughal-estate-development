<?php

namespace App\Http\Controllers\Registration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Registration\StoreResidentialRequest;
use App\Http\Requests\Registration\UpdateResidentialRequest;
use App\Models\Residential;

class ResidentialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $residentials = Residential::latest()->paginate(10);
        return view('registration.residentials.index', compact('residentials'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('registration.residentials.create');

    }

    /**
     * Store a newly created resource in storage.
     */
       public function store(StoreResidentialRequest $request)
    {
        Residential::create($request->all());

        return redirect()->route('residentials.index')
            ->with('success', __('messages.record-saved'));
    }
    /**
     * Display the specified resource.
     */
    public function show(Residential $residential)
    {
        return view('registration.residentials.show', compact('residential'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Residential $residential)
    {
        return view('registration.residentials.edit', compact('residential'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateResidentialRequest $request, Residential $residential)
    {
        $residential->update($request->all());

        return redirect()->route('residentials.index')
            ->with('success', __('messages.record-updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Residential $residential)
    {
        $residential->delete();

        return redirect()->route('residentials.index')
            ->with('success', __('messages.record-deleted'));
    }
}
