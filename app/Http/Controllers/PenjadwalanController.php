<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePenjadwalanRequest;
use App\Http\Requests\UpdatePenjadwalanRequest;
use App\Models\Penjadwalan;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PenjadwalanController extends Controller
{
    /**
     * Constructor untuk menambahkan middleware.
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Menampilkan jadwal.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Penjadwalan::whereDate('start', '>=', $request->start)
                ->whereDate('end',   '<=', $request->end)
                ->select(['id_penjadwalan' , 
                        'agenda', 
                        'start', 
                        'end', 
                        'id_ruangan',
                        'id_kota'
                        ])
                        ->get();
            return response()->json($data);
        }
        return view('calendar');
    }

    /**
     * Menampilkan jadwal kegiatan pada waktu dan ruang tertentu.
     */
    public function agenda()
    {
        $events = DB::table('penjadwalan')
            ->join('ruangan', 'penjadwalan.id_ruangan', '=', 'ruangan.id_ruangan') // JOIN tabel ruangan
            ->select(
                'penjadwalan.id_penjadwalan', 
                'penjadwalan.agenda', 
                'penjadwalan.start', 
                'penjadwalan.end', 
                'penjadwalan.tanggal',
                'penjadwalan.id_ruangan',
                'ruangan.nama_ruangan',
                'penjadwalan.id_kota'
            )
            ->get()
            ->map(function ($event) {
                $event->start = \Carbon\Carbon::parse($event->start)->toIso8601String();
                $event->end = \Carbon\Carbon::parse($event->end)->toIso8601String();
                return $event;
            });

        return response()->json($events);
    }


    /**
     * Mengambil sesi yang tersedia pada tanggal tertentu.
     */
    public function sesiUntukPenjadwalan($tanggal)
    {
        $sessions = [1, 2, 3, 4]; 

        $scheduledSessions = DB::table('penjadwalan')
            ->where('tanggal', $tanggal)
            ->pluck('sesi')
            ->toArray();

        $availableSessions = array_diff($sessions, $scheduledSessions);

        return response()->json(array_values($availableSessions));
    }
}
