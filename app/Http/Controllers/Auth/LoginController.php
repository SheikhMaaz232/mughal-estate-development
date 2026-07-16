<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    protected function authenticated(Request $request, $user)
    {
        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        session()->forget('selected_company_id');
        // // If using database persistence:
        // if (Auth::check()) {
        //     Auth::user()->update(['current_company_id' => null]);
        // }

        Auth::logout();
        return redirect('/login');
    }
}
