<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Ruangan</title>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">
    <!-- Improved Authentication Buttons -->
    <div class="d-flex justify-content-end mb-4">
        @auth
            <div class="d-flex align-items-center">
                <span class="me-3 text-muted">Hi, {{ Auth::user()->name }}</span>
                
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('ruangan.index') }}" class="btn btn-success btn-lg me-2">
                        <i class="fas fa-tools me-2"></i>Admin Panel
                    </a>
                @endif
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-lg px-4">
                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                    </button>
                </form>
            </div>
        @else
            <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-4">
                <i class="fas fa-sign-in-alt me-2"></i>Login
            </a>
            <a href="{{ route('register') }}" class="btn btn-outline-primary btn-lg ms-2 px-4">
                <i class="fas fa-user-plus me-2"></i>Register
            </a>
        @endauth
    </div>

    <h1 class="mb-5">Pengelolaan dan Penjadwalan Ruangan</h1>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-dark text-white text-center">
                    <h3 class="card-title ">Kalender</h3>
                </div>

                <div class="card-body">
                    <div class="col-3 mb-3">
                        <label for="calendarDate" class="form-label">Pilih Tanggal:</label>
                        <input type="date" id="calendarDate" class="form-control">
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <div>
                            <button id="prev-date" class="btn btn-secondary me-2">&lt;</button>
                            <button id="next-date" class="btn btn-secondary">&gt;</button>
                        </div>
                        <h4 id="current-date" class="mb-0 flex-grow-1 text-center"></h4>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered text-center mt-4">
                            <thead class="table-light">
                                <tr>
                                    <th>Ruangan</th>
                                    <th>06:00</th>
                                    <th>07:00</th>
                                    <th>08:00</th>
                                    <th>09:00</th>
                                    <th>10:00</th>
                                    <th>11:00</th>
                                    <th>12:00</th>
                                    <th>13:00</th>
                                    <th>14:00</th>
                                    <th>15:00</th>
                                    <th>16:00</th>
                                    <th>17:00</th>
                                    <th>18:00</th>
                                </tr>
                            </thead>
                            <tbody id="schedule-body">
                            </tbody>
                        </table>
                    </div>
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

<!-- Calendar JS -->
<script src="{{ asset('js/calendar.js') }}"></script>

</body>
</html>
