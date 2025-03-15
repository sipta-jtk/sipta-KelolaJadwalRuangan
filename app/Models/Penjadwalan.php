<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjadwalan extends Model
{
    use HasFactory;

    protected $table = 'penjadwalan';

    protected $fillable = [
        'sesi',
        'agenda',
        'id_ruangan',
        'tanggal',
        'id_kota',
        'nip',
        'start',
        'end',
    ];

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class, 'id_ruangan');
    }
}