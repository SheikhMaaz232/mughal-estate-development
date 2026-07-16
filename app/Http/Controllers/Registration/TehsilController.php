<?php

namespace App\Http\Controllers\Registration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Registration\StoreTehsilRequest;
use App\Http\Requests\Registration\UpdateTehsilRequest;
use App\Models\Tehsil;

class TehsilController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tehsils = Tehsil::latest()->paginate(10);
        return view('registration.tehsils.index', compact('tehsils'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('registration.tehsils.create');

    }

    /**
     * Store a newly created resource in storage.
     */
       public function store(StoreTehsilRequest $request)
    {
        Tehsil::create($request->all());

        return redirect()->route('tehsils.index')
            ->with('success', __('messages.record-saved'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tehsil $tehsil)
    {
        return view('registration.tehsils.edit', compact('tehsil'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTehsilRequest $request, Tehsil $tehsil)
    {
        $tehsil->update($request->all());

        return redirect()->route('tehsils.index')
            ->with('success', __('messages.record-updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tehsil $tehsil)
    {
        $tehsil->delete();

        return redirect()->route('tehsils.index')
            ->with('success', __('messages.record-deleted'));
    }
}
