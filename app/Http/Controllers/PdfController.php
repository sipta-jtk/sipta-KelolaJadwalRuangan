<?php

namespace App\Http\Controllers;

use App\Models\Penjadwalan;
use App\Models\Ruangan;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\PDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PdfController extends Controller
{
    /**
     * Generate and download PDF of room schedule for a specific date
     */
    public function downloadSchedulePdf(Request $request)
    {
        // Get the date from the request
        $date = $request->input('date');
        
        if (!$date) {
            return redirect()->back()->with('error', 'Tanggal tidak valid');
        }
        
        // Format the date
        $formattedDate = Carbon::parse($date)->format('Y-m-d');
        $displayDate = Carbon::parse($date)->format('d F Y');
        
        // Get all rooms
        $rooms = Ruangan::where('status_ruangan', 'tersedia')->get();
        
        // Get schedule for this date
        $schedules = Penjadwalan::with('ruangan')
            ->where('tanggal', $formattedDate)
            ->get();

        // Define agenda mapping from request parameters
        $agendaMapping = [
            'seminar_1' => $request->input('seminar_1', 'Seminar 1'),
            'seminar_2' => $request->input('seminar_2', 'Seminar 2'),
            'seminar_3' => $request->input('seminar_3', 'Seminar 3'),
            'sidang' => $request->input('sidang', 'Sidang'),
        ];

        // Define time slots (hours) for the schedule
        $timeSlots = ['06:00', '07:00', '08:00', '09:00', '10:00', '11:00', 
                      '12:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00'];
        
        // Map sessions to time slots (based on your existing code)
        $sessionMapping = [
            1 => '07:00',
            2 => '09:00',
            3 => '13:00',
            4 => '15:00'
        ];
        
        // Duration of each session in hours
        $sessionDuration = 2;
        
        // Prepare data for PDF
        $data = [
            'date' => $displayDate,
            'rooms' => $rooms,
            'schedules' => $schedules,
            'timeSlots' => $timeSlots,
            'sessionMapping' => $sessionMapping,
            'sessionDuration' => $sessionDuration,
            'agendaMapping' => $agendaMapping // Add agenda mapping to view data
        ];
        
        // Generate PDF
        $pdf = PDF::loadView('download', $data);
        
        // Download the PDF
        return $pdf->download('jadwal-ruangan-' . Carbon::parse($date)->format('d-m-Y') . '.pdf');
    }
}