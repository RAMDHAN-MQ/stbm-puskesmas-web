<?php

namespace App\Http\Controllers;

use App\Models\Wilayah;
use Illuminate\Http\Request;

class WilayahController extends Controller
{
    public function index()
    {
        $wilayah = Wilayah::all();
        return view('main.wilayah.index', compact('wilayah'));
    }

    // tambah
    public function create()
    {
        return view('main.wilayah.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kota'     => 'required',
            'kecamatan'     => 'required',
            'desa'    => 'required',
        ]);

        Wilayah::create([
            'kota'     => $request->kota,
            'kecamatan'     => $request->kecamatan,
            'desa'    => $request->desa,
        ]);

        return redirect()->route('admin.wilayah.index')
            ->with('success', 'Wilayah berhasil ditambahkan');
    }

    // edit
    public function edit($id)
    {
        $wilayah = Wilayah::findOrFail($id);
        return view('main.wilayah.edit', compact('wilayah'));
    }

    public function update(Request $request, $id)
    {
        $wilayah = Wilayah::findOrFail($id);

        $data = $request->validate([
            'kota' => 'required',
            'kecamatan' => 'required',
            'desa' => 'required',
        ]);

        $wilayah->update($data);

        return redirect()->route('admin.wilayah.index')
            ->with('success', 'Data wilayah berhasil diperbarui');
    }

    // delete
    public function destroy($id)
    {
        $wilayah = Wilayah::findOrFail($id);

        $wilayah->delete();
        return redirect()
            ->route('admin.wilayah.index')
            ->with('success', 'Wilayah berhasil dihapus');
    }
}
