<?php

namespace App\Services;

use App\Models\SubSubHead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;

class SubSubHeadService
{

    public function getById($id)
    {
        return SubSubHead::findOrFail($id);
    }

    public function create(array $data)
    {
        $subSubHead= SubSubHead::create($data);
        Cache::forget('sub_sub_heads_data');
        return $subSubHead;
    }

    public function update($id, array $data)
    {
        $subSubHead = SubSubHead::findOrFail($id);
        $subSubHead->update($data);
        Cache::forget('sub_sub_heads_data');
        return $subSubHead;
    }

    public function delete($id)
    {
        $subSubHead = SubSubHead::findOrFail($id);
        $deleted= $subSubHead->delete();
        Cache::forget('sub_sub_heads_data');
        return $deleted;
    }

    public function getSubSubHeadsForSubHead($subHead)
    {
        $field = App::getLocale() === 'ur' ? 'name_ur' : 'name_en';
        return SubSubHead::where('sub_head_id', $subHead)->pluck($field, 'id'); //change here
    }
}
