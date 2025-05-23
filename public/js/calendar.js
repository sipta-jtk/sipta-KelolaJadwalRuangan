$(document).ready(function() {
    function fetchSchedules(selectedDate) {  
        $.getJSON("api/v1/rooms/names", function(roomsData) {
            let rooms = {};
            let roomIds = {}; // menyimpan ID ruangan map ke nama ruangan
            
            roomsData.forEach(room => {
                const roomName = room.nama_ruangan.trim();
                rooms[roomName] = {};
                roomIds[roomName] = room.id_ruangan; // simpan ID ruangan
                
                for (let hour = 6; hour <= 18; hour++) {
                    rooms[roomName][hour] = "<td></td>";
                }
            });
    
            // Ambil data jadwal dan gabungkan dengan ruangan
            $.getJSON("api/v1/schedules", function(schedulesData) {
                let warnaAgenda = {};
                const agendaMapping = {
                    seminar_1: "Seminar 1",
                    seminar_2: "Seminar 2",
                    seminar_3: "Seminar 3",
                    sidang: "Sidang",
                };
    
                schedulesData
                    .filter(event => event.tanggal === selectedDate)
                    .forEach(event => {
                        let startHour = new Date(Date.parse(event.start)).getUTCHours();
                        let endHour = new Date(Date.parse(event.end)).getUTCHours();
                        let roomName = event.nama_ruangan.trim();
                        let duration = endHour - startHour;
                        let agenda = agendaMapping[event.agenda] || event.agenda;
                        
                        if (rooms[roomName]) {
                            let warnaLast = warnaAgenda[roomName] || "table-primary";
                            let warnaNew = warnaLast === "table-primary" ? "table-secondary" : "table-primary";
                            warnaAgenda[roomName] = warnaNew;
        
                            rooms[roomName][startHour] = `<td class='${warnaNew}' colspan='${duration}'>KoTA ${event.id_kota} | ${agenda}</td>`;
                            for(let hour = startHour+1; hour < endHour; hour++) {
                                rooms[roomName][hour] = "";
                            }
                        }
                    });
    
                // Generate tabel
                let tableBody = "";
                Object.keys(rooms).forEach(roomName => {
                    // membuat link untuk nama ruangan
                    const roomId = roomIds[roomName];
                    tableBody += `<tr><td><a href="#" class="room-name-link" data-id="${roomId}">${roomName}</a></td>`;
                    
                    for (let hour = 6; hour <= 18; hour++) {
                        if(rooms[roomName][hour] !== "") {
                            tableBody += rooms[roomName][hour];
                        }
                    }
                    tableBody += "</tr>";
                });
    
                // Masukkan hasil ke dalam tabel di halaman
                $("#schedule-body").html(tableBody);
                
                // Initialize room detail links after rendering the table
                if (window.setupRoomDetailLinks) {
                    window.setupRoomDetailLinks();
                }
            });
        });
    }

    function formatDateToIndonesian(dateString) {
        const date = new Date(dateString);
        return new Intl.DateTimeFormat('id-ID', {
            weekday: 'long',
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        }).format(date);
    }

    function updateDate(newDate) {
        const formattedDate = newDate.toISOString().split('T')[0]; // Format as YYYY-MM-DD
        $("#calendarDate").val(formattedDate); // Update the calendarDate input
        $("#current-date").text(formatDateToIndonesian(formattedDate)); // Update the #current-date element
        fetchSchedules(formattedDate); // Fetch and render schedules for the new date
    }

    // Initialize with today's date
    const today = new Date().toLocaleDateString('en-CA'); // 'en-CA' ensures the format is YYYY-MM-DD
    updateDate(new Date(today));

    // Listen for changes in the date input field
    $("#calendarDate").on("change", function () {
        const selectedDate = new Date($(this).val());
        updateDate(selectedDate);
    });

    // Listen for clicks on the prev-date and next-date buttons
    $("#prev-date").on("click", function () {
        const currentDate = new Date($("#calendarDate").val());
        currentDate.setDate(currentDate.getDate() - 1); // Subtract 1 day
        updateDate(currentDate);
    });

    $("#next-date").on("click", function () {
        const currentDate = new Date($("#calendarDate").val());
        currentDate.setDate(currentDate.getDate() + 1); // Add 1 day
        updateDate(currentDate);
    });

    // Listen for clicks on the download PDF button
    $('#downloadPdfBtn').click(function() {
        // Get the currently selected date
        const date = $('#calendarDate').val();
        
        if (!date) {
            alert('Silakan pilih tanggal terlebih dahulu');
            return;
        }
        
        // Create agenda mapping object to send with the request
        const agendaMapping = {
            seminar_1: "Seminar 1",
            seminar_2: "Seminar 2",
            seminar_3: "Seminar 3",
            sidang: "Sidang"
        };
    
        // Redirect to the PDF download route with mapping as query parameters
        const queryParams = new URLSearchParams({
            date: date,
            ...agendaMapping
        });
    
        window.location.href = `/penjadwalan-ruangan/download-schedule-pdf?${queryParams.toString()}`;
    });

    // Add this at the end of your calendar.js file
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize room detail links after calendar renders
        if (window.setupRoomDetailLinks) {
            window.setupRoomDetailLinks();
        }
    });

});
