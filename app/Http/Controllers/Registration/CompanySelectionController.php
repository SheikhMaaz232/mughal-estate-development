<?php

namespace App\Http\Controllers\Registration;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Company;

class CompanySelectionController extends Controller
{
    public function showForm()
    {
        $companies = Company::select('id', 'name_en', 'name_ur')->get();
        return view('auth.select_company', compact('companies'));
    }

    public function storeSelection(Request $request)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id',
        ], [
            'company_id.required' => 'Please select a company.',
            'company_id.exists' => 'The selected company is invalid.',
        ]);

        // Save selected company in session
        session(['selected_company_id' => $request->company_id]);

        // Redirect to the originally intended URL or dashboard
        $redirectTo = session()->pull('url.intended_after_company', route('dashboard'));

        return redirect()->to($redirectTo);
    }
}
