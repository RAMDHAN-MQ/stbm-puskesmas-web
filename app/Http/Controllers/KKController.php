<?php

namespace App\Http\Controllers;

use App\Models\KK;
use App\Models\Wilayah;
use Illuminate\Http\Request;


class KKController extends Controller
{
    public function index()
    {
        $kk = KK::all();
        return view('main.kk.index', compact('kk'));
    }

    public function create()
    {
        $wilayah = Wilayah::all();
        return view('main.kk.create', compact('wilayah'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_kk' => 'required|unique:kk,no_kk',
            'nama_kepala_kk' => 'required',
            'wilayah' => 'required',
            'rt' => 'required',
            'rw' => 'required',
            'jumlah_jiwa' => 'required|numeric',
            'jumlah_jiwa_menetap' => 'required|numeric',
        ], [
            'no_kk.unique' => 'No KK sudah terdaftar!',
        ]);

        KK::create([
            'no_kk' => $request->no_kk,
            'nama_kepala_kk' => $request->nama_kepala_kk,
            'wilayah_id' => $request->wilayah,
            'rt' => $request->rt,
            'rw' => $request->rw,
            'jumlah_jiwa' => $request->jumlah_jiwa,
            'jumlah_jiwa_menetap' => $request->jumlah_jiwa_menetap
        ]);

        return redirect()
            ->route('admin.kk.index')
            ->with('success', 'Data KK berhasil ditambahkan');
    }

    public function edit($no_kk)
    {
        $kk = KK::findOrFail($no_kk);
        $wilayah = Wilayah::all();
        return view('main.kk.edit', compact('kk', 'wilayah'));
    }

    public function update(Request $request, $no_kk)
    {
        $request->validate([
            'no_kk' => 'required|unique:kk,no_kk,' . $no_kk . ',no_kk',
            'nama_kepala_kk' => 'required',
            'wilayah' => 'required',
            'rt' => 'required',
            'rw' => 'required',
            'jumlah_jiwa' => 'required|numeric',
            'jumlah_jiwa_menetap' => 'required|numeric',
        ], [
            'no_kk.unique' => 'No KK sudah terdaftar!',
        ]);

        $kk = KK::findOrFail($no_kk);

        $kk->update([
            'no_kk' => $request->no_kk,
            'nama_kepala_kk' => $request->nama_kepala_kk,
            'wilayah_id' => $request->wilayah,
            'rt' => $request->rt,
            'rw' => $request->rw,
            'jumlah_jiwa' => $request->jumlah_jiwa,
            'jumlah_jiwa_menetap' => $request->jumlah_jiwa_menetap
        ]);

        return redirect()
            ->route('admin.kk.index')
            ->with('success', 'Data KK berhasil diperbarui');
    }

    public function destroy($no_kk)
    {
        $kk = KK::findOrFail($no_kk);
        $kk->delete();
        return redirect()
            ->route('admin.kk.index')
            ->with('success', 'Data KK berhasil dihapus');
    }
}
