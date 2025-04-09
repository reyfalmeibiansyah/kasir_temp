<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AkunController extends Controller
{
    // Menampilkan semua akun
    public function index()
    {
        $users = User::all();
        return view('admin.user.index', compact('users'));
    }

    // Menampilkan form tambah akun
    public function create()
    {
        return view('admin.user.create');
    }

    // Menyimpan akun baru
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role'     => 'required|in:admin,petugas,user'
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role
        ]);

        return redirect()->route('admin.user.index')->with('success', 'Akun berhasil ditambahkan.');
    }

    // Menampilkan form edit akun
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.user.edit', compact('user'));
    }

    // Mengupdate data akun
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role'  => 'required|in:admin,petugas,user'
        ]);

        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
            'role'  => $request->role
        ]);

        return redirect()->route('admin.user.index')->with('success', 'Akun berhasil diperbarui.');
    }

    // Menghapus akun
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.user.index')->with('success', 'Akun berhasil dihapus.');
    }
}
