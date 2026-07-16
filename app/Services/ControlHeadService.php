<?php

namespace App\Services;

use App\Models\ControlHead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class ControlHeadService
{
    public function getAll($perPage = 10)
    {
        return ControlHead::with('mainHead')->latest()->paginate($perPage);
    }

    public function getById($id)
    {
        return ControlHead::findOrFail($id);
    }

    public function create(array $data)
    {
        return ControlHead::create($data);
    }

    public function update($id, array $data)
    {
        $controlHead = ControlHead::findOrFail($id);
        $controlHead->update($data);
        return $controlHead;
    }

    public function delete($id)
    {
        $controlHead = ControlHead::findOrFail($id);
        return $controlHead->delete();
    }

    public function getControlHeadsForMainHead($mainHead)
    {
        $field = App::getLocale() === 'ur' ? 'name_ur' : 'name_en';
        return ControlHead::where('main_head_id', $mainHead)->pluck($field, 'id');
    }
}
