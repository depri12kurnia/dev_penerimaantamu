<section class="content">
      <div class="container-fluid">
            <div class="row">
                  <!-- Dibuat col-md-6 agar form tidak terlalu lebar dan terlihat proporsional, silakan ganti ke col-md-12 jika ingin memenuhi layar -->
                  <div class="col-md-6 mx-auto">
                        <div class="card card-outline card-info shadow-sm">
                              <div class="card-header">
                                    <h3 class="card-title"><i class="fas fa-lock mr-2"></i> Change Password</h3>
                              </div>

                              <div class="card-body">
                                    <!-- Notifikasi Validasi Error -->
                                    <?php if (validation_errors()) : ?>
                                          <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                                                <h5><i class="icon fas fa-ban"></i> Terjadi Kesalahan!</h5>
                                                <?php echo validation_errors(); ?>
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                      <span aria-hidden="true">&times;</span>
                                                </button>
                                          </div>
                                    <?php endif; ?>

                                    <!-- Notifikasi Flashdata Sistem -->
                                    <?php if ($this->session->flashdata('message')) : ?>
                                          <div class="alert alert-info alert-dismissible fade show shadow-sm" role="alert">
                                                <h5><i class="icon fas fa-info"></i> Informasi</h5>
                                                <?php echo $this->session->flashdata('message'); ?>
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                      <span aria-hidden="true">&times;</span>
                                                </button>
                                          </div>
                                    <?php endif; ?>

                                    <?php echo form_open("auth/change_password"); ?>
                                    <div class="form-group">
                                          <label for="oldPassword">Old Password</label>
                                          <div class="input-group">
                                                <input type="password" name="oldPassword" id="oldPassword" class="form-control" placeholder="Masukkan password lama" required>
                                                <div class="input-group-append">
                                                      <div class="input-group-text"><span class="fas fa-key"></span></div>
                                                </div>
                                          </div>
                                    </div>

                                    <div class="form-group">
                                          <label for="newPassword">New Password</label>
                                          <div class="input-group">
                                                <input type="password" name="newPassword" id="newPassword" class="form-control" placeholder="Masukkan password baru" required>
                                                <div class="input-group-append">
                                                      <div class="input-group-text"><span class="fas fa-lock"></span></div>
                                                </div>
                                          </div>
                                    </div>

                                    <div class="form-group">
                                          <label for="confirmPassword">Confirm Password</label>
                                          <div class="input-group">
                                                <input type="password" name="confirmPassword" id="confirmPassword" class="form-control" placeholder="Ulangi password baru" required>
                                                <div class="input-group-append">
                                                      <div class="input-group-text"><span class="fas fa-lock"></span></div>
                                                </div>
                                          </div>
                                    </div>

                                    <div class="form-group mt-3">
                                          <div class="icheck-primary d-inline">
                                                <input type="checkbox" id="showPassword">
                                                <label for="showPassword" class="font-weight-normal text-muted" style="cursor: pointer;">
                                                      Show Password
                                                </label>
                                          </div>
                                    </div>

                                    <hr>

                                    <div class="row">
                                          <div class="col-12">
                                                <button type="submit" class="btn btn-success float-right shadow-sm">
                                                      <i class="fas fa-save mr-1"></i> Change Password
                                                </button>
                                          </div>
                                    </div>
                                    <?php echo form_close(); ?>
                              </div>
                              <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                  </div>
            </div>
      </div>
</section>

<script>
      $(document).ready(function() {
            $('#showPassword').click(function() {
                  if ($(this).is(':checked')) {
                        $('#oldPassword, #newPassword, #confirmPassword').attr('type', 'text');
                  } else {
                        $('#oldPassword, #newPassword, #confirmPassword').attr('type', 'password');
                  }
            });
      });
</script>