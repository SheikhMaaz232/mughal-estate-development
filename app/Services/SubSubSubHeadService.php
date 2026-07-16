<?php

namespace App\Services;

use App\Models\SubSubSubHead;
use Illuminate\Support\Facades\App;

class SubSubSubHeadService
{
    public function getAll($perPage = 10)
    {
        return SubSubSubHead::with('mainHead', 'controlHead', 'subHead', 'subSubHead')->latest()->paginate($perPage);
    }

    public function getById($id)
    {
        return SubSubSubHead::findOrFail($id);
    }

    public function create(array $data)
    {
        return SubSubSubHead::create($data);
    }

    public function update($id, array $data)
    {
        $subSubSubHead = SubSubSubHead::findOrFail($id);
        $subSubSubHead->update($data);
        return $subSubSubHead;
    }

    public function delete($id)
    {
        $subSubSubHead = SubSubSubHead::findOrFail($id);
        return $subSubSubHead->delete();
    }

    public function getSubSubSubHeadsForSubSubHead($subSubHead)
    {
        $field = App::getLocale() === 'ur' ? 'name_ur' : 'name_en';
        return SubSubSubHead::where('sub_sub_head_id', $subSubHead)->pluck($field, 'id'); //change here
    }
}
