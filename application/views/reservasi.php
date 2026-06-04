<!-- =================== LIST RESERVASI WITH DATATABLES =================== -->
<section class="page-section container" style="padding-top: 150px; padding-bottom: 60px;">
    <div class="text-center mb-5">
        <h2 class="fw-extrabold text-dark">Reservasi</h2>
        <p class="text-secondary">Daftar Riwayat Reservasi Anda di Poltekkes Jakarta III</p>
    </div>
    <?php if ($this->session->flashdata('message')): ?>
        <div class="container mb-3">
            <div class="alert alert-info"><?= $this->session->flashdata('message'); ?></div>
        </div>
    <?php endif; ?>
    <div class="card card-custom p-3 mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0 fw-bold">Daftar Reservasi</h5>
            <div>
                <button id="btnAddReservasi" class="btn btn-pk-primary rounded-pill">Tambah Reservasi</button>
            </div>
        </div>

        <div id="reservationsContainer">
            <table id="reservationsTable" class="table table-striped table-bordered dt-responsive nowrap small" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No Ticket</th>
                        <th>Nama Pemohon</th>
                        <th>Instansi</th>
                        <th>Tanggal Kunjungan</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($reservations)): ?>
                        <?php $i = 1;
                        foreach ($reservations as $row): ?>
                            <tr>
                                <td><?= $i++; ?></td>
                                <td><?= htmlspecialchars($row->no_ticket); ?></td>
                                <td><?= htmlspecialchars($row->nama_pemohon); ?></td>
                                <td><?= htmlspecialchars($row->nama_instansi); ?></td>
                                <td><?= htmlspecialchars($row->tanggal_berkunjung); ?></td>
                                <td><?= htmlspecialchars($row->jumlah_peserta); ?></td>
                                <td>
                                    <?php if ($row->status === 'pending'): ?>
                                        <span class="badge bg-warning">Menunggu</span>
                                    <?php elseif ($row->status === 'approved'): ?>
                                        <span class="badge bg-success">Disetujui</span>
                                    <?php elseif ($row->status === 'rejected'): ?>
                                        <span class="badge bg-danger">Ditolak</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary"><?= htmlspecialchars($row->status); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="viewReservation(<?= $row->id; ?>)">Lihat</button>
                                    <!-- Jika status di setujui maka tombol cetak muncul -->
                                    <?php if ($row->status === 'approved'): ?>
                                        <button type="button" class="btn btn-sm btn-outline-success ms-2" onclick="printReservation(<?= $row->id; ?>)">Cetak</button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- =================== FORM RESERVASI SECTION =================== -->
