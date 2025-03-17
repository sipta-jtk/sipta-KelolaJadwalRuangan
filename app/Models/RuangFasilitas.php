<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RuangFasilitas extends Model
{
    use HasFactory;

    protected $table = 'ruang_fasilitas';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'id_fasilitas',
        'id_ruangan',
        'jumlah_fasilitas'
    ];

    public function fasilitas()
    {
        return $this->belongsTo(Fasilitas::class, 'id_fasilitas');
    }

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class, 'id_ruangan');
    }
}
