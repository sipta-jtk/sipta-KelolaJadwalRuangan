<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fasilitas extends Model
{
    use HasFactory;

    protected $table = 'fasilitas';
    protected $primaryKey = 'id_fasililtas';
    protected $fillable = [
        'id_fasilitas',
        'nama_fasilitas',
    ];

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class, 'id_fasilitas');
    }
}