<section id="form_reservasi" class="page-section container" style="padding-top: 110px; padding-bottom: 60px; display: none;">
    <div class="card card-custom border-0 overflow-hidden">
        <div class="p-4 text-white text-center" style="background-color: var(--pk-teal);">
            <h3 class="fw-extrabold mb-1">Formulir Pengajuan Kunjungan</h3>
            <p class="mb-0 text-white-50 small">Harap lengkapi semua isian bertanda bintang (*)</p>
        </div>

        <form id="reservasiForm" class="p-4 p-md-5" onsubmit="event.preventDefault(); submitForm();" enctype="multipart/form-data">
            <?php // CSRF token for CodeIgniter (needed for AJAX requests)
            $csrf_name = $this->security->get_csrf_token_name();
            $csrf_hash = $this->security->get_csrf_hash();
            ?>
            <input type="hidden" name="<?= $csrf_name; ?>" value="<?= $csrf_hash; ?>" id="csrf_token">

            <!-- Form Sub-section: Data Pemohon -->
            <h5 class="fw-extrabold mb-4 pb-2 text-dark border-bottom">
                <span class="border-bottom border-3 border-success pb-2"><i
                        class="far fa-id-card me-2 text-kemenkes-teal"></i>Data Pemohon</span>
            </h5>

            <div class="row g-4 mb-5">
                <div class="col-md-6">
                    <label class="form-label fw-bold text-dark small">Asal Tamu *</label>
                    <select name="asal_tamu" required class="form-select form-select-custom">
                        <option value="">-- Pilih Asal Instansi --</option>
                        <option value="Kementerian/Lembaga">Kementerian / Lembaga</option>
                        <option value="Poltekkes">Poltekkes</option>
                        <option value="Pemerintah Daerah">Pemerintah Daerah</option>
                        <option value="Universitas/Politeknik/SMA">Universitas / Politeknik / SMA</option>
                        <option value="Lembaga Non Kementerian">Lembaga Non Kementerian</option>
                        <option value="Kelompok Masyarakat">Kelompok Masyarakat</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold text-dark small">Nomor WhatsApp *</label>
                    <input name="nomor_whatsapp" type="tel" required class="form-control form-control-custom" value="<?= isset($user->phone) ? htmlspecialchars($user->phone) : ''; ?>" placeholder="Contoh: 0812XXXXXXXX">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold text-dark small">Nama Pemohon *</label>
                    <input name="nama_pemohon" type="text" required class="form-control form-control-custom" value="<?= isset($user->first_name) ? htmlspecialchars($user->first_name) : ''; ?>" placeholder="Nama lengkap pengaju">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold text-dark small">Email Instansi / Pemohon *</label>
                    <input name="email" type="email" required class="form-control form-control-custom" value="<?= isset($user->email) ? htmlspecialchars($user->email) : ''; ?>" placeholder="nama@instansi.go.id">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold text-dark small">Nama Instansi Pemohon *</label>
                    <input name="nama_instansi" type="text" required class="form-control form-control-custom" value="<?= isset($user->company) ? htmlspecialchars($user->company) : ''; ?>" placeholder="Nama lengkap instansi asal">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold text-dark small">Provinsi *</label>
                    <select name="provinsi" required class="form-select form-select-custom">
                        <option value="">-- Pilih Provinsi Asal --</option>

                        <!-- Pulau Jawa -->
                        <option value="DKI Jakarta">DKI Jakarta</option>
                        <option value="Banten">Banten</option>
                        <option value="Jawa Barat">Jawa Barat</option>
                        <option value="Jawa Tengah">Jawa Tengah</option>
                        <option value="DI Yogyakarta">DI Yogyakarta</option>
                        <option value="Jawa Timur">Jawa Timur</option>

                        <!-- Pulau Sumatera -->
                        <option value="Aceh">Aceh</option>
                        <option value="Sumatera Utara">Sumatera Utara</option>
                        <option value="Sumatera Barat">Sumatera Barat</option>
                        <option value="Riau">Riau</option>
                        <option value="Kepulauan Riau">Kepulauan Riau</option>
                        <option value="Jambi">Jambi</option>
                        <option value="Bengkulu">Bengkulu</option>
                        <option value="Sumatera Selatan">Sumatera Selatan</option>
                        <option value="Kepulauan Bangka Belitung">Kepulauan Bangka Belitung</option>
                        <option value="Lampung">Lampung</option>

                        <!-- Bali & Nusa Tenggara -->
                        <option value="Bali">Bali</option>
                        <option value="Nusa Tenggara Barat">Nusa Tenggara Barat</option>
                        <option value="Nusa Tenggara Timur">Nusa Tenggara Timur</option>

                        <!-- Pulau Kalimantan -->
                        <option value="Kalimantan Barat">Kalimantan Barat</option>
                        <option value="Kalimantan Tengah">Kalimantan Tengah</option>
                        <option value="Kalimantan Selatan">Kalimantan Selatan</option>
                        <option value="Kalimantan Timur">Kalimantan Timur</option>
                        <option value="Kalimantan Utara">Kalimantan Utara</option>

                        <!-- Pulau Sulawesi -->
                        <option value="Sulawesi Utara">Sulawesi Utara</option>
                        <option value="Gorontalo">Gorontalo</option>
                        <option value="Sulawesi Tengah">Sulawesi Tengah</option>
                        <option value="Sulawesi Barat">Sulawesi Barat</option>
                        <option value="Sulawesi Selatan">Sulawesi Selatan</option>
                        <option value="Sulawesi Tenggara">Sulawesi Tenggara</option>

                        <!-- Kepulauan Maluku -->
                        <option value="Maluku">Maluku</option>
                        <option value="Maluku Utara">Maluku Utara</option>

                        <!-- Pulau Papua -->
                        <option value="Papua">Papua</option>
                        <option value="Papua Barat">Papua Barat</option>
                        <option value="Papua Selatan">Papua Selatan</option>
                        <option value="Papua Tengah">Papua Tengah</option>
                        <option value="Papua Pegunungan">Papua Pegunungan</option>
                        <option value="Papua Barat Daya">Papua Barat Daya</option>

                        <option value="Lainnya">Lainnya...</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold text-dark small">Nomor HP (Dapat Panggilan) *</label>
                    <input name="nomor_hp" type="tel" required class="form-control form-control-custom" value="<?= isset($user->phone) ? htmlspecialchars($user->phone) : ''; ?>" placeholder="Nomor telepon aktif">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold text-dark small">Alamat Instansi *</label>
                    <input name="alamat_instansi" type="text" required class="form-control form-control-custom"
                        placeholder="Alamat lengkap kantor / sekolah">
                </div>
            </div>

            <!-- Form Sub-section: Detail Rencana Kunjungan -->
            <h5 class="fw-extrabold mb-4 pb-2 text-dark border-bottom">
                <span class="border-bottom border-3 border-success pb-2"><i
                        class="far fa-calendar-alt me-2 text-kemenkes-teal"></i>Detail Kunjungan</span>
            </h5>

            <div class="row g-4 mb-5">
                <div class="col-md-4">
                    <label class="form-label fw-bold text-dark small">Jumlah Peserta Kunjungan *</label>
                    <input name="jumlah_peserta" type="number" min="1" required class="form-control form-control-custom"
                        placeholder="Contoh: 15">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold text-dark small">Klasifikasi Tamu *</label>
                    <select name="klasifikasi" required class="form-select form-select-custom">
                        <option value="">-- Pilih Klasifikasi --</option>
                        <option value="Kementerian/Lembaga">Kementerian / Lembaga</option>
                        <option value="Poltekkes">Poltekkes</option>
                        <option value="Pemerintah Daerah">Pemerintah Daerah</option>
                        <option value="Universitas/Politeknik/SMA">Universitas / Politeknik / SMA</option>
                        <option value="Kelompok Masyarakat">Kelompok Masyarakat</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold text-dark small">Nama Pimpinan Rombongan *</label>
                    <input name="nama_pimpinan" type="text" required class="form-control form-control-custom"
                        placeholder="Nama Pimpinan Dengan Gelar">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold text-dark small">Tanggal Berkunjung *</label>
                    <div id="tanggalWarning" class="alert alert-warning alert-sm mb-2 py-2 px-3" style="display: none; font-size: 0.875rem;">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <span id="tanggalWarningText">Tanggal kunjungan minimal 5 hari kerja dari hari ini</span>
                    </div>
                    <div id="conflictWarning" class="alert alert-danger alert-sm mb-2 py-2 px-3" style="display: none; font-size: 0.875rem;">
                        <i class="fas fa-calendar-times me-2"></i>
                        <span id="conflictWarningText">Slot waktu ini sudah dipesan. Silakan pilih tanggal atau jam lain.</span>
                    </div>
                    <input id="tanggalBerkunjung" name="tanggal_berkunjung" type="date" required class="form-control form-control-custom">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold text-dark small">Jam Kunjungan *</label>
                    <select id="jamKunjungan" name="jam_kunjungan" required class="form-select form-select-custom">
                        <option value="08.00">08.00 WIB</option>
                        <option value="09.00">09.00 WIB</option>
                        <option value="10.00">10.00 WIB</option>
                        <option value="11.00">11.00 WIB</option>
                        <option value="13.00">13.00 WIB</option>
                        <option value="14.00">14.00 WIB</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold text-dark small">Lokasi/Tujuan Kampus Utama *</label>
                    <select name="lokasi" required class="form-select form-select-custom">
                        <option value="">-- Pilih Unit Layanan --</option>
                        <option value="Direktorat">Gedung Direktorat</option>
                        <option value="Pelayanan Terpadu">Pelayanan Terpadu</option>
                        <option value="Resepsionis">Resepsionis Utama</option>
                        <option value="Perpustakaan">Perpustakaan Terpadu</option>
                        <option value="Klinik Pratama">Klinik Pratama Poltekkes</option>
                        <option value="Gedung Keperawatan">Gedung Keperawatan</option>
                        <option value="Gedung Kebidanan">Gedung Kebidanan</option>
                        <option value="Gedung TLM">Gedung TLM (Lab Teknologi Lab Medis)</option>
                        <option value="Gedung Fisioterapi">Gedung Fisioterapi</option>
                        <option value="Gedung Kelas Internasional">Gedung Kelas Internasional</option>
                        <option value="Gedung Pulomas">Gedung Pulomas</option>
                    </select>
                </div>

                <div class="col-md-12">
                    <label class="form-label fw-bold text-dark small">Topik Diskusi / Acara Utama *</label>
                    <textarea name="topik" required rows="3" class="form-control form-control-custom"
                        placeholder="Uraikan secara spesifik dan detil rencana agenda studi banding / koordinasi kerja..."></textarea>
                </div>

                <div class="col-md-12">
                    <label class="form-label fw-bold text-dark small">Alamat Hotel (Jika Menginap)</label>
                    <input name="alamat_hotel" type="text" class="form-control form-control-custom"
                        placeholder="Tuliskan nama hotel tempat menginap (opsional)">
                </div>
            </div>

            <!-- Form Sub-section: Upload Berkas -->
            <h5 class="fw-extrabold mb-4 pb-2 text-dark border-bottom">
                <span class="border-bottom border-3 border-success pb-2"><i
                        class="far fa-file-pdf me-2 text-kemenkes-teal"></i>Dokumen Pengantar</span>
            </h5>

            <div class="p-4 bg-light rounded-4 border border-dashed border-2 text-center mb-4">
                <i class="fas fa-cloud-upload-alt text-secondary fs-1 mb-3"></i>
                <h6 class="fw-bold text-dark mb-1">Unggah Surat Permohonan Kunjungan Resmi *</h6>
                <p class="text-muted small mb-3">Ditujukan kepada Direktur Poltekkes Kemenkes Jakarta III</p>
                <input name="document" type="file" required accept=".jpg,.jpeg,.png,.pdf" class="form-control w-50 mx-auto">
                <div class="form-text mt-3">Format berkas: .jpg, .jpeg, .png, .pdf (Maksimal ukuran 3MB)</div>
            </div>

            <!-- Alert Disclaimer -->
            <div class="alert alert-warning border-0 rounded-3 d-flex align-items-center mb-5" role="alert"
                style="background-color: #FFFBEB; color: #B45309;">
                <i class="fas fa-info-circle fs-5 me-3"></i>
                <div class="small fw-semibold">
                    Dengan menekan tombol Ajukan, Anda menyatakan data yang diinput adalah benar dan bersedia
                    mematuhi tata tertib Poltekkes Kemenkes Jakarta III.
                </div>
            </div>

            <!-- Form Action Buttons -->
            <div class="d-flex justify-content-end gap-3 border-top pt-4">
                <button type="button" onclick="navigate('reservasi_sop')"
                    class="btn btn-outline-secondary rounded-pill px-4">Batal</button>
                <button id="submitReservasiBtn" type="submit" class="btn btn-pk-primary px-5 rounded-pill">Kirim Pengajuan</button>
            </div>
        </form>
    </div>
