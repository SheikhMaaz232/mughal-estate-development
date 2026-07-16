<?php

namespace App\Services;

use App\Models\DetailAccount;
use App\Models\Land;
use App\Models\LandDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LandService
{
    /**
     * Store a land with details (master-details)
     *
     * @param array $data validated data
     * @return Land
     */
    public function create(array $data): Land
    {
        return DB::transaction(function () use ($data) {
            try {
                Log::info('Starting land registration transaction', ['data_keys' => array_keys($data)]);

                $details = $data['land_details'] ?? [];
                unset($data['land_details']);

                Log::info('Land details count:', ['count' => count($details)]);
                Log::info('Land details data:', $details);

                // Create the main land record
                $land = Land::create($data);
                if (!$land) {
                    throw new \Exception('Failed to create land record');
                }

                Log::info('Land record created', ['land_id' => $land->id]);

                // Create land details
                foreach ($details as $index => $detail) {
                    Log::info("Creating land detail $index", $detail);

                    $detail['land_id'] = $land->id;
                    $landDetail = LandDetail::create($detail);

                    if (!$landDetail) {
                        throw new \Exception("Failed to create land detail at index $index");
                    }

                    Log::info("Land detail $index created", ['land_detail_id' => $landDetail->id]);
                }

                Log::info('Transaction completed successfully');
                return $land->load('details');

            } catch (\Exception $e) {
                Log::error('Transaction failed: ' . $e->getMessage(), [
                    'exception' => $e->getTraceAsString()
                ]);
                throw $e;
            }
        });
    }

    /**
     * Update a land and its details
     */
    public function update(Land $land, array $data): Land
    {
        return DB::transaction(function () use ($land, $data) {
        try {
            Log::info('Starting land update transaction', ['land_id' => $land->id, 'data_keys' => array_keys($data)]);

            $details = $data['land_details'] ?? [];
            unset($data['land_details']);

            Log::info('Land details count for update:', ['count' => count($details)]);

            // Update the main land record
            $updated = $land->update($data);
            if (!$updated) {
                throw new \Exception('Failed to update land record');
            }

            Log::info('Land record updated successfully', ['land_id' => $land->id]);

            // Delete all existing details and create new ones
            $deletedCount = $land->details()->delete();
            Log::info('Deleted existing land details', ['count' => $deletedCount]);

            // Create new land details
            foreach ($details as $index => $detail) {
                $detail['land_id'] = $land->id;
                $landDetail = LandDetail::create($detail);

                if (!$landDetail) {
                    throw new \Exception("Failed to create land detail at index $index");
                }

                Log::info("Land detail $index created", ['land_detail_id' => $landDetail->id]);
            }

            Log::info('Land update transaction completed successfully');
            return $land->fresh('details');

        } catch (\Exception $e) {
            Log::error('Land update transaction failed: ' . $e->getMessage());
            throw $e;
        }
    });
    }

    public function delete(Land $land)
    {
        return DB::transaction(function () use ($land) {
            $land->delete();
        });
    }

    public function getPartyAccounts()
    {
        // Get 6th level accounts where parent is 5th level account payable
        return DetailAccount::whereIn('main_head_id', [1,2,4])->whereNull('deleted_at')->orderBy('name_en')
            ->get(['name_en', 'name_ur', 'id']);
    }
}
