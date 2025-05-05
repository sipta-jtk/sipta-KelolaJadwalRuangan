<!-- Room Detail Modal -->
<div class="modal fade" id="roomDetailModal" tabindex="-1" aria-labelledby="roomDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="roomDetailModalLabel">Detail Ruangan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-5">
                        <div class="room-image-container mb-3">
                            <img id="roomImage" src="" alt="Foto Ruangan" class="img-fluid rounded">
                        </div>
                    </div>
                    <div class="col-md-7">
                        <table class="table">
                            <tr>
                                <th width="40%">Kode Ruangan</th>
                                <td id="roomCode"></td>
                            </tr>
                            <tr>
                                <th>Nama Ruangan</th>
                                <td id="roomName"></td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td id="roomStatus"></td>
                            </tr>
                            <tr>
                                <th>Gedung</th>
                                <td id="buildingCode"></td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <div class="mt-4">
                    <h6 class="text-primary fw-bold">Fasilitas Ruangan</h6>
                    <div id="facilityList" class="row g-3 mt-2"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<style>
    .room-name-link {
        color: #0d6efd;
        text-decoration: none;
        cursor: pointer;
        font-weight: 500;
    }
    
    .room-name-link:hover {
        text-decoration: underline;
    }
    
    .room-image-container {
        border-radius: 8px;
        overflow: hidden;
        height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f8f9fa;
    }
    
    .room-image-container img {
        object-fit: cover;
        width: 100%;
        height: 100%;
    }
    
    #facilityList .card {
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
    }
    
    #facilityList .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add click event to all room names
        const setupRoomDetailLinks = function() {
            const roomNameLinks = document.querySelectorAll('.room-name-link');
            roomNameLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const roomId = this.getAttribute('data-id');
                    fetchRoomDetails(roomId);
                });
            });
        };
        
        // Function to fetch room details
        function fetchRoomDetails(roomId) {
            fetch(`/penjadwalan-ruangan/api/v1/rooms/${roomId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    populateModal(data);
                    const modal = new bootstrap.Modal(document.getElementById('roomDetailModal'));
                    modal.show();
                })
                .catch(error => {
                    console.error('Error fetching room details:', error);
                    alert('Gagal memuat data ruangan. Silakan coba lagi.');
                });
        }
        
        // Function to populate modal with room details
        function populateModal(room) {
            document.getElementById('roomDetailModalLabel').textContent = `Detail Ruangan: ${room.nama_ruangan}`;
            document.getElementById('roomCode').textContent = room.kode_ruangan;
            document.getElementById('roomName').textContent = room.nama_ruangan;
            
            // Set status with badge
            const statusElement = document.getElementById('roomStatus');
            if (room.status_ruangan === 'tersedia') {
                statusElement.innerHTML = '<span class="status-badge bg-success text-white rounded p-1">Tersedia</span>';
            } else {
                statusElement.innerHTML = '<span class="status-badge bg-danger text-white rounded p-1">Tidak Tersedia</span>';
            }
            
            document.getElementById('buildingCode').textContent = room.gedung ? 
                `${room.gedung.nama_gedung} (${room.kode_gedung})` : room.kode_gedung;
            
            // Set room image
            const imageElement = document.getElementById('roomImage');
            if (room.link_ruangan) {
                imageElement.src = `/public/storage/image/ruangan/${room.link_ruangan}`;
                imageElement.parentElement.classList.remove('d-none');
            } else {
                imageElement.src = '/public/storage/image/ruangan/default-room.jpg';
            }
            
            // Populate facilities
            const facilityListElement = document.getElementById('facilityList');
            facilityListElement.innerHTML = '';
            
            if (room.fasilitas && room.fasilitas.length > 0) {
                room.fasilitas.forEach(facility => {
                    const facilityItem = document.createElement('div');
                    facilityItem.className = 'col-md-6 col-lg-4';
                    facilityItem.innerHTML = `
                        <div class="card h-100">
                            <div class="card-body">
                                <h6 class="card-title">${facility.nama_fasilitas}</h6>
                                <p class="card-text">Jumlah: ${facility.pivot.jumlah_fasilitas}</p>
                            </div>
                        </div>
                    `;
                    facilityListElement.appendChild(facilityItem);
                });
            } else {
                facilityListElement.innerHTML = '<div class="col-12"><p class="text-muted">Tidak ada fasilitas terdaftar.</p></div>';
            }
        }
        
        // Initialize room links if they already exist on the page
        setupRoomDetailLinks();
        
        // Export function for external use
        window.setupRoomDetailLinks = setupRoomDetailLinks;
    });
</script>