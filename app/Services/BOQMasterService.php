<?php

namespace App\Services;

use App\Models\BOQMaster;
use App\Models\BOQDetail;

class BOQMasterService
{
    public function create(array $data): BOQMaster
    {
        // Extract detail arrays from data
        $itemIds = $data['item_id'] ?? [];
        $quantities = $data['quantity'] ?? [];
        $rates = $data['rate'] ?? [];
        $grossAmounts = $data['gross_amount'] ?? [];

        // Remove detail arrays from master data
        unset($data['item_id'], $data['quantity'], $data['rate'], $data['gross_amount']);

        // Create BOQMaster record
        $boqMaster = BOQMaster::create($data);

        // Create BOQDetail records
        foreach ($itemIds as $index => $itemId) {
            if ($itemId && isset($quantities[$index]) && isset($rates[$index])) {
                BOQDetail::create([
                    'boq_master_id' => $boqMaster->id,
                    'item_id' => $itemId,
                    'quantity' => $quantities[$index],
                    'rate' => $rates[$index],
                    'gross_amount' => $grossAmounts[$index] ?? 0,
                ]);
            }
        }

        // Return BOQMaster with details loaded
        return $boqMaster->load('details');
    }

    public function update(array $data, $id): BOQMaster
    {
        $boqMaster = BOQMaster::findOrFail($id);

        // Extract detail arrays from data
        $itemIds = $data['item_id'] ?? [];
        $quantities = $data['quantity'] ?? [];
        $rates = $data['rate'] ?? [];
        $grossAmounts = $data['gross_amount'] ?? [];

        // Remove detail arrays from master data
        unset($data['item_id'], $data['quantity'], $data['rate'], $data['gross_amount']);

        // Update BOQMaster record
        $boqMaster->update($data);

        // If detail arrays are provided, update/create details
        if (!empty($itemIds)) {
            // Delete existing details
            $boqMaster->details()->delete();

            // Create new BOQDetail records
            foreach ($itemIds as $index => $itemId) {
                if ($itemId && isset($quantities[$index]) && isset($rates[$index])) {
                    BOQDetail::create([
                        'boq_master_id' => $boqMaster->id,
                        'item_id' => $itemId,
                        'quantity' => $quantities[$index],
                        'rate' => $rates[$index],
                        'gross_amount' => $grossAmounts[$index] ?? 0,
                    ]);
                }
            }
        }

        return $boqMaster->load('details');
    }

    public function delete($id): void
    {
        $boqMaster = BOQMaster::findOrFail($id);

        // Delete all related BOQDetail records first
        $boqMaster->details()->delete();

        // Delete the BOQMaster record
        $boqMaster->delete();
    }

    public function addDetail(array $data): BOQDetail
    {
        return BOQDetail::create($data);
    }

    public function updateDetail(array $data, $id): BOQDetail
    {
        $detail = BOQDetail::findOrFail($id);
        $detail->update($data);

        return $detail;
    }

    public function deleteDetail($id): void
    {
        $detail = BOQDetail::findOrFail($id);
        $detail->delete();
    }

    public function calculateTotalAmount($boqMasterId): void
    {
        $boqMaster = BOQMaster::findOrFail($boqMasterId);
        $totalAmount = BOQDetail::where('boq_master_id', $boqMasterId)
            ->sum('gross_amount');

        $boqMaster->update(['total_amount' => $totalAmount]);
    }
}
