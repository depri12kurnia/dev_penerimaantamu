<style>
    :root {
        --pk-teal: #00a99d;
        --pk-teal-dark: #007c73;
        --pk-lime: #bed62f;
    }

    /* --- Tampilan Default (Desktop / Tablet) --- */
    .bg-hero-custom {
        background-image: linear-gradient(180deg, rgba(1, 84, 78, 0.75) 0%, rgba(2, 110, 99, 0.65) 100%),
            url('https://res.cloudinary.com/dmi0wyye1/image/upload/q_auto/f_auto/v1780588454/beckground_slider__jadwal_ijlzym.png');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        border-radius: 0 0 64px 64px;
        padding: 120px 20px;
        min-height: 400px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-outline-teal {
        color: var(--pk-teal);
        border-color: var(--pk-teal);
    }

    .btn-outline-teal:hover,
    .btn-outline-teal.active {
        background-color: var(--pk-teal);
        color: white;
    }

    /* --- Tampilan Responsif (Khusus Mobile / Layar di bawah 768px) --- */
    @media (max-width: 767px) {
        .bg-hero-custom {
            border-radius: 0 0 32px 32px;
            padding: 60px 16px;
            min-height: 350px;
            background-position: center top;
        }
    }

    /* --- Kalender Styles --- */
    .calendar-header-day {
        background-color: #f8f9fa;
        padding: 12px;
        font-weight: 600;
        font-size: 0.875rem;
        color: #475569;
        border-bottom: 1px solid #e2e8f0;
    }

    .calendar-day-box {
        aspect-ratio: 1;
        border: 1px solid #e2e8f0;
        position: relative;
        overflow-y: auto;
        min-height: 110px;
        display: flex;
        flex-direction: column;
        padding: 8px !important;
        transition: background-color 0.2s ease;
    }

    .calendar-day-box.has-events {
        cursor: pointer;
    }

    .calendar-day-box.has-events:hover {
        background-color: #f1f5f9;
    }

    .calendar-day-box.other-month {
        background-color: #f8f9fa;
        color: #cbd5e1;
    }

    .calendar-day-box.weekend {
        background-color: #f0f9ff;
    }

    .calendar-day-box.today {
        background-color: #fef08a !important;
    }

    .calendar-date-number {
        font-weight: 600;
        display: block;
        margin-bottom: 6px;
        font-size: 0.95rem;
    }

    .calendar-event {
        font-size: 0.7rem;
        font-weight: 600;
        padding: 4px 6px;
        margin-bottom: 3px;
        border-radius: 4px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        text-decoration: none !important;
        display: block;
        z-index: 2;
    }

    .calendar-event:hover {
        opacity: 0.85;
    }

    .calendar-week-mode .calendar-day-box {
        aspect-ratio: auto;
        min-height: 250px;
    }

    @media (max-width: 768px) {
        .calendar-day-box {
            min-height: 85px;
            font-size: 0.8rem;
        }

        .calendar-event {
            font-size: 0.65rem;
        }
    }
</style>

<section id="jadwal" class="position-relative overflow-hidden" style="padding-top: 80px; padding-bottom: 5px;">
    <div class="bg-hero-custom text-white text-center">
        <div class="container py-4">
            <h1 class="display-5 fw-extrabold mb-3">
                <span class="border-bottom border-warning border-3 pb-2">Jadwal Penerimaan Tamu</span>
            </h1>

            <h4 class="h4 fw-extrabold mb-3" style="color: var(--pk-lime);">
                Informasi ketersediaan waktu studi banding atau kunjungan kerja dinas
            </h4>

            <p class="lead opacity-75 mb-0" style="color: #ffffff; font-size: 1.50rem;">
                Reservasi tamu online dalam satu platform
            </p>
        </div>
    </div>
</section>

<section class="page-section container" style="padding-top: 20px; padding-bottom: 60px;">
    <div class="card card-custom p-3 mb-4 shadow-sm border-0">
        <div class="d-flex flex-wrap gap-2 align-items-center justify-content-center">
            <span class="text-muted small fw-bold text-uppercase me-2">Kategori:</span>
            <span class="badge rounded-pill bg-danger px-3 py-2">Pemerintah Pusat (Kementerian/Lembaga)</span>
            <span class="badge rounded-pill bg-primary px-3 py-2">Poltekkes</span>
            <span class="badge rounded-pill bg-success px-3 py-2">Pemerintah Daerah</span>
            <span class="badge rounded-pill bg-warning text-dark px-3 py-2">DPRD Provinsi / Kabupaten / Kota</span>
            <span class="badge rounded-pill bg-info text-dark px-3 py-2">BUMD Kabupaten/Kota</span>
            <span class="badge rounded-pill bg-secondary px-3 py-2">Universitas / Politeknik / SMA / SMK</span>
            <span class="badge rounded-pill bg-light text-dark px-3 py-2">Akademisi</span>
            <span class="badge rounded-pill bg-dark text-white px-3 py-2">Lembaga Non Pemerintah</span>
            <span class="badge rounded-pill bg-white text-dark px-3 py-2">Lainnya</span>
        </div>
    </div>

    <div class="card card-custom overflow-hidden shadow-sm border-0">
        <div class="card-header bg-white border-bottom p-4 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
            <div class="d-flex align-items-center gap-2">
                <button id="prevBtn" class="btn btn-sm btn-outline-secondary rounded-circle"><i class="fas fa-chevron-left"></i></button>
                <button id="nextBtn" class="btn btn-sm btn-outline-secondary rounded-circle"><i class="fas fa-chevron-right"></i></button>
                <button id="todayBtn" class="btn btn-sm btn-outline-teal ms-2 fw-bold rounded-pill px-3">Hari ini</button>
            </div>
            <h3 id="monthYearDisplay" class="h4 fw-bold mb-0 text-dark">-</h3>
            <div class="btn-group rounded-pill overflow-hidden" style="border: 1px solid #CBD5E1;">
                <button type="button" id="viewMonthBtn" class="btn btn-sm btn-outline-teal active border-0 px-3 fw-bold">Bulanan</button>
                <button type="button" id="viewWeekBtn" class="btn btn-sm btn-outline-teal border-0 px-3 fw-bold">Mingguan</button>
            </div>
        </div>

        <div class="row g-0 text-center">
            <div class="col calendar-header-day">Sen</div>
            <div class="col calendar-header-day">Sel</div>
            <div class="col calendar-header-day">Rab</div>
            <div class="col calendar-header-day">Kam</div>
            <div class="col calendar-header-day">Jum</div>
            <div class="col calendar-header-day text-danger">Sab</div>
            <div class="col calendar-header-day text-danger">Min</div>
        </div>

        <div id="calendarBody">
        </div>
    </div>
</section>

<!-- Modal Bootstrap 5 -->
<div class="modal fade" id="modalEventDetail" tabindex="-1" aria-labelledby="modalEventTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-bold text-dark" id="modalEventTitle"><i class="fas fa-info-circle text-teal me-2"></i>Detail Kunjungan Tamu</h5>
                <!-- PERBAIKAN: Menggunakan data-bs-dismiss yang valid -->
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div id="modalEventBody">
                </div>
            </div>
            <div class="modal-footer bg-light border-top-0">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    let currentDate = new Date();
    let currentView = 'month';
    const approvedReservations = <?= json_encode($approved_reservations ?? []); ?>;

    // PERBAIKAN: Konversi klasifikasi langsung ke class Bootstrap 5
    function getBadgeClass(klasifikasi) {
        const mapping = {
            'Pemerintah Pusat (Kementerian/Lembaga)': 'bg-danger text-white',
            'Kementerian/Lembaga': 'bg-danger text-white',
            'Poltekkes': 'bg-primary text-white',
            'Pemerintah Daerah': 'bg-success text-white',
            'DPRD Provinsi / Kabupaten / Kota': 'bg-warning text-dark',
            'BUMD Kabupaten/Kota': 'bg-info text-dark',
            'Universitas / Politeknik / SMA / SMK': 'bg-secondary text-white',
            'Universitas/Politeknik/SMA': 'bg-secondary text-white',
            'Akademisi': 'bg-secondary text-white',
            'Lembaga Non Pemerintah': 'bg-secondary text-white',
            'Lembaga Non Kementerian': 'bg-secondary text-white',
            'Kelompok Masyarakat': 'bg-secondary text-white',
            'Lainnya': 'bg-secondary text-white'
        };
        // Fallback jika tidak cocok
        return mapping[klasifikasi] || 'bg-secondary text-white';
    }

    function getDayOfWeek(date) {
        const day = date.getDay();
        return day === 0 ? 6 : day - 1;
    }

    function getEventsForDate(date) {
        const d = date.getDate().toString().padStart(2, '0');
        const m = (date.getMonth() + 1).toString().padStart(2, '0');
        const y = date.getFullYear();
        const dateStr = `${y}-${m}-${d}`;
        return approvedReservations.filter(res => res.tanggal_berkunjung === dateStr);
    }

    function renderCalendar() {
        const calendarBody = document.getElementById('calendarBody');
        const monthYearDisplay = document.getElementById('monthYearDisplay');
        const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        calendarBody.innerHTML = '';

        if (currentView === 'month') {
            calendarBody.classList.remove('calendar-week-mode');
            const year = currentDate.getFullYear();
            const month = currentDate.getMonth();

            monthYearDisplay.textContent = `${monthNames[month]} ${year}`;

            const firstDay = new Date(year, month, 1);
            const startingDayOfWeek = getDayOfWeek(firstDay);
            const daysInMonth = new Date(year, month + 1, 0).getDate();

            let html = '';
            let dayCount = 1;
            const totalCells = startingDayOfWeek + daysInMonth;
            const rows = Math.ceil(totalCells / 7);

            for (let row = 0; row < rows; row++) {
                html += '<div class="row g-0 border-top">';
                for (let col = 0; col < 7; col++) {
                    const cellIndex = row * 7 + col;

                    if (cellIndex < startingDayOfWeek || dayCount > daysInMonth) {
                        html += '<div class="col calendar-day-box other-month"></div>';
                    } else {
                        const currentDay = dayCount;
                        const cellDate = new Date(year, month, currentDay);
                        const isToday = cellDate.toDateString() === new Date().toDateString();
                        const isWeekend = col === 5 || col === 6;
                        const events = getEventsForDate(cellDate);
                        const hasEventsClass = events.length > 0 ? 'has-events' : '';

                        html += `<div class="col calendar-day-box ${isToday ? 'today' : ''} ${isWeekend ? 'weekend' : ''} ${hasEventsClass}" ${events.length > 0 ? `onclick="showDaySummary('${cellDate.toISOString()}')"` : ''}>`;
                        html += `<span class="calendar-date-number">${currentDay}</span>`;

                        events.forEach(event => {
                            const badgeClasses = getBadgeClass(event.klasifikasi);
                            // PERBAIKAN: Fallback jika instansi kosong
                            const namaInstansi = event.nama_instansi || event.nama_pemohon;
                            const title = `${event.jam_kunjungan} - ${namaInstansi}`;

                            html += `<a href="javascript:void(0)" class="calendar-event ${badgeClasses}" title="${title}" onclick="event.stopPropagation(); showEventDetail(${event.id})">${title}</a>`;
                        });

                        html += '</div>';
                        dayCount++;
                    }
                }
                html += '</div>';
            }
            calendarBody.innerHTML = html;

        } else if (currentView === 'week') {
            calendarBody.classList.add('calendar-week-mode');

            const currentDayOfWeek = getDayOfWeek(currentDate);
            const startOfWeek = new Date(currentDate);
            startOfWeek.setDate(currentDate.getDate() - currentDayOfWeek);

            const endOfWeek = new Date(startOfWeek);
            endOfWeek.setDate(startOfWeek.getDate() + 6);

            const startStr = `${startOfWeek.getDate()} ${monthNames[startOfWeek.getMonth()]}`;
            const endStr = `${endOfWeek.getDate()} ${monthNames[endOfWeek.getMonth()]} ${endOfWeek.getFullYear()}`;
            monthYearDisplay.textContent = `${startStr} - ${endStr}`;

            let html = '<div class="row g-0 border-top">';
            for (let col = 0; col < 7; col++) {
                const cellDate = new Date(startOfWeek);
                cellDate.setDate(startOfWeek.getDate() + col);

                const isToday = cellDate.toDateString() === new Date().toDateString();
                const isWeekend = col === 5 || col === 6;
                const events = getEventsForDate(cellDate);
                const hasEventsClass = events.length > 0 ? 'has-events' : '';

                html += `<div class="col calendar-day-box ${isToday ? 'today' : ''} ${isWeekend ? 'weekend' : ''} ${hasEventsClass}" ${events.length > 0 ? `onclick="showDaySummary('${cellDate.toISOString()}')"` : ''}>`;
                html += `<span class="calendar-date-number">${cellDate.getDate()} ${monthNames[cellDate.getMonth()].substring(0,3)}</span>`;

                events.forEach(event => {
                    const badgeClasses = getBadgeClass(event.klasifikasi);
                    const namaInstansi = event.nama_instansi || event.nama_pemohon;
                    const title = `${event.jam_kunjungan} - ${namaInstansi}`;
                    html += `<a href="javascript:void(0)" class="calendar-event ${badgeClasses}" title="${title}" onclick="event.stopPropagation(); showEventDetail(${event.id})">${title}</a>`;
                });

                html += '</div>';
            }
            html += '</div>';
            calendarBody.innerHTML = html;
        }
    }

    function showEventDetail(eventId) {
        const event = approvedReservations.find(r => r.id == eventId);

        if (event) {
            const namaInstansi = event.nama_instansi || '-';
            const badgeClasses = getBadgeClass(event.klasifikasi);

            let html = `
            <table class="table table-striped table-bordered mb-0" style="font-size: 0.9rem;">
                <tbody>
                    <tr><th width="35%">Nama Instansi</th><td><strong>${namaInstansi}</strong></td></tr>
                    <tr><th>Nama Pemohon</th><td>${event.nama_pemohon}</td></tr>
                    <tr><th>Waktu</th><td>${event.tanggal_berkunjung} Pukul ${event.jam_kunjungan} WIB</td></tr>
                    <tr><th>Jumlah Peserta</th><td>${event.jumlah_peserta} Orang</td></tr>
                    <tr><th>Lokasi Pertemuan</th><td>${event.lokasi || '-'}</td></tr>
                    <tr><th>Klasifikasi</th><td><span class="badge ${badgeClasses}">${event.klasifikasi}</span></td></tr>
                </tbody>
            </table>
            `;
            document.getElementById('modalEventBody').innerHTML = html;

            const modalEl = document.getElementById('modalEventDetail');
            const myModal = bootstrap.Modal.getOrCreateInstance(modalEl);
            myModal.show();
        }
    }

    function showDaySummary(dateIsoString) {
        const selectedDate = new Date(dateIsoString);
        const events = getEventsForDate(selectedDate);

        if (events.length > 0) {
            let html = `<p class="mb-3 text-muted">Ditemukan <strong>${events.length} acara</strong> pada tanggal ini. Klik salah satu untuk melihat detail:</p>`;
            html += `<div class="list-group">`;

            events.forEach(event => {
                const badgeClasses = getBadgeClass(event.klasifikasi);
                const namaInstansi = event.nama_instansi || event.nama_pemohon;

                html += `
                <button type="button" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3" onclick="showEventDetail('${event.id}')">
                    <div>
                        <h6 class="mb-1 fw-bold text-dark">${namaInstansi}</h6>
                        <small class="text-secondary"><i class="far fa-clock me-1"></i> Pukul ${event.jam_kunjungan} WIB</small>
                    </div>
                    <span class="badge rounded-pill ${badgeClasses} px-2 py-1" style="font-size:0.7rem;">${event.klasifikasi}</span>
                </button>
                `;
            });
            html += `</div>`;

            document.getElementById('modalEventBody').innerHTML = html;

            const modalEl = document.getElementById('modalEventDetail');
            const myModal = bootstrap.Modal.getOrCreateInstance(modalEl);
            myModal.show();
        }
    }

    // Penanganan Navigasi
    document.getElementById('prevBtn').addEventListener('click', function() {
        if (currentView === 'month') {
            currentDate.setMonth(currentDate.getMonth() - 1);
        } else {
            currentDate.setDate(currentDate.getDate() - 7);
        }
        renderCalendar();
    });

    document.getElementById('nextBtn').addEventListener('click', function() {
        if (currentView === 'month') {
            currentDate.setMonth(currentDate.getMonth() + 1);
        } else {
            currentDate.setDate(currentDate.getDate() + 7);
        }
        renderCalendar();
    });

    document.getElementById('todayBtn').addEventListener('click', function() {
        currentDate = new Date();
        renderCalendar();
    });

    document.getElementById('viewMonthBtn').addEventListener('click', function() {
        currentView = 'month';
        this.classList.add('active');
        document.getElementById('viewWeekBtn').classList.remove('active');
        renderCalendar();
    });

    document.getElementById('viewWeekBtn').addEventListener('click', function() {
        currentView = 'week';
        this.classList.add('active');
        document.getElementById('viewMonthBtn').classList.remove('active');
        renderCalendar();
    });

    document.addEventListener('DOMContentLoaded', renderCalendar);
</script>