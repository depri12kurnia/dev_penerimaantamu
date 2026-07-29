<div class="row">
    <div class="col-12">
        <div class="card card-info card-outline">
            <div class="card-header align-items-center d-flex">
                <h3 class="card-title mr-auto">
                    <i class="fas fa-cogs mr-1"></i> Settings Website
                </h3>
                <div class="card-tools">
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table id="data" class="table table-hover table-head-fixed text-nowrap">
                    <thead>
                        <tr>
                            <th style="width: 5%">#</th>
                            <th>Nama Website</th>
                            <th>Perusahaan</th>
                            <th>Telepon / Email</th>
                            <th class="text-center" style="width: 10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($settings)): ?>
                            <?php foreach ($settings as $row) { ?>
                                <tr data-widget="expandable-table" aria-expanded="false" style="cursor: pointer;">
                                    <td><?php echo $row->id; ?></td>
                                    <td>
                                        <strong><?php echo $row->name; ?></strong>
                                    </td>
                                    <td><?php echo $row->company; ?></td>
                                    <td>
                                        <span class="d-block"><i class="fas fa-phone fa-xs text-muted mr-1"></i> <?php echo $row->telepon; ?></span>
                                        <span class="d-block text-muted text-sm"><i class="fas fa-envelope fa-xs mr-1"></i> <?php echo $row->email; ?></span>
                                    </td>
                                    <td class="text-center" onclick="event.stopPropagation();">
                                        <a href="settings/edit/<?php echo $row->id; ?>" class="btn btn-sm btn-warning" title="Edit Settings">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                    </td>
                                </tr>

                                <tr>
                                    <td colspan="5">
                                        <div class="p-3">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h5><i class="fas fa-info-circle text-info mr-1"></i> Detail Informasi</h5>
                                                    <hr class="mt-1 mb-2">
                                                    <p><strong>Deskripsi:</strong><br> <span class="text-wrap"><?php echo $row->description; ?></span></p>
                                                    <p><strong>Alamat:</strong><br> <span class="text-wrap"><?php echo $row->address; ?></span></p>
                                                </div>
                                                <div class="col-md-6 border-left">
                                                    <h5><i class="fas fa-images text-success mr-1"></i> Media & Assets</h5>
                                                    <hr class="mt-1 mb-2">
                                                    <div class="row text-center">
                                                        <div class="col-6">
                                                            <span class="d-block text-muted mb-1">Logo</span>
                                                            <?php if ($row->logo): ?>
                                                                <img src="<?php echo base_url('uploads/' . $row->logo); ?>" alt="Logo" class="img-thumbnail elevation-1" style="max-height: 60px;">
                                                            <?php else: ?>
                                                                <span class="badge badge-secondary">No Image</span>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="col-6">
                                                            <span class="d-block text-muted mb-1">Favicon / Icon</span>
                                                            <?php if ($row->icon): ?>
                                                                <img src="<?php echo base_url('uploads/' . $row->icon); ?>" alt="Icon" class="img-thumbnail elevation-1" style="max-height: 40px;">
                                                            <?php else: ?>
                                                                <span class="badge badge-secondary">No Icon</span>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted p-4">Belum ada data pengaturan website.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>