<?php

namespace App\Services;

use App\Models\Project;
use App\Models\UnitRegistration;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;

class UnitRegistrationService
{
    protected $commonService;

    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }

    public function getAll($perPage = 10)
    {
        return UnitRegistration::with('coveringUnit', 'volumeUnit', 'road', 'product', 'project', 'company')->latest()->paginate($perPage);
    }

    public function getById($id)
    {
        return UnitRegistration::findOrFail($id);
    }

    public function create(array $data, $image = null): UnitRegistration
    {
        if ($image) {
            $data['image'] = $this->commonService->uploadImage($image, 'unitRegistrations');
        }

        return UnitRegistration::create($data);
    }

    public function update(int $id, array $data, $image = null): UnitRegistration
    {
        $unit = UnitRegistration::findOrFail($id);

        if ($image) {
            // Optionally delete the old image
            if ($unit->image && Storage::disk('public')->exists($unit->image)) {
                Storage::disk('public')->delete($unit->image);
            }

            // Upload and store new image
            $data['image'] = $this->commonService->uploadImage($image, 'unitRegistrations');
        }

        $unit->update($data);

        return $unit;
    }

    public function delete($id)
    {
        $unitRegistration = UnitRegistration::findOrFail($id);
        return $unitRegistration->delete();
    }

    public function getProjectData($projectId)
    {
        return Project::find($projectId); // No need for relationships
    }
}
