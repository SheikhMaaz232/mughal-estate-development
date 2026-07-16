<?php

namespace Modules\Payroll\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Payroll\App\Models\AttendanceDevice;

class AttendanceDeviceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $devices = AttendanceDevice::latest()->paginate(10);

        return view('payroll::registration.devices.index', compact('devices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('payroll::registration.devices.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name_en' => 'required',
            'name_ur' => 'required',
            'ip_address' => 'required',
        ]);

        AttendanceDevice::create([
            'name_en' => $request->name_en,
            'name_ur' => $request->name_ur,
            'ip_address' => $request->ip_address,
            'port' => $request->port ?? 4370,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('payroll.devices.index')->with('success', __('payroll::messages.created'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $device = AttendanceDevice::findOrFail($id);
        return view('payroll::registration.devices.edit', compact('device'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $device = AttendanceDevice::findOrFail($id);

        $device->update([
            'name_en' => $request->name_en,
            'name_ur' => $request->name_ur,
            'ip_address' => $request->ip_address,
            'port' => $request->port ?? 4370,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('payroll.devices.index')->with('success', __('payroll::messages.updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        AttendanceDevice::findOrFail($id)->delete();
        return back()->with('success', __('payroll::messages.deleted'));
    }
}
