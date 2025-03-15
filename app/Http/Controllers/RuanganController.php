<?php

namespace App\Http\Controllers;

use App\Models\Ruangan;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class RuanganController extends Controller
{
    public function allruanganname()
    {
        $ruangan = DB::table('ruangan')->select('id_ruangan AS id', 'nama_ruangan AS title')->get();
        return response()->json($ruangan);
    }
}