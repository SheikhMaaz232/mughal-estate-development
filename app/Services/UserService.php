<?php
namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Storage;

class UserService
{
    protected $commonService;

    public function __construct(CommonService $commonService) {
        $this->commonService = $commonService;
    }
    public function register(array $data, $image = null): User
    {
        // dd($data);
        $avatarPath = null;
    
        if ($image) {
            $avatarPath = $this->commonService->uploadImage($image, 'user_images');
        }

        $user = User::create([
            'name_en' => $data['name_en'],
            'name_ur' => $data['name_ur'],
            'father_name_en' => $data['father_name_en'],
            'father_name_ur' => $data['father_name_ur'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'avatar' => $avatarPath,
        ]);

        event(new Registered($user));

        return $user;
    }

    // public function update(User $user, array $data, $image = null): User
    // {
    //     if ($image) {
    //         // Optional: delete old image
    //         if ($user->avatar && Storage::exists($user->avatar)) {
    //             Storage::delete($user->avatar);
    //         }

    //         $data['avatar'] = $this->commonService->uploadImage($image, 'user_images');
    //     }

    //     $user->update($data->only(['name_en', 'name_ur','father_name_en', 'father_name_ur', 'avatar']));;
    //     return $user;
    // }
}
