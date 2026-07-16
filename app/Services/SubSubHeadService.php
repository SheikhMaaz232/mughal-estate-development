<?php

namespace App\Services;

use App\Models\SubSubHead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SubSubHeadService
{

    public function getById($id)
    {
        return SubSubHead::findOrFail($id);
    }

    public function create(array $data)
    {
        return SubSubHead::create($data);
    }

    public function update($id, array $data)
    {
        $subSubHead = SubSubHead::findOrFail($id);
        $subSubHead->update($data);
        return $subSubHead;
    }

    public function delete($id)
    {
        $subSubHead = SubSubHead::findOrFail($id);
        return $subSubHead->delete();
    }

    public function getSubSubHeadsForSubHead($subHead)
    {
        $field = App::getLocale() === 'ur' ? 'name_ur' : 'name_en';
        return SubSubHead::where('sub_head_id', $subHead)->pluck($field, 'id'); //change here
    }
}
