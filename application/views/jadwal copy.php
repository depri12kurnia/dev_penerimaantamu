<!-- PHP CodeIgniter 3/4 Compatible View file -->
<style>
    :root {
        --pk-teal: #00a99d;
        --pk-teal-dark: #007c73;
        --pk-lime: #bed62f;
    }

    /* --- Tampilan Default (Desktop / Tablet) --- */
    .bg-hero-custom {
        background-image: linear-gradient(180deg, rgba(1, 84, 78, 0.75) 0%, rgba(2, 110, 99, 0.65) 100%),
            url('<?= base_url("public/settings/logo/beckground_slider.png"); ?>');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        border-radius: 0 0 64px 64px;
        padding: 120px 20px;
        /* Diyeimbangkan kembali agar flexbox bekerja sempurna */
        min-height: 400px;
        display: flex;
        align-items: center;
        justify-content: center;
        /* Memastikan rata tengah horizontal lewat flexbox */
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

            <p class="lead opacity-75 mb-0" style="color: var(--pk-gray-bg);">
                Reservasi tamu online dalam satu platform
            </p>
        </div>
    </div>
</section>
<section id="jadwal" class="page-section container" style="padding-top: 20px; padding-bottom: 60px;">
    <!-- Legend Badge Bar -->
    <div class="card card-custom p-3 mb-4">
        <div class="d-flex flex-wrap gap-2 align-items-center justify-content-center">
            <span class="text-muted small fw-bold text-uppercase me-2">Kategori:</span>
            <span class="badge rounded-pill bg-danger px-3 py-2">Kementerian/Lembaga</span>
            <span class="badge rounded-pill bg-primary px-3 py-2">Poltekkes</span>
            <span class="badge rounded-pill bg-success px-3 py-2">Universitas/Politeknik/SMA</span>
            <span class="badge rounded-pill bg-warning text-dark px-3 py-2">Kelompok Masyarakat</span>
            <span class="badge rounded-pill bg-info text-dark px-3 py-2">Pemerintah Daerah</span>
            <span class="badge rounded-pill bg-secondary px-3 py-2">Lembaga Non Kementerian</span>
        </div>
    </div>

    <!-- Calendar Grid Card -->
    <div class="card card-custom overflow-hidden">
        <div
            class="card-header bg-white border-bottom p-4 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
            <div class="d-flex align-items-center gap-2">
                <button id="prevMonth" class="btn btn-sm btn-outline-secondary rounded-circle"><i
                        class="fas fa-chevron-left"></i></button>
                <button id="nextMonth" class="btn btn-sm btn-outline-secondary rounded-circle"><i
                        class="fas fa-chevron-right"></i></button>
                <button id="todayBtn" class="btn btn-sm btn-outline-teal ms-2 fw-bold rounded-pill px-3">Hari ini</button>
            </div>
            <h3 id="monthYearDisplay" class="h4 fw-bold mb-0 text-dark">Mei 2026</h3>
            <div class="btn-group rounded-pill overflow-hidden" style="border: 1px solid #CBD5E1;">
                <button type="button" class="btn btn-sm bg-light text-dark fw-bold border-0 px-3">Bulanan</button>
                <button type="button" class="btn btn-sm bg-light text-dark fw-bold border-0 px-3">Mingguan</button>
            </div>
        </div>

        <!-- Calendar Headings -->
        <div class="row g-0 text-center">
            <div class="col calendar-header-day">Sen</div>
            <div class="col calendar-header-day">Sel</div>
            <div class="col calendar-header-day">Rab</div>
            <div class="col calendar-header-day">Kam</div>
            <div class="col calendar-header-day">Jum</div>
            <div class="col calendar-header-day text-danger">Sab</div>
            <div class="col calendar-header-day text-danger">Min</div>
        </div>

        <!-- Calendar Body -->
        <div id="calendarBody">
            <!-- Will be populated by JavaScript -->
        </div>
    </div>
</section>

<style>
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
        min-height: 100px;
        display: flex;
        flex-direction: column;
        padding: 8px !important;
    }

    .calendar-day-box.other-month {
        background-color: #f8f9fa;
        color: #cbd5e1;
    }

    .calendar-day-box.weekend {
        background-color: #f0f9ff;
    }

    .calendar-day-box.today {
        background-color: #fef08a;
    }

    .calendar-date-number {
        font-weight: 600;
        display: block;
        margin-bottom: 4px;
        font-size: 0.95rem;
    }

    .calendar-event {
        font-size: 0.7rem;
        font-weight: 600;
        padding: 4px 6px;
        margin-bottom: 2px;
        border-radius: 4px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        cursor: pointer;
        color: white !important;
        text-decoration: none !important;
    }

    .calendar-event:hover {
        opacity: 0.9;
    }

    .calendar-event-kementerian {
        background-color: #dc2626;
    }

    .calendar-event-poltekkes {
        background-color: #2563eb;
    }

    .calendar-event-universitas {
        background-color: #16a34a;
    }

    .calendar-event-masyarakat {
        background-color: #ca8a04;
    }

    .calendar-event-pemerintah {
        background-color: #0891b2;
    }

    .calendar-event-lembaga {
        background-color: #6b7280;
    }

    @media (max-width: 768px) {
        .calendar-day-box {
            min-height: 80px;
            font-size: 0.8rem;
        }

        .calendar-event {
            font-size: 0.65rem;
        }
    }
