@extends('layouts.ruangan')

@section('title', 'Create/Update Ruangan')

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

    /* Add new validation styles */
    .form-control.is-invalid,
    .form-select.is-invalid {
        border-color: #dc3545;
        padding-right: calc(1.5em + 0.75rem);
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right calc(0.375em + 0.1875rem) center;
        background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
    }

    .form-control.is-invalid:focus,
    .form-select.is-invalid:focus {
        border-color: #dc3545;
        box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25);
    }

    .invalid-feedback {
        display: none;
        width: 100%;
        margin-top: 0.25rem;
        font-size: 0.875em;
        color: #dc3545;
    }

    .form-control.is-invalid ~ .invalid-feedback,
    .form-select.is-invalid ~ .invalid-feedback {
        display: block;
    }

    .upload-area.is-invalid {
        border-color: #dc3545;
    }

    .upload-area.is-invalid .invalid-feedback {
        display: block;
        margin-top: 0.5rem;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-body p-4">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('ruangan.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
                <h2 class="text-center flex-grow-1 mb-0">Tambah Ruangan</h2>
            </div>
            
            <form action="{{ route('ruangan.store') }}" 
                method="POST" 
                enctype="multipart/form-data"
                id="ruanganForm"
                novalidate>
                @csrf
                @if(isset($ruangan))
                    @method('PUT')
                @endif

                <!-- Data Ruangan Section -->
                <div class="form-section">
                    <h5 class="section-title">Data Ruangan</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Kode Ruangan</label>
                                <input type="text" 
                                    class="form-control @error('kode_ruangan') is-invalid @enderror" 
                                    name="kode_ruangan"
                                    id="kode_ruangan"
                                    value="{{ old('kode_ruangan', $ruangan->kode_ruangan ?? '') }}"
                                    placeholder="Masukan Kode Ruangan Anda"
                                    minlength="3"
                                    maxlength="6"
                                    required>
                                <div class="invalid-feedback">
                                    Kode ruangan harus diisi (3-6 karakter)
                                </div>
                                @error('kode_ruangan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Nama Ruangan</label>
                                <input type="text" 
                                    class="form-control @error('nama_ruangan') is-invalid @enderror" 
                                    name="nama_ruangan"
                                    id="nama_ruangan"
                                    value="{{ old('nama_ruangan', $ruangan->nama_ruangan ?? '') }}"
                                    placeholder="Masukan Nama Ruangan Anda"
                                    minlength="3"
                                    maxlength="127"
                                    required>
                                <div class="invalid-feedback">
                                    Nama ruangan harus diisi (3-127 karakter)
                                </div>
                                @error('nama_ruangan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Kode Gedung</label>
                                <select class="form-select @error('kode_gedung') is-invalid @enderror" 
                                        name="kode_gedung" 
                                        id="kode_gedung"
                                        required>
                                    <option value="" selected disabled>Pilih Kode Gedung</option>
                                    @foreach($gedung as $g)
                                        <option value="{{ $g->kode_gedung }}" 
                                            {{ old('kode_gedung', $ruangan->kode_gedung ?? '') == $g->kode_gedung ? 'selected' : '' }}>
                                            {{ $g->nama_gedung }} ({{ $g->kode_gedung }})
                                        </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">
                                    Silakan pilih kode gedung
                                </div>
                                @error('kode_gedung')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-select @error('status_ruangan') is-invalid @enderror" 
                                        name="status_ruangan" 
                                        id="status_ruangan"
                                        required>
                                    <option value="" selected disabled>Pilih Status Ruangan</option>
                                    <option value="tersedia" {{ old('status_ruangan', $ruangan->status_ruangan ?? '') == 'tersedia' ? 'selected' : '' }}>
                                        Tersedia
                                    </option>
                                    <option value="tidak_tersedia" {{ old('status_ruangan', $ruangan->status_ruangan ?? '') == 'tidak_tersedia' ? 'selected' : '' }}>
                                        Tidak Tersedia
                                    </option>
                                </select>
                                <div class="invalid-feedback">
                                    Silakan pilih status ruangan
                                </div>
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
                            <div class="upload-area @error('foto') is-invalid @enderror" id="uploadArea">
                                <div id="uploadPrompt" class="{{ old('foto') || isset($ruangan) ? 'd-none' : '' }}">
                                    <i class="fas fa-cloud-upload-alt fa-3x mb-3"></i>
                                    <p class="mb-0">Klik untuk upload foto</p>
                                    <small class="text-muted">atau drag and drop file di sini</small>
                                </div>
                                <div id="imageContainer" class="position-relative {{ old('foto') || isset($ruangan) ? '' : 'd-none' }}">
                                    <button type="button" class="btn-close position-absolute top-0 end-0 m-2" id="removeImage" style="background-color: #fff; padding: 8px; border-radius: 50%; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                                    </button>
                                    <div class="ratio ratio-1x1" style="max-width: 200px; margin: 0 auto;">
                                        <img id="imagePreview" 
                                            class="img-fluid rounded object-fit-cover" 
                                            src="{{ old('foto') ? asset('storage/' . old('foto')) : (isset($ruangan) ? asset('storage/' . $ruangan->foto) : '#') }}"
                                            style="border-radius: 8px;">
                                    </div>
                                </div>
                                <input type="file" 
                                    class="d-none" 
                                    id="photoInput" 
                                    name="foto" 
                                    accept="image/*"
                                    required>
                                <input type="hidden" name="old_foto" value="{{ old('foto', isset($ruangan) ? $ruangan->foto : '') }}">
                                <div class="invalid-feedback">
                                    Foto ruangan harus diunggah
                                </div>
                            </div>
                            @error('foto')
                                <div class="invalid-feedback">{{ $message }}</div>
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
                                @if(old('fasilitas'))
                                    @foreach(old('fasilitas') as $id => $jumlah)
                                        @php
                                            $fasilitasItem = $fasilitas->firstWhere('id_fasilitas', $id);
                                        @endphp
                                        @if($fasilitasItem)
                                            <div class="facility-item" data-id="{{ $id }}">
                                                <div class="facility-info">
                                                    <strong>{{ $fasilitasItem->nama_fasilitas }}</strong>
                                                    <div class="text-muted">Jumlah: {{ $jumlah }}</div>
                                                </div>
                                                <input type="hidden" name="fasilitas[{{ $id }}]" value="{{ $jumlah }}">
                                                <div class="facility-controls">
                                                    <button type="button" class="btn btn-sm btn-outline-primary edit-facility">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-danger remove-facility">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                @elseif(isset($ruangan))
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
                    <a href="{{ route('ruangan.index') }}" class="btn btn-danger">Cancel</a>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Form validation
            const form = document.getElementById('ruanganForm');
            const requiredFields = form.querySelectorAll('[required]');
            const photoInput = document.getElementById('photoInput');
            const uploadArea = document.getElementById('uploadArea');
            const uploadPrompt = document.getElementById('uploadPrompt');
            const imageContainer = document.getElementById('imageContainer');
            const imagePreview = document.getElementById('imagePreview');
            const removeImage = document.getElementById('removeImage');
            const oldFotoInput = document.querySelector('input[name="old_foto"]');

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
                    const file = this.files[0];
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            imagePreview.src = e.target.result;
                            uploadPrompt.classList.add('d-none');
                            imageContainer.classList.remove('d-none');
                        };
                        reader.readAsDataURL(file);
                        uploadArea.classList.remove('is-invalid');
                    } else {
                        alert('Mohon pilih file gambar (JPG, PNG, GIF, dll)');
                        this.value = '';
                    }
                } else {
                    uploadArea.classList.add('is-invalid');
                }
            });
            
            // Remove image button
            removeImage.addEventListener('click', function(e) {
                e.stopPropagation();
                photoInput.value = '';
                imagePreview.src = '#';
                uploadPrompt.classList.remove('d-none');
                imageContainer.classList.add('d-none');
                uploadArea.classList.add('is-invalid');
                oldFotoInput.value = '';
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

            // Form submission
            form.addEventListener('submit', function(event) {
                event.preventDefault();
                let isValid = true;

                // Check all required fields
                requiredFields.forEach(field => {
                    if (!field.value) {
                        field.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        field.classList.remove('is-invalid');
                    }
                });

                // Check if image is uploaded or has old image
                if (!photoInput.files.length && !oldFotoInput.value) {
                    uploadArea.classList.add('is-invalid');
                    isValid = false;
                }

                // Check if at least one facility is added
                const facilityList = document.getElementById('facilityList');
                if (facilityList.children.length === 0) {
                    const facilityError = document.getElementById('facilityError');
                    facilityError.textContent = 'Minimal satu fasilitas harus ditambahkan';
                    facilityError.classList.add('facility-error');
                    isValid = false;
                }

                if (!isValid) {
                    // Scroll to first invalid element
                    const firstInvalid = form.querySelector('.is-invalid');
                    if (firstInvalid) {
                        firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                    return;
                }

                // Create FormData object
                const formData = new FormData(form);

                // Submit form using fetch
                fetch(form.action, {
                    method: form.method,
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Redirect to index page with success message
                        window.location.href = data.redirect + '?success=' + encodeURIComponent(data.message);
                    } else {
                        // Handle validation errors
                        if (data.errors) {
                            Object.keys(data.errors).forEach(field => {
                                const input = form.querySelector(`[name="${field}"]`);
                                if (input) {
                                    input.classList.add('is-invalid');
                                    const feedback = input.nextElementSibling;
                                    if (feedback && feedback.classList.contains('invalid-feedback')) {
                                        feedback.textContent = data.errors[field][0];
                                    }
                                }
                            });
                        }
                        // Scroll to first error
                        const firstInvalid = form.querySelector('.is-invalid');
                        if (firstInvalid) {
                            firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan. Silakan coba lagi.');
                });
            });

            // Real-time validation on input
            requiredFields.forEach(field => {
                field.addEventListener('input', function() {
                    if (this.value) {
                        this.classList.remove('is-invalid');
                    } else {
                        this.classList.add('is-invalid');
                    }
                });
            });

            // Facility management code
            const facilityInput = document.getElementById('facilityInput');
            const facilityQuantity = document.getElementById('facilityQuantity');
            const addFacilityButton = document.getElementById('addFacility');
            const facilityList = document.getElementById('facilityList');
            const facilityError = document.getElementById('facilityError');

            let editingFacility = null;
            let originalOptions = [];

            // Simpan semua opsi fasilitas asli
            Array.from(facilityInput.options).forEach(option => {
                if (option.value) {
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

                clearError();

                // Jika sedang dalam mode edit
                if (editingFacility) {
                    const existingItem = document.querySelector(`.facility-item[data-id="${editingFacility.id}"]`);
                    
                    if (editingFacility.id !== selectedFacilityId) {
                        addOptionToDropdown(editingFacility.id, editingFacility.name);
                        removeOptionFromDropdown(selectedFacilityId);
                    }
                    
                    existingItem.querySelector('.facility-info strong').textContent = selectedFacilityName;
                    existingItem.querySelector('.facility-info .text-muted').textContent = `Jumlah: ${quantity}`;
                    existingItem.querySelector('input[type="hidden"]').value = quantity;
                    existingItem.setAttribute('data-id', selectedFacilityId);
                    existingItem.querySelector('input[type="hidden"]').name = `fasilitas[${selectedFacilityId}]`;

                    editingFacility = null;
                    addFacilityButton.innerHTML = '<i class="fas fa-plus"></i>';
                } else {
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
                    removeOptionFromDropdown(selectedFacilityId);
                }

                facilityInput.value = '';
                facilityQuantity.value = '';
            });

            function showError(message) {
                facilityError.textContent = message;
                facilityError.classList.add('facility-error');
            }

            function clearError() {
                facilityError.textContent = '';
                facilityError.classList.remove('facility-error');
            }

            function removeOptionFromDropdown(facilityId) {
                for (let i = 0; i < facilityInput.options.length; i++) {
                    if (facilityInput.options[i].value === facilityId) {
                        facilityInput.remove(i);
                        break;
                    }
                }
            }

            function addOptionToDropdown(facilityId, facilityName) {
                const originalOption = originalOptions.find(opt => opt.value === facilityId);
                if (originalOption) {
                    const option = document.createElement('option');
                    option.value = originalOption.value;
                    option.text = originalOption.text;
                    option.dataset.nama = originalOption.dataNama;
                    
                    let inserted = false;
                    for (let i = 1; i < facilityInput.options.length; i++) {
                        if (facilityInput.options[i].text > originalOption.text) {
                            facilityInput.add(option, facilityInput.options[i]);
                            inserted = true;
                            break;
                        }
                    }
                    
                    if (!inserted) {
                        facilityInput.add(option);
                    }
                }
            }

            facilityList.addEventListener('click', function(event) {
                if (event.target.closest('.edit-facility')) {
                    const facilityItem = event.target.closest('.facility-item');
                    const facilityId = facilityItem.getAttribute('data-id');
                    const facilityName = facilityItem.querySelector('.facility-info strong').textContent;
                    const quantity = facilityItem.querySelector('input[type="hidden"]').value;

                    addOptionToDropdown(facilityId, facilityName);
                    
                    facilityInput.value = facilityId;
                    facilityQuantity.value = quantity;

                    editingFacility = { id: facilityId, name: facilityName };
                    addFacilityButton.innerHTML = '<i class="fas fa-save"></i>';
                }

                if (event.target.closest('.remove-facility')) {
                    const facilityItem = event.target.closest('.facility-item');
                    const facilityId = facilityItem.getAttribute('data-id');
                    const facilityName = facilityItem.querySelector('.facility-info strong').textContent;
                    
                    addOptionToDropdown(facilityId, facilityName);
                    facilityList.removeChild(facilityItem);
                    
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