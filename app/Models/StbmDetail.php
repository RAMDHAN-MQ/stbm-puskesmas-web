<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StbmDetail extends Model
{
    use HasFactory;
    protected $table = 'stbm_detail';

    public $timestamps = false;

    protected $fillable = [
        'stbm_id',
        'pertanyaan_id',
        'jawaban',
    ];

    public function stbm()
    {
        return $this->belongsTo(Stbm::class, 'stbm_id');
    }

    public function pertanyaan()
    {
        return $this->belongsTo(Pertanyaan::class, 'pertanyaan_id');
    }
}
