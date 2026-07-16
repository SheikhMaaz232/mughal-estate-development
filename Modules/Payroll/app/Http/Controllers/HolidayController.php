<?php

namespace Modules\Payroll\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Payroll\App\Http\Requests\StoreHolidayRequest;
use Modules\Payroll\App\Http\Requests\UpdateHolidayRequest;
use Modules\Payroll\App\Models\Holiday;
use Modules\Payroll\App\Models\HolidayType;

class HolidayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $holidays = Holiday::with('holidayType')->latest()->paginate(10);

        return view('payroll::registration.holidays.index', compact('holidays'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $holidayTypes = HolidayType::latest()->get();

        return view('payroll::registration.holidays.create', compact('holidayTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreHolidayRequest $request)
    {
        Holiday::create($request->validated());

        return redirect()->route('payroll.holidays.index')
            ->with('success', __('messages.record-saved'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Holiday $holiday)
    {
        return view('payroll::registration.holidays.show', compact('holiday'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Holiday $holiday)
    {
        $holidayTypes = HolidayType::latest()->get();

        return view('payroll::registration.holidays.edit', compact('holiday', 'holidayTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateHolidayRequest $request, Holiday $holiday)
    {
        $holiday->update($request->validated());

        return redirect()->route('payroll.holidays.index')
            ->with('success', __('messages.record-updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Holiday $holiday)
    {
        $holiday->delete();

        return redirect()->route('payroll.holidays.index')
            ->with('success', __('messages.record-deleted'));
    }
}
