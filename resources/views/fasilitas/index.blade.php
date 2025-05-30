@extends('layouts.fasilitas')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <!-- Header Section with Shadow -->
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h1 class="mb-0">Manajemen Fasilitas</h1>
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
                    <li class="breadcrumb-item active" aria-current="page">Manajemen Fasilitas</li>
                </ol>
            </nav>

            <!-- Form Input Card with Soft Shadow -->
            <div class="card shadow-sm mb-4 border-0 rounded-1">
                <div class="card-header bg-white py-3 text-end">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahFasilitasModal">
                        <i class="fas fa-plus me-1"></i>Tambah Fasilitas
                    </button>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="fasilitasTable" class="table table-hover align-middle">
                            <thead class="sticky-header">
                                <tr class="table-dark" >
                                    <th width="5%" class="text-center">No</th>
                                    <th>Nama Fasilitas</th>
                                    <th width="15%" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($fasilitas as $index => $item)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $item->nama_fasilitas }}</td>
                                    <td class="text-center">
                                        <button class="btn btn-warning btn-md " 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editModal{{ $item->id_fasilitas }}" 
                                                title="Edit">
                                            <i class="fas fa-edit text-white"></i>
                                        </button>
                                        <form action="{{ route('fasilitas.destroy', $item->id_fasilitas) }}" 
                                                method="POST" class="d-inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            @if($item->tersedia)
                                                <span data-bs-toggle="tooltip" 
                                                        data-bs-placement="top"
                                                        title="Tidak dapat dihapus karena fasilitas sedang digunakan ruangan">
                                                    <button type="submit" 
                                                            class="btn btn-danger btn-md" 
                                                            disabled>
                                                        <i class="fas fa-trash text-white"></i>
                                                    </button>
                                                </span>
                                            @else
                                                <button type="submit" 
                                                        class="btn btn-danger btn-md delete-btn"
                                                        data-id="{{ $item->id_fasilitas }}"
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
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
    </div>
</div>

<!-- Modal Tambah Fasilitas -->
<div class="modal fade" id="tambahFasilitasModal" tabindex="-1" aria-labelledby="tambahFasilitasModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="tambahFasilitasModalLabel">
                    <i class="fas fa-plus me-2"></i>Tambah Fasilitas
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('fasilitas.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    @if($errors->tambahFasilitas->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->tambahFasilitas->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    <div class="mb-3">
                        <label for="nama_fasilitas" class="form-label">Nama Fasilitas</label>
                        <input type="text" class="form-control {{ $errors->tambahFasilitas->has('nama_fasilitas') ? 'is-invalid' : '' }}" 
                                id="nama_fasilitas" 
                                name="nama_fasilitas" 
                                required
                                minlength="2"
                                maxlength="100"
                                pattern="[A-Za-z0-9\s]+"
                                title="Nama fasilitas tidak boleh mengandung simbol"
                                placeholder="Masukkan nama fasilitas"
                                value="{{ old('nama_fasilitas') }}">
                        @if($errors->tambahFasilitas->has('nama_fasilitas'))
                            <div class="invalid-feedback">
                                {{ $errors->tambahFasilitas->first('nama_fasilitas') }}
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
@foreach($fasilitas as $item)
<div class="modal fade" id="editModal{{ $item->id_fasilitas }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light">
                <h5 class="modal-title">
                    <i class="fas fa-edit me-2"></i>Edit Fasilitas
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('fasilitas.update', $item->id_fasilitas) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    @if($errors->{'editFasilitas_'.$item->id_fasilitas}->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->{'editFasilitas_'.$item->id_fasilitas}->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    <div class="mb-3">
                        <label for="edit_nama_fasilitas" class="form-label">Nama Fasilitas</label>
                        <input type="text" class="form-control {{ $errors->{'editFasilitas_'.$item->id_fasilitas}->has('nama_fasilitas') ? 'is-invalid' : '' }}" 
                                id="edit_nama_fasilitas" 
                                name="nama_fasilitas" 
                                value="{{ old('nama_fasilitas', $item->nama_fasilitas) }}" 
                                required
                                minlength="2"
                                maxlength="100"
                                pattern="[A-Za-z0-9\s]+"
                                title="Nama fasilitas tidak boleh mengandung simbol">
                        @if($errors->{'editFasilitas_'.$item->id_fasilitas}->has('nama_fasilitas'))
                            <div class="invalid-feedback">
                                {{ $errors->{'editFasilitas_'.$item->id_fasilitas}->first('nama_fasilitas') }}
                            </div>
                        @endif
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Tutup
                    </button>
                    <button type="submit" class="btn btn-success" id="submitEdit{{ $item->id_fasilitas }}">
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
    document.querySelector('form[action="{{ route('fasilitas.store') }}"]').addEventListener('submit', function(e) {
        const submitButton = document.getElementById('submitCreate');
        showLoading(submitButton);
    });

    // Handle edit form submissions
    document.querySelectorAll('form[action^="{{ route('fasilitas.update', '') }}"]').forEach(form => {
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
            
            if (confirm('Apakah Anda yakin ingin menghapus fasilitas ini?')) {
                showLoading(deleteButton);
                this.submit();
            }
        });
    });

    // Initialize DataTable
    jQuery(document).ready(function($) {
        var table = $('#fasilitasTable').DataTable({
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
    
    // Show tambah fasilitas modal if there are validation errors
    @if($errors->tambahFasilitas->any() || session('showTambahFasilitasModal'))
        document.addEventListener('DOMContentLoaded', function() {
            var tambahModal = new bootstrap.Modal(document.getElementById('tambahFasilitasModal'));
            tambahModal.show();
        });
    @endif
    
    // Show edit modal for specific fasilitas if there are validation errors
    @if(session('showEditFasilitasModal'))
        document.addEventListener('DOMContentLoaded', function() {
            var editModal = new bootstrap.Modal(document.getElementById('editModal{{ session('showEditFasilitasModal') }}'));
            editModal.show();
        });
    @endif
</script>
@endsection