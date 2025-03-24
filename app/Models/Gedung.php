<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gedung extends Model
{
    use HasFactory;

    protected $table = 'gedung';

    protected $primaryKey = 'kode_gedung';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kode_gedung',
        'nama_gedung',
    ];

    // Relasi ke ruangan
    public function ruangan()
    {
        return $this->hasMany(Ruangan::class, 'kode_gedung', 'kode_gedung');
    }

    // Mutator untuk memastikan kode_gedung selalu uppercase
    public function setKodeGedungAttribute($value)
    {
        $this->attributes['kode_gedung'] = strtoupper($value);
    }
}