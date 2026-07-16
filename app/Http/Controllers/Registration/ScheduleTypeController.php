<?php

namespace App\Http\Controllers\Registration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Registration\StoreScheduleTypeRequest;
use App\Http\Requests\Registration\UpdateScheduleTypeRequest;
use App\Models\ScheduleType;
use Illuminate\Http\Request;

class ScheduleTypeController extends Controller
{
     /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $scheduleTypes = ScheduleType::latest()->paginate(10);
        return view('registration.schedule-types.index', compact('scheduleTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('registration.schedule-types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreScheduleTypeRequest $request)
    {
        ScheduleType::create($request->all());

        return redirect()->route('schedule-types.index')
            ->with('success', __('messages.record-saved'));
    }

    /**
     * Display the specified resource.
     */
    public function show(ScheduleType $scheduleType)
    {
        return view('registration.schedule-types.show', compact('scheduleType'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ScheduleType $scheduleType)
    {
        return view('registration.schedule-types.edit', compact('scheduleType'));
    }

    /**
     * Update the specified resource in storage.
     */
   public function update(UpdateScheduleTypeRequest $request, ScheduleType $scheduleType)
    {
        $scheduleType->update($request->all());

        return redirect()->route('schedule-types.index')
            ->with('success', __('messages.record-updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ScheduleType $scheduleType)
    {
        $scheduleType->delete();

        return redirect()->route('schedule-types.index')
            ->with('success', __('messages.record-deleted'));
    }
}
