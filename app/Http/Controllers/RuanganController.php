<?php

namespace App\Http\Controllers;

use App\Models\Ruangan;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class RuanganController extends Controller
{
    public function allruanganname()
    {
        $ruangan = DB::table('ruangan')
            ->select('id_ruangan', 'nama_ruangan')
            ->where('status_ruangan', 'Tersedia')
            ->get();
        return response()->json($ruangan);
    }

    public function ruanganUntukPenjadwalan(Request $request)
    {
        // mengambil data ruangan yang belum terjadwal pada tanggal tertentu
        // baca tanggal yang terpilih
        // baca sesi yang terpilih
        // ambil ruangan yang kosong pada tanggal dan sesi tersebut
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