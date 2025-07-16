<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showAuthForm()
    {
        return view('auth');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $account = Auth::user();
            
            if ($account->role === 'landboard') {
                return redirect()->route('landboard.dashboard.index');
            }

            if ($account->role === 'tenant') {
                return $account->is_first_login
                    ? redirect()->route('tenant.profile.complete-form')
                    : redirect()->route('tenant.dashboard.index');
            }
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('auth');
    }
}