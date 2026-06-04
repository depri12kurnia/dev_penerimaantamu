<!-- PHP CodeIgniter 3/4 Compatible View file -->
<style>
    :root {
        --pk-teal: #00a99d;
        --pk-teal-dark: #007c73;
        --pk-lime: #bed62f;
    }

    /* --- Tampilan Default (Desktop / Tablet) --- */
    .bg-hero-custom {
        /* background-image: linear-gradient(180deg, rgba(1, 84, 78, 0.75) 0%, rgba(2, 110, 99, 0.65) 100%),
            url('<?= base_url("public/settings/logo/beckground_slider.png"); ?>'); */
        background-image: linear-gradient(180deg, rgba(1, 84, 78, 0.75) 0%, rgba(2, 110, 99, 0.65) 100%),
            url('https://res.cloudinary.com/dmi0wyye1/image/upload/q_auto/f_auto/v1780581756/beckground_slider_pepvyd.png');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        border-radius: 0 0 64px 64px;
        padding: 160px 20px;
        /* Menjaga ruang agar gambar background terlihat */
        min-height: 600px;
        /* Tinggi standar untuk desktop */
        display: flex;
        align-items: center;
        /* Memastikan konten di dalamnya tetap di tengah */
    }

    /* --- Tampilan Responsif (Khusus Mobile / Layar di bawah 768px) --- */
    @media (max-width: 767px) {
        .bg-hero-custom {
            /* 1. Sesuaikan radius agar tidak terlalu melengkung di layar kecil */
            border-radius: 0 0 32px 32px;

            /* 2. Sesuaikan padding agar konten tidak terlalu mepet ke tepi HP */
            padding: 60px 16px;

            /* 3. Atur tinggi minimal khusus mobile agar gambar tidak gepeng/hilang */
            min-height: 350px;

            /* 4. Opsional: Fokuskan gambar ke tengah atas jika objek utama gambar terpotong */
            background-position: center top;
        }
    }

    .card-overlapping {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card-overlapping:hover {
        transform: translateY(-8px);
        box-shadow: 0 1rem 3rem rgba(0, 169, 157, 0.15) !important;
    }
</style>

<!-- Beranda / Hero Section -->
<section id="beranda" class="position-relative overflow-hidden" style="padding-top: 120px; padding-bottom: 60px;">
    <div class="bg-hero-custom text-white py-5 text-center" style="min-height: 800px; padding-bottom: 9rem !important;">
        <div class="container py-4">

            <!-- Welcome Badge -->
            <div class="d-inline-flex align-items-center gap-3 px-4 py-2 rounded-pill shadow-lg mb-4" style="background-color: var(--pk-lime); color: #111;">
                <h1 class="fw-bold tracking-wide">Selamat Datang</h1>
            </div>

            <!-- Headings -->
            <h1 class="display-5 fw-extrabold mb-2">
                di <span class="border-bottom border-warning border-3">Website Penerimaan Tamu</span>
            </h1>
            <h2 class="h1 fw-extrabold mb-4" style="color: var(--pk-lime);">
                Poltekkes Kemenkes Jakarta III

                <p class="lead opacity-75" style="color: var(--pk-gray-bg);">
                    Reservasi tamu online dalam satu platform
                </p>
        </div>
    </div>

    <!-- Overlapping Option Cards -->
    <div class=" container" style="margin-top: -8.5rem; position: relative; z-index: 5;">
        <div class="row g-4 justify-content-center">
            <!-- Card 1: Reservasi -->
            <div class="col-md-6 col-lg-5">
                <div class="card h-100 border-0 rounded-4 shadow-lg p-4 card-overlapping bg-white">
                    <div class="card-body d-flex flex-column justify-content-between p-2">
                        <div>
                            <div class="rounded-3 d-flex align-items-center justify-content-center mb-3" style="width: 50px; height: 50px; background-color: rgba(0, 169, 157, 0.1); color: var(--pk-teal);">
                                <i class="fa-solid fa-calendar-days fs-4"></i>
                            </div>
                            <h4 class="fw-bold text-dark mb-2">Reservasi Kunjungan</h4>
                            <p class="text-muted small">Jadwalkan kunjungan Anda ke Poltekkes Kemenkes Jakarta III dengan cepat dan nyaman.</p>
                        </div>
                        <div class="mt-3">
                            <a href="<?= base_url('login'); ?>" class="btn btn-primary rounded-pill px-4 py-2" style="background-color: var(--pk-teal); border-color: var(--pk-teal);">Mulai Reservasi</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card 2: Website Instansi -->
            <div class="col-md-6 col-lg-5">
                <div class="card h-100 border-0 rounded-4 shadow-lg p-4 card-overlapping text-dark" style="background-color: var(--pk-lime);">
                    <div class="card-body d-flex flex-column justify-content-between p-2">
                        <div>
                            <div class="rounded-3 bg-dark text-warning d-flex align-items-center justify-content-center mb-3" style="width: 50px; height: 50px;">
                                <i class="fa-solid fa-house fs-4"></i>
                            </div>
                            <h4 class="fw-bold text-dark mb-2">Website Instansi</h4>
                            <p class="text-dark opacity-75 small">Akses informasi lengkap seputar Poltekkes Kemenkes Jakarta III melalui website resmi kami.</p>
                        </div>
                        <div class="mt-3">
                            <a href="https://www.poltekkesjakarta3.ac.id" target="_blank" class="btn btn-dark rounded-pill px-4 py-2 text-white">Kunjungi Website</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Tata Cara Kunjungan Section -->
<section class="py-5" style="background-color: #f8fafc; padding-top: 0.5rem !important;">
    <div class="container my-4">
        <div class="text-center mb-5">
            <h2 class="fw-extrabold text-dark display-6">Tata Cara Kunjungan</h2>
            <div class="mx-auto" style="width: 80px; height: 5px; background-color: var(--pk-teal); border-radius: 5px;"></div>
        </div>

        <div class="row g-4 justify-content-center">

            <!-- Step 1 -->
            <div class="col-md-6">
                <div class="card border-0 rounded-4 shadow-sm p-4 text-white h-100 position-relative overflow-hidden" style="background: linear-gradient(135deg, var(--pk-teal) 0%, var(--pk-teal-dark) 100%);">
                    <span class="position-absolute opacity-10 font-weight-bold" style="right: 1.5rem; top: 1rem; font-size: 5.5rem;">1</span>
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background-color: rgba(255, 255, 255, 0.15);">
                            <i class="fa-solid fa-file-signature fs-4 text-white"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold mb-2">Registrasi Kunjungan</h5>
                    <p class="text-white-50 small mb-0">Buka website penerimaan tamu lalu isi formulir kunjungan dengan data diri dan tanggal kunjungan secara lengkap.</p>
                </div>
            </div>

            <!-- Step 2 -->
            <div class="col-md-6">
                <div class="card border-0 rounded-4 shadow-sm p-4 bg-white text-dark h-100 position-relative overflow-hidden">
                    <span class="position-absolute text-muted opacity-25 font-weight-bold" style="right: 1.5rem; top: 1rem; font-size: 5.5rem;">2</span>
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background-color: rgba(0, 169, 157, 0.08); color: var(--pk-teal);">
                            <i class="fa-solid fa-shield-halved fs-4"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold mb-2 text-dark">Verifikasi Data</h5>
                    <p class="text-muted small mb-0">Admin akan memeriksa data dan jadwal kunjungan yang telah diajukan.</p>
                </div>
            </div>

            <!-- Step 3 -->
            <div class="col-md-6">
                <div class="card border-0 rounded-4 shadow-sm p-4 bg-white text-dark h-100 position-relative overflow-hidden">
                    <span class="position-absolute text-muted opacity-25 font-weight-bold" style="right: 1.5rem; top: 1rem; font-size: 5.5rem;">3</span>
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background-color: rgba(0, 169, 157, 0.08); color: var(--pk-teal);">
                            <i class="fa-solid fa-comments fs-4"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold mb-2 text-dark">Konfirmasi Kunjungan</h5>
                    <p class="text-muted small mb-0">Informasi persetujuan atau jadwal kunjungan akan dikirim melalui WhatsApp.</p>
                </div>
            </div>

            <!-- Step 4 -->
            <div class="col-md-6">
                <div class="card border-0 rounded-4 shadow-sm p-4 bg-white text-dark h-100 position-relative overflow-hidden">
                    <span class="position-absolute text-muted opacity-25 font-weight-bold" style="right: 1.5rem; top: 1rem; font-size: 5.5rem;">4</span>
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background-color: rgba(0, 169, 157, 0.08); color: var(--pk-teal);">
                            <i class="fa-solid fa-user-check fs-4"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold mb-2 text-dark">Pelaksanaan Kunjungan</h5>
                    <p class="text-muted small mb-0">Tunjukkan bukti pendaftaran kepada petugas dan ikuti arahan serta tata tertib selama kunjungan berlangsung.</p>
                </div>
            </div>

        </div>
    </div>
</section>