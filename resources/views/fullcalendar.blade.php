<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Jadwal Ruangan</title>

    <!-- FullCalendar CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fullcalendar/core/main.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fullcalendar/resource-timeline/main.min.css">

    <!-- Bootstrap CSS (pastikan kamu punya Bootstrap di proyek) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">
    <h1>Pengelolaan dan Penjadwalan Ruangan</h1>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Kalender</h3>
                </div>

                <div class="card-body">
                    <div class="col-3 mb-3">
                        <label for="calendarDate" class="form-label">Pilih Tanggal:</label>
                        <input type="date" id="calendarDate" class="form-control">
                    </div>
                    <div id="calendar"></div>
                </div>

                <!-- Modal untuk memilih event -->
                <div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="eventModalLabel">Penjadwalan</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <label for="eventSelect">Pilih Agenda:</label>
                                <select class="form-control" id="eventSelect" name="agenda">
                                    <option value="seminar_1">Seminar 1</option>
                                    <option value="seminar_2">Seminar 2</option>
                                    <option value="seminar_3">Seminar 3</option>
                                    <option value="sidang">Sidang</option>
                                </select>
                                <label for="sessionSelect">Pilih Sesi:</label>
                                <select class="form-control" id="sessionSelect" name="sesi">
                                    <option value="sesi_1">Sesi 1</option>
                                    <option value="sesi_2">Sesi 2</option>
                                    <option value="Sesi_3">Sesi 3</option>
                                    <option value="Sesi_4">Sesi 4</option>
                                </select>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-primary" id="saveEventBtn">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- FullCalendar JS -->
@vite('resources/js/app.js')

</body>
</html>
