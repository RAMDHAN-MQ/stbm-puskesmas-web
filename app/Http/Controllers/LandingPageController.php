<?php

namespace App\Http\Controllers;

use App\Models\KK;
use App\Models\Stbm;
use App\Models\Wilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LandingPageController extends Controller
{
    public function index()
    {
        $totalkk = KK::count();

        $selesai = Stbm::where('status', 'selesai')
            ->whereYear('created_at', now()->year)
            ->count();

        $layak = Stbm::where('status', 'selesai')
            ->whereYear('created_at', now()->year)
            ->where('pilar_1', 'layak')
            ->where('pilar_2', 'layak')
            ->where('pilar_3', 'layak')
            ->where('pilar_4', 'layak')
            ->where('pilar_5', 'layak')
            ->count();

        $desa = Wilayah::count();

        $desas = Wilayah::all();

        $rekomendasi = [];

        foreach ($desas as $d) {

            $kks = Stbm::where('wilayah_id', $d->id)
                ->where('status', 'selesai')
                ->whereYear('created_at', now()->year)
                ->get();

            $totalKK = $kks->count();

            if ($totalKK == 0) {

                $rekomendasi[$d->desa] = [
                    'desa' => $d->desa,
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

            $rekomendasi[$d->desa] = [
                'desa' => $d->desa,
                'total_kk' => $totalKK,
                'kk_layak' => $layakKK,
                'kk_tidak_layak' => $tidakLayakKK,
                'status' => $status
            ];
        }

        // data status untuk peta
        $statusDesa = [];

        foreach ($rekomendasi as $namaDesa => $data) {
            $statusDesa[$namaDesa] = $data['status'];
        }

        return view('main.landing_page', compact(
            'totalkk',
            'selesai',
            'layak',
            'desa',
            'rekomendasi',
            'statusDesa'
        ));
    }
}
