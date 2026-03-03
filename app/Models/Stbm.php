<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stbm extends Model
{
    use HasFactory;
    protected $table = 'stbm';

    protected $fillable = [
        'pegawai_id',
        'no_kk',
        'wilayah_id',
        'status',
        'pilar_1',
        'pilar_2',
        'pilar_3',
        'pilar_4',
        'pilar_5',
    ];

    public function details()
    {
        return $this->hasMany(StbmDetail::class, 'stbm_id');
    }

    public function pegawai()
    {
        return $this->belongsTo(User::class, 'pegawai_id');
    }

    public function wilayah()
    {
        return $this->belongsTo(Wilayah::class, 'wilayah_id');
    }

    public function kk()
    {
        return $this->belongsTo(KK::class, 'no_kk');
    }
}
