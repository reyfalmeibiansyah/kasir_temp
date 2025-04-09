<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // Show login form
    public function showLoginForm()
    {
        return view('auth.login');
    }
    public function dashboard(){
        return view('admin.dashboard');
    }

    // Handle login request
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
    
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
    
            if ($user->role === 'admin') {
                // Redirect to SuperAdmin dashboard
                return redirect()->route('admin.dashboard');
            } elseif ($user->role === 'petugas') {
                // Redirect to admin_tu dashboard
                return redirect()->route('petugas.dashboard');
            
            }else {
                // Jika user tidak memiliki role yang sesuai, logout dan beri pesan
                Auth::logout();
                return redirect()->route('login')->withErrors(['msg' => 'You do not have access to this section.']);
            }
        }
    
        return redirect()->route('login')->withErrors(['msg' => 'Invalid credentials']);
    }
    


    // Handle logout request
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
