<?php
namespace App\Services;

use App\Models\MainHead;
use Illuminate\Http\Request;

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
        return MainHead::create($data);
    }

    public function update($id, array $data)
    {
        $mainHead = MainHead::findOrFail($id);
        $mainHead->update($data);
        return $mainHead;
    }

    public function delete($id)
    {
        $mainHead = MainHead::findOrFail($id);
        return $mainHead->delete();
    }
}