</section>
<!-- =================== MODAL: Tata Cara Penerimaan (dipicu oleh Tambah Reservasi) =================== -->
<div class="modal fade" id="addReservasiModal" tabindex="-1" aria-labelledby="addReservasiModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addReservasiModalLabel">Tata Cara Penerimaan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-secondary">Harap baca & pahami syarat di bawah ini sebelum membuat formulir</p>

                <div class="bg-light p-4 rounded-4 border border-light mb-4">
                    <div class="d-flex align-items-start gap-3 mb-3 pb-3 border-bottom">
                        <div class="step-circle step-active">1</div>
                        <div>
                            <h6 class="fw-bold text-dark mb-1">Pendaftaran Daring</h6>
                            <p class="text-secondary small mb-0">Melakukan reservasi kunjungan melalui tautan portal resmi url : https://penerimaankunjungankerja.poltekkesjakarta3.ac.id</p>
                        </div>
                    </div>

                    <div class="d-flex align-items-start gap-3 mb-3 pb-3 border-bottom">
                        <div class="step-circle step-active">2</div>
                        <div>
                            <h6 class="fw-bold text-dark mb-1">Pengisian Formulir Lengkap</h6>
                            <p class="text-secondary small mb-0">Isikan data permohonan pada formulir: Asal Tamu, Nama Pemohon, Instansi, No HP/WA, Email, Provinsi, dan Alamat Lengkap.</p>
                        </div>
                    </div>

                    <div class="d-flex align-items-start gap-3 mb-3 pb-3 border-bottom">
                        <div class="step-circle step-active">3</div>
                        <div>
                            <h6 class="fw-bold text-dark mb-1">Kelayakan Berkas (Surat Resmi)</h6>
                            <p class="text-secondary small mb-0">Mengunggah Surat Permohonan Kunjungan Resmi yang ditujukan kepada <strong>Direktur Poltekkes Kemenkes Jakarta III</strong>.</p>
                        </div>
                    </div>

                    <div class="d-flex align-items-start gap-3 mb-3 pb-3 border-bottom">
                        <div class="step-circle step-active">4</div>
                        <div>
                            <h6 class="fw-bold text-dark mb-1">Batas Waktu Layanan</h6>
                            <p class="text-secondary small mb-0">Reservasi dapat dilakukan <strong>PALING LAMBAT 5 (lima) HARI KERJA</strong> sebelum waktu pelaksanaan kunjungan.</p>
                        </div>
                    </div>

                    <div class="d-flex align-items-start gap-3 mb-3 pb-3 border-bottom">
                        <div class="step-circle step-active">5</div>
                        <div>
                            <h6 class="fw-bold text-dark mb-1">Ketentuan SPPD</h6>
                            <p class="text-secondary small mb-0">Penandatanganan SPPD (Surat Perintah Perjalanan Dinas) hanya dilayani sesuai dengan daftar nama dan jumlah rombongan yang benar-benar hadir di lokasi.</p>
                        </div>
                    </div>
                </div>

                <div class="list-group list-group-flush mb-3">
                    <label class="list-group-item"><input type="checkbox" class="form-check-input me-2 checklist-item"> Saya telah membaca dan memahami tata cara penerimaan kunjungan</label>
                    <label class="list-group-item"><input type="checkbox" class="form-check-input me-2 checklist-item"> Formulir akan diisi lengkap dan surat permohonan diunggah</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" id="modalAcceptBtn" class="btn btn-pk-primary" disabled>Terima & Lanjutkan</button>
            </div>
        </div>
    </div>
