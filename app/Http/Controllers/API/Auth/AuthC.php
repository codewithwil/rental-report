<?php

namespace App\Http\Controllers\API\Auth;

use App\{
    Http\Controllers\Controller,
};

use Illuminate\{
    Http\Request,
    Support\Facades\Auth,   
    Support\Str,   
    Support\Facades\RateLimiter
};

class AuthC extends Controller
{
    public function index()
    {
        return view('admin.auth.login');
    }

    public function login(Request $req)
    {
        $credentials = $req->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $key = Str::lower('login|' . $req->ip() . '|' . $credentials['email']);

        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            $minutes = ceil($seconds / 60);
            return back()->withErrors([
                'login' => "Terlalu banyak percobaan login. Silakan coba lagi dalam $minutes menit."
            ]);
        }

        if (Auth::attempt($credentials)) {
            RateLimiter::clear($key); 
            $req->session()->regenerate();

            $user = Auth::user();
            return redirect('dashboard')->with('success', 'Login berhasil');
        }

        RateLimiter::hit($key, 3600); 

        return redirect()->route('login')->withErrors([
            'login' => 'Email atau password salah.',
        ]);
    }
    public function logout(Request $req)
    {
        Auth::logout();
        $req->session()->invalidate();
        $req->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah logout.');
    }
}