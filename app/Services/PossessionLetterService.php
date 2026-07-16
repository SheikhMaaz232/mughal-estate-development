<?php

namespace App\Services;

use App\Models\Party;
use App\Models\PartyBank;
use App\Models\PossessionLetter;
use Illuminate\Support\Facades\Storage;

class PossessionLetterService
{
    protected $commonService;

    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }

    public function getById($id)
    {
        return PossessionLetter::findOrFail($id);
    }

    public function create(array $data): PossessionLetter
    {

        return PossessionLetter::create($data);
    }

    public function update(array $data, $id): PossessionLetter
    {
        $possessionLetter = PossessionLetter::findOrFail($id);
        $possessionLetter->update($data);
        return $possessionLetter;
    }

    public function delete($id)
    {
        $possessionLetter = PossessionLetter::findOrFail($id);

        // Then delete the party
        return $possessionLetter->delete();
    }
}
