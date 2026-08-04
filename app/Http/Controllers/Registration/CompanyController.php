<?php

namespace App\Http\Controllers\Registration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Registration\StoreCompanyRequest;
use App\Http\Requests\Registration\UpdateCompanyRequest;
use App\Models\Company;
use App\Models\Group;
use App\Services\CommonService;
use App\Services\CompanyService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    protected $commonService;

    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }

    public function index()
    {
        $groups = Cache::remember('groups_data', 3600, function () {
            return Group::select('id', 'name_en', 'name_ur')->get();
        });

        $companies = Company::latest()->paginate(10);

        return view('registration.companies.index', compact('companies', 'groups'));
    }

    public function create()
    {
        return view('registration.companies.create');
    }

    public function store(StoreCompanyRequest $request)
    {
        $data = $request->all();
        $group = app(CompanyService::class)->create($data, $request->file('image'));
        return redirect()->route('companies.index')->with('success', __('messages.record-saved'));
    }

    public function show(Company $company)
    {
        return view('registration.companies.show', compact('company'));
    }

    public function edit($id)
    {
        $groups = Cache::remember('groups_data', 3600, function () {
            return Group::select('id', 'name_en', 'name_ur')->get();
        });
        $company = Company::findOrFail($id);
        return view('registration.companies.edit', compact('company', 'groups'));
    }

    public function update(UpdateCompanyRequest $request, Company $company)
    {
        $data = $request->validated();

        if ($company->logo && Storage::exists($company->logo)) {
            Storage::delete($company->logo);
        }

        // Upload new image if present
        if ($request->hasFile('logo')) {
            $data['logo'] = $this->commonService->uploadImage($request->file('logo'), 'company_images');
        }
        $company->update($data);

        return redirect()->route('companies.index')
            ->with('success', __('messages.record-saved'));
    }

    public function destroy(Company $company)
    {
        $company->delete();

        return redirect()->route('companies.index')
            ->with('success', __('messages.record-saved'));
    }
}
