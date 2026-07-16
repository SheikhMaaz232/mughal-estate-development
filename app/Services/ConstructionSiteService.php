<?php

namespace App\Services;

use App\Models\ConstructionSite;

class ConstructionSiteService
{
    public function create(array $data): ConstructionSite
    {
        return ConstructionSite::create($data);
    }

    public function update(array $data, $id): ConstructionSite
    {
        $site = ConstructionSite::findOrFail($id);
        $site->update($data);

        return $site;
    }

    public function delete($id): void
    {
        $site = ConstructionSite::findOrFail($id);
        $site->delete();
    }
}
