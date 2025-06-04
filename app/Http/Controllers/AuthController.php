<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Clear any existing SIPTA token session data before attempting local login
        Session::forget('sipta_token');
        Session::forget('token_authenticated');
        Session::forget('token_user_role');
        Session::forget('token_user_name');

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            return redirect()->intended('/penjadwalan-ruangan');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'user', // Default role
        ]);
        

        Auth::login($user);

        return redirect('/penjadwalan-ruangan');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        // Clear SIPTA token authentication
        Session::forget('sipta_token');
        Session::forget('token_authenticated');
        Session::forget('token_user_role');
        Session::forget('token_user_name');

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->view('auth.logout_redirect', [
            'redirect_url' => '/penjadwalan-ruangan'
        ]);
    }
}