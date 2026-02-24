@extends('layouts.master')
@section('title', 'Rekomendasi Detail STBM')

@section('content')
<h2 class="mb-3">Rekomendasi Detail Wilayah STBM</h2>

<div class="mb-4">
    <a href="{{ route('admin.rekomendasi.index') }}" class="btn btn-success hover">Semua Desa</a>
    <a href="{{ route('admin.rekomendasi.perdesa') }}" class="btn btn-outline-success">Per Desa</a>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-success text-white">
        <strong>Filter Data Rekomendasi</strong>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.rekomendasi.index') }}">
            <div class="row align-items-center g-3">
                <div class="col-md-4">
                    <select name="filter" class="form-select shadow-sm" onchange="this.form.submit()">
                        <option value="">Semua Tahun</option>
                        @foreach($tahun as $t)
                            <option value="{{ $t }}" {{ $filterTahun == $t ? 'selected' : '' }}>
                                {{ $t }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-success text-white">
        <strong>Rasio Layak Desa</strong>
    </div>
    <div class="card-body" style="height:280px;">
        <canvas id="rasioChart"></canvas>
    </div>
</div>

<div class="bg-white p-4 rounded-3 shadow-sm">
    <table class="table table-striped table-hover table-bordered align-middle">
        <thead class="table-success">
            <tr>
                <th>Desa</th>
                <th>KK Selesai</th>
                <th>KK Layak</th>
                <th>KK Tidak Layak</th>
                <th>Status Desa</th>
                <th>Pilar 1</th>
                <th>Pilar 2</th>
                <th>Pilar 3</th>
                <th>Pilar 4</th>
                <th>Pilar 5</th>
                <th>Analisis / Saran Tindakan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rekomendasi as $desa => $data)
            <tr>
                <td>{{ $desa }}</td>
                <td class="text-center">{{ $data['total_kk'] }}</td>
                <td class="text-center">{{ $data['kk_layak'] }}</td>
                <td class="text-center">{{ $data['kk_tidak_layak'] }}</td>
                <td>
                    @if($data['status'] == 'Layak')
                    <span class="badge bg-success">{{ $data['status'] }}</span>
                    @elseif($data['status'] == 'Cukup')
                    <span class="badge bg-warning">{{ $data['status'] }}</span>
                    @elseif($data['status'] == 'Tidak Layak')
                    <span class="badge bg-danger">{{ $data['status'] }}</span>
                    @else
                    <span class="badge bg-secondary">{{ $data['status'] }}</span>
                    @endif
                </td>
                @for($i=1;$i<=5;$i++)
                    <td>{{ $data['capaian_pilar'][$i] ?? 0 }}%</td>
                    @endfor
                    <td>{{ $data['analisis'] }}</td>
            </tr>
            @endforeach
        </tbody>

    </table>

    <div class="bg-warning bg-opacity-10 text-dark p-3 rounded-3 mt-4">
        <h4><i class="bi bi-circle-fill text-warning me-2"></i>Keterangan</h4>
        <ul>
            <li>
                Persentase Pilar:
                <strong>Capaian Pilar (%) = (Jumlah Pilar (1/2/3/4/5) KK Yang Layak / Total Pilar (1/2/3/4/5) KK ) × 100%</strong>
            </li>
            <li>
                Rasio Layak:
                <strong>Rasio Layak = KK Layak / Total KK</strong>
                <ul>
                    <li>Layak: ≥ 80% KK sepenuhnya layak</li>
                    <li>Cukup: 30% ≤ KK sepenuhnya layak < 80%</li>
                    <li>Tidak Layak: < 30% KK sepenuhnya layak</li>
                </ul>
            </li>
        </ul>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const desaLabels = @json(array_values(array_keys($rekomendasi)));

    const rasioLayak = @json(
        array_values(array_map(function($d) {
            if (($d['total_kk'] ?? 0) == 0) return 0;
            return round(($d['kk_layak'] / $d['total_kk']) * 100, 2);
        }, $rekomendasi))
    );

    const statusDesa = @json(
        array_values(array_map(fn($d) => $d['status'] ?? 'Tidak Diketahui', $rekomendasi))
    );


    // Warna batang berdasarkan status
    const barColors = statusDesa.map(status => {
        if (status === 'Layak') return 'rgba(25, 135, 84, 0.8)'; // hijau
        if (status === 'Cukup') return 'rgba(255, 193, 7, 0.8)'; // kuning
        if (status === 'Tidak Layak') return 'rgba(220, 53, 69, 0.8)'; // merah
        return 'rgba(108, 117, 125, 0.8)'; // abu
    });

    const ctx = document.getElementById('rasioChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: desaLabels,
            datasets: [{
                label: 'Rasio KK Layak (%)',
                data: rasioLayak,
                backgroundColor: barColors
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100
                }
            }
        }
    });
</script>
@endpush