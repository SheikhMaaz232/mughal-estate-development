<?php

namespace App\Services;

use App\Models\DetailAccount;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;

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
        $detailAccount = DetailAccount::create($data);

        Cache::forget('detail_accounts_data');

        return $detailAccount;
    }

    public function update($id, array $data)
    {
        $detailAccount = DetailAccount::findOrFail($id);
        $detailAccount->update($data);
        Cache::forget('detail_accounts_data');
        return $detailAccount;
    }

    public function delete($id)
    {
        $detailAccount = DetailAccount::findOrFail($id);
        $deleted = $detailAccount->delete();
        Cache::forget('detail_accounts_data');

        return $deleted;
    }

    public function getSubSubSubHeadsForSubSubHead($subSubHead)
    {
        $field = App::getLocale() === 'ur' ? 'name_ur' : 'name_en';
        return DetailAccount::where('sub_sub_head_id', $subSubHead)->pluck($field, 'id'); //change here
    }
}
