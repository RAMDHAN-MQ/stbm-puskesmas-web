<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class StbmExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    ShouldAutoSize
{
    protected $data;
    protected $no = 0;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'No',
            'No KK',
            'Nama Kepala Keluarga',
            'Desa',
            'RT',
            'RW',
            'Jumlah Jiwa',
            'Jumlah Jiwa Menetap',
            'Pilar 1',
            'Pilar 2',
            'Pilar 3',
            'Pilar 4',
            'Pilar 5',
            'Status',
            'Petugas',
            'Tanggal'
        ];
    }

    public function map($item): array
    {
        return [
            ++$this->no,
            $item->no_kk,
            $item->nama_kepala_kk,
            $item->wilayah->desa ?? '-',
            str_pad($item->rt, 3, '0', STR_PAD_LEFT),
            str_pad($item->rw, 3, '0', STR_PAD_LEFT),
            $item->jumlah_jiwa,
            $item->jumlah_jiwa_menetap,
            $item->pilar_1,
            $item->pilar_2,
            $item->pilar_3,
            $item->pilar_4,
            $item->pilar_5,
            strtoupper($item->status),
            $item->pegawai->nama ?? '-',
            $item->created_at->format('d-m-Y'),
        ];
    }
}
