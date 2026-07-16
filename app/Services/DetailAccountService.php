<?php

namespace App\Services;

use App\Models\DetailAccount;
use Illuminate\Support\Facades\App;

class DetailAccountService
{
    public function getAll($perPage = 10)
    {
        return DetailAccount::with('mainHead', 'controlHead', 'subHead', 'subSubHead')->latest()->paginate($perPage);
    }

    public function getById($id)
    {
        return DetailAccount::findOrFail($id);
    }

    public function create(array $data)
    {
        return DetailAccount::create($data);
    }

    public function update($id, array $data)
    {
        $detailAccount = DetailAccount::findOrFail($id);
        $detailAccount->update($data);
        return $detailAccount;
    }

    public function delete($id)
    {
        $detailAccount = DetailAccount::findOrFail($id);
        return $detailAccount->delete();
    }

    public function getSubSubSubHeadsForSubSubHead($subSubHead)
    {
        $field = App::getLocale() === 'ur' ? 'name_ur' : 'name_en';
        return DetailAccount::where('sub_sub_head_id', $subSubHead)->pluck($field, 'id'); //change here
    }
}
