<style>
    :root {
        --pk-teal: #00a99d;
        --pk-teal-dark: #007c73;
        --pk-lime: #bed62f;
    }

    /* --- Tampilan Default (Desktop / Tablet) --- */
    .bg-hero-custom {
        background-image: linear-gradient(180deg, rgba(1, 84, 78, 0.82) 0%, rgba(2, 110, 99, 0.75) 100%),
            url('<?= base_url("public/settings/logo/beckground_slider.png"); ?>');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        /* border-radius: 0 0 64px 64px; */
        /* Dikembalikan agar konsisten melengkung */
        padding: 100px 20px;
        min-height: 500px;
        /* Ditambah sedikit ruang vertikal agar card bernapas */
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Modern Card Glassmorphism Container */
    .login-box-modern {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 24px;
        padding: 2.5rem 2rem;
        max-width: 450px;
        margin: 0 auto;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }

    /* Mengatur ilustrasi agar ukurannya pas & ideal */
    .img-illustration {
        max-width: 400px;
        height: auto;
        margin: 0 auto 1.5rem auto;
        display: block;
        border-radius: 24px;
    }

    /* Transisi halus saat tombol Google di-hover */
    .btn-google-wrapper {
        display: inline-block;
        transition: transform 0.2s ease, filter 0.2s ease;
    }

    .btn-google-wrapper:hover {
        transform: translateY(-2px);
        filter: brightness(1.1);
    }

    /* Link teks modern */
    .login-link {
        color: var(--pk-lime);
        text-decoration: none;
        font-weight: 700;
        border-bottom: 2px dashed rgba(190, 214, 47, 0.5);
        transition: all 0.2s ease;
    }

    .login-link:hover {
        color: #fff;
        border-bottom-color: #fff;
    }

    /* --- Tampilan Responsif (Khusus Mobile) --- */
    @media (max-width: 767px) {
        .bg-hero-custom {
            /* border-radius: 0 0 32px 32px; */
            padding: 60px 16px;
            min-height: 450px;
            background-position: center top;
        }

        .login-box-modern {
            padding: 2rem 1.5rem;
            max-width: 100%;
        }

        .img-illustration {
            max-width: 300px;
        }
    }
</style>

<section id="login" class="position-relative overflow-hidden" style="padding-top: 80px; padding-bottom: 0px;">
    <div class="bg-hero-custom text-white text-center">
        <div class="container">

            <div class="login-box-modern">

                <img src="<?= base_url('public/settings/logo/belum_login.png'); ?>" alt="Ilustrasi Belum Login" class="img-illustration">

                <div class="mb-4">
                    <p class="fs-5 fw-medium mb-1">Akses Terbatas</p>
                    <p class="opacity-90 small">
                        Anda belum login. Silakan <a href="<?php echo $google_login_url; ?>" class="login-link">Login</a> terlebih dahulu untuk melanjutkan menggunakan layanan platform:
                    </p>
                </div>

                <a href="<?php echo $google_login_url; ?>" class="btn-google-wrapper">
                    <img src="https://developers.google.com/identity/images/btn_google_signin_dark_normal_web.png" alt="Google Sign In" style="width: 220px; border-radius: 4px;">
                </a>

            </div>

        </div>
    </div>
</section>