</style>

<script>
    let currentDate = new Date();
    const approvedReservations = <?= json_encode($approved_reservations ?? []); ?>;

    // Mapping klasifikasi ke warna
    const klasifikasiColor = {
        'Kementerian/Lembaga': 'kementerian',
        'Poltekkes': 'poltekkes',
        'Universitas/Politeknik/SMA': 'universitas',
        'Kelompok Masyarakat': 'masyarakat',
        'Pemerintah Daerah': 'pemerintah',
        'Lembaga Non Kementerian': 'lembaga'
    };

    function getDayOfWeek(date) {
        const day = date.getDay();
        return day === 0 ? 6 : day - 1; // Adjust so Monday is 0
    }

    function getMonthData(date) {
        const year = date.getFullYear();
        const month = date.getMonth();
        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);
        const daysInMonth = lastDay.getDate();
        const startingDayOfWeek = getDayOfWeek(firstDay);

        return {
            year,
            month,
            daysInMonth,
            startingDayOfWeek
        };
    }

    function getEventsForDate(date) {
        const dateStr = date.toISOString().split('T')[0];
        return approvedReservations.filter(res => res.tanggal_berkunjung === dateStr);
    }

    function renderCalendar() {
        const data = getMonthData(currentDate);
        const {
            year,
            month,
            daysInMonth,
            startingDayOfWeek
        } = data;

        // Update month/year display
        const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli',
            'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        document.getElementById('monthYearDisplay').textContent = `${monthNames[month]} ${year}`;

        let html = '';
        let dayCount = 1;

        // Calculate number of rows needed
        const totalCells = startingDayOfWeek + daysInMonth;
        const rows = Math.ceil(totalCells / 7);

        for (let row = 0; row < rows; row++) {
            html += '<div class="row g-0 border-top">';

            for (let col = 0; col < 7; col++) {
                const cellIndex = row * 7 + col;

                if (cellIndex < startingDayOfWeek || dayCount > daysInMonth) {
                    // Empty cell (previous or next month)
                    html += '<div class="col calendar-day-box other-month"></div>';
                } else {
                    const currentDay = dayCount;
                    const cellDate = new Date(year, month, currentDay);
                    const today = new Date();
                    const isToday = cellDate.toDateString() === today.toDateString();
                    const isWeekend = col === 5 || col === 6;

                    html += `<div class="col calendar-day-box ${isToday ? 'today' : ''} ${isWeekend ? 'weekend' : ''} p-2">`;
                    html += `<span class="calendar-date-number">${currentDay}</span>`;

                    // Get events for this date
                    const events = getEventsForDate(cellDate);
                    events.forEach(event => {
                        const colorClass = klasifikasiColor[event.klasifikasi] || 'lembaga';
                        const title = `${event.jam_kunjungan} - ${event.nama_instansi}`;
                        html += `<a href="javascript:void(0)" class="calendar-event calendar-event-${colorClass}" title="${title}" onclick="showEventDetail(${event.id})">${title}</a>`;
                    });

                    html += '</div>';
                    dayCount++;
                }
            }

            html += '</div>';
        }

        document.getElementById('calendarBody').innerHTML = html;
    }

    function showEventDetail(eventId) {
        const event = approvedReservations.find(r => r.id === eventId);
        if (event) {
            alert(
                `Detail Kunjungan:\n\n` +
                `Instansi: ${event.nama_instansi}\n` +
                `Pemohon: ${event.nama_pemohon}\n` +
                `Tanggal: ${event.tanggal_berkunjung}\n` +
                `Jam: ${event.jam_kunjungan} WIB\n` +
                `Jumlah Peserta: ${event.jumlah_peserta}\n` +
                `Lokasi: ${event.lokasi}\n` +
                `Klasifikasi: ${event.klasifikasi}`
            );
        }
    }

    function goToToday() {
        currentDate = new Date();
        renderCalendar();
    }

    function goToPrevMonth() {
        currentDate.setMonth(currentDate.getMonth() - 1);
        renderCalendar();
    }

    function goToNextMonth() {
        currentDate.setMonth(currentDate.getMonth() + 1);
        renderCalendar();
    }

    // Event listeners
    document.getElementById('prevMonth').addEventListener('click', goToPrevMonth);
    document.getElementById('nextMonth').addEventListener('click', goToNextMonth);
    document.getElementById('todayBtn').addEventListener('click', goToToday);

    // Initial render
    document.addEventListener('DOMContentLoaded', renderCalendar);
</script>