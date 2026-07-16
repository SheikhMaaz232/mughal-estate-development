<?php

namespace App\Http\Controllers\Registration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Registration\StoreUserRequest;
use App\Http\Requests\Registration\UpdateUserRequest;
use App\Models\User;
use App\Services\CommonService;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    protected $userService;
    protected $commonService;

    public function __construct(UserService $userService, CommonService $commonService)
    {
        $this->userService = $userService;
        $this->commonService = $commonService;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');

        $users = User::when($search, function ($query, $search) {
            return $query->where(function ($query) use ($search) {
                $query->where('name_en', 'like', '%' . $search . '%')
                    ->orWhere('name_ur', 'like', '%' . $search . '%')
                    ->orWhere('father_name_en', 'like', '%' . $search . '%')
                    ->orWhere('father_name_ur', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            });
        })
        ->latest()
        ->paginate(5)
        ->appends(['search' => $search]); // This preserves search in pagination links

        return view('registration.users.index', compact('users'));
    }

    public function create()
    {
        return view('registration.users.create');
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        app(UserService::class)->register($request->validated(), $request->file('avatar'));
        return redirect()->route('users.index')
            ->with('success', __('messages.record-saved'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('registration.users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->only(['name_en', 'name_ur', 'father_name_en', 'father_name_ur', 'avatar']);

        // Delete old image if exists
        if ($user->avatar && Storage::exists($user->avatar)) {
            Storage::delete($user->avatar);
        }

        // Upload new image if present
        if ($request->hasFile('avatar')) {
            $data['avatar'] = $this->commonService->uploadImage($request->file('avatar'), 'user_images');
        }

        $user->update($data);
        return redirect()->route('users.index')
            ->with('success', __('messages.record-updated'));
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', __('messages.record-deleted'));
    }

    public function editPermissions(User $user)
    {
        $roles = Role::all();
        $permissions = Permission::all()->groupBy(function ($item) {
            return explode('.', $item->name)[0]; // Group by module
        });

        return view('users.permissions', compact('user', 'roles', 'permissions'));
    }

    public function updatePermissions(Request $request, User $user)
    {
        $user->syncRoles($request->roles);
        $user->syncPermissions($request->permissions);

        return redirect()->back()->with('success', 'Permissions updated successfully.');
    }
}
