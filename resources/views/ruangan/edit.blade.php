@extends('layouts.ruangan')

@section('title', 'Edit Ruangan')

@section('css')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    .content-wrapper {
        background-color: #ffffff !important;
    }

    .card {
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
        border-radius: 25px;
        border: none;
        background: #fff;
    }

    .section-title {
        color: #0d6efd;
        font-size: 1rem;
        font-weight: 500;
        margin-bottom: 1.5rem;
    }

    .form-section {
        background: #f8f9fa;
        border-radius: 15px;
        padding: 1rem;
        margin-bottom: 1.5rem;
    }

    .form-label {
        color: #212529;
        font-weight: 500;
        margin-bottom: 0.5rem;
    }

    .form-control, .form-select {
        border-radius: 8px;
        padding: 0.75rem 1rem;
        border: 1px solid #dee2e6;
    }

    .form-control:focus, .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }

    .upload-area {
        border: 2px dashed #ddd;
        border-radius: 8px;
        padding: 30px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
        background-color: #f8f9fa;
        min-height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .upload-area:hover {
        border-color: #6c757d;
        background-color: #f1f3f5;
    }

    .facility-input-container {
        margin-bottom: 1rem;
    }

    .facility-input-group {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 0.5rem;
    }

    .facility-input-group .form-select {
        flex: 2;
    }

    .facility-input-group .form-control {
        flex: 1;
        max-width: 120px;
    }

    .btn-add {
        background: #0d6efd;
        color: white;
        border-radius: 8px;
        padding: 0.75rem 1rem;
    }

    .btn-submit {
        background: #198754;
        color: white;
        border-radius: 8px;
        padding: 0.75rem 2rem;
    }

    .btn-cancel {
        background: #dc3545;
        color: white;
        border-radius: 8px;
        padding: 0.75rem 2rem;
    }

    .action-buttons {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        margin-top: 2rem;
    }

    /* Override AdminLTE styles */
    .content-wrapper > .content {
        padding: 2rem;
    }

    #imageContainer {
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .btn-close {
        z-index: 10;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-close:hover {
        transform: scale(1.1);
        background-color: #dc3545 !important;
        color: white;
    }

    .drop-zone {
        max-width: 100%;
        height: 200px;
        padding: 25px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        border: 2px dashed #0d6efd;
        border-radius: 10px;
        background-color: #f8f9fa;
        cursor: pointer;
        transition: border 0.3s ease-in-out;
    }

    .drop-zone:hover {
        border-color: #0b5ed7;
        background-color: #e9ecef;
    }

    .drop-zone.dragover {
        border-color: #0b5ed7;
        background-color: #e9ecef;
    }

    .drop-zone__input {
        display: none;
    }

    .image-preview-wrapper {
        position: relative;
        display: inline-block;
    }

    #imagePreview {
        max-height: 300px;
        object-fit: contain;
    }

    .facility-list {
        max-height: 300px;
        overflow-y: auto;
    }

    .facility-item {
        background-color: #fff;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 12px 15px;
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: all 0.3s ease;
    }

    .facility-item:hover {
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        transform: translateY(-1px);
    }

    .facility-info {
        flex-grow: 1;
    }

    .facility-info strong {
        display: block;
        color: #2c3e50;
        margin-bottom: 2px;
    }

    .facility-info .text-muted {
        font-size: 0.875rem;
    }

    .facility-controls {
        display: flex;
        gap: 8px;
    }

    .facility-controls button {
        padding: 4px 8px;
        border-radius: 4px;
    }

    .invalid-feedback {
        display: block;
        margin-top: 0.5rem;
    }

    .facility-error-container {
        min-height: 24px; /* Tinggi minimum untuk menghindari lompatan layout */
    }

    .facility-error {
        color: #dc3545;
        font-size: 0.875rem;
        margin-top: 0.25rem;
        animation: fadeIn 0.3s ease-in-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeOut {
        from {
            opacity: 1;
            transform: translateY(0);
        }
        to {
            opacity: 0;
            transform: translateY(-10px);
        }
    }

    .facility-error.removing {
        animation: fadeOut 0.3s ease-in-out;
    }
</style>
@stop

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-body p-4">
            <h2 class="text-center mb-4">Edit Ruangan</h2>
            
            <form action="{{ route('ruangan.update', $ruangan->id_ruangan) }}" 
                method="POST" 
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Data Ruangan Section -->
                <div class="form-section">
                    <h5 class="section-title">Data Ruangan</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Code Ruangan</label>
                                <input type="text" 
                                    class="form-control @error('kode_ruangan') is-invalid @enderror" 
                                    name="kode_ruangan"
                                    value="{{ old('kode_ruangan', $ruangan->kode_ruangan) }}"
                                    placeholder="Masukan Code Ruangan Anda"
                                    maxlength="6"
                                    required>
                                @error('kode_ruangan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Nama Ruangan</label>
                                <input type="text" 
                                    class="form-control @error('nama_ruangan') is-invalid @enderror" 
                                    name="nama_ruangan"
                                    value="{{ old('nama_ruangan', $ruangan->nama_ruangan) }}"
                                    placeholder="Masukan Nama Ruangan Anda"
                                    maxlength="127"
                                    required>
                                @error('nama_ruangan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Code Gedung</label>
                                <select class="form-select @error('kode_gedung') is-invalid @enderror" 
                                        name="kode_gedung" 
                                        required>
                                    <option value="" selected disabled>Pilih Code Gedung</option>
                                    @foreach($gedung as $g)
                                        <option value="{{ $g->kode_gedung }}" 
                                            {{ old('kode_gedung', $ruangan->kode_gedung) == $g->kode_gedung ? 'selected' : '' }}>
                                            {{ $g->nama_gedung }} ({{ $g->kode_gedung }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('kode_gedung')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-select @error('status_ruangan') is-invalid @enderror" 
                                        name="status_ruangan" 
                                        required>
                                    <option value="" selected disabled>Pilih Status Ruangan</option>
                                    <option value="tersedia" {{ old('status_ruangan', $ruangan->status_ruangan) == 'tersedia' ? 'selected' : '' }}>
                                        Tersedia
                                    </option>
                                    <option value="tidak_tersedia" {{ old('status_ruangan', $ruangan->status_ruangan) == 'tidak_tersedia' ? 'selected' : '' }}>
                                        Tidak Tersedia
                                    </option>
                                </select>
                                @error('status_ruangan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Foto Ruangan Section -->
                    <div class="col-md-6 h-200">
                        <div class="form-section">
                            <h5 class="section-title">Foto Ruangan</h5>
                            <div class="upload-area" id="uploadArea">
                                <div id="uploadPrompt" class="{{ $ruangan->link_ruangan ? 'd-none' : '' }}">
                                    <i class="fas fa-cloud-upload-alt fa-3x mb-3"></i>
                                    <p class="mb-0">Klik untuk upload foto</p>
                                    <small class="text-muted">atau drag and drop file di sini</small>
                                </div>
                                <div id="imageContainer" class="position-relative {{ $ruangan->link_ruangan ? '' : 'd-none' }}">
                                    <button type="button" class="btn-close position-absolute top-0 end-0 m-2" id="removeImage" style="background-color: #fff; padding: 8px; border-radius: 50%; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                    </button>
                                    <div class="ratio ratio-1x1" style="max-width: 200px; margin: 0 auto;">
                                        <img id="imagePreview" 
                                            class="img-fluid rounded object-fit-cover" 
                                            src="{{ $ruangan->link_ruangan ? asset('storage/image/ruangan/' . $ruangan->link_ruangan) : '#' }}"
                                            style="border-radius: 8px;">
                                    </div>
                                </div>
                                <input type="file" 
                                    class="d-none" 
                                    id="photoInput" 
                                    name="foto" 
                                    accept="image/*">
                                <input type="hidden" name="remove_foto" id="removeFoto" value="0">
                            </div>
                            @error('foto')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Fasilitas Ruangan Section -->
                    <div class="col-md-6">
                        <div class="form-section h-200">
                            <h5 class="section-title">Fasilitas Ruangan</h5>
                            <div class="facility-input-container">
                                <div class="facility-input-group">
                                    <select class="form-select" id="facilityInput">
                                        <option value="" selected disabled>Pilih Fasilitas</option>
                                        @foreach($fasilitas as $f)
                                            <option value="{{ $f->id_fasilitas }}" 
                                                    data-nama="{{ $f->nama_fasilitas }}">
                                                {{ $f->nama_fasilitas }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <input type="number" 
                                        class="form-control" 
                                        id="facilityQuantity" 
                                        placeholder="Jumlah" 
                                        min="1">
                                    <button class="btn btn-add" type="button" id="addFacility">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                                <div id="facilityError" class="facility-error-container"></div>
                            </div>
                            
                            <div id="facilityList" class="facility-list mt-3">
                                @if($ruangan->fasilitas)
                                    @foreach($ruangan->fasilitas as $f)
                                        <div class="facility-item" data-id="{{ $f->id_fasilitas }}">
                                            <div class="facility-info">
                                                <strong>{{ $f->nama_fasilitas }}</strong>
                                                <div class="text-muted">Jumlah: {{ $f->pivot->jumlah_fasilitas }}</div>
                                            </div>
                                            <input type="hidden" name="fasilitas[{{ $f->id_fasilitas }}]" value="{{ $f->pivot->jumlah_fasilitas }}">
                                            <div class="facility-controls">
                                                <button type="button" class="btn btn-sm btn-outline-primary edit-facility">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger remove-facility">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <a href="{{ route('ruangan.index') }}" class="btn btn-cancel">Cancel</a>
                    <button type="submit" class="btn btn-submit">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const uploadArea = document.getElementById('uploadArea');
            const photoInput = document.getElementById('photoInput');
            const uploadPrompt = document.getElementById('uploadPrompt');
            const imageContainer = document.getElementById('imageContainer');
            const imagePreview = document.getElementById('imagePreview');
            const removeImage = document.getElementById('removeImage');
            const removeFoto = document.getElementById('removeFoto');
            
            // Click on upload area to trigger file input
            uploadArea.addEventListener('click', function(e) {
                if (e.target !== removeImage && !removeImage.contains(e.target)) {
                    photoInput.click();
                }
            });
            
            // Handle drag and drop
            uploadArea.addEventListener('dragover', function(e) {
                e.preventDefault();
                uploadArea.style.borderColor = '#007bff';
                uploadArea.style.backgroundColor = '#e9f5ff';
            });
            
            uploadArea.addEventListener('dragleave', function() {
                uploadArea.style.borderColor = '#ddd';
                uploadArea.style.backgroundColor = '#f8f9fa';
            });
            
            uploadArea.addEventListener('drop', function(e) {
                e.preventDefault();
                uploadArea.style.borderColor = '#ddd';
                uploadArea.style.backgroundColor = '#f8f9fa';
                
                if (e.dataTransfer.files.length) {
                    photoInput.files = e.dataTransfer.files;
                    handleFileSelect(e.dataTransfer.files[0]);
                }
            });
            
            // Handle file selection
            photoInput.addEventListener('change', function() {
                if (this.files.length) {
                    handleFileSelect(this.files[0]);
                    removeFoto.value = '0'; // Reset remove flag when new photo is selected
                }
            });
            
            // Remove image button
            removeImage.addEventListener('click', function(e) {
                e.stopPropagation();
                photoInput.value = '';
                imagePreview.src = '#';
                uploadPrompt.classList.remove('d-none');
                imageContainer.classList.add('d-none');
                removeFoto.value = '1'; // Set flag to remove photo
            });
            
            function handleFileSelect(file) {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imagePreview.src = e.target.result;
                        uploadPrompt.classList.add('d-none');
                        imageContainer.classList.remove('d-none');
                    };
                    reader.readAsDataURL(file);
                } else {
                    alert('Mohon pilih file gambar (JPG, PNG, GIF, dll)');
                }
            }

            const facilityInput = document.getElementById('facilityInput');
            const facilityQuantity = document.getElementById('facilityQuantity');
            const addFacilityButton = document.getElementById('addFacility');
            const facilityList = document.getElementById('facilityList');
            const facilityError = document.getElementById('facilityError');

            let editingFacility = null; // Untuk menyimpan fasilitas yang sedang diedit
            let originalOptions = []; // Untuk menyimpan semua opsi fasilitas asli

            // Simpan semua opsi fasilitas asli
            Array.from(facilityInput.options).forEach(option => {
                if (option.value) { // Skip opsi default "Pilih Fasilitas"
                    originalOptions.push({
                        value: option.value,
                        text: option.text,
                        dataNama: option.dataset.nama
                    });
                }
            });

            // Inisialisasi - hapus fasilitas yang sudah ada dari dropdown
            initializeExistingFacilities();

            function initializeExistingFacilities() {
                const existingFacilities = document.querySelectorAll('.facility-item');
                existingFacilities.forEach(facility => {
                    const facilityId = facility.getAttribute('data-id');
                    removeOptionFromDropdown(facilityId);
                });
            }

            // Fungsi untuk menambahkan fasilitas
            addFacilityButton.addEventListener('click', function() {
                const selectedFacilityId = facilityInput.value;
                const selectedFacilityName = facilityInput.options[facilityInput.selectedIndex]?.dataset.nama;
                const quantity = facilityQuantity.value;

                // Validasi input
                if (!selectedFacilityId || !quantity) {
                    showError('Silakan pilih fasilitas dan masukkan jumlah.');
                    return;
                }

                clearError(); // Reset error

                // Jika sedang dalam mode edit
                if (editingFacility) {
                    const existingItem = document.querySelector(`.facility-item[data-id="${editingFacility.id}"]`);
                    
                    // Jika fasilitas yang diedit berbeda dengan yang dipilih sekarang
                    if (editingFacility.id !== selectedFacilityId) {
                        // Tambahkan kembali fasilitas lama ke dropdown
                        addOptionToDropdown(editingFacility.id, editingFacility.name);
                        // Hapus fasilitas baru dari dropdown
                        removeOptionFromDropdown(selectedFacilityId);
                    }
                    
                    existingItem.querySelector('.facility-info strong').textContent = selectedFacilityName;
                    existingItem.querySelector('.facility-info .text-muted').textContent = `Jumlah: ${quantity}`;
                    existingItem.querySelector('input[type="hidden"]').value = quantity;
                    existingItem.setAttribute('data-id', selectedFacilityId);
                    existingItem.querySelector('input[type="hidden"]').name = `fasilitas[${selectedFacilityId}]`;

                    // Reset editing
                    editingFacility = null;
                    addFacilityButton.innerHTML = '<i class="fas fa-plus"></i>';
                } else {
                    // Tambah fasilitas baru
                    const facilityItem = document.createElement('div');
                    facilityItem.className = 'facility-item';
                    facilityItem.setAttribute('data-id', selectedFacilityId);

                    facilityItem.innerHTML = `
                        <div class="facility-info">
                            <strong>${selectedFacilityName}</strong>
                            <div class="text-muted">Jumlah: ${quantity}</div>
                        </div>
                        <input type="hidden" name="fasilitas[${selectedFacilityId}]" value="${quantity}">
                        <div class="facility-controls">
                            <button type="button" class="btn btn-sm btn-outline-primary edit-facility">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger remove-facility">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    `;

                    facilityList.appendChild(facilityItem);
                    
                    // Hapus fasilitas dari dropdown
                    removeOptionFromDropdown(selectedFacilityId);
                }

                // Reset input
                facilityInput.value = '';
                facilityQuantity.value = '';
            });

            // Fungsi untuk menampilkan error
            function showError(message) {
                facilityError.textContent = message;
                facilityError.classList.add('facility-error');
            }

            // Fungsi untuk menghapus error
            function clearError() {
                facilityError.textContent = '';
                facilityError.classList.remove('facility-error');
            }

            // Fungsi untuk menghapus opsi dari dropdown
            function removeOptionFromDropdown(facilityId) {
                for (let i = 0; i < facilityInput.options.length; i++) {
                    if (facilityInput.options[i].value === facilityId) {
                        facilityInput.remove(i);
                        break;
                    }
                }
            }

            // Fungsi untuk menambahkan opsi ke dropdown
            function addOptionToDropdown(facilityId, facilityName) {
                // Cari data fasilitas dari originalOptions
                const originalOption = originalOptions.find(opt => opt.value === facilityId);
                if (originalOption) {
                    const option = document.createElement('option');
                    option.value = originalOption.value;
                    option.text = originalOption.text;
                    option.dataset.nama = originalOption.dataNama;
                    
                    // Tambahkan opsi ke dropdown dengan urutan yang benar (abjad)
                    let inserted = false;
                    for (let i = 1; i < facilityInput.options.length; i++) { // Mulai dari 1 untuk melewati opsi default
                        if (facilityInput.options[i].text > originalOption.text) {
                            facilityInput.add(option, facilityInput.options[i]);
                            inserted = true;
                            break;
                        }
                    }
                    
                    // Jika belum dimasukkan (karena harus di akhir)
                    if (!inserted) {
                        facilityInput.add(option);
                    }
                }
            }

            // Event delegation untuk edit dan hapus fasilitas
            facilityList.addEventListener('click', function(event) {
                if (event.target.closest('.edit-facility')) {
                    const facilityItem = event.target.closest('.facility-item');
                    const facilityId = facilityItem.getAttribute('data-id');
                    const facilityName = facilityItem.querySelector('.facility-info strong').textContent;
                    const quantity = facilityItem.querySelector('input[type="hidden"]').value;

                    // Tambahkan kembali fasilitas yang sedang diedit ke dropdown
                    addOptionToDropdown(facilityId, facilityName);
                    
                    // Set input untuk edit
                    facilityInput.value = facilityId;
                    facilityQuantity.value = quantity;

                    // Set mode edit
                    editingFacility = { id: facilityId, name: facilityName };
                    addFacilityButton.innerHTML = '<i class="fas fa-save"></i>';
                }

                if (event.target.closest('.remove-facility')) {
                    const facilityItem = event.target.closest('.facility-item');
                    const facilityId = facilityItem.getAttribute('data-id');
                    const facilityName = facilityItem.querySelector('.facility-info strong').textContent;
                    
                    // Tambahkan kembali fasilitas ke dropdown
                    addOptionToDropdown(facilityId, facilityName);
                    
                    // Hapus item dari daftar
                    facilityList.removeChild(facilityItem);
                    
                    // Jika sedang dalam mode edit fasilitas yang dihapus, reset mode edit
                    if (editingFacility && editingFacility.id === facilityId) {
                        editingFacility = null;
                        addFacilityButton.innerHTML = '<i class="fas fa-plus"></i>';
                        facilityInput.value = '';
                        facilityQuantity.value = '';
                    }
                }
            });
        });
    </script>
@endsection