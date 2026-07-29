<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3><?= $summary->total_reservasi ?? 0 ?></h3>
                        <p>Total Reservasi</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3><?= $summary->total_pending ?? 0 ?></h3>
                        <p>Pending Request</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3><?= $summary->total_approved ?? 0 ?></h3>
                        <p>Approved Kunjungan</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3><?= $summary->total_rejected ?? 0 ?></h3>
                        <p>Rejected / Cancelled</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-times-circle"></i>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <!-- Gunakan card-outline agar terlihat lebih elegan di AdminLTE 3 -->
                <div class="card card-info">
                    <div class="card-header border-0">
                        <h3 class="card-title font-weight-bold mt-1" id="monthYearDisplay">Memuat Kalender...</h3>

                        <!-- Pindah ke card-tools khas AdminLTE -->
                        <div class="card-tools">
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-default" onclick="currentDate.setMonth(currentDate.getMonth() - 1); renderCalendar();"><i class="fas fa-chevron-left"></i> Prev</button>
                                <button type="button" class="btn btn-sm btn-default" onclick="currentDate = new Date(); renderCalendar();">Today</button>
                                <button type="button" class="btn btn-sm btn-default" onclick="currentDate.setMonth(currentDate.getMonth() + 1); renderCalendar();">Next <i class="fas fa-chevron-right"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <!-- Elemen Kontainer Kalender Utama -->
                        <div id="calendarBody" class="w-100"></div>
                    </div>
                </div>
            </div>

            <!-- PIE Chart & Bar Chart -->
            <div class="col-md-6">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Klasifikasi Tamu</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <canvas id="pieChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Statistik Kunjungan <?= date('Y') ?></h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart">
                            <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Bar Chart Untuk Asal Tamu -->
            <div class="col-md-12">
                <div class="card card-info">
                    <div class="card-header border-0">
                        <h3 class="card-title">Asal Instansi Tamu</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- ID Canvas diganti menjadi barChartAsalTamu -->
                        <canvas id="barChartAsalTamu" style="min-height: 280px; height: 280px; max-height: 350px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Bootstrap 4 Modal untuk Detail Event / Reservasi (Standar AdminLTE 3) -->
<div class="modal fade" id="modalEventDetail" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h4 class="modal-title" id="modalEventDetailLabel">Detail Agenda Kunjungan</h4>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modalEventBody">
                <!-- Konten dinamis dari javascript akan disisipkan di sini -->
            </div>
            <div class="modal-footer justify-content-end">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<style>
    /* CSS Tambahan agar grid kalender terlihat rapi di dalam card body p-0 */
    .calendar-day-box {
        min-height: 100px;
        border-right: 1px solid #dee2e6;
        border-bottom: 1px solid #dee2e6;
        padding: 5px;
        transition: background-color 0.2s;
    }

    .calendar-day-box:nth-child(7n) {
        border-right: none;
        /* Hilangkan border kanan di kolom terakhir */
    }

    .calendar-day-box.has-events:hover {
        background-color: #f8f9fa;
        cursor: pointer;
    }

    .calendar-date-number {
        font-weight: 600;
        color: #495057;
    }

    .calendar-event-item {
        font-size: 11px;
        border-radius: 3px;
        padding: 2px 5px;
        margin-bottom: 3px;
        display: block;
        color: #fff !important;
        text-align: left;
    }

    .calendar-event-item:hover {
        filter: brightness(90%);
        color: #fff;
    }
</style>

