<?php

namespace App\Http\Controllers\LandRegistration;

use App\Http\Controllers\Controller;
use App\Http\Requests\LandRegistration\StoreLandRequest;
use App\Http\Requests\LandRegistration\UpdateLandRequest;
use App\Models\Land;
use App\Services\LandService;
use Illuminate\Http\Request;

class LandController extends Controller
{
    protected $service;

    public function __construct(LandService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $query = Land::with('details');

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('commission_amount', 'like', "%{$searchTerm}%")
                ->orWhere('land_amount', 'like', "%{$searchTerm}%")
                ->orWhere('remarks', 'like', "%{$searchTerm}%")
                ->orWhereHas('details', function($detailQuery) use ($searchTerm) {
                    $detailQuery->where('khawat_no', 'like', "%{$searchTerm}%")
                                ->orWhere('fard_id_no', 'like', "%{$searchTerm}%")
                                ->orWhere('registry_no', 'like', "%{$searchTerm}%")
                                ->orWhere('moza', 'like', "%{$searchTerm}%")
                                ->orWhere('remarks', 'like', "%{$searchTerm}%");
                });
            });
        }

        // Filter by project
        if ($request->has('project_id') && !empty($request->project_id)) {
            $query->where('project_id', $request->project_id);
        }

        // Date range filter
        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $landRegistrations = $query->latest()->paginate(15);

        // Get projects for filter dropdown
        $projects = \App\Models\Project::all()->pluck('name_en', 'id');

        return view('lands.index', compact('landRegistrations', 'projects'));
    }

    public function create()
    {
        $partyAccounts = $this->service->getPartyAccounts();
        $sellerAccounts = $this->service->getPartyAccounts();
        $commissionAccounts = $this->service->getPartyAccounts();

        return view('lands.create', [
            'land' => null,
            'details' => [],
            'method' => 'create',
            'buyerAccounts' => $partyAccounts,
            'sellerAccounts' => $sellerAccounts,
            'commissionAccounts' => $commissionAccounts
        ]);
    }

    public function store(StoreLandRequest $request)
    {
        $data = $request->all();
        $land = $this->service->create($data);

        return redirect()->route('lands.show', $land)->with('success', __('messages.record-saved'));
    }

    public function show(Land $land)
    {
        $land->load('details');
        return view('lands.show', compact('land'));
    }

    public function edit(Land $land)
    {
        $partyAccounts = $this->service->getPartyAccounts();
        $sellerAccounts = $this->service->getPartyAccounts();
        $commissionAccounts = $this->service->getPartyAccounts();
        $land->load('details');
        // dd($land);
        return view('lands.edit', [
            'land' => $land,
            'details' => $land->details,
            'method' => 'edit',
            'buyerAccounts' => $partyAccounts,
            'sellerAccounts' => $sellerAccounts,
            'commissionAccounts' => $commissionAccounts
        ]);
    }

    public function update(UpdateLandRequest $request, Land $land)
    {
        $data = $request->all();
        $this->service->update($land, $data);

        return redirect()->route('lands.show', $land)->with('success', __('messages.record-updated'));
    }

    public function destroy(Land $land)
    {
        $this->service->delete($land);
        return redirect()->route('lands.index')->with('success', __('messages.record-deleted'));
    }
}
