<?php

namespace App\Http\Controllers\LandPurchase;

use App\Http\Controllers\Controller;
use App\Models\LandRegistration;
use App\Services\LandRegistrationService;
use App\Http\Requests\StoreLandRegistrationRequest;
use App\Http\Requests\UpdateLandRegistrationRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class LandRegistrationController extends Controller
{
    protected $landRegistrationService;

    public function __construct(LandRegistrationService $landRegistrationService)
    {
        $this->landRegistrationService = $landRegistrationService;
    }

    public function index(Request $request)
    {
        $landRegistrations = $this->landRegistrationService->getLandRegistrations($request->all());
        $projects = $this->landRegistrationService->getProjects();
        $partyAccounts = $this->landRegistrationService->getPartyAccounts();

        return view('land-purchase.land-registration.index', compact('landRegistrations', 'projects', 'partyAccounts'));
    }

    public function create()
    {
        $partyAccounts = $this->landRegistrationService->getPartyAccounts();

        return view('land-purchase.land-registration.create', compact( 'partyAccounts'));
    }

    public function store(StoreLandRegistrationRequest $request)
    {
        try {
            $landRegistration = $this->landRegistrationService->createLandRegistration($request->validated());
            return redirect()->route('land-registrations.index')
                ->with('success', 'Land registration created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error creating land registration: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(LandRegistration $landRegistration)
    {
        $landRegistration->load(['project', 'partyAccount', 'createdBy', 'updatedBy']);
        return view('land-purchase.land-registration.show', compact('landRegistration'));
    }

    public function edit(LandRegistration $landRegistration)
    {
        $projects = $this->landRegistrationService->getProjects();
        $partyAccounts = $this->landRegistrationService->getPartyAccounts();

        return view('land-purchase.land-registration.edit', compact('landRegistration', 'projects', 'partyAccounts'));
    }

    public function update(UpdateLandRegistrationRequest $request, LandRegistration $landRegistration)
    {
        try {
            $this->landRegistrationService->updateLandRegistration($landRegistration, $request->validated());
            return redirect()->route('land-registrations.index')
                ->with('success', 'Land registration updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating land registration: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(LandRegistration $landRegistration)
    {
        try {
            $landRegistration->delete();
            return redirect()->route('land-registrations.index')
                ->with('success', 'Land registration deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error deleting land registration: ' . $e->getMessage());
        }
    }

    public function calculate(Request $request): JsonResponse
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'kanal' => 'required|numeric|min:0',
            'merla' => 'required|numeric|min:0',
            'square_feet' => 'required|numeric|min:0',
        ]);

        $calculation = $this->landRegistrationService->calculateLandArea($request->all());
        return response()->json($calculation);
    }
}
