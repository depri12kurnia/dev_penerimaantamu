<!-- login yang baru -->
<section id="login" class="page-section"
  style="padding-top: 100px; background-color: #EDF2F7; min-height: 100vh;">
  <div class="container py-5 d-flex align-items-center justify-content-center">
    <div class="card card-custom border-0 shadow-lg w-100 overflow-hidden"
      style="max-width: 420px; border-radius: 24px;">
      <div class="p-5 text-center text-white"
        style="background: linear-gradient(135deg, var(--pk-teal) 0%, #007A71 100%);">
        <i class="far fa-user-circle fs-1 mb-3"></i>
        <h4 class="fw-extrabold mb-1">Selamat Datang</h4>
        <p class="mb-0 text-white-50 small">Sistem Autentikasi Admin & Humas</p>
      </div>
      <script>
        document.addEventListener('DOMContentLoaded', function() {

          // 1. Alert untuk Validation Errors (CodeIgniter)
          <?php if (validation_errors()) : ?>
            Swal.fire({
              icon: 'error',
              title: 'Validation Error',
              html: '<?php echo str_replace(["\r", "\n"], '', validation_errors()); ?>',
              confirmButtonColor: '#3085d6',
            });
          <?php endif; ?>

          // 2. Alert untuk Flashdata Message
          <?php if ($this->session->flashdata('message')) : ?>
            Swal.fire({
              icon: 'warning',
              title: 'Attention',
              text: '<?php echo $this->session->flashdata('message'); ?>',
              confirmButtonColor: '#3085d6',
            });
          <?php endif; ?>

          // 3. Tambahan: Alert untuk Flashdata Success (Opsional tapi sering dipakai)
          <?php if ($this->session->flashdata('success')) : ?>
            Swal.fire({
              icon: 'success',
              title: 'Success!',
              text: '<?php echo $this->session->flashdata('success'); ?>',
              timer: 3000,
              showConfirmButton: false
            });
          <?php endif; ?>

        });
      </script>

      <div class="card-body p-5 bg-white">
        <?php echo form_open("auth/login"); ?>
        <div class="mb-3">
          <label class="form-label small fw-bold text-dark">Username / Email</label>
          <input type="text" name="identity" required class="form-control form-control-custom text-center"
            placeholder="Masukkan username">
        </div>
        <div class="mb-4">
          <label class="form-label small fw-bold text-dark">Password</label>
          <input type="password" name="password" required class="form-control form-control-custom text-center"
            placeholder="••••••••">
        </div>
        <button type="submit" class="btn btn-pk-primary w-100 rounded-pill py-3 fw-bold">Masuk
          Aplikasi</button>
        <?php echo form_close(); ?>
        <div style="margin-top: 15px; text-align: center;">
          <p>Login tamu silahkan menggunakan:</p>
          <a href="<?php echo $google_login_url; ?>">
            <img src="https://developers.google.com/identity/images/btn_google_signin_dark_normal_web.png" alt="Google Sign In" style="width: 200px;">
          </a>
        </div>
      </div>
    </div>
  </div>
</section>