<?php

namespace App\Services;

use App\Models\Party;
use App\Models\PartyBank;
use Illuminate\Support\Facades\Storage;

class PartiesService
{
    protected $commonService;

    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }

    public function getById($id)
    {
        return Party::findOrFail($id);
    }

    public function getPartyBanksByPartyId($id)
    {
        return PartyBank::where('party_id', $id)
            ->whereNull('deleted_at')
            ->get();
    }

    public function create(array $data): Party
    {
        // Handle image uploads directly from $data if present
        if (!empty($data['cnic_front_image']) && $data['cnic_front_image'] instanceof \Illuminate\Http\UploadedFile) {
            $data['cnic_front_image'] = $this->commonService->uploadImage($data['cnic_front_image'], 'party_images');
        }

        if (!empty($data['cnic_back_image']) && $data['cnic_back_image'] instanceof \Illuminate\Http\UploadedFile) {
            $data['cnic_back_image'] = $this->commonService->uploadImage($data['cnic_back_image'], 'party_images');
        }

        if (!empty($data['profile_image']) && $data['profile_image'] instanceof \Illuminate\Http\UploadedFile) {
            $data['profile_image'] = $this->commonService->uploadImage($data['profile_image'], 'party_images');
        }

        // Create the party
        $party = Party::create($data);

        // Prepare and insert bank details if any
        if (!empty($data['bank_id'])) {
            $bankDetails = collect($data['bank_id'])->map(function ($bankId, $index) use ($data, $party) {
                if (!$bankId) return null;

                return [
                    'party_id'       => $party->id,
                    'bank_id'        => $bankId,
                    'account_title'  => $data['account_title'][$index] ?? null,
                    'account_number' => $data['account_number'][$index] ?? null,
                    'branch_code'    => $data['branch_code'][$index] ?? null,
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ];
            })->filter()->values()->toArray();

            PartyBank::insert($bankDetails);
        }

        return $party;
    }



    public function update(int $id, array $data, $cnicFrontImage = null, $cnicBackImage = null, $profileImage = null): Party
    {
        $party = Party::findOrFail($id);

        if ($cnicFrontImage) {
            // Optionally delete the old image
            if ($party->cnic_front_image && Storage::disk('public')->exists($party->cnic_front_image)) {
                Storage::disk('public')->delete($party->cnic_front_image);
            }

            // Upload and store new image
            $data['cnic_front_image'] = $this->commonService->uploadImage($cnicFrontImage, 'party_images');
        }

        if ($cnicBackImage) {
            // Optionally delete the old image
            if ($party->cnic_back_image && Storage::disk('public')->exists($party->cnic_back_image)) {
                Storage::disk('public')->delete($party->cnic_back_image);
            }

            // Upload and store new image
            $data['cnic_back_image'] = $this->commonService->uploadImage($cnicBackImage, 'party_images');
        }

        if ($profileImage) {
            // Optionally delete the old image
            if ($party->profile_image && Storage::disk('public')->exists($party->profile_image)) {
                Storage::disk('public')->delete($party->profile_image);
            }

            // Upload and store new image
            $data['profile_image'] = $this->commonService->uploadImage($profileImage, 'party_images');
        }
        $party->update($data);

        // If bank data is passed, update bank records
        if (!empty($data['bank_id'])) {
            // Delete old details not in the incoming request (optional)
            PartyBank::where('party_id', $party->id)->delete();

            // Re-insert current bank data
            $bankDetails = collect($data['bank_id'])->map(function ($bankId, $index) use ($data, $party) {
                if (!$bankId) return null;

                return [
                    'party_id'       => $party->id,
                    'bank_id'        => $bankId,
                    'account_title'  => $data['account_title'][$index] ?? null,
                    'account_number' => $data['account_number'][$index] ?? null,
                    'branch_code'    => $data['branch_code'][$index] ?? null,
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ];
            })->filter()->values()->toArray();

            PartyBank::insert($bankDetails);
        }


        return $party;
    }

    public function delete($id)
    {
        $party = Party::findOrFail($id);

        // Delete related bank details first
        $party->banks()->delete();

        // Then delete the party
        return $party->delete();
    }
}
