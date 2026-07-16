<?php
namespace App\Services;

use App\Models\Group;
use Illuminate\Support\Facades\Storage;
use App\Services\CommonService;

class GroupService
{
    protected $commonService;

    public function __construct(CommonService $commonService) {
        $this->commonService = $commonService;
    }

    public function create(array $data, $image = null): Group
    {
        if ($image) {
            $data['image'] = $this->commonService->uploadImage($image, 'group_images');
        }

        return Group::create($data);
    }

    public function update(Group $group, array $data, $image = null): Group
    {
        if ($image) {
            // Optional: delete old image
            if ($group->image && Storage::exists($group->image)) {
                Storage::delete($group->image);
            }

            $data['image'] = $this->commonService->uploadImage($image, 'group_images');
        }

        $group->update($data);
        return $group;
    }

    public function delete(Group $group)
    {
        return $group->delete();
    }

    public function getAll()
    {
        return Group::all();
    }

    public function findById($id)
    {
        return Group::findOrFail($id);
    }
}
