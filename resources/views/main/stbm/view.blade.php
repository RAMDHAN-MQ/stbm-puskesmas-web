@extends('layouts.master')

@section('title', 'Lihat STBM')

@section('content')

<div class="sticky-top py-2 mb-3" style="z-index: 1020; background-color: #f8f9fa;">
    <div class="d-flex justify-content-between align-items-center">
        <a class="btn btn-outline-success"
            href="{{ route('admin.stbm.index') }}">
            <i class="bi bi-chevron-left"></i> Kembali
        </a>

        @if($stbm->status === 'proses')
        <button type="button" class="btn btn-outline-success" id="btn-selesai">
            <i class="bi bi-check-circle me-1"></i> Tandai Selesai
        </button>

        <form id="form-selesai" action="{{ route('admin.stbm.selesai', $stbm->id) }}" method="POST" class="d-none">
            @csrf
            @method('PUT')
        </form>
        @endif
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-bordered">
            <tr>
                <th width=10%>Pegawai</th>
                <td>{{ $stbm->pegawai->nama ?? '-' }}</td>
            </tr>
            <tr>
                <th>Wilayah</th>
                <td>{{ $stbm->wilayah->desa ?? '-' }}</td>
            </tr>
            <tr>
                <th>No KK</th>
                <td>{{ $stbm->no_kk }}</td>
            </tr>
            <tr>
                <th>Kepala KK</th>
                <td>{{ $stbm->kk->nama_kepala_kk ?? '-' }}</td>
            </tr>
            <tr>
                <th>RT / RW</th>
                <td>
                    {{ str_pad($stbm->kk->rt, 3, '0', STR_PAD_LEFT) }}
                    /
                    {{ str_pad($stbm->kk->rw, 3, '0', STR_PAD_LEFT) }}
                </td>
            </tr>

            <tr>
                <th>Jumlah Jiwa</th>
                <td>{{ $stbm->kk->jumlah_jiwa ?? '-' }}</td>
            </tr>
            <tr>
                <th>Jumlah Jiwa Menetap</th>
                <td>{{ $stbm->kk->jumlah_jiwa_menetap ?? '-' }}</td>
            </tr>
            <tr>
                <th>Status</th>
                @if($stbm->status === 'selesai')
                <td><span class="badge rounded-pill text-bg-success bg-opacity-25 text-success">{{ $stbm->status }}</span></td>
                @else
                <td><span class="badge rounded-pill text-bg-primary bg-opacity-25 text-primary">{{ $stbm->status }}</span></td>
                @endif
            </tr>
            @if($stbm->status === 'selesai')
            <tr>
                <th>Pilar 1</th>
                <td>
                    <span class="badge rounded-pill
                {{ $stbm->pilar_1 === 'layak' ? 'bg-success' : 'bg-danger' }}">
                        {{ strtoupper(str_replace('_', ' ', $stbm->pilar_1)) }}
                    </span>
                </td>
            </tr>

            <tr>
                <th>Pilar 2</th>
                <td>
                    <span class="badge rounded-pill
                {{ $stbm->pilar_2 === 'layak' ? 'bg-success' : 'bg-danger' }}">
                        {{ strtoupper(str_replace('_', ' ', $stbm->pilar_2)) }}
                    </span>
                </td>
            </tr>

            <tr>
                <th>Pilar 3</th>
                <td>
                    <span class="badge rounded-pill
                {{ $stbm->pilar_3 === 'layak' ? 'bg-success' : 'bg-danger' }}">
                        {{ strtoupper(str_replace('_', ' ', $stbm->pilar_3)) }}
                    </span>
                </td>
            </tr>

            <tr>
                <th>Pilar 4</th>
                <td>
                    <span class="badge rounded-pill
                {{ $stbm->pilar_4 === 'layak' ? 'bg-success' : 'bg-danger' }}">
                        {{ strtoupper(str_replace('_', ' ', $stbm->pilar_4)) }}
                    </span>
                </td>
            </tr>

            <tr>
                <th>Pilar 5</th>
                <td>
                    <span class="badge rounded-pill
                {{ $stbm->pilar_5 === 'layak' ? 'bg-success' : 'bg-danger' }}">
                        {{ strtoupper(str_replace('_', ' ', $stbm->pilar_5)) }}
                    </span>
                </td>
            </tr>
            @endif

        </table>

        <hr>

        @php
        $grouped = $stbm->details->groupBy(fn($d) => $d->pertanyaan->pilar);
        @endphp

        @foreach ($grouped as $pilar => $details)
        <div class="card mb-3">
            <div class="card-header text-white" style="background-color: #198754;">
                <strong>Pilar {{ $pilar }}</strong>
            </div>

            <div class="card-body">
                @foreach ($details as $index => $detail)
                <div class="mb-2">
                    <strong>{{ $index + 1 }}. {{ $detail->pertanyaan->pertanyaan }}</strong><br>

                    Jawaban:
                    <span class="badge 
                    {{ $detail->jawaban == 'ya' ? 'bg-success' : 'bg-danger' }}">
                        {{ strtoupper($detail->jawaban) }}
                    </span>
                    <hr>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach

    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('btn-selesai')?.addEventListener('click', function() {
        Swal.fire({
            title: 'Tandai STBM sebagai selesai?',
            text: 'Data tidak dapat diubah kembali.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            cancelButtonColor: '#dc3545',
            confirmButtonText: 'Ya, Selesai'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form-selesai').submit();
            }
        });
    });
</script>
@endpush