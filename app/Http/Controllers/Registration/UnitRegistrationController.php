<?php

namespace App\Http\Controllers\Registration;

use App\Models\Product;
use App\Http\Controllers\Controller;
use App\Services\UnitRegistrationService;
use App\Http\Requests\Registration\StoreUnitRegistrationRequest;

class UnitRegistrationController extends Controller
{
    protected $unitRegistrationService;

    public function __construct(UnitRegistrationService $unitRegistrationService)
    {
        $this->unitRegistrationService = $unitRegistrationService;
    }

    /**
     * Display a listing of registered units.
     */
    public function index()
    {
        $registeredUnits = $this->unitRegistrationService->getAll();

        return view('registration.unit_registrations.index', compact('registeredUnits'));
    }

    /**
     * Show the form for creating a new registered unit.
     */
    public function create()
    {
        return view('registration.unit_registrations.create');
    }

    /**
     * Store a newly created registered unit in storage.
     */
    public function store(StoreUnitRegistrationRequest $request)
    {

        try {
            $data = $request->all();
            app(UnitRegistrationService::class)->create($data, $request->file('image'));

            return redirect()->route('unitRegistration.index')->with('success', __('messages.record-saved'));
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', __('messages.unexpected-error'));
        }
    }

    /**
     * Show the form for editing the specified registered unit.
     */
    public function edit($id)
    {
        try {
        $registeredUnit = $this->unitRegistrationService->getById($id);

        return view('registration.unit_registrations.edit', compact('registeredUnit'));
        } catch (\Exception $e) {
            return redirect()->route('unitRegistration.index')->with('error', __('messages.unexpected-error'));
        }
    }

    /**
     * Update the specified registered unit in storage.
     */
    public function update(StoreUnitRegistrationRequest $request, $id)
    {
        try {
            $data = $request->validated(); // Or use $request->all() if needed
            $image = $request->file('image'); // Get uploaded image if present

            $this->unitRegistrationService->update($id, $data, $image);

            return redirect()->route('unitRegistration.index')->with('success', __('messages.record-updated'));
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', __('messages.unexpected-error'));
        }
    }

    /**
     * Remove the specified registered unit from storage.
     */
    public function destroy($id)
    {
        try {
            $this->unitRegistrationService->delete($id);

            return redirect()->route('unitRegistration.index')->with('success', __('messages.record-deleted'));
        } catch (\Exception $e) {
            return redirect()->route('unitRegistration.index')->with('error', __('messages.unexpected-error'));
        }
    }

    public function getProjectInformation($projectId)
    {
        $project = $this->unitRegistrationService->getProjectData($projectId);

        if ($project) {
            return response()->json([
                'status' => 'success',
                'data' => [
                    'phase_en'   => $project->phase_en,
                    'phase_ur'   => $project->phase_ur,
                    'company_id' => $project->company_id,
                ]
            ]);
        }

        return response()->json(['status' => 'fail', 'data' => []]);
    }

    public function getProductInformation($productId)
    {
        $product = Product::with(['baseVolumeUnit', 'baseCoverageUnit'])->find($productId);

        if ($product) {
            return response()->json([
                'status' => 'success',
                'data' => [
                    'base_volume'           => $product->base_volume,
                    'base_coverage'         => $product->base_coverage,
                    'volume_unit_en'        => $product->baseVolumeUnit->name_en ?? '',
                    'volume_unit_ur'        => $product->baseVolumeUnit->name_ur ?? '',
                    'coverage_unit_en'      => $product->baseCoverageUnit->name_en ?? '',
                    'coverage_unit_ur'      => $product->baseCoverageUnit->name_ur ?? '',
                    'base_volume_unit_id'   => $product->base_volume_unit,
                    'base_coverage_unit_id' => $product->base_coverage_unit,
                ]
            ]);
        }

        return response()->json(['status' => 'fail', 'data' => []]);
    }
}