<script>
    let currentDate = new Date();
    let currentView = 'month';

    // Data dari Controller
    const approvedReservations = <?= json_encode($approved_reservations ?? []); ?>;

    // Mapping langsung ke utility class warna AdminLTE 3 (Bootstrap 4)
    const klasifikasiColor = {
        'Kunjungan Kerja': 'bg-danger', // Biru
        'Studi Tiru/Studi Banding': 'bg-primary', // Hijau
        'Pendidikan/Pelatihan': 'bg-success', // Cyan
    };

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
            const year = currentDate.getFullYear();
            const month = currentDate.getMonth();

            monthYearDisplay.textContent = `${monthNames[month]} ${year}`;

            const firstDay = new Date(year, month, 1);
            const startingDayOfWeek = getDayOfWeek(firstDay);
            const daysInMonth = new Date(year, month + 1, 0).getDate();

            let html = '';

            // Header Hari
            const dayNames = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
            html += '<div class="row m-0 border-bottom bg-light">';
            for (let i = 0; i < 7; i++) {
                html += `<div class="col text-center py-2 font-weight-bold" style="border-right: ${i < 6 ? '1px solid #dee2e6' : 'none'};">${dayNames[i]}</div>`;
            }
            html += '</div>';

            let dayCount = 1;
            const totalCells = startingDayOfWeek + daysInMonth;
            const rows = Math.ceil(totalCells / 7);

            for (let row = 0; row < rows; row++) {
                html += '<div class="row m-0">';
                for (let col = 0; col < 7; col++) {
                    const cellIndex = row * 7 + col;

                    if (cellIndex < startingDayOfWeek || dayCount > daysInMonth) {
                        html += '<div class="col calendar-day-box bg-light" style="opacity:0.5;"></div>';
                    } else {
                        const currentDay = dayCount;
                        const cellDate = new Date(year, month, currentDay);
                        const isToday = cellDate.toDateString() === new Date().toDateString();
                        const events = getEventsForDate(cellDate);
                        const hasEventsClass = events.length > 0 ? 'has-events' : '';

                        // Jika hari ini, beri warna biru muda tipis
                        const todayBg = isToday ? 'style="background-color: #e8f4fd;"' : '';

                        html += `<div class="col calendar-day-box ${hasEventsClass}" ${todayBg} ${events.length > 0 ? `onclick="showDaySummary('${cellDate.toISOString()}')"` : ''}>`;

                        // Badge untuk tanggal jika hari ini
                        if (isToday) {
                            html += `<span class="calendar-date-number d-inline-block mb-1 badge badge-primary">${currentDay}</span>`;
                        } else {
                            html += `<span class="calendar-date-number d-inline-block mb-1">${currentDay}</span>`;
                        }

                        events.forEach(event => {
                            const colorClass = klasifikasiColor[event.klasifikasi] || 'bg-secondary';
                            const title = `${event.jam_kunjungan} - ${event.nama_instansi || event.nama_pemohon}`;
                            html += `<a href="javascript:void(0)" class="calendar-event-item text-truncate ${colorClass}" title="${title}" onclick="event.stopPropagation(); showEventDetail(${event.id})">${title}</a>`;
                        });

                        html += '</div>';
                        dayCount++;
                    }
                }
                html += '</div>';
            }
            calendarBody.innerHTML = html;
        }
    }

    function showEventDetail(eventId) {
        const event = approvedReservations.find(r => r.id == eventId);

        if (event) {
            // Gunakan tabel standar Bootstrap 4 dari AdminLTE
            let html = `
            <table class="table table-sm table-striped table-bordered mb-0">
                <tbody>
                    <tr><th style="width: 35%;">Nama Instansi</th><td><strong>${event.nama_instansi || '-'}</strong></td></tr>
                    <tr><th>Nama Pemohon</th><td>${event.nama_pemohon}</td></tr>
                    <tr><th>Waktu</th><td><span class="badge badge-success">${event.tanggal_berkunjung}</span> Jam ${event.jam_kunjungan} WIB</td></tr>
                    <tr><th>Jml Peserta</th><td>${event.jumlah_peserta || 0} Orang</td></tr>
                    <tr><th>Lokasi</th><td>${event.lokasi || '-'}</td></tr>
                    <tr><th>Topik</th><td>${event.topik || '-'}</td></tr>
                    <tr><th>Klasifikasi</th><td><span class="badge badge-info">${event.klasifikasi}</span></td></tr>
                </tbody>
            </table>
            `;
            document.getElementById('modalEventBody').innerHTML = html;

            // Memanggil modal menggunakan jQuery (standar AdminLTE 3 / Bootstrap 4)
            $('#modalEventDetail').modal('show');
        } else {
            console.error("Data event tidak ditemukan untuk ID:", eventId);
        }
    }

    function showDaySummary(dateIsoString) {
        const selectedDate = new Date(dateIsoString);
        const events = getEventsForDate(selectedDate);

        if (events.length > 0) {
            let html = `<p class="mb-3 text-muted">Ditemukan <strong>${events.length} acara</strong> pada tanggal ini.</p>`;
            html += `<div class="list-group">`;

            events.forEach(event => {
                const titleText = event.nama_instansi || event.nama_pemohon;
                const badgeColor = klasifikasiColor[event.klasifikasi] || 'bg-secondary';
                // Ubah class dari badge-primary ke dinamis
                html += `
                <a href="javascript:void(0)" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" onclick="showEventDetail('${event.id}')">
                    <div>
                        <h6 class="mb-1 font-weight-bold text-dark">${titleText}</h6>
                        <small class="text-secondary"><i class="far fa-clock mr-1"></i> Pukul ${event.jam_kunjungan} WIB</small>
                    </div>
                    <span class="badge badge-pill ${badgeColor}">${event.klasifikasi}</span>
                </a>
                `;
            });
            html += `</div>`;

            document.getElementById('modalEventBody').innerHTML = html;

            // Memanggil modal menggunakan jQuery (standar AdminLTE 3)
            $('#modalEventDetail').modal('show');
        }
    }

    // Inisialisasi render saat halaman dimuat
    $(document).ready(function() {
        renderCalendar();
    });
</script>

<script>
    $(function() {
        // 1. Parse JSON dari Controller
        var donutData = <?= $pie_chart_data ?? '{}' ?>;
        var areaChartData = <?= $bar_chart_data ?? '{}' ?>;
        var asalTamuChartData = <?= $bar_chart_asal_tamu ?? '{}' ?>;

        //-------------
        //- PIE CHART -
        //-------------
        if ($('#pieChart').length) {
            var pieChartCanvas = $('#pieChart').get(0).getContext('2d');
            new Chart(pieChartCanvas, {
                type: 'pie',
                data: donutData,
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                }
            });
        }

        //----------------------------
        //- BAR CHART (STATISTIK BULANAN) -
        //----------------------------
        if ($('#barChart').length) {
            var barChartCanvas = $('#barChart').get(0).getContext('2d');
            var barChartData = $.extend(true, {}, areaChartData);

            new Chart(barChartCanvas, {
                type: 'bar',
                data: barChartData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    datasetFill: false,
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                                precision: 0
                            }
                        }]
                    }
                }
            });
        }

        //---------------------------
        //- BAR CHART (ASAL TAMU) -
        //---------------------------
        if ($('#barChartAsalTamu').length) {
            var asalTamuCanvas = $('#barChartAsalTamu').get(0).getContext('2d');

            new Chart(asalTamuCanvas, {
                type: 'bar',
                data: asalTamuChartData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        display: false // Sembunyikan legend karena warna tiap bar berbeda
                    },
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                                precision: 0
                            },
                            gridLines: {
                                display: true
                            }
                        }],
                        xAxes: [{
                            gridLines: {
                                display: false
                            },
                            ticks: {
                                autoSkip: false // Tampilkan seluruh nama instansi
                            }
                        }]
                    }
                }
            });
        }
    });
</script>