<?php

namespace App\Services;

use App\Models\Tender;

class TenderService
{
    public function create(array $data): Tender
    {
        return Tender::create($data);
    }

    public function update(array $data, $id): Tender
    {
        $tender = Tender::findOrFail($id);
        $tender->update($data);

        return $tender;
    }

    public function delete($id): void
    {
        $tender = Tender::findOrFail($id);
        $tender->delete();
    }
}
