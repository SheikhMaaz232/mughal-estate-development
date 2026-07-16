<?php

namespace App\Services;

use App\Http\Requests\LandRegistration\StoreLandTransferRequest;
use App\Models\LandTransfer;
use App\Http\Requests\LandTransferRequest;
use App\Models\DetailAccount;
use App\Models\Land;
use App\Models\RegistryType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class LandTransferService
{
    public function getAllTransfers()
    {
        return LandTransfer::with(['land', 'registryType', 'purchaserAccount', 'sellerAccount'])
            ->latest()
            ->paginate(15);
    }

    public function getTransferById($id)
    {
        return LandTransfer::with(['land', 'registryType', 'purchaserAccount', 'sellerAccount'])->findOrFail($id);
    }

    public function createTransfer(StoreLandTransferRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $data = $request->all();
            // Handle file uploads
            $data = $this->handleFileUploads($data, $request);

            return LandTransfer::create($data);
        });
    }

    public function updateTransfer(LandTransfer $landTransfer, StoreLandTransferRequest $request)
    {
        return DB::transaction(function () use ($landTransfer, $request) {
            $data = $request->validated();

            // Handle file uploads
            $data = $this->handleFileUploads($data, $request, $landTransfer);

            $landTransfer->update($data);

            return $landTransfer;
        });
    }

    public function deleteTransfer(LandTransfer $landTransfer)
    {
        return DB::transaction(function () use ($landTransfer) {
            // Delete associated files
            $this->deleteFiles($landTransfer);

            return $landTransfer->delete();
        });
    }

    private function handleFileUploads(array $data, StoreLandTransferRequest $request, ?LandTransfer $landTransfer = null): array
    {
        $imageFields = ['attachment_1', 'attachment_2', 'attachment_3'];

        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {
                // Delete old file if exists
                if ($landTransfer && $landTransfer->$field) {
                    Storage::disk('public')->delete('land-transfers/' . $landTransfer->$field);
                }

                // Store new file
                $file = $request->file($field);
                $fileName = time() . '_' . $field . '.' . $file->getClientOriginalExtension();

                // Store using public disk explicitly
                $file->storeAs('land-transfers', $fileName, 'public');
                $data[$field] = $fileName;
            } elseif ($landTransfer && !$request->has($field)) {
                // Keep existing file if not updating
                $data[$field] = $landTransfer->$field;
            }
        }

        return $data;
    }

    private function deleteFiles(LandTransfer $landTransfer): void
    {
        $imageFields = ['attachment_1', 'attachment_2', 'attachment_3'];

        foreach ($imageFields as $field) {
            if (in_array($field, $imageFields, true) && $landTransfer->$field) {
                Storage::delete('public/land-transfers/' . basename($landTransfer->$field));
            }
        }
    }

    public function getFormData($landId = null)
    {
        $registryTypes = RegistryType::select('title_en','title_ur', 'id')->get();
        $purchaserAccounts = DetailAccount::whereIn('main_head_id', [1,2,4])->whereNull('deleted_at')
            ->orderBy('name_en')->get(['name_en','name_ur', 'id']);
        $sellerAccounts = DetailAccount::whereIn('main_head_id', [1,2,4])->whereNull('deleted_at')
            ->orderBy('name_en')->get(['name_en','name_ur', 'id']);

        $landData = [];
        if ($landId) {
            $land = Land::with('details')->find($landId);
            if ($land) {
                $landData = [
                    'fard_no' => $land->details->first()->fard_id_no ?? '',
                    'khawat_no' => $land->details->first()->khawat_no ?? '',
                ];
            }
        }

        return compact('registryTypes', 'purchaserAccounts', 'sellerAccounts', 'landData');
    }

    public function getRegistryTypes()
    {
        return RegistryType::orderBy('title_en')
            ->get('title_en','title_ur', 'id');
    }
}
