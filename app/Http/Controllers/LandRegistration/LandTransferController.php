<?php

namespace App\Http\Controllers\LandRegistration;

use App\Http\Controllers\Controller;
use App\Http\Requests\LandRegistration\StoreLandTransferRequest;
use App\Models\LandTransfer;
use App\Http\Requests\LandTransferRequest;
use App\Services\LandTransferService;
use Illuminate\Http\Request;

class LandTransferController extends Controller
{
    protected $landTransferService;

    public function __construct(LandTransferService $landTransferService)
    {
        $this->landTransferService = $landTransferService;
    }

    public function index()
    {
        $landTransfers = $this->landTransferService->getAllTransfers();
        $registryTypes = $this->landTransferService->getRegistryTypes();

        return view('lands.land-transfers.index', compact('landTransfers', 'registryTypes'));
    }

    public function create(Request $request)
    {
        $landId = $request->get('land_id');
        $formData = $this->landTransferService->getFormData($landId);

        return view('lands.land-transfers.create', $formData);
    }

    public function store(StoreLandTransferRequest $request)
    {
        try {
            $landTransfer = $this->landTransferService->createTransfer($request);
            return redirect()->route('lands.index')
                ->with('success', __('messages.record-saved'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error creating land transfer: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(LandTransfer $landTransfer)
    {
        $landTransfer->load(['land', 'registryType', 'purchaserAccount', 'sellerAccount']);
        return view('lands.land-transfers.show', compact('landTransfer'));
    }

    public function edit(LandTransfer $landTransfer)
    {
        $formData = $this->landTransferService->getFormData($landTransfer->land_id);
        return view('lands.land-transfers.edit', compact('landTransfer') + $formData);
    }

    public function update(StoreLandTransferRequest $request, LandTransfer $landTransfer)
    {
        try {
            $this->landTransferService->updateTransfer($landTransfer, $request);
            return redirect()->route('land-transfers.index')
                ->with('success', __('messages.record-updated'));
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error updating land transfer: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(LandTransfer $landTransfer)
    {
        try {
            $this->landTransferService->deleteTransfer($landTransfer);
            return redirect()->route('land-transfers.index')
                ->with('success', __('messages.record-deleted'));
        } catch (\Exception $e) {
            return redirect()->route('land-transfers.index')
                ->with('error', 'Error deleting land transfer: ' . $e->getMessage());
        }
    }
}
