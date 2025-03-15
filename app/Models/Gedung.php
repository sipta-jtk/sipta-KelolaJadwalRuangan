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
}