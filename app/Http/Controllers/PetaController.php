<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wilayah;
use Illuminate\Support\Facades\DB;

class PetaController extends Controller
{
    public function index(Request $request)
    {
        $filterTahun = $request->filter;

        $tahun = DB::table('stbm')
            ->selectRaw('YEAR(created_at) as tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        $desas = Wilayah::with(['stbm' => function ($query) use ($filterTahun) {
            $query->where('status', 'selesai');

            if ($filterTahun) {
                $query->whereYear('created_at', $filterTahun);
            }
        }])->get();

        $statusDesa = [];

        foreach ($desas as $desa) {

            $kks = $desa->stbm;

            if ($kks->isEmpty()) {
                $statusDesa[$desa->desa] = 'Belum Ada Data';
                continue;
            }

            $totalKK = $kks->count();

            $layakKK = $kks->filter(function ($kk) {
                $pilar = [
                    $kk->pilar_1,
                    $kk->pilar_2,
                    $kk->pilar_3,
                    $kk->pilar_4,
                    $kk->pilar_5
                ];

                return count(array_unique($pilar)) === 1 && $pilar[0] === 'layak';
            })->count();

            $layakRatio = $layakKK / $totalKK;

            if ($layakRatio >= 0.8) {
                $statusDesa[$desa->desa] = 'Layak';
            } elseif ($layakRatio >= 0.3) {
                $statusDesa[$desa->desa] = 'Cukup';
            } else {
                $statusDesa[$desa->desa] = 'Tidak Layak';
            }
        }

        return view('main.peta.index', compact(
            'statusDesa',
            'tahun',
            'filterTahun'
        ));
    }
}
