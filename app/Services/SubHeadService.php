<?php
namespace App\Services;

use App\Models\SubHead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;

class SubHeadService
{

    public function getById($id)
    {
        return SubHead::findOrFail($id);
    }

    public function create(array $data)
    {
        $subHead= SubHead::create($data);
        Cache::forget('sub_heads_data');

        return $subHead;
    }

    public function update($id, array $data)
    {
        $subHead = SubHead::findOrFail($id);
        $subHead->update($data);
        Cache::forget('sub_heads_data');
        return $subHead;
    }

    public function delete($id)
    {
        $subHead = SubHead::findOrFail($id);
        $deleted= $subHead->delete();
        Cache::forget('sub_heads_data');

        return $deleted;
    }

    public function getSubHeadsForControlHead($controlHead)
    {
        $field = App::getLocale() === 'ur' ? 'name_ur' : 'name_en';
        return SubHead::where('control_head_id', $controlHead)->pluck($field , 'id');//change here
    }

}
