<?php

namespace App\Http\Controllers\LandPurchase;

use App\Http\Controllers\Controller;
use App\Http\Requests\LandPurchase\RegistryTypeStoreRequest;
use App\Http\Requests\LandPurchase\RegistryTypeUpdateRequest;
use App\Models\RegistryType;
use Illuminate\Http\Request;

class RegistryTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $registryTypes = RegistryType::latest()->paginate(10);
        return view('land-purchase.registry-types.index', compact('registryTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('land-purchase.registry-types.create');

    }

    /**
     * Store a newly created resource in storage.
     */
       public function store(RegistryTypeStoreRequest $request)
    {
        RegistryType::create($request->except('_token'));

        return redirect()->route('registry-types.index')
            ->with('success', __('messages.record-saved'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RegistryType $registryType)
    {
        return view('land-purchase.registry-types.edit', compact('registryType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RegistryTypeUpdateRequest $request, RegistryType $registryType)
    {
        $registryType->update($request->all());

        return redirect()->route('registry-types.index')
            ->with('success', __('messages.record-updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RegistryType $registryType)
    {
        $registryType->delete();

        return redirect()->route('registry-types.index')
            ->with('success', __('messages.record-deleted'));
    }
}
