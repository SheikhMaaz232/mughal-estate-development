<?php

namespace App\Http\Controllers\Registration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Registration\StoreRoadSpecificationRequest;
use App\Http\Requests\Registration\UpdateRoadSpecificationRequest;
use App\Models\RoadSpecification;
use Illuminate\Http\Request;

class RoadSpecificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roadSpecifications = RoadSpecification::latest()->paginate(10);
        return view('registration.road-specifications.index', compact('roadSpecifications'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('registration.road-specifications.create');

    }

    /**
     * Store a newly created resource in storage.
     */
       public function store(StoreRoadSpecificationRequest $request)
    {
        RoadSpecification::create($request->all());

        return redirect()->route('road-specifications.index')
            ->with('success', __('messages.record-saved'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RoadSpecification $roadSpecification)
    {
        return view('registration.road-specifications.edit', compact('roadSpecification'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoadSpecificationRequest $request, RoadSpecification $roadSpecification)
    {
        $roadSpecification->update($request->all());

        return redirect()->route('road-specifications.index')
            ->with('success', __('messages.record-updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RoadSpecification $roadSpecification)
    {
        $roadSpecification->delete();

        return redirect()->route('road-specifications.index')
            ->with('success', __('messages.record-deleted'));
    }
}
