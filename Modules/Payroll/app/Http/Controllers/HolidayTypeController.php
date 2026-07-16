<?php

namespace Modules\Payroll\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Payroll\App\Http\Requests\StoreHolidayTypesRequest;
use Modules\Payroll\App\Http\Requests\UpdateHolidayTypesRequest;
use Modules\Payroll\App\Models\HolidayType;

class HolidayTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $holidayTypes = HolidayType::latest()->paginate(10);
        return view('payroll::registration.holiday-types.index', compact('holidayTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('payroll::registration.holiday-types.create');

    }

    /**
     * Store a newly created resource in storage.
     */
       public function store(StoreHolidayTypesRequest $request)
        {
            HolidayType::create($request->all());

            return redirect()->route('payroll.holiday-types.index')
                ->with('success', __('messages.record-saved'));
        }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HolidayType $holidayType)
    {
        return view('payroll::registration.holiday-types.edit', compact('holidayType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateHolidayTypesRequest $request, HolidayType $holidayType)
    {
        $holidayType->update($request->all());

        return redirect()->route('payroll.holiday-types.index')
            ->with('success', __('messages.record-updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HolidayType $holidayType)
    {
        $holidayType->delete();

        return redirect()->route('payroll.holiday-types.index')
            ->with('success', __('messages.record-deleted'));
    }
}
