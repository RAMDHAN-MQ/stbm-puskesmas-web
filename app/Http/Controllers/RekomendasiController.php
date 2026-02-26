<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wilayah;
use App\Models\Stbm;
use Illuminate\Support\Facades\DB;

class RekomendasiController extends Controller
{
    public function index(Request $request)
    {
        $filterTahun = $request->filter;

        // Ambil daftar tahun unik dari tabel stbm
        $tahun = DB::table('stbm')
            ->selectRaw('YEAR(created_at) as tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        $desas = Wilayah::all();
        $pertanyaan = DB::table('pertanyaan')->get()->groupBy('pilar');
        $rekomendasi = [];

        foreach ($desas as $desa) {

            $query = DB::table('stbm')
                ->where('wilayah_id', $desa->id)
                ->where('status', 'selesai');

            // Jika ada filter tahun
            if ($filterTahun) {
                $query->whereYear('created_at', $filterTahun);
            }

            $kks = $query->get();

            $totalKK = $kks->count();

            if ($totalKK == 0) {
                $rekomendasi[$desa->desa] = [
                    'total_kk' => 0,
                    'kk_layak' => 0,
                    'kk_tidak_layak' => 0,
                    'status' => 'Belum Ada Data',
                    'capaian_pilar' => [],
                    'analisis' => 'Tidak ada data KK yang selesai di desa ini.'
                ];
                continue;
            }

            $capaian_pilar = [];
            for ($i = 1; $i <= 5; $i++) {
                $layakCount = $kks->where("pilar_$i", 'layak')->count();
                $capaian_pilar[$i] = round(($layakCount / $totalKK) * 100, 1);
            }

            $layakKK = $kks->filter(function ($kk) {
                $pilar = [$kk->pilar_1, $kk->pilar_2, $kk->pilar_3, $kk->pilar_4, $kk->pilar_5];
                return count(array_unique($pilar)) === 1 && $pilar[0] === 'layak';
            })->count();

            $tidakLayakKK = $totalKK - $layakKK;
            $layakRatio = $layakKK / $totalKK;

            if ($layakRatio >= 0.8) {
                $status = 'Layak';
                $analisis = 'Mayoritas KK sudah memenuhi semua pilar STBM (≥80%), tidak perlu intervensi.';
            } elseif ($layakRatio >= 0.3) {
                $status = 'Cukup';
                $analisis = 'Beberapa pilar belum optimal, disarankan intervensi fokus di pilar yang capaian <80%.';
            } else {
                $status = 'Tidak Layak';
                $analisis = 'Mayoritas KK belum memenuhi pilar, perlu prioritas intervensi pada pilar dengan capaian rendah.';
            }

            $rekomendasi[$desa->desa] = [
                'total_kk' => $totalKK,
                'kk_layak' => $layakKK,
                'kk_tidak_layak' => $tidakLayakKK,
                'status' => $status,
                'capaian_pilar' => $capaian_pilar,
                'analisis' => $analisis
            ];
        }

        return view('main.rekomendasi.index', compact('rekomendasi', 'pertanyaan', 'tahun', 'filterTahun'));
    }

    public function perdesa(Request $request)
    {
        $filterTahun = $request->tahun;
        $desaId = $request->desa;

        $daftarDesa = Wilayah::orderBy('desa')->get();

        $tahunList = DB::table('stbm')
            ->selectRaw('YEAR(created_at) as tahun')
            ->distinct()
            ->orderBy('tahun', 'asc')
            ->pluck('tahun');

        $timeseries = [];
        $desaDipilih = null;
        $detailPerTahun = [];
        $lastRasio = null;
        $prevRasio = null;

        if ($desaId) {
            $desaDipilih = Wilayah::find($desaId);
            $detailPerTahun = [];
            $lastRasio = null;
            $prevRasio = null;

            foreach ($tahunList as $tahun) {

                $query = DB::table('stbm')
                    ->where('wilayah_id', $desaId)
                    ->where('status', 'selesai')
                    ->whereYear('created_at', $tahun);

                $kks = $query->get();
                $totalKK = $kks->count();

                if ($totalKK == 0) {
                    $timeseries[$tahun] = 0;
                    $detailPerTahun[$tahun] = [
                        'total' => 0,
                        'layak' => 0,
                        'rasio' => 0
                    ];
                    continue;
                }

                $layakKK = $kks->filter(function ($kk) {
                    $pilar = [$kk->pilar_1, $kk->pilar_2, $kk->pilar_3, $kk->pilar_4, $kk->pilar_5];
                    return count(array_unique($pilar)) === 1 && $pilar[0] === 'layak';
                })->count();

                $rasio = round(($layakKK / $totalKK) * 100, 2);

                $timeseries[$tahun] = $rasio;

                $detailPerTahun[$tahun] = [
                    'total' => $totalKK,
                    'layak' => $layakKK,
                    'rasio' => $rasio
                ];
            }
            if (count($timeseries) >= 2) {
                $values = array_values($timeseries);
                $lastRasio = end($values);
                $prevRasio = $values[count($values) - 2];
            }
        }

        return view('main.rekomendasi.perdesa', compact(
            'daftarDesa',
            'tahunList',
            'desaId',
            'desaDipilih',
            'timeseries',
            'detailPerTahun',
            'lastRasio',
            'prevRasio'
        ));
    }

    // statistik di mobile stbm
    public function statistik(Request $request)
    {
        $pegawaiId = $request->pegawai_id;
        $tahun = $request->tahun;

        $tahunList = Stbm::where('pegawai_id', $pegawaiId)
            ->select(DB::raw('YEAR(created_at) as tahun'))
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        $query = Stbm::where('pegawai_id', $pegawaiId);

        if ($tahun) {
            $query->whereYear('created_at', $tahun);
        }

        $perDesa = $query->select('wilayah_id', DB::raw('count(*) as total'))
            ->with('wilayah')
            ->groupBy('wilayah_id')
            ->get()
            ->map(function ($item) {
                return [
                    'desa' => $item->wilayah->desa,
                    'total' => $item->total,
                ];
            });

        $pilar = [];

        for ($i = 1; $i <= 5; $i++) {
            $pilar["pilar_$i"] = [
                'layak' => Stbm::where('pegawai_id', $pegawaiId)
                    ->when($tahun, fn($q) => $q->whereYear('created_at', $tahun))
                    ->where("pilar_$i", 'layak')
                    ->count(),

                'tidak_layak' => Stbm::where('pegawai_id', $pegawaiId)
                    ->when($tahun, fn($q) => $q->whereYear('created_at', $tahun))
                    ->where("pilar_$i", 'tidak_layak')
                    ->count(),
            ];
        }

        return response()->json([
            'tahun_list' => $tahunList,
            'per_desa' => $perDesa,
            'pilar' => $pilar,
        ]);
    }
}
