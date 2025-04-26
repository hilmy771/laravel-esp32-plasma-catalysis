<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UsersController extends Controller
{
    public function index()
    {
        $user = Auth::user(); // Get the currently logged-in user
        return view('users', compact('user')); // Pass user data to the view
    }

    public function index2()
    {
        $users = User::all();
        return view('users_data', compact('users'));
    }

    public function updateRole(Request $request, $id)
    {
        // Cek apakah user yang login adalah admin
        if (Auth::user()->role !== 'admin') {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk mengubah peran pengguna.');
        }

        // Validasi input
        $request->validate([
            'role' => 'required|in:user,admin',
        ]);

        // Temukan user berdasarkan ID
        $user = User::findOrFail($id);
        $user->role = $request->role;
        $user->save();
        return redirect()->back()->with('success', 'Role pengguna berhasil diperbarui.');
    }
}
