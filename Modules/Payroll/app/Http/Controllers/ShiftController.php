<?php

namespace Modules\Payroll\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Payroll\App\Http\Requests\StoreShiftRequest;
use Modules\Payroll\App\Http\Requests\UpdateShiftRequest;
use Modules\Payroll\App\Models\Shift;

class ShiftController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $shifts = Shift::when($search, function ($query, $search) {
            return $query->where(function ($query) use ($search) {
                $query->where('shift_name', 'like', '%' . $search . '%');
            });
        })
        ->latest()
        ->paginate(5)
        ->appends(['search' => $search]);

        return view('payroll::registration.shifts.index', compact('shifts'));
    }

    public function create()
    {
        return view('payroll::registration.shifts.create');
    }

    public function store(StoreShiftRequest $request)
    {
        $data = $request->except('_token');
        Shift::create($data);

        return redirect()->route('payroll.shifts.index')
            ->with('success', __('messages.record-saved'));
    }

    public function edit(Shift $shift)
    {
        return view('payroll::registration.shifts.edit', compact('shift'));
    }

    public function update(UpdateShiftRequest $request, Shift $shift)
    {
        $shift->update($request->all());

        return redirect()->route('payroll.shifts.index')
            ->with('success', __('payroll::messages.record-updated'));
    }

    public function destroy(Shift $shift)
    {
        $shift->delete();

        return redirect()->route('payroll.shifts.index')
            ->with('success', __('payroll::messages.record-deleted'));
    }
}
