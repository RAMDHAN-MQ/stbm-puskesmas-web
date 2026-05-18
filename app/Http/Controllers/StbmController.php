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
use App\Models\KK;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class StbmController extends Controller
{
    public function index()
    {
        $stbm = Stbm::with(['wilayah', 'pegawai'])
            ->orderBy('created_at', 'desc')
            ->get();
        $kk = KK::all();
        $desa = Wilayah::orderBy('desa')->get();

        return view('main.stbm.index', compact('stbm', 'kk', 'desa'));
    }

    public function view($id)
    {
        $stbm = Stbm::with([
            'kk',
            'wilayah',
            'pegawai',
            'details.stbm'
        ])->findOrFail($id);

        return view('main.stbm.view', compact('stbm'));
    }

    public function destroy($id)
    {
        $stbm = Stbm::findOrFail($id);

        if ($stbm->bukti) {
            $path = 'stbm/' . $stbm->bukti;
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }
        $stbm->delete();

        return redirect()
            ->route('admin.stbm.index')
            ->with('success', 'STBM berhasil dihapus');
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
    public function indexHP(Request $request)
    {
        $pegawaiId = $request->pegawai_id;
        $desa = $request->desa;
        $search = $request->search;

        $stbm = Stbm::with(['wilayah', 'pegawai', 'kk'])
            ->where('pegawai_id', $pegawaiId)

            ->when($desa, function ($q) use ($desa) {
                $q->whereHas('wilayah', function ($w) use ($desa) {
                    $w->where('desa', $desa);
                });
            })

            ->when($search, function ($q) use ($search) {
                $q->whereHas('kk', function ($k) use ($search) {
                    $k->where('nama_kepala_kk', 'like', "%$search%")
                        ->orWhere('no_kk', 'like', "%$search%");
                });
            })

            ->latest()
            ->paginate(5);

        return response()->json($stbm);
    }

    public function listDesa(Request $request)
    {
        $pegawaiId = $request->pegawai_id;

        $desa = Stbm::with('wilayah')
            ->where('pegawai_id', $pegawaiId)
            ->select('wilayah_id')
            ->distinct()
            ->get()
            ->map(function ($item) {
                return [
                    'desa' => $item->wilayah->desa,
                ];
            })
            ->unique('desa')
            ->values();

        return response()->json($desa);
    }

    // 3. Tampil STBM spesifik (opsional)
    public function pilarDetail($id, $pilar)
    {
        $data = Pertanyaan::where('pilar', $pilar)
            ->with(['stbmDetails' => function ($q) use ($id) {
                $q->where('stbm_id', $id);
            }])
            ->get();

        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }

    // kk dropdown
    public function kk()
    {
        $tahunIni = now()->year;

        $kk = KK::with('wilayah')
            ->whereDoesntHave('stbm', function ($query) use ($tahunIni) {
                $query->whereYear('created_at', $tahunIni);
            })
            ->get();

        return response()->json($kk);
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
            $filename = null;

            if ($request->hasFile('bukti')) {
                $file = $request->file('bukti');
                $filename = time() . '_' . $file->getClientOriginalName();

                $buktiPath = $file->storeAs('stbm', $filename, 'public');
            }

            $stbm = Stbm::create([
                'pegawai_id' => $request->pegawai_id,
                'wilayah_id' => $request->wilayah_id,
                'no_kk' => $request->no_kk,
                'bukti' => $filename,
                'status' => 'proses',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach (json_decode($request->jawaban, true) as $j) {
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
                'bukti' => $filename,
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
