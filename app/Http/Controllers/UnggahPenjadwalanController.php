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

                // id kota yg sama gaboleh bikin lebih dari 1 jadwal di masa depan
                $latestScheduleDate = Penjadwalan::where('id_kota', $request->id_kota)
                    ->max('tanggal');
                
                $today = date('Y-m-d');

                if($tanggal <= $today){
                    return response()->json(['error' => 'Tanggal penjadwalan tidak valid.'], 400);                    
                }
                
                if ($latestScheduleDate &&  $tanggal > $latestScheduleDate && $latestScheduleDate >= $today) {
                    return response()->json(['error' => 'Kelompok TA telah membuat penjadwalan.'], 400);
                }

                // gaboleh ada yang bikin penjadwalan di tanggal, sesi, dan ruangan yg sama
                $duplicateScheduleExists = Penjadwalan::where('tanggal', $tanggal)
                    ->where('sesi', $sesi)
                    ->where('id_ruangan', $request->id_ruangan)
                    ->exists();

                if ($duplicateScheduleExists) {
                    return response()->json(['error' => 'Tidak bisa membuat penjadwalan! Sudah ada penjadwalan di tanggal, sesi, dan ruangan tersebut.'], 400);
                }

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

            if ($request->type == 'update') {
                $event = Penjadwalan::where('id_penjadwalan', $request->id)->first();
                if ($event) {
                    $today = date('Y-m-d');

                    if($tanggal <= $today){
                        return response()->json(['error' => 'Tanggal penjadwalan tidak valid.'], 400);                    
                    }
                    // gaboleh ada yang bikin penjadwalan di tanggal, sesi, dan ruangan yg sama
                    $duplicateScheduleExists = Penjadwalan::where('tanggal', $tanggal)
                        ->where('sesi', $sesi)
                        ->where('id_ruangan', $request->resourceId ?? $event->id_ruangan)
                        ->where('id_penjadwalan', '!=', $event->id_penjadwalan)
                        ->exists();
    
                    if ($duplicateScheduleExists) {
                        return response()->json(['error' => 'Tidak bisa membuat penjadwalan! Sudah ada penjadwalan di tanggal, sesi, dan ruangan tersebut.'], 400);
                    }

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
        }

        return response()->json(['error' => 'Invalid request'], 400);
    }
}