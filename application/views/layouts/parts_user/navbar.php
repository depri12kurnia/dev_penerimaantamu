<nav class="navbar navbar-expand-lg navbar-light navbar-custom fixed-top py-3">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="<?php echo base_url('/'); ?>">
            <img src="<?php echo base_url(); ?>public/settings/logo/logo.png" alt="Logo" width="280">
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto gap-2 align-items-center mt-3 mt-lg-0">
                <li class="nav-item">
                    <a href="<?php echo base_url('beranda'); ?>">
                        <button class="btn nav-link-custom <?= ($this->uri->segment(1) == 'beranda')  ? 'active' : ''; ?>">BERANDA</button>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo base_url('jadwal'); ?>"><button class="btn nav-link-custom <?= ($this->uri->segment(1) == 'jadwal')  ? 'active' : ''; ?>">JADWAL</button></a>
                </li>
                <li class="nav-item">

                    <?php if ($this->ion_auth->logged_in()): ?>
                        <a href="<?= base_url('reservasi'); ?>" class="btn nav-link-custom <?= ($this->uri->segment(1) == 'reservasi')  ? 'active' : ''; ?>">RESERVASI</a>
                    <?php else: ?>
                        <a href="<?= base_url('login'); ?>" class="btn nav-link-custom <?= ($this->uri->segment(1) == 'login')  ? 'active' : ''; ?>">RESERVASI</a>
                    <?php endif; ?>
                </li>
                <li class="nav-item">
                    <a href="<?php echo base_url('galeri'); ?>"><button class="btn nav-link-custom <?= ($this->uri->segment(1) == 'galeri')  ? 'active' : ''; ?>">GALERI</button></a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo base_url('faq'); ?>"><button class="btn nav-link-custom <?= ($this->uri->segment(1) == 'faq')  ? 'active' : ''; ?>">FAQ</button></a>
                </li>

                <li class="nav-item">
                    <div class="d-flex align-items-center gap-2">
                        <?php if ($this->ion_auth->logged_in()): ?>
                            <?php
                            $user = $this->ion_auth->user()->row();
                            $picture = !empty($user->profile_pic) ? $user->profile_pic : '';

                            // Normalize picture URL: if not absolute, prepend base_url()
                            $picture_url = '';
                            if ($picture) {
                                if (strpos($picture, 'http://') === 0 || strpos($picture, 'https://') === 0) {
                                    $picture_url = $picture;
                                } else {
                                    $picture_url = base_url($picture);
                                }
                            }
                            ?>
                            <div class="dropdown">
                                <button class="btn btn-link d-flex align-items-center justify-content-center p-0 border-0" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="text-decoration: none; width:40px; height:40px;">
                                    <?php if ($picture_url): ?>
                                        <img src="<?= $picture_url; ?>" alt="Profile" class="rounded-circle" width="40" height="40" style="object-fit: cover; cursor: pointer;" crossorigin="anonymous" referrerpolicy="no-referrer" loading="lazy" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <?php else: ?>
                                        <div class="bg-pk-green rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; cursor: pointer;">
                                            <i class="fas fa-user text-white"></i>
                                        </div>
                                    <?php endif; ?>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                                    <li><span class="dropdown-header"><?= $user->email; ?></span></li>
                                </ul>
                            </div>
                            <a href="<?= base_url('auth/logout'); ?>" class="btn btn-pk-primary px-4 py-2 rounded-pill w-100 d-inline-block text-center" style="text-decoration: none;">
                                <i class="fas fa-sign-out-alt me-2"></i>LOGOUT
                            </a>
                        <?php else: ?>
                            <a href="<?= base_url('auth/login'); ?>" class="btn btn-pk-primary px-4 py-2 rounded-pill w-100 d-inline-block text-center" style="text-decoration: none;">
                                <i class="fas fa-sign-in-alt me-2"></i>LOGIN
                            </a>
                    </div>
                <?php endif; ?>
        </div>
    </div>
</nav>