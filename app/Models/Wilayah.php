<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wilayah extends Model
{
    use HasFactory;
    protected $table = 'wilayah';

    protected $fillable = [
        'kota',
        'kecamatan',
        'desa',
    ];

    public function stbm()
    {
        return $this->hasMany(Stbm::class, 'wilayah_id');
    }
}
