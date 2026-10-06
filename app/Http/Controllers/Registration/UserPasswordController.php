<?php

namespace App\Http\Controllers\Registration;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class UserPasswordController extends Controller
{
    public function index(Request $request): View
    {
        return view('registration.user-passwords.index', [
            'user' => $request->user(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = $request->user();
        $user->password = $validated['password'];
        $user->save();

        return redirect()
            ->route('user-passwords.index')
            ->with('success', __('messages.user_password_updated'));
    }
}
