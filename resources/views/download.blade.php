<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Jadwal Ruangan {{ $date }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
        }
        h1 {
            font-size: 16pt;
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        th, td {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
            font-size: 8pt;
            word-wrap: break-word;
        }
        th {
            background-color: #f2f2f2;
        }
        .room-name {
            font-weight: bold;
            text-align: left;
            width: 120px;
        }
        .reserved {
            background-color: #cce5ff;
            font-weight: bold;
        }
        th:nth-child(1) { width: 15%; }
        th:nth-child(n+2) { width: 6.1%; }
    </style>
</head>
<body>
    <h1>Jadwal Ruangan - {{ $date }}</h1>
    
    <table>
        <thead>
            <tr>
                <th>Ruangan</th>
                @foreach($timeSlots as $time)
                    <th>{{ $time }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($rooms as $room)
                <tr>
                    <td class="room-name">{{ $room->nama_ruangan }}</td>
                    @php
                        $skip = 0;
                        $sessionDuration = 2; // Example: Each session spans 2 hours
                    @endphp
                    
                    @foreach($timeSlots as $index => $time)
                        @if($skip > 0)
                            @php $skip--; @endphp
                            @continue
                        @endif

                        @php
                            $reserved = false;
                            $agenda = '';
                            $colspan = 1;
                            
                            foreach($schedules as $schedule) {
                                if ($schedule->id_ruangan == $room->id_ruangan) {
                                    $startTime = $sessionMapping[$schedule->sesi] ?? null;
                                    
                                    if ($startTime == $time) {
                                        $reserved = true;
                                        $mappedAgenda = $agendaMapping[$schedule->agenda] ?? $schedule->agenda;
                                        $agenda = $mappedAgenda . ' KoTA ' . $schedule->id_kota; 
                                        $colspan = $sessionDuration;
                                        break;
                                    }
                                }
                            }
                            
                            if ($reserved) {
                                $skip = $colspan - 1;
                            }
                        @endphp

                        @if($reserved)
                            <td class="reserved" colspan="{{ $colspan }}">{{ $agenda }}</td>
                        @else
                            <td></td>
                        @endif
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    <script src="{{ asset('js/calendar.js') }}"></script>


</body>
</html>