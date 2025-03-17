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
     * Menampilkan jadwal.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Penjadwalan::whereDate('start', '>=', $request->start)
                ->whereDate('end',   '<=', $request->end)
                ->select(['id_penjadwalan as id' , 
                        'agenda as title', 
                        'start', 
                        'end', 
                        'id_ruangan'
                        ])
                        ->get();
            return response()->json($data);
        }
        return view('fullcalendar');
    }

    /**
     * Menampilkan jadwal kegiatan pada waktu dan ruang tertentu.
     */
    public function getEvent()
    {
        $events = DB::table('penjadwalan')
            ->select('id_penjadwalan as id', 'agenda as title', 'start', 'end', 'id_ruangan')
            ->get()
            ->map(function ($event) {
                // Convert start and end to ISO8601 format with the timezone offset (e.g., UTC or your desired time zone)
                $event->start = \Carbon\Carbon::parse($event->start)->toIso8601String();  // Format to ISO8601
                $event->end = \Carbon\Carbon::parse($event->end)->toIso8601String();      // Format to ISO8601
                $event->resourceId = $event->id_ruangan;
                return $event;
            });

        return response()->json($events);
    }
}
