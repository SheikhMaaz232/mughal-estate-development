<?php

namespace App\Http\Controllers\ConstructionModule;

use App\Http\Controllers\Controller;
use App\Http\Requests\ConstructionModule\StoreConstructionSiteRequest;
use App\Http\Requests\ConstructionModule\UpdateConstructionSiteRequest;
use App\Services\ConstructionSiteService;
use App\Models\ConstructionSite;
use App\Models\Company;
use App\Models\Project;
use App\Models\Party;

class ConstructionSiteController extends Controller
{
    protected $service;

    public function __construct(ConstructionSiteService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $sites = ConstructionSite::query()
            ->applyFilters(request()->all())
            ->latest()
            ->with('company', 'project', 'party')->paginate(10);

        return view('Construction-Module.construction-site.index', compact('sites'));
    }

    public function create()
    {
        $companies = Company::all();
        $projects = Project::all();
        $parties = Party::all();

        return view('Construction-Module.construction-site.create', compact('companies', 'projects', 'parties'));
    }

    public function store(StoreConstructionSiteRequest $request)
    {
        $this->service->create($request->validated());

        return redirect()->route('construction-sites.index')
            ->with('success', __('messages.construction_site_created_successfully'));
    }

    public function show($id)
    {
        $site = ConstructionSite::findOrFail($id);
        return view('Construction-Module.construction-site.show', compact('site'));
    }

    public function edit($id)
    {
        $site = ConstructionSite::findOrFail($id);
        $companies = Company::all();
        $projects = Project::all();
        $parties = Party::all();

        return view('Construction-Module.construction-site.edit', compact('site', 'companies', 'projects', 'parties'));
    }

    public function update(UpdateConstructionSiteRequest $request, $id)
    {
        $this->service->update($request->validated(), $id);

        return redirect()->route('construction-sites.index')
            ->with('success', __('messages.construction_site_updated_successfully'));
    }

    public function destroy($id)
    {
        $this->service->delete($id);

        return redirect()->route('construction-sites.index')
            ->with('success', __('messages.construction_site_deleted_successfully'));
    }
}
