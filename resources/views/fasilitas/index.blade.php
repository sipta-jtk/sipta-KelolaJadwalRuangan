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
                <div class="card-body d-flex justify-content-end align-items-center mb-3">
                    <label for="search" class="me-2">Cari:</label>
                    <form action="{{ route('fasilitas.index') }}" method="GET">
                        <div class="input-group">
                            <input type="text" class="form-control" 
                                   placeholder="Cari fasilitas..." name="search" 
                                   value="{{ request('search') }}">
                        </div>
                    </form>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th width="5%" class="text-center">#</th>
                                    <th>Nama Fasilitas</th>
                                    <th width="15%" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($fasilitas as $index => $item)
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
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-md" 
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus fasilitas ini?')"
                                                    title="Hapus">
                                                <i class="fas fa-trash text-white"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">
                                        <i class="fas fa-inbox fa-2x mb-3 d-block"></i>
                                        Tidak ada data fasilitas
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
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->tambahFasilitas->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <div class="mb-3">
                        <label for="nama_fasilitas" class="form-label">Nama Fasilitas</label>
                        <input type="text" class="form-control {{ $errors->tambahFasilitas->has('nama_fasilitas') ? 'is-invalid' : '' }}" 
                               id="nama_fasilitas" 
                               name="nama_fasilitas" 
                               required
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
                    <button type="submit" class="btn btn-success">
                        Simpan
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
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->{'editFasilitas_'.$item->id_fasilitas}->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <div class="mb-3">
                        <label for="edit_nama_fasilitas" class="form-label">Nama Fasilitas</label>
                        <input type="text" class="form-control {{ $errors->{'editFasilitas_'.$item->id_fasilitas}->has('nama_fasilitas') ? 'is-invalid' : '' }}" 
                               id="edit_nama_fasilitas" 
                               name="nama_fasilitas" 
                               value="{{ old('nama_fasilitas', $item->nama_fasilitas) }}" 
                               required>
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
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-2"></i>Simpan
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
