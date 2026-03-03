<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class PegawaiController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('main.pegawai.index', compact('users'));
    }

    // tambah
    public function create()
    {
        return view('main.pegawai.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'     => 'required|string|max:255',
            'nip'     => 'required|numeric',
            'email'    => 'required|email',
            'password' => 'required|min:6',
            'role'     => 'required|in:admin,pegawai',
            'foto'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $fotoName = null;
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $fotoName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('profile', $fotoName, 'public');
        }


        User::create([
            'nama'     => $request->nama,
            'nip'     => $request->nip,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
            'foto'    => $fotoName,
        ]);

        return redirect()
            ->route('admin.pegawai.index')
            ->with('success', 'Pegawai berhasil ditambahkan');
    }

    // delete
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if ($user->foto && Storage::disk('public')->exists('profile/' . $user->foto)) {
            Storage::disk('public')->delete('profile/' . $user->foto);
        }
        $user->delete();
        return redirect()
            ->route('admin.pegawai.index')
            ->with('success', 'Pegawai berhasil dihapus');
    }

    // lihat
    public function view($id)
    {
        $user = User::findOrFail($id);
        return view('main.pegawai.view', compact('user'));
    }

    // edit
    public function edit($id)
    {
        $pegawai = User::findOrFail($id);
        return view('main.pegawai.edit', compact('pegawai'));
    }

    public function update(Request $request, $id)
    {
        $pegawai = User::findOrFail($id);

        $data = $request->validate([
            'nama' => 'required',
            'nidn' => 'required',
            'email' => 'required|email',
            'role' => 'required',
            'password' => 'nullable|min:6',
            'foto' => 'nullable|image'
        ]);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']);
        }

        if ($request->hasFile('foto')) {

            if ($pegawai->foto && Storage::disk('public')->exists('profile/' . $pegawai->foto)) {
                Storage::disk('public')->delete('profile/' . $pegawai->foto);
            }

            $file = $request->file('foto');
            $fotoName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('profile', $fotoName, 'public');

            $data['foto'] = $fotoName;
        }

        $pegawai->update($data);

        return redirect()->route('admin.pegawai.index')
            ->with('success', 'Data pegawai berhasil diperbarui');
    }
}
