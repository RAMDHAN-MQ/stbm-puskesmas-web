<?php

namespace App\Http\Controllers;

use App\Models\Wilayah;
use App\Models\Stbm;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BerandaController extends Controller
{
    public function index()
    {
        $total = Stbm::count();
        $proses = Stbm::where('status', 'proses')->count();
        $selesai = Stbm::where('status', 'selesai')->count();

        $tidakLayak = Stbm::where('status', 'selesai')
            ->where(function ($q) {
                $q->where('pilar_1', 'tidak_layak')
                    ->orWhere('pilar_2', 'tidak_layak')
                    ->orWhere('pilar_3', 'tidak_layak')
                    ->orWhere('pilar_4', 'tidak_layak')
                    ->orWhere('pilar_5', 'tidak_layak');
            })->count();

        $layak = Stbm::where('status', 'selesai')
            ->where('pilar_1', 'layak')
            ->where('pilar_2', 'layak')
            ->where('pilar_3', 'layak')
            ->where('pilar_4', 'layak')
            ->where('pilar_5', 'layak')
            ->count();

        // ===== BAR CHART DESA =====
        $desaData = Wilayah::with(['stbm' => function ($q) {
            $q->where('status', 'selesai');
        }])->get()->map(function ($wilayah) {
            $layak = $wilayah->stbm->where(function ($q) {
                return
                    $q->pilar_1 === 'layak' &&
                    $q->pilar_2 === 'layak' &&
                    $q->pilar_3 === 'layak' &&
                    $q->pilar_4 === 'layak' &&
                    $q->pilar_5 === 'layak';
            })->count();

            $tidakLayak = $wilayah->stbm->count() - $layak;

            return [
                'desa' => $wilayah->desa,
                'layak' => $layak,
                'tidak_layak' => $tidakLayak,
            ];
        });

        // ===== PIE CHART PILAR =====
        $totalSelesai = Stbm::where('status', 'selesai')->count();

        $pilarLayak = [];
        for ($i = 1; $i <= 5; $i++) {
            $pilarLayak[$i] = Stbm::where('status', 'selesai')
                ->where("pilar_$i", 'layak')
                ->count();
        }

        $terbaru = Stbm::with(['wilayah', 'pegawai'])
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        return view('main.beranda', compact(
            'total',
            'terbaru',
            'proses',
            'selesai',
            'layak',
            'tidakLayak',
            'desaData',
            'pilarLayak',
            'totalSelesai'
        ));
    }

    // mobile beranda
    public function indexHP(Request $request)
    {
        $pegawaiId = $request->pegawai_id; 

        $totalData = Stbm::where('pegawai_id', $pegawaiId)->count();

        $bulanIni = Stbm::where('pegawai_id', $pegawaiId)
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        $hariIni = Stbm::where('pegawai_id', $pegawaiId)
            ->whereDate('created_at', Carbon::today())
            ->count();

        $desaList = Wilayah::select('wilayah.id', 'wilayah.desa')
            ->withCount(['stbm as total_input' => function ($query) use ($pegawaiId) {
                $query->where('pegawai_id', $pegawaiId);
            }])
            ->get();

        $dataTerakhir = Stbm::with('wilayah')
            ->where('pegawai_id', $pegawaiId)
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($item) {
                return [
                    'nama_kepala_kk' => $item->nama_kepala_kk,
                    'desa' => $item->wilayah->desa ?? '-',
                    'tanggal' => $item->created_at->format('d-m-Y H:i'),
                ];
            });

        return response()->json([
            'total_data' => $totalData,
            'bulan_ini' => $bulanIni,
            'hari_ini' => $hariIni,
            'desa_list' => $desaList,
            'data_terakhir' => $dataTerakhir,
        ]);
    }
}
