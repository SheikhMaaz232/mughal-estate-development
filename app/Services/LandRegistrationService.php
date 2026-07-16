<?php
// app/Services/LandRegistrationService.php

namespace App\Services;

use App\Models\LandRegistration;
use App\Models\DetailAccount;
use App\Models\Project;
use App\Models\SubSubSubHead;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class LandRegistrationService
{
    public function getPartyAccounts()
    {
        // Get 6th level accounts where parent is 5th level account payable
        return DetailAccount::where('sub_sub_sub_head_id', 5)
            ->orderBy('name_en')
            ->get('name_en','name_ur', 'id');
    }

    public function getProjects()
    {
        return Project::orderBy('name_en')
            ->pluck('name_en', 'id');
    }

    public function createLandRegistration(array $data): LandRegistration
    {
        $data['created_by'] = Auth::user()->id;
        $data['updated_by'] = Auth::user()->id;

        return LandRegistration::create($data);
    }

    public function updateLandRegistration(LandRegistration $landRegistration, array $data): bool
    {
        $data['updated_by'] = Auth::user()->id;

        return $landRegistration->update($data);
    }

    public function getLandRegistrations(array $filters = []): LengthAwarePaginator
    {
        $query = LandRegistration::with(['project', 'partyAccount', 'createdBy', 'updatedBy']);

        if (!empty($filters['project_id'])) {
            $query->where('project_id', $filters['project_id']);
        }

        if (!empty($filters['party_account_id'])) {
            $query->where('party_account_id', $filters['party_account_id']);
        }

        if (!empty($filters['khawat_number'])) {
            $query->where('khawat_number', 'like', '%' . $filters['khawat_number'] . '%');
        }

        return $query->orderBy('created_at', 'desc')
                    ->paginate(config('constants.PER_PAGE', 10));
    }

    public function calculateLandArea(array $data): array
    {
        $project = Project::find($data['project_id']);
        $projectSquareFeetPerMerla = $project->square_feet_per_merla ?? 272.25;

        $kanalToMerla = ($data['kanal'] ?? 0) * 20;
        $squareFeetToMerla = ($data['square_feet'] ?? 0) / $projectSquareFeetPerMerla;
        $totalMerla = $kanalToMerla + ($data['merla'] ?? 0) + $squareFeetToMerla;

        return [
            'kanal_to_merla' => $kanalToMerla,
            'square_feet_to_merla' => $squareFeetToMerla,
            'total_merla' => $totalMerla
        ];
    }
}
