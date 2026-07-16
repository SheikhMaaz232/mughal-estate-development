<?php

namespace Modules\Payroll\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Payroll\App\Http\Requests\StoreQualificationRequest;
use Modules\Payroll\App\Http\Requests\UpdateQualificationRequest;
use Modules\Payroll\App\Models\Qualification;

class QualificationController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $qualifications = Qualification::when($search, function ($query, $search) {
            return $query->where(function ($query) use ($search) {
                $query->where('name_en', 'like', '%' . $search . '%')
                    ->orWhere('name_ur', 'like', '%' . $search . '%');
            });
        })
        ->latest()
        ->paginate(5)
        ->appends(['search' => $search]); // This preserves search in pagination links

        return view('payroll::registration.qualifications.index', compact('qualifications'));
    }

    public function create()
    {
        return view('payroll::registration.qualifications.create');
    }

    public function store(StoreQualificationRequest $request)
    {
        $data = $request->except('_token');
        Qualification::create($data);

        return redirect()->route('payroll.qualifications')
            ->with('success', __('messages.record-saved'));
    }

    public function edit(Qualification $qualification)
    {
        return view('payroll::registration.qualifications.edit', compact('qualification'));
    }

    public function update(UpdateQualificationRequest $request, Qualification $qualification)
    {
        $qualification->update($request->all());

        return redirect()->route('payroll.qualifications')
            ->with('success', 'Qualification updated successfully');
    }

    public function destroy(Qualification $qualification)
    {
        $qualification->delete();

        return redirect()->route('payroll.qualifications')
            ->with('success', __('payroll::messages.record-deleted'));
    }
}
