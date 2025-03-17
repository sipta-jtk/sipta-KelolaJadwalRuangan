<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fasilitas extends Model
{
    use HasFactory;

    protected $table = 'fasilitas';
    protected $primaryKey = 'id_fasilitas';
    public $timestamps = false;
    protected $fillable = [
        'nama_fasilitas',
        // tambahkan kolom lain yang diperlukan
    ];

    public function ruangan()
    {
        return $this->belongsToMany(Ruangan::class, 'ruang_fasilitas', 'id_fasilitas', 'id_ruangan')
                    ->withPivot('jumlah_fasilitas');
    }
}
