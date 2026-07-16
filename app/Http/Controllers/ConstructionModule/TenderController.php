<?php

namespace App\Http\Controllers\ConstructionModule;

use App\Http\Controllers\Controller;
use App\Http\Requests\ConstructionModule\StoreTenderRequest;
use App\Http\Requests\ConstructionModule\UpdateTenderRequest;
use App\Models\ConstructionSite;
use App\Models\DetailAccount;
use App\Models\Tender;
use App\Services\TenderService;
use Illuminate\Http\Request;

class TenderController extends Controller
{
    protected $service;

    public function __construct(TenderService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $constructionSiteId = $request->id;
        $search = $request->all();

        $tendersListing = Tender::with('constructionSite')
            ->search($search)
            ->latest()
            ->paginate(10)->appends(request()->input());


        return view('Construction-Module.tender.index', compact('tendersListing', 'constructionSiteId'));
    }

    public function create(Request $request)
    {
        $site = ConstructionSite::findOrFail($request->id);

        return view('Construction-Module.tender.create', compact('site'));
    }

    public function store(StoreTenderRequest $request)
    {
        $this->service->create($request->validated());
        return redirect()->route('tenders.index')
            ->with('success', __('messages.tender-created-successfully'));
    }

    public function show($id)
    {
        $tender = Tender::findOrFail($id);
        return view('Construction-Module.tender.show', compact('tender'));
    }

    public function edit($id)
    {
        $tender = Tender::findOrFail($id);
        $constructionSites = ConstructionSite::all();
        $detailAccounts = DetailAccount::all();

        return view('Construction-Module.tender.edit', compact('tender', 'constructionSites', 'detailAccounts'));
    }

    public function update(UpdateTenderRequest $request, $id)
    {
        $this->service->update($request->validated(), $id);

        $tender = Tender::findOrFail($id);
        return redirect()->route('tenders.index', ['constructionSiteId' => $tender->construction_site_id])
            ->with('success', __('messages.tender-updated-successfully'));
    }

    public function destroy($id)
    {
        $tender = Tender::findOrFail($id);
        $siteId = $tender->construction_site_id;

        $this->service->delete($id);

        return redirect()->route('tenders.index', ['constructionSiteId' => $siteId])
            ->with('success', __('messages.tender-deleted-successfully'));
    }
}
