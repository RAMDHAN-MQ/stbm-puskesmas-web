<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KK extends Model
{
    use HasFactory;
    protected $table = 'kk';
    protected $primaryKey = 'no_kk';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'no_kk',
        'nama_kepala_kk',
        'wilayah_id',
        'rt',
        'rw',
        'jumlah_jiwa',
        'jumlah_jiwa_menetap',
    ];

    public function wilayah()
    {
        return $this->belongsTo(Wilayah::class, 'wilayah_id');
    }

    public function stbm()
    {
        return $this->hasMany(Stbm::class, 'no_kk');
    }
}