</div>

<!-- =================== MODAL: Detail Reservasi =================== -->
<div class="modal fade" id="detailReservasiModal" tabindex="-1" aria-labelledby="detailReservasiModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailReservasiModalLabel">Detail Reservasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="detailModalBody">
                <!-- Content will be loaded here -->
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- 1. INISIALISASI DATATABLES ---
        if (typeof jQuery !== 'undefined' && $.fn.DataTable) {
            try {
                var table = $('#reservationsTable');
                if (table.length > 0 && !$.fn.DataTable.isDataTable(table)) {
                    table.DataTable({
                        responsive: true,
                        paging: true,
                        searching: true,
                        ordering: true,
                        pageLength: 10,
                        lengthMenu: [
                            [5, 10, 25, 50, -1],
                            [5, 10, 25, 50, "Semua"]
                        ],
                        language: {
                            search: "Cari:",
                            lengthMenu: "Tampilkan _MENU_ data per halaman"
                        },
                        emptyTable: "Belum ada reservasi.",
                        columnDefs: [{
                            orderable: false,
                            targets: -1
                        }]
                    });
                }
            } catch (e) {
                console.error('DataTables initialization error:', e);
            }
        } else {
            console.warn('jQuery or DataTables not loaded');
        }

        // --- 2. LOGIKA MODAL DAN CHECKLIST ---
        const addBtn = document.getElementById('btnAddReservasi');
        const modalAcceptBtn = document.getElementById('modalAcceptBtn');
        const checklistItems = document.querySelectorAll('.checklist-item');
        const addModal = document.getElementById('addReservasiModal');

        // Perbaikan: Pastikan library bootstrap sudah ada sebelum membuat instance modal
        let bsModal = null;
        if (addModal && typeof bootstrap !== 'undefined') {
            bsModal = new bootstrap.Modal(addModal);
        }

        function updateModalAccept() {
            // Perbaikan: Cegah error jika modalAcceptBtn tidak ditemukan di halaman
            if (!modalAcceptBtn) return;

            let allChecked = true;
            checklistItems.forEach(function(cb) {
                if (!cb.checked) allChecked = false;
            });
            modalAcceptBtn.disabled = !allChecked;
        }

        // Perbaikan: Hanya jalankan event listener jika checklist item benar-benar ada
        if (checklistItems.length > 0) {
            checklistItems.forEach(function(cb) {
                cb.addEventListener('change', updateModalAccept);
            });
        }

        if (addBtn) {
            addBtn.addEventListener('click', function() {
                if (bsModal) bsModal.show();
            });
        }

        if (modalAcceptBtn) {
            modalAcceptBtn.addEventListener('click', function() {
                if (bsModal) bsModal.hide();

                // Tampilkan form dan scroll otomatis
                const formSection = document.getElementById('form_reservasi');
                if (formSection) {
                    formSection.style.display = 'block';
                    formSection.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        }
    });

    // Fungsi untuk validasi tanggal berkunjung (minimal 5 hari dari hari ini)
    function validateTanggalBerkunjung() {
        const tanggalInput = document.getElementById('tanggalBerkunjung');
        const warningDiv = document.getElementById('tanggalWarning');
        const selectedDate = new Date(tanggalInput.value);

        if (!tanggalInput.value) {
            warningDiv.style.display = 'none';
            return true; // Field belum diisi
        }

        // Hitung tanggal minimal (hari ini + 5 hari)
        const today = new Date();
        const minimalDate = new Date(today);
        minimalDate.setDate(minimalDate.getDate() + 5);

        // Hapus waktu untuk perbandingan tanggal yang akurat
        selectedDate.setHours(0, 0, 0, 0);
        minimalDate.setHours(0, 0, 0, 0);

        if (selectedDate < minimalDate) {
            warningDiv.style.display = 'block';
            return false;
        } else {
            warningDiv.style.display = 'none';
            return true;
        }
    }

    // Add event listener untuk real-time validation
    document.addEventListener('DOMContentLoaded', function() {
        const tanggalInput = document.getElementById('tanggalBerkunjung');
        if (tanggalInput) {
            tanggalInput.addEventListener('change', validateTanggalBerkunjung);
            tanggalInput.addEventListener('blur', validateTanggalBerkunjung);
        }
    });
</script>
<script>
    function submitForm() {
        // Validasi tanggal berkunjung terlebih dahulu
        if (!validateTanggalBerkunjung()) {
            alert('Tanggal kunjungan minimal harus 5 hari kerja dari hari ini. Silakan pilih tanggal yang sesuai.');
            return;
        }

        // First check if user has pending reservation
        fetch('<?= base_url('reservasi/check_pending'); ?>', {
            method: 'GET',
            credentials: 'same-origin',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        }).then(r => r.json()).then(checkRes => {
            // Update CSRF token if provided
            if (checkRes && checkRes.csrf_name && checkRes.csrf_hash) {
                const csrfInput = document.getElementById('csrf_token');
                if (csrfInput) csrfInput.value = checkRes.csrf_hash;
            }

            // If user has pending reservation, show notification and stop
            if (checkRes.has_pending) {
                alert('Masih ada proses reservasi yang belum disetujui. Silakan tunggu sampai proses sebelumnya selesai.');
                return;
            }

            // Otherwise, proceed with form submission
            const form = document.getElementById('reservasiForm');
            const fd = new FormData(form);

            fetch('<?= base_url('reservasi/submit'); ?>', {
                method: 'POST',
                body: fd,
                credentials: 'same-origin',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            }).then(r => r.json()).then(res => {
                // update CSRF token in the form if provided
                try {
                    if (res && res.csrf_name && res.csrf_hash) {
                        // update hidden input (name may remain same, update value)
                        const csrfInput = document.getElementById('csrf_token');
                        if (csrfInput) csrfInput.value = res.csrf_hash;
                        // optionally update meta tag or global var if used elsewhere
                        window._csrf = {
                            name: res.csrf_name,
                            hash: res.csrf_hash
                        };
                    }
                } catch (e) {
                    console.warn('Failed to update CSRF token', e);
                }

                if (res.status === 'success') {
                    alert(res.message || 'Reservasi berhasil');
                    window.location.href = '<?= base_url('reservasi'); ?>';
                } else {
                    alert(res.message || 'Gagal menyimpan reservasi');
                }
            }).catch(err => {
                console.error(err);
                alert('Terjadi kesalahan, silakan coba lagi.');
            });
        }).catch(err => {
            console.error('Error checking pending status:', err);
            alert('Terjadi kesalahan, silakan coba lagi.');
        });
    }

    /**
     * View reservation detail in modal
     */
    function viewReservation(reservationId) {
        const detailModal = document.getElementById('detailReservasiModal');
        const detailBody = document.getElementById('detailModalBody');

        if (!detailModal) {
            alert('Modal tidak ditemukan');
            return;
        }

        // Show loading spinner
        detailBody.innerHTML = '<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>';

        // Fetch reservation detail from server
        fetch('<?= base_url('reservasi/get_detail/'); ?>' + reservationId, {
            method: 'GET',
            credentials: 'same-origin',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        }).then(r => r.json()).then(res => {
            if (res.status === 'success' && res.data) {
                const data = res.data;
                const statusBadge = data.status === 'pending' ? '<span class="badge bg-warning">Menunggu</span>' :
                    data.status === 'approved' ? '<span class="badge bg-success">Disetujui</span>' :
                    data.status === 'rejected' ? '<span class="badge bg-danger">Ditolak</span>' :
                    '<span class="badge bg-secondary">' + data.status + '</span>';

                const documentLink = data.document_path ?
                    '<a href="<?= base_url(); ?>' + data.document_path + '" target="_blank" class="btn btn-sm btn-outline-primary"><i class="fas fa-download"></i> Download Dokumen</a>' :
                    '<span class="text-muted">-</span>';

                const modalContent = `
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="fw-bold text-dark mb-2">Data Pemohon</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td class="fw-semibold">Nama Pemohon</td>
                                    <td>${data.nama_pemohon}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">Email</td>
                                    <td>${data.email}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">No. HP</td>
                                    <td>${data.nomor_hp}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">No. WhatsApp</td>
                                    <td>${data.nomor_whatsapp}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">Asal Tamu</td>
                                    <td>${data.asal_tamu}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">Provinsi</td>
                                    <td>${data.provinsi}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold text-dark mb-2">Data Instansi</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td class="fw-semibold">Instansi</td>
                                    <td>${data.nama_instansi}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">Alamat</td>
                                    <td>${data.alamat_instansi}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">Pimpinan</td>
                                    <td>${data.nama_pimpinan}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">Klasifikasi</td>
                                    <td>${data.klasifikasi}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">Jumlah Peserta</td>
                                    <td>${data.jumlah_peserta}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="fw-bold text-dark mb-2">Detail Kunjungan</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td class="fw-semibold">Tanggal Kunjungan</td>
                                    <td>${data.tanggal_berkunjung}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">Jam Kunjungan</td>
                                    <td>${data.jam_kunjungan} WIB</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">Lokasi</td>
                                    <td>${data.lokasi}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">Hotel</td>
                                    <td>${data.alamat_hotel || '-'}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold text-dark mb-2">Status & Topik</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td class="fw-semibold">Status</td>
                                    <td>${statusBadge}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">Topik Diskusi</td>
                                    <td>${data.topik}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">Tgl. Dibuat</td>
                                    <td>${new Date(data.created_at).toLocaleDateString('id-ID')}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-12">
                            <h6 class="fw-bold text-dark mb-2">Dokumen Pengantar</h6>
                            <div>${documentLink}</div>
                        </div>
                    </div>
                `;

                detailBody.innerHTML = modalContent;
            } else {
                detailBody.innerHTML = '<div class="alert alert-danger">Gagal memuat detail reservasi</div>';
            }
        }).catch(err => {
            console.error(err);
            detailBody.innerHTML = '<div class="alert alert-danger">Terjadi kesalahan, silakan coba lagi.</div>';
        });

        // Show modal
        const bsModal = new bootstrap.Modal(detailModal);
        bsModal.show();
    }
</script>

<script>
    function printReservation(id) {
        if (!id) {
            alert("ID Reservasi tidak valid.");
            return;
        }
        // Arahkan ke URL controller untuk cetak PDF, parameter ID disisipkan
        // Sesuaikan 'admin/verifikasi/cetak_pdf' dengan route/struktur folder Anda
        var printUrl = "<?= base_url('reservasi/cetak_pdf/') ?>" + id;

        // Buka PDF di tab baru
        window.open(printUrl, '_blank');
    }
</script>