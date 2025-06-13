@extends('layouts.ruangan')

@section('css')
    <style>
        /* Base styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        
        .sidebar {
            background-color: #343a40;
            color: white;
            height: 100vh;
            padding: 20px;
        }
        
        .content {
            padding: 30px;
            background-color: #f8f9fa;
            background: linear-gradient(to bottom right, #f8f9fa, #ffffff);
            min-height: 100vh;
        }
        
        /* Page header section */
        .page-header {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e9ecef;
        }
        
        .page-title {
            color: #2c3e50;
            font-weight: 700;
            font-size: 2.2rem;
            margin-bottom: 20px;
        }
        
        /* Action buttons at top */
        .action-top-buttons {
            display: flex;
            justify-content: flex-end;
            gap: 15px;
            margin-bottom: 30px;
        }
        
        .btn-action {
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 500;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        
        .btn-action:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 10px rgba(0,0,0,0.15);
        }
        
        /* Search box section */
        .search-box {
            background-color: white;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
        
        .search-row {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            align-items: center;
        }
        
        .search-col-select {
            flex: 1;
            min-width: 250px;
        }
        
        .search-col-input {
            flex: 2;
            min-width: 300px;
        }
        
        /* Form elements */
        .form-select {
            padding: 12px 15px;
            height: auto;
            border-radius: 8px;
            border: 1px solid #ced4da;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            font-size: 1rem;
            transition: all 0.3s ease;
            background-color: #fff;
            cursor: pointer;
        }
        
        .form-select:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
        
        .input-group {
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            border-radius: 8px;
            overflow: hidden;
        }
        
        .input-group .form-control {
            padding: 12px 15px;
            border: 1px solid #ced4da;
            border-right: none;
            height: auto;
        }
        
        .input-group .btn {
            padding: 0 20px;
            font-weight: 500;
        }
        
        /* Table card */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            margin-bottom: 30px;
            overflow: hidden;
        }
        
        .card:hover {
            box-shadow: 0 12px 25px rgba(0,0,0,0.12);
        }
        
        .card-header {
            background-color: white;
            border-bottom: 1px solid #eee;
            padding: 1.5rem 2rem;
        }
        
        .card-header h5 {
            font-weight: 600;
            color: #2c3e50;
            margin: 0;
        }
        
        /* Table styles */
        .table-responsive {
            padding: 0;
        }
        
        .table {
            margin-bottom: 0;
        }
        
        .table th {
            font-weight: 600;
            padding: 15px 20px;
            background-color: #212529;
            color: #fff;
            border: none;
            border-bottom: 2px solid #dee2e6;
        }
        
        .table td {
            padding: 15px 20px;
            vertical-align: middle;
        }
        
        .table tbody tr {
            border-bottom: 1px solid #f2f2f2;
        }
        
        /* Status badge */
        .status-badge {
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
            display: inline-block;
            transition: all 0.3s ease;
        }
        
        /* Action buttons in table */
        .action-buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
        }
        
        .btn-sm {
            padding: 8px 12px;
            border-radius: 6px;
            transition: all 0.3s ease;
        }
        
        .btn-sm:hover {
            transform: translateY(-2px);
            box-shadow: 0 3px 6px rgba(0,0,0,0.1);
        }
        
        /* Empty state */
        .text-center {
            padding: 25px;
            color: #6c757d;
        }
        
        /* Alert messages */
        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .search-row {
                flex-direction: column;
                gap: 15px;
            }
            
            .search-col-select, 
            .search-col-input {
                width: 100%;
                min-width: 100%;
            }
            
            .action-top-buttons {
                flex-direction: column;
                align-items: stretch;
            }
            
            .table th, 
            .table td {
                padding: 12px 15px;
            }
        }
    </style>
@endsection

