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
                <div class="card-body d-flex justify-content-end align-items-center mb-3">
                <label for="search" class="me-2">Cari:</label>
                    <form action="{{ route('gedung.index') }}" method="GET">
                        <div class="input-group">
                            <input type="text" class="form-control"  name="search" 
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
                                        
                                        @if($item->ruangan_count > 0)
                                            <button type="button" 
                                                    class="btn btn-secondary btn-md" 
                                                    disabled
                                                    title="Gedung memiliki {{ $item->ruangan_count }} ruangan">
                                                <i class="fas fa-trash text-white"></i>
                                            </button>
                                        @else
                                            <form action="{{ route('gedung.destroy', $item->kode_gedung) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-danger btn-md" 
                                                        onclick="return confirm('Apakah Anda yakin ingin menghapus gedung ini?')"
                                                        title="Hapus">
                                                    <i class="fas fa-trash text-white"></i>
                                                </button>
                                            </form>
                                        @endif
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
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->tambahGedung->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
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
                    <button type="submit" class="btn btn-success">
                        Simpan
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
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->{'editGedung_'.$item->kode_gedung}->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <div class="mb-3">
                        <label for="edit_nama_gedung" class="form-label">Nama Gedung</label>
                        <input type="text" class="form-control {{ $errors->{'editGedung_'.$item->kode_gedung}->has('nama_gedung') ? 'is-invalid' : '' }}" 
                               id="edit_nama_gedung" 
                               name="nama_gedung" 
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

    // Force uppercase for kode_gedung input
    document.getElementById('kode_gedung').addEventListener('input', function() {
        this.value = this.value.toUpperCase();
    });

    // Show modal if there are validation errors
    @if(session('showTambahGedungModal') || $errors->tambahGedung->any())
        document.addEventListener('DOMContentLoaded', function() {
            var tambahModal = new bootstrap.Modal(document.getElementById('tambahGedungModal'));
            tambahModal.show();
        });
    @endif
</script>
@endsection
