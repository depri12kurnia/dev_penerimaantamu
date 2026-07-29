<div class="row">
    <div class="col-md-12">
        <div class="card card-info card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-edit mr-1"></i> Edit Settings Website
                </h3>
            </div>
            <form action="<?php echo base_url('admin/settings/update/' . $settings->id); ?>" enctype="multipart/form-data" method="post" accept-charset="utf-8">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name"><i class="fas fa-globe text-secondary mr-1"></i> Website Name</label>
                                <input type="text" class="form-control" name="name" id="name" value="<?php echo htmlspecialchars($settings->name); ?>" placeholder="Masukkan nama website" required>
                            </div>

                            <div class="form-group">
                                <label for="company"><i class="fas fa-building text-secondary mr-1"></i> Company / Institution</label>
                                <input type="text" class="form-control" name="company" id="company" value="<?php echo htmlspecialchars($settings->company); ?>" placeholder="Masukkan nama instansi/perusahaan">
                            </div>

                            <div class="form-group">
                                <label for="description"><i class="fas fa-align-left text-secondary mr-1"></i> Description</label>
                                <textarea class="form-control" name="description" id="description" rows="4" placeholder="Deskripsi singkat website..."><?php echo htmlspecialchars($settings->description); ?></textarea>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="telepon"><i class="fas fa-phone text-secondary mr-1"></i> Telepon</label>
                                        <input type="text" class="form-control" name="telepon" id="telepon" value="<?php echo htmlspecialchars($settings->telepon); ?>" placeholder="021-xxxxxx">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="email"><i class="fas fa-envelope text-secondary mr-1"></i> Email</label>
                                        <input type="email" class="form-control" name="email" id="email" value="<?php echo htmlspecialchars($settings->email); ?>" placeholder="admin@domain.com">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="address"><i class="fas fa-map-marker-alt text-secondary mr-1"></i> Address</label>
                                <textarea class="form-control" name="address" id="address" rows="2" placeholder="Alamat lengkap..."><?php echo htmlspecialchars($settings->address); ?></textarea>
                            </div>

                            <div class="form-group">
                                <label for="logo"><i class="fas fa-image text-secondary mr-1"></i> Website Logo</label>

                                <?php if (!empty($settings->logo)): ?>
                                    <div class="mb-2">
                                        <small class="text-muted d-block mb-1">Logo Saat Ini:</small>
                                        <img src="<?php echo base_url('uploads/' . $settings->logo); ?>" class="img-thumbnail" style="max-height: 50px;" alt="Current Logo">
                                    </div>
                                <?php endif; ?>

                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" name="logo" id="logo" accept="image/*">
                                        <label class="custom-file-label" for="logo">Pilih file gambar baru...</label>
                                    </div>
                                </div>
                                <small class="text-muted">Format: PNG, JPG, JPEG. Maksimal 2MB. Biarkan kosong jika tidak diganti.</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light">
                    <button type="submit" class="btn btn-info px-4">
                        <i class="fas fa-save mr-1"></i> Simpan Perubahan
                    </button>
                    <a href="<?php echo base_url('admin/settings'); ?>" class="btn btn-default float-right">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Agar nama file yang dipilih otomatis muncul di label penjelajah file
        if (typeof bsCustomFileInput !== 'undefined animate') {
            bsCustomFileInput.init();
        }
    });
</script>