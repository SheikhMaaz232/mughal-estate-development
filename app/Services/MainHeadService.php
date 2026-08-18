<?php
namespace App\Services;

use App\Models\MainHead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MainHeadService
{
    public function getAll()
    {
        return MainHead::all();
    }

    public function getById($id)
    {
        return MainHead::findOrFail($id);
    }

    public function create(array $data)
    {
        $mainHeads = MainHead::create($data);

        Cache::forget('main_heads_data');

        return $mainHeads;
    }

    public function update($id, array $data)
    {
        $mainHead = MainHead::findOrFail($id);
        $mainHead->update($data);
        Cache::forget('main_heads_data');
        return $mainHead;
    }

    public function delete($id)
    {
        $mainHead = MainHead::findOrFail($id);
        $deleted= $mainHead->delete();
        Cache::forget('main_heads_data');

        return $deleted;
    }
}
