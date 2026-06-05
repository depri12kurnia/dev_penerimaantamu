<style>
    /* Warna kustom text-muted agar kontras di background gelap */
    .text-light-muted {
        color: rgba(255, 255, 255, 0.8) !important;
    }

    /* Efek hover interaktif untuk tombol media sosial */
    .social-btn {
        width: 42px;
        /* Ditingkatkan ke 42px agar ramah sentuhan jari (touch target) di mobile */
        height: 42px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1.5px solid rgba(255, 255, 255, 0.4);
        color: #ffffff;
        border-radius: 50%;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .social-btn:hover {
        background-color: #ffffff;
        color: var(--pk-teal) !important;
        border-color: #ffffff;
        transform: translateY(-3px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
    }

    /* Merapikan list info kontak */
    .footer-info-list {
        list-style: none;
        padding-left: 0;
        margin-bottom: 0;
    }

    .footer-info-list li {
        margin-bottom: 12px;
        display: flex;
        align-items: flex-start;
    }

    .footer-info-list i {
        margin-top: 4px;
        margin-right: 12px;
        width: 16px;
        text-align: center;
    }

    /* MEDIA QUERY: RESPONSIVE UNTUK MOBILE DEVICE (Layar < 768px) */
    @media (max-width: 767.98px) {
        .footer-info-list li {
            flex-direction: row;
            /* Mengubah list menjadi baris vertikal di mobile */
            align-items: left;
            justify-content: left;
            text-align: left;
            margin-bottom: 18px;
            /* Memberikan ruang antar informasi */
        }

        .footer-info-list i {
            margin-right: 16px;
            /* Menghilangkan margin kanan ikon saat berpusat */
            margin-bottom: 0px;
            /* Jarak antara ikon dan teks di bawahnya */
            font-size: 1.2rem;
            /* Memperbesar sedikit ukuran ikon di mobile */
        }

        /* Mengatur jarak antar tombol sosial media agar tidak terlalu rapat di layar sentuh */
        .social-btn {
            width: 44px;
            height: 44px;
        }
    }
</style>
<footer class="text-white pt-5 pb-0 border-top border-4"
    style="background-color: var(--pk-teal); border-color: var(--pk-green) !important;">
    <div class="container pb-5">
        <div class="row gy-4 text-center text-md-start">

            <!-- Kolom 1: Profil Kampus -->
            <div class="col-lg-4 col-md-6">
                <h5 class="fw-bold text-white mb-3">Poltekkes Kemenkes Jakarta III</h5>
                <p class="text-light-muted small mb-0" style="line-height: 1.7;">
                    Website Penerimaan Tamu Poltekkes Kemenkes Jakarta III Reservasi tamu online dalam satu platform
                </p>
            </div>

            <!-- Kolom 2: Waktu Pelayanan -->
            <div class="col-lg-4 col-md-6">
                <h5 class="fw-bold text-white mb-3">Waktu Pelayanan</h5>
                <ul class="footer-info-list text-light-muted small">
                    <li>
                        <i class="far fa-clock text-white"></i>
                        <div>
                            <strong>Senin - Kamis:</strong><br>
                            07:30 - 16:00 WIB
                        </div>
                    </li>
                    <li>
                        <i class="far fa-clock text-white"></i>
                        <div>
                            <strong>Jumat:</strong><br>
                            07:30 - 16:30 WIB
                        </div>
                    </li>
                    <li>
                        <i class="fas fa-ban text-white"></i>
                        <div>
                            <strong>Sabtu & Minggu:</strong><br>
                            Libur (Pelayanan Tutup)
                        </div>
                    </li>
                </ul>
            </div>

            <!-- Kolom 3: Hubungi Kami & Media Sosial -->
            <div class="col-lg-4 col-md-12">
                <h5 class="fw-bold text-white mb-3">Hubungi Kami</h5>
                <ul class="footer-info-list text-light-muted small mb-4">
                    <li>
                        <i class="fas fa-map-marker-alt text-white"></i>
                        <span>Jl. Melati II No.2, Jatiwarna, Pondok Melati, Kota Bekasi, Jawa Barat 17415</span>
                    </li>
                    <li>
                        <i class="fas fa-phone-alt text-white"></i>
                        <span>(021) 84978693</span>
                    </li>
                    <li>
                        <i class="fas fa-envelope text-white"></i>
                        <span>humas@poltekkesjakarta3.ac.id</span>
                    </li>
                </ul>

                <div class="d-flex justify-content-center justify-content-lg-start gap-2 pt-2">
                    <a href="https://wa.me/6281113102256" class="social-btn" title="WhatsApp">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                    <a href="https://www.instagram.com/polkesjakarta3?igsh=dXNzNHVjYjQyNWk3" class="social-btn" title="Instagram">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="https://www.youtube.com/channel/UChzyWfEnnmomSdNLkt6Iutw" class="social-btn" title="YouTube">
                        <i class="fab fa-youtube"></i>
                    </a>
                    <a href="https://web.facebook.com/POLTEKKES.JAKARTA3?_rdc=1&_rdr#" class="social-btn" title="Facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="https://www.poltekkesjakarta3.ac.id" class="social-btn" title="Website Resmi">
                        <i class="fas fa-globe"></i>
                    </a>
                </div>
            </div>

        </div>
    </div>

    <!-- Bilah Hak Cipta Bawah (Full Width Bottom Bar) -->
    <div class="py-3 border-top" style="background-color: rgba(0, 0, 0, 0.18); border-color: rgba(255, 255, 255, 0.1) !important;">
        <div class="container text-center">
            <p class="text-light-muted small mb-0">
                &copy; 2026 Portal Humas Digital - Poltekkes Kemenkes Jakarta III. Semua Hak Cipta Dilindungi.
            </p>
        </div>
    </div>
</footer>

<!-- Bootstrap 5 Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>