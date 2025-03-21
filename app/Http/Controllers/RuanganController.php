<?php

namespace App\Http\Controllers;

use App\Models\Ruangan;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class RuanganController extends Controller
{
    /**
     * Constructor untuk menambahkan middleware.
     */
    // public function __construct()
    // {
    //     $this->middleware('auth'); // Middleware untuk memastikan pengguna sudah login
    // }
    
    /**
     * Menampilkan nama ruangan yang tersedia.
     */
    public function namaRuanganTersedia()
    {
        $ruangan = DB::table('ruangan')
            ->select('id_ruangan', 'nama_ruangan')
            ->where('status_ruangan', 'Tersedia')
            ->get();
        return response()->json($ruangan);
    }

    /**
     * Menampilkan ruangan yang tersedia untuk pada tanggal dan sesi tertentu.
     */
    public function ruanganUntukPenjadwalan(Request $request)
    {
        $tanggal = date('Y-m-d', strtotime($request->tanggal));
        $sesi = $request->sesi;

        $availableRooms = DB::table('ruangan')
        ->leftJoin('penjadwalan', function ($join) use ($tanggal, $sesi) {
            $join->on('ruangan.id_ruangan', '=', 'penjadwalan.id_ruangan')
                ->where('penjadwalan.tanggal', '=', $tanggal)
                ->where('penjadwalan.sesi', '=', $sesi);
        })
        ->whereNull('penjadwalan.id_ruangan')
        ->where('ruangan.status_ruangan', '=', 'tersedia')
        ->select('ruangan.id_ruangan', 'ruangan.nama_ruangan')
        ->get();

        return response()->json($availableRooms);
    }
}