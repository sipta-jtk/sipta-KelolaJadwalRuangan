<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePenjadwalanRequest;
use App\Http\Requests\UpdatePenjadwalanRequest;
use App\Models\Penjadwalan;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class UnggahPenjadwalanController extends Controller
{
    
    public function action(Request $request)
    {
        $sessions = [
            1 => '07:00:00',
            2 => '09:00:00',
            3 => '13:00:00',
            4 => '15:00:00'
        ]; 
        
        $startTime = date('H:i:s', strtotime($request->start));
        $tanggal   = date('Y-m-d', strtotime($request->start));
        
        // konversi sesi
        $sesi = array_search($startTime, $sessions);

        if ($request->ajax()) {
            Log::info('Request Data:', $request->all());
            if ($request->type == 'add') {

                $event = Penjadwalan::create([
                    'agenda'     => $request->title,
                    'start'     => date('Y-m-d H:i:s', strtotime($request->start)), // Konversi format
                    'end'       => date('Y-m-d H:i:s', strtotime($request->end)),   // Konversi format
                    'tanggal'   => $tanggal,
                    'id_ruangan' => $request->id_ruangan,
                    'sesi'      => $sesi,
                    'id_kota'   => $request->id_kota ?? null,
                    'nip'       => $request->nip ?? null
                ]);

                return response()->json($event);
            }
        }

        if ($request->type == 'update') {
            $event = Penjadwalan::where('id_penjadwalan', $request->id)->first();
            if ($event) {
                $event->update([
                    'agenda'     => $request->title,
                    'start'     => date('Y-m-d H:i:s', strtotime($request->start)), // Konversi format
                    'end'       => date('Y-m-d H:i:s', strtotime($request->end)),   // Konversi format
                    'tanggal'   => $tanggal,
                    'id_ruangan' => $request->resourceId ?? $event->id_ruangan,
                    'sesi'      => $sesi,
                ]);
                return response()->json($event);
            }
        }

        if ($request->type == 'delete') {
            $event = Penjadwalan::where('id_penjadwalan', $request->id)->first();
            if ($event) {
                $event->delete();
                return response()->json(['message' => 'Event deleted successfully']);
            }
        }
        

        return response()->json(['error' => 'Invalid request'], 400);
    }
}