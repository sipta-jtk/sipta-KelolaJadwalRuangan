@extends('layouts.gedung')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <!-- Header Section with Shadow -->
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h1 class="mb-0">Manajemen Gedung</h1>
                <div style="width: 85px;"></div><!-- Spacer untuk menyeimbangkan layout -->
            </div>

            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('ruangan.index') }}" class="text-decoration-none text-primary">
                            Ruangan
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Manajemen Gedung</li>
                </ol>
            </nav>

            <!-- Form Input Card with Soft Shadow -->
            <div class="card shadow-sm mb-4 border-0 rounded-1">
                <div class="card-header bg-white py-3 text-end">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahGedungModal">
                        <i class="fas fa-plus me-1"></i>Tambah Gedung
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="gedungTable" class="table table-hover align-middle">
                            <thead class="sticky-header">
                                <tr class="table-dark">
                                    <th width="5%" class="text-center">No</th>
                                    <th width="20%">Kode Gedung</th>
                                    <th>Nama Gedung</th>
                                    <th width="15%" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($gedung as $index => $item)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $item->kode_gedung }}</td>
                                    <td>{{ $item->nama_gedung }}</td>
                                    <td class="text-center">
                                        <button class="btn btn-warning btn-md " 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editModal{{ $item->kode_gedung }}"
                                                title="Edit">
                                            <i class="fas fa-edit text-white"></i>
                                        </button>
                                        
                                        <form action="{{ route('gedung.destroy', $item->kode_gedung) }}" 
                                                method="POST" class="d-inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            @if($item->ruangan_count > 0)
                                                <span data-bs-toggle="tooltip" 
                                                        data-bs-placement="top"
                                                        title="Tidak dapat dihapus karena gedung sedang digunakan ruangan">
                                                    <button type="submit" 
                                                            class="btn btn-danger btn-md" 
                                                            disabled>
                                                        <i class="fas fa-trash text-white"></i>
                                                    </button>
                                                </span>
                                            @else
                                                <button type="submit" 
                                                        class="btn btn-danger btn-md delete-btn"
                                                        data-id="{{ $item->kode_gedung }}"
                                                        data-bs-toggle="tooltip"
                                                        data-bs-placement="top"
                                                        title="Hapus">
                                                    <span class="button-text">
                                                        <i class="fas fa-trash text-white"></i>
                                                    </span>
                                                    <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                                </button>
                                            @endif
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">
                                        <i class="fas fa-building fa-2x mb-3 d-block"></i>
                                        Tidak ada data gedung
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Gedung -->
<div class="modal fade" id="tambahGedungModal" tabindex="-1" aria-labelledby="tambahGedungModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="tambahGedungModalLabel">
                    <i class="fas fa-plus me-2"></i>Tambah Gedung
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('gedung.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    @if($errors->tambahGedung->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->tambahGedung->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    <div class="mb-3">
                        <label for="kode_gedung" class="form-label">Kode Gedung</label>
                        <input type="text" class="form-control {{ $errors->tambahGedung->has('kode_gedung') ? 'is-invalid' : '' }}" 
                               id="kode_gedung" 
                               name="kode_gedung" 
                               required 
                               maxlength="1"
                               pattern="[A-Za-z]{1}"
                               placeholder="Contoh: A"
                               style="text-transform: uppercase;"
                               value="{{ old('kode_gedung') }}">
                        @if($errors->tambahGedung->has('kode_gedung'))
                            <div class="invalid-feedback">
                                {{ $errors->tambahGedung->first('kode_gedung') }}
                            </div>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label for="nama_gedung" class="form-label">Nama Gedung</label>
                        <input type="text" class="form-control {{ $errors->tambahGedung->has('nama_gedung') ? 'is-invalid' : '' }}" 
                               id="nama_gedung" 
                               name="nama_gedung" 
                               required
                               minlength="3"
                               maxlength="100"
                               placeholder="Masukkan nama gedung"
                               value="{{ old('nama_gedung') }}">
                        @if($errors->tambahGedung->has('nama_gedung'))
                            <div class="invalid-feedback">
                                {{ $errors->tambahGedung->first('nama_gedung') }}
                            </div>
                        @endif
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                        Tutup
                    </button>
                    <button type="submit" class="btn btn-success" id="submitCreate">
                        <span class="button-text">Simpan</span>
                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modals -->
@foreach($gedung as $item)
<div class="modal fade" id="editModal{{ $item->kode_gedung }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light">
                <h5 class="modal-title">
                    <i class="fas fa-edit me-2"></i>Edit Gedung
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('gedung.update', $item->kode_gedung) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    @if($errors->{'editGedung_'.$item->kode_gedung}->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->{'editGedung_'.$item->kode_gedung}->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    <div class="mb-3">
                        <label for="edit_nama_gedung" class="form-label">Nama Gedung</label>
                        <input type="text" class="form-control {{ $errors->{'editGedung_'.$item->kode_gedung}->has('nama_gedung') ? 'is-invalid' : '' }}" 
                               id="edit_nama_gedung" 
                               name="nama_gedung" 
                               maxlength="100"
                               minlength="3"
                               value="{{ old('nama_gedung', $item->nama_gedung) }}" 
                               required>
                        @if($errors->{'editGedung_'.$item->kode_gedung}->has('nama_gedung'))
                            <div class="invalid-feedback">
                                {{ $errors->{'editGedung_'.$item->kode_gedung}->first('nama_gedung') }}
                            </div>
                        @endif
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Tutup
                    </button>
                    <button type="submit" class="btn btn-success" id="submitEdit{{ $item->kode_gedung }}">
                        <span class="button-text">
                            <i class="fas fa-save me-2"></i>Simpan
                        </span>
                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@if(session('success'))
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div class="toast show" role="alert">
        <div class="toast-header bg-success text-white">
            <i class="fas fa-check-circle me-2"></i>
            <strong class="me-auto">Sukses</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body">
            {{ session('success') }}
        </div>
    </div>
</div>
@endif

@if(session('error'))
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div class="toast show" role="alert">
        <div class="toast-header bg-danger text-white">
            <i class="fas fa-exclamation-circle me-2"></i>
            <strong class="me-auto">Error</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body">
            {{ session('error') }}
        </div>
    </div>
</div>
@endif
@endsection

@section('scripts')
<script>
    // Initialize Bootstrap tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    });

    // Function to show loading state
    function showLoading(button) {
        const buttonText = button.querySelector('.button-text');
        const spinner = button.querySelector('.spinner-border');
        
        button.disabled = true;
        buttonText.style.display = 'none';
        spinner.classList.remove('d-none');
    }

    // Handle create form submission
    document.querySelector('form[action="{{ route('gedung.store') }}"]').addEventListener('submit', function(e) {
        const submitButton = document.getElementById('submitCreate');
        showLoading(submitButton);
    });

    // Handle edit form submissions
    document.querySelectorAll('form[action^="{{ route('gedung.update', '') }}"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            const id = this.action.split('/').pop();
            const submitButton = document.getElementById('submitEdit' + id);
            showLoading(submitButton);
        });
    });

    // Handle delete form submissions
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const deleteButton = this.querySelector('.delete-btn');
            
            if (confirm('Apakah Anda yakin ingin menghapus gedung ini?')) {
                showLoading(deleteButton);
                this.submit();
            }
        });
    });

    // Initialize DataTable
    jQuery(document).ready(function($) {
        var table = $('#gedungTable').DataTable({
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
                infoEmpty: "",
                infoFiltered: "",
                paginate: {
                    first: "<<",
                    last: ">>",
                    next: ">",
                    previous: "<"
                }
            }
        });

        // Update row numbers
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

    // Auto-hide toast after 3 seconds
    setTimeout(function() {
        $('.toast').toast('hide');
    }, 3000);

    // Force uppercase for kode_gedung input
    document.getElementById('kode_gedung').addEventListener('input', function() {
        this.value = this.value.toUpperCase();
    });

    // Show tambah gedung modal if there are validation errors
    @if($errors->tambahGedung->any() || session('showTambahGedungModal'))
        document.addEventListener('DOMContentLoaded', function() {
            var tambahModal = new bootstrap.Modal(document.getElementById('tambahGedungModal'));
            tambahModal.show();
        });
    @endif

    // Show edit modal for specific gedung if there are validation errors
    @if(session('showEditGedungModal'))
        document.addEventListener('DOMContentLoaded', function() {
            var editModal = new bootstrap.Modal(document.getElementById('editModal{{ session('showEditGedungModal') }}'));
            editModal.show();
        });
    @endif
</script>
@endsection
