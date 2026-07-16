<?php

namespace App\Services;

use App\Models\Relation;

class RelationService
{

    public function update($id, array $data)
    {
        $relation = Relation::findOrFail($id);
        $relation->update($data);
        return $relation;
    }

    public function delete($id)
    {
        $relation = Relation::findOrFail($id);
        return $relation->delete();
    }

}
