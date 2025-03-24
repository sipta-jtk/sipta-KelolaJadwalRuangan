@extends('layouts.gedung')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <!-- Header Section with Shadow -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <a href="{{ route('ruangan.index') }}" class="btn btn-outline-warning">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
                <h2 class="mb-0 text-center flex-grow-1"><i class="fas fa-building me-2"></i>Manajemen Gedung</h2>
                <div style="width: 85px;"></div><!-- Spacer untuk menyeimbangkan layout -->
            </div>

            <!-- Form Input Card with Soft Shadow -->
            <div class="card shadow-sm mb-4 border-0 rounded-3">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0 text-warning">
                        <i class="fas fa-plus-circle me-2"></i>Tambah Gedung
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('gedung.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="kode_gedung" class="form-label">Kode Gedung</label>
                                <input type="text" class="form-control @error('kode_gedung') is-invalid @enderror" 
                                    id="kode_gedung" 
                                    name="kode_gedung" 
                                    required 
                                    maxlength="1"
                                    pattern="[A-Za-z]{1}"
                                    placeholder="Contoh: A"
                                    style="text-transform: uppercase;"
                                    value="{{ old('kode_gedung') }}">
                                @error('kode_gedung')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-8 mb-3">
                                <label for="nama_gedung" class="form-label">Nama Gedung</label>
                                <input type="text" class="form-control" id="nama_gedung" 
                                    name="nama_gedung" required
                                    placeholder="Masukkan nama gedung">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-plus me-2"></i>Tambah
                        </button>
                    </form>
                </div>
            </div>

            <!-- Search Card -->
            <div class="card shadow-sm mb-4 border-0 rounded-3">
                <div class="card-body">
                    <form action="{{ route('gedung.index') }}" method="GET">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="fas fa-search text-warning"></i>
                            </span>
                            <input type="text" class="form-control border-start-0 ps-0" 
                                placeholder="Cari gedung..." name="search" 
                                value="{{ request('search') }}">
                        </div>
                    </form>
                </div>
            </div>

            <!-- Table Card -->
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
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
                                        <button class="btn btn-link text-warning p-0 me-2" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editModal{{ $item->kode_gedung }}"
                                                title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        
                                        @if($item->ruangan_count > 0)
                                            <button type="button" 
                                                    class="btn btn-link text-secondary p-0" 
                                                    disabled
                                                    title="Gedung memiliki {{ $item->ruangan_count }} ruangan">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @else
                                            <form action="{{ route('gedung.destroy', $item->kode_gedung) }}" 
                                                  method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-link text-danger p-0" 
                                                        onclick="return confirm('Apakah Anda yakin ingin menghapus gedung ini?')"
                                                        title="Hapus">
                                                    <i class="fas fa-trash"></i>
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
                    <div class="mb-3">
                        <label class="form-label">Kode Gedung</label>
                        <input type="text" class="form-control" value="{{ $item->kode_gedung }}" 
                               disabled readonly>
                    </div>
                    <div class="mb-3">
                        <label for="edit_nama_gedung" class="form-label">Nama Gedung</label>
                        <input type="text" class="form-control" id="edit_nama_gedung" 
                               name="nama_gedung" value="{{ $item->nama_gedung }}" required>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
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
</script>
@endsection
