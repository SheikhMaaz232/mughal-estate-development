<?php

namespace App\Http\Controllers\Registration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Registration\StoreRoadCategoryRequest;
use App\Http\Requests\Registration\UpdateRoadCategoryRequest;
use App\Models\RoadCategory;

class RoadCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roadCategories = RoadCategory::latest()->paginate(10);
        return view('registration.road-categories.index', compact('roadCategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('registration.road-categories.create');

    }

    /**
     * Store a newly created resource in storage.
     */
       public function store(StoreRoadCategoryRequest $request)
    {
        RoadCategory::create($request->all());

        return redirect()->route('road-categories.index')
            ->with('success', __('messages.record-saved'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RoadCategory $roadCategory)
    {
        return view('registration.road-categories.edit', compact('roadCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoadCategoryRequest $request, RoadCategory $roadCategory)
    {
        $roadCategory->update($request->all());

        return redirect()->route('road-categories.index')
            ->with('success', __('messages.record-updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RoadCategory $roadCategory)
    {
        $roadCategory->delete();

        return redirect()->route('road-categories.index')
            ->with('success', __('messages.record-deleted'));
    }
}