@section('content')
<div class="container-fluid p-4">
    <div class="row">
        <!-- Main Content -->
        <div class="col-md-12">
            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <h2 class="page-title">Ruangan</h2>
                    <div class="d-flex justify-content-end mb-4">
                        @if(Auth::check() || Session::get('token_authenticated'))
                            <div class="d-flex align-items-center">
                                <span class="me-3 text-muted">Hi, {{ Auth::check() ? Auth::user()->name : Session::get('token_user_name') }}</span>
                                
                                @if(Auth::check())
                                <a href="{{ route('penjadwalan.index') }}" class="btn btn-success btn-lg me-2">
                                    <i class="fas fa-tools me-2"></i>Kalender Penjadwalan
                                </a>
                                    
                                @endif
                                @if(Session::has('sipta_token'))
                                    {{-- User is from SIPTA, so use the SIPTA logout route --}}
                                    <form method="POST" action="{{ route('logout.to.sipta') }}">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-lg px-4">
                                            <i class="fas fa-sign-out-alt me-2"></i>Logout to SIPTA
                                        </button>
                                    </form>
                                @else
                                    {{-- User is local, use the regular logout route --}}
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-lg px-4">
                                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-4">
                                <i class="fas fa-sign-in-alt me-2"></i>Login
                            </a>
                            <a href="{{ route('register') }}" class="btn btn-outline-primary btn-lg ms-2 px-4">
                                <i class="fas fa-user-plus me-2"></i>Register
                            </a>
                        @endif
                    </div>
                </div>

                <div class="d-flex justify-content-end align-items-center mb-4">
                    <div class="d-flex gap-3">
                        <a href="{{ route('gedung.index') }}" class="btn btn-warning btn-action">
                            <i class="fas fa-plus me-2"></i> Gedung
                        </a>
                        <a href="{{ route('fasilitas.index') }}" class="btn btn-success btn-action">
                            <i class="fas fa-plus me-2"></i> Fasilitas
                        </a>
                        <a href="{{ route('ruangan.create') }}" class="btn btn-primary btn-action">
                            <i class="fas fa-plus me-2"></i> Ruangan
                        </a>
                    </div>
                </div>
            </div>
            <!-- Page Header with Buttons -->

            <!-- Search Box -->
            {{-- <div class="search-box mb-4">
                <form action="{{ route('ruangan.index') }}" method="GET">
                    <div class="row g-2">
                        <div class="col-md-4">
                            <select class="form-select shadow-sm" id="searchType" name="search_type">
                                <option value="">Pilih Tipe Pencarian</option>
                                <option value="nama_ruangan" {{ request('search_type') == 'nama_ruangan' ? 'selected' : '' }}>Search by Name</option>
                                <option value="kode_ruangan" {{ request('search_type') == 'kode_ruangan' ? 'selected' : '' }}>Search by Code</option>
                            </select>
                        </div>
                        <div class="col-md-8">
                            <div class="input-group">
                                <input type="text" class="form-control" name="keyword" value="{{ request('keyword') }}" placeholder="Masukkan kata kunci pencarian...">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div> --}}

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (request()->has('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    {{ request()->get('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    {{ $errors->first('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Table Card -->
            <div class="card">
                <div class="card-body">
                    <div class="table-container">
                        <table id="ruanganTable" class="table table-striped text-center mb-0">
                            <thead class="sticky-header">
                                <tr class="bg-dark text-white">
                                    <th style="width: 3%;">No</th>
                                    <th style="width: 15%;">Kode Ruangan</th>
                                    <th style="width: 37%;">Nama Ruangan</th>
                                    <th style="width: 15%;">Status</th>
                                    <th style="width: 15%;">Kode Gedung</th>
                                    <th style="width: 15%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ruangan as $key => $room)
                                <tr>
                                    <td class="px-4 py-3">{{ $key + 1 }}</td>
                                    <td class="px-4 py-3">{{ $room->kode_ruangan }}</td>
                                    <td class="px-4 py-3">
                                        <a href="#" class="room-name-link" data-id="{{ $room->id_ruangan }}">
                                            {{ $room->nama_ruangan }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-3">
                                        @if ($room->status_ruangan == 'tersedia')
                                            <span class="status-badge bg-success text-white rounded p-1">Tersedia</span>
                                        @else
                                            <span class="status-badge bg-danger text-white rounded p-1">Tidak Tersedia</span>
                                        @endif
                                    </td>
                                    <td class="align-middle">{{ $room->kode_gedung }}</td>
                                    <td class="align-middle">
                                        <div class="action-buttons justify-content-center">
                                            <a href="{{ route('ruangan.edit', $room->id_ruangan) }}" class="btn btn-warning btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('ruangan.destroy', $room->id_ruangan) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <div data-bs-toggle="tooltip" 
                                                    data-bs-placement="top"
                                                    data-bs-title="{{ $room->is_used ? 'Tidak dapat menghapus ruangan karena sedang digunakan dalam penjadwalan' : 'Delete' }}">
                                                    <button type="submit" 
                                                        class="btn btn-danger btn-sm" 
                                                        onclick="return confirm('Apakah Anda yakin ingin menghapus ruangan ini?')" 
                                                        {{$room->is_used ? 'disabled' : ''}}
                                                        >
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('ruangan.detail')

<script>
    $(document).ready(function () {
    var table = $('#ruanganTable').DataTable({
        columnDefs: [
            {
                targets: 0,
                searchable: false,
                orderable: false,
            },
        ],
        order: [[1, "asc"]],
        paging: true,
        lengthMenu: [10, 25, 50, 100],
        pageLength: 5,
        searching: true,
        ordering: true,
        info: true,
        autoWidth: false,
        language: {
            search: "Cari: ",
            lengthMenu: "",
            zeroRecords: "Data tidak ditemukan",
            info: " ",
            infoEmpty: "Tidak ada data tersedia",
            infoFiltered: "(difilter dari total MAX data)",
            paginate: {
                first: "<<",
                last: ">>",
                next: ">",
                previous: "<"
            }
        }
    });

    // Gunakan variabel table untuk menambahkan nomor urut
    table
        .on("order.dt search.dt draw.dt", function () {
            table
                .column(0, { search: "applied", order: "applied" })
                .nodes()
                .each(function (cell, i) {
                    cell.innerHTML = i + 1;
                });
        })
        .draw();
});
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>
</div>
@endsection