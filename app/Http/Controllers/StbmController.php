<?php

namespace App\Http\Controllers;

use App\Models\Pertanyaan;
use App\Models\Stbm;
use App\Models\StbmDetail;
use App\Models\Wilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StbmExport;

class StbmController extends Controller
{
    public function index()
    {
        $stbm = Stbm::with(['wilayah', 'pegawai'])->get();

        $jumlah = Wilayah::leftJoin('stbm', 'wilayah.id', '=', 'stbm.wilayah_id')
            ->select(
                'wilayah.id',
                'wilayah.desa',
                DB::raw('COUNT(stbm.id) as total')
            )
            ->groupBy('wilayah.id', 'wilayah.desa')
            ->orderBy('wilayah.desa')
            ->get();

        $desa = Wilayah::orderBy('desa')->get();

        return view('main.stbm.index', compact('stbm', 'jumlah', 'desa'));
    }

    public function view($id)
    {
        $stbm = Stbm::with([
            'wilayah',
            'pegawai',
            'details.stbm'
        ])->findOrFail($id);

        return view('main.stbm.view', compact('stbm'));
    }

    public function selesai($id)
    {
        $stbm = Stbm::with('details.pertanyaan')->findOrFail($id);

        $hasilPilar = [
            1 => 'layak',
            2 => 'layak',
            3 => 'layak',
            4 => 'layak',
            5 => 'layak',
        ];

        foreach ($stbm->details as $detail) {
            $pilar      = $detail->pertanyaan->pilar;
            $jawaban    = strtolower($detail->jawaban);
            $isNegatif  = $detail->pertanyaan->is_negatif;

            if ($isNegatif == 1 && $jawaban === 'ya') {
                $hasilPilar[$pilar] = 'tidak_layak';
            }

            if ($isNegatif == 0 && $jawaban === 'tidak') {
                $hasilPilar[$pilar] = 'tidak_layak';
            }
        }

        $stbm->update([
            'pilar_1' => $hasilPilar[1],
            'pilar_2' => $hasilPilar[2],
            'pilar_3' => $hasilPilar[3],
            'pilar_4' => $hasilPilar[4],
            'pilar_5' => $hasilPilar[5],
            'status'  => 'selesai',
        ]);

        return redirect()
            ->route('admin.stbm.index')
            ->with('success', 'STBM berhasil diselesaikan');
    }

    public function export(Request $request)
    {
        $query = Stbm::query();

        if ($request->desa_id) {
            $query->where('wilayah_id', $request->desa_id);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->tanggal_mulai && $request->tanggal_selesai) {
            $query->whereBetween('created_at', [
                $request->tanggal_mulai,
                $request->tanggal_selesai
            ]);
        }

        $data = $query->get();

        return Excel::download(
            new StbmExport($data),
            'STBM_' . now()->format('Y-m-d_H-i-s') . '.xlsx'
        );
    }

    // STBM MOBILE
    // 1. Tampil semua STBM
    public function indexHP()
    {
        $stbm = Stbm::with(['wilayah', 'pegawai'])
            ->latest()
            ->get();

        return response()->json($stbm);
    }

    // 2. Tambah STBM
    public function storeHP(Request $request)
    {
        $request->validate([
            'wilayah' => 'required|string|max:255',
            'status' => 'required|string|max:50',
        ]);

        $stbm = Stbm::createHP([
            'wilayah' => $request->wilayah,
            'status' => $request->status,
        ]);

        return response()->json($stbm, 201);
    }

    // 3. Tampil STBM spesifik (opsional)
    public function showHP($id)
    {
        $data = Stbm::with(['wilayah', 'pegawai'])
            ->findOrFail($id);

        return response()->json($data);
    }

    // wilayah dropdown
    public function wilayah()
    {
        $wilayah = Wilayah::select('id', 'desa')->get();
        return response()->json($wilayah);
    }

    // pertanyaan stbm pilar
    public function pertanyaan()
    {
        $pertanyaan = Pertanyaan::select('id', 'pilar', 'pertanyaan')->get();
        return response()->json($pertanyaan);
    }

    // simpan data stbm dan detailnya
    public function storeSTBM(Request $request)
    {
        DB::beginTransaction();

        try {
            $stbm = Stbm::create([
                'pegawai_id' => $request->pegawai_id,
                'wilayah_id' => $request->wilayah_id,
                'no_kk' => $request->no_kk,
                'nama_kepala_kk' => $request->nama_kepala_kk,
                'rt' => $request->rt,
                'rw' => $request->rw,
                'jumlah_jiwa' => $request->jumlah_jiwa,
                'jumlah_jiwa_menetap' => $request->jumlah_jiwa_menetap,
                'status' => 'proses',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($request->jawaban as $j) {
                StbmDetail::create([
                    'stbm_id' => $stbm->id,
                    'pertanyaan_id' => $j['pertanyaan_id'],
                    'jawaban' => strtolower($j['jawaban']),
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => 'STBM berhasil disimpan',
                'stbm_id' => $stbm->id,
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Gagal menyimpan STBM',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
