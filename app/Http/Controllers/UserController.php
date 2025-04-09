<?php

namespace App\Http\Controllers;

use DB;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        // Render view pembelian
        return view('auth.login');
    }

    public function login_proses(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (Auth::attempt($data)) {
            $user = Auth::user();
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role === 'petugas') {
                return redirect()->route('petugas.dashboard');
            }
        }

        return redirect()->back()->with('failed', 'Proses login gagal, silahkan coba kembali dengan data yang benar!');
    }

    public function logout() {
        Auth::logout();
        return redirect()->route('login')->with('logout', 'Anda telah logout!');
    }

}
