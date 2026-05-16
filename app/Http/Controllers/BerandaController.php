<?php

namespace App\Http\Controllers;

use App\Models\Wilayah;
use App\Models\Stbm;
use App\Models\User;
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



        // ===== PIE CHART PILAR =====
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

        $desas = Wilayah::all();

        $rekomendasi = [];

        foreach ($desas as $desa) {

            $query = Stbm::where('wilayah_id', $desa->id)
                ->where('status', 'selesai')
                ->whereYear('created_at', now()->year);

            $kks = $query->get();

            $totalKK = $kks->count();

            if ($totalKK == 0) {

                $rekomendasi[$desa->desa] = [
                    'total_kk' => 0,
                    'kk_layak' => 0,
                    'kk_tidak_layak' => 0,
                    'status' => 'Belum Ada Data'
                ];

                continue;
            }

            $layakKK = $kks->filter(function ($kk) {

                $pilar = [
                    $kk->pilar_1,
                    $kk->pilar_2,
                    $kk->pilar_3,
                    $kk->pilar_4,
                    $kk->pilar_5
                ];

                return count(array_unique($pilar)) === 1
                    && $pilar[0] === 'layak';
            })->count();

            $tidakLayakKK = $totalKK - $layakKK;

            $layakRatio = $layakKK / $totalKK;

            if ($layakRatio >= 0.8) {
                $status = 'Layak';
            } elseif ($layakRatio >= 0.3) {
                $status = 'Cukup';
            } else {
                $status = 'Tidak Layak';
            }

            $rekomendasi[$desa->desa] = [
                'total_kk' => $totalKK,
                'kk_layak' => $layakKK,
                'kk_tidak_layak' => $tidakLayakKK,
                'status' => $status
            ];
        }

        $pegawaiChart = User::withCount('stbm')
            ->where('role', 'pegawai')
            ->orderByDesc('stbm_count')
            ->take(10)
            ->get();

        return view('main.beranda', compact(
            'total',
            'terbaru',
            'proses',
            'selesai',
            'layak',
            'tidakLayak',
            'pilarLayak',
            'rekomendasi',
            'pegawaiChart'
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

        $dataTerakhir = Stbm::with('wilayah', 'kk')
            ->where('pegawai_id', $pegawaiId)
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($item) {
                return [
                    'nama_kepala_kk' => $item->kk->nama_kepala_kk ?? '-',
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
