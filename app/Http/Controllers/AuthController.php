<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($credentials)) {
            return back()->withErrors([
                'email' => 'Email atau password salah',
            ])->withInput();
        }

        $request->session()->regenerate();

        if (auth()->user()->role !== 'admin') {
            Auth::logout();

            return back()->withErrors([
                'email' => 'Akun anda tidak memiliki akses admin',
            ]);
        }
        return redirect()->route('admin.beranda.index');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    // stbm mobile
    public function loginHP(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'message' => 'Email atau password salah'
            ], 401);
        }

        if ($user->role !== 'pegawai') {
            return response()->json([
                'message' => 'Akses ditolak. Akun bukan pegawai.'
            ], 403);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Email atau password salah'
            ], 401);
        }

        return response()->json([
            'message' => 'Login berhasil',
            'user' => [
                'id' => $user->id,
                'nama' => $user->nama,
                'nip' => $user->nip,
                'foto' => $user->foto,
                'role' => $user->role,
            ]
        ]);
    }
}
