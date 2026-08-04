<?php

namespace App\Http\Controllers\Registration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Registration\StoreTehsilRequest;
use App\Http\Requests\Registration\UpdateTehsilRequest;
use App\Models\City;
use App\Models\Tehsil;
use Illuminate\Support\Facades\Cache;

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
        $cities = Cache::remember('cities_data', 3600, function () {
            return City::select('id', 'name_en', 'name_ur')->get();
        });

        return view('registration.tehsils.create', compact('cities'));
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
        $cities = Cache::remember('cities_data', 3600, function () {
            return City::select('id', 'name_en', 'name_ur')->get();
        });

        return view('registration.tehsils.edit', compact('tehsil', 'cities'));
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
