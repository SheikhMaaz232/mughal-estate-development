<?php
namespace App\Http\Controllers\Registration;

use App\Http\Controllers\Controller;

use App\Http\Requests\Registration\StoreGroupRequest;
use App\Http\Requests\Registration\UpdateGroupRequest;
use App\Models\Group;
use Illuminate\Http\Request;
use App\Services\GroupService;

class GroupController extends Controller
{
    protected $groupService;

    public function __construct(GroupService $groupService)
    {
        $this->groupService = $groupService;
    }

    public function index()
    {
        $groups = $this->groupService->getAll();
        return view('registration.groups.index', compact('groups'));
    }

    public function create()
    {
        return view('registration.groups.create');
    }

    public function store(StoreGroupRequest $request)
    {
        $data = $request->all();
        $group = app(GroupService::class)->create($data, $request->file('image'));
        return redirect()->route('groups.index')->with('success', __('messages.record-saved'));
    }

    public function show(Group $group)
    {
        return view('registration.groups.show', compact('group'));
    }

    public function edit(Group $group)
    {
        return view('registration.groups.edit', compact(var_name: 'group'));
    }

    public function update(UpdateGroupRequest $request, Group $group)
    {
        $this->groupService->update($group, $request->all());
        return redirect()->route('groups.index')->with('success', __('messages.record-updated'));
    }

    public function destroy(Group $group)
    {
        $this->groupService->delete($group);
        return redirect()->route('groups.index')->with('success', __('messages.record-deleted'));
    }
}
