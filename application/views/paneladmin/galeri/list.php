<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Galleries List</h3>
                        <div class="card-tools">
                            <button class="btn btn-success" onclick="add_gallery()">
                                <i class="fa fa-plus"></i> Add New Gallery
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label>Filter by Category:</label>
                                <select id="categoryFilter" class="form-control">
                                    <option value="">All Categories</option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?= $category->id; ?>"><?= $category->name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>Filter by Status:</label>
                                <select id="statusFilter" class="form-control">
                                    <option value="">All Status</option>
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                            </div>
                        </div>

                        <table id="galleriesTable" class="table table-bordered table-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Images</th>
                                    <th>Category</th>
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="galleryModal" tabindex="-1" role="dialog" aria-labelledby="galleryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="galleryModalLabel">Add Gallery</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="galleryForm" enctype="multipart/form-data">
                <input type="hidden" name="id" id="gallery_id">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-7">
                            <div class="form-group">
                                <label for="category_id">Category <span class="text-danger">*</span></label>
                                <select class="form-control" id="category_id" name="category_id">
                                    <option value="">Select Category</option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?= $category->id; ?>"><?= $category->name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="help-block text-danger"></span>
                            </div>

                            <div class="form-group">
                                <label for="title">Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="title" name="title" placeholder="Enter gallery title">
                                <span class="help-block text-danger"></span>
                            </div>

                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="5" placeholder="Enter description"></textarea>
                                <span class="help-block text-danger"></span>
                            </div>
                        </div>

                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="image">Image <span class="text-danger">*</span></label>
                                <input type="file" class="form-control-file" id="image" name="images" accept="image/*">
                                <small class="text-muted">Max size: 5MB. Formats: JPG, JPEG, PNG</small>
                                <div id="imagePreview" class="mt-2"></div>
                                <span class="help-block text-danger"></span>
                            </div>

                            <div class="form-group">
                                <label for="status">Status</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                                <span class="help-block text-danger"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="btnSave">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="viewGalleryModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Gallery Details</h4>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="galleryViewContent">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="csrf_token" name="csrf_token_jkt3" value="<?= $this->security->get_csrf_hash(); ?>">

<script>
    let table;
    let save_method;

    function getCsrfToken() {
        let token = document.cookie.split('; ')
            .find(row => row.startsWith('csrf_cookie_jkt3='))
            ?.split('=')[1] || '';
        return token;
    }

    // Client side preview logic
    function previewImage(input) {
        if (input.files && input.files[0]) {
            let reader = new FileReader();
            reader.onload = function(e) {
                $('#imagePreview').html('<img src="' + e.target.result + '" class="img-thumbnail" style="max-height: 150px;">');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $(document).ready(function() {
        // Initialize DataTable
        table = $('#galleriesTable').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "<?= base_url('admin/galeri/ajax_list'); ?>",
                "type": "POST",
                "data": function(d) {
                    d.csrf_token_jkt3 = getCsrfToken();
                    d.category_filter = $('#categoryFilter').val();
                    d.status_filter = $('#statusFilter').val();
                },
                "beforeSend": function(xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', getCsrfToken());
                },
                "dataSrc": function(json) {
                    if (json.csrf_token) {
                        document.cookie = "csrf_cookie_jkt3=" + json.csrf_token + "; path=/";
                    }
                    return json.data;
                }
            },
            "columnDefs": [{
                "targets": [0, 1, 4, 5],
                "orderable": false,
            }],
            "responsive": true,
            "pageLength": 10
        });

        // Filter handlers
        $('#categoryFilter, #statusFilter').change(function() {
            table.draw();
        });

        // Form submission
        $('#galleryForm').submit(function(e) {
            e.preventDefault();
            save_gallery();
        });

        // Image preview triggers
        $('#image').change(function() {
            previewImage(this);
        });
    });

    function add_gallery() {
        save_method = 'add';
        $('#galleryForm')[0].reset();
        $('.form-group').removeClass('has-error');
        $('.help-block').empty();
        $('#imagePreview').empty();
        $('#galleryModal .modal-title').text('Add New Gallery');
        $('#galleryModal').modal('show');
    }

    function edit_gallery(id) {
        save_method = 'update';
        $('#galleryForm')[0].reset();
        $('.form-group').removeClass('has-error');
        $('.help-block').empty();
        $('#imagePreview').empty();

        $.ajax({
            url: "<?= base_url('admin/galeri/ajax_edit/'); ?>" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $('[name="id"]').val(data.id);
                $('[name="category_id"]').val(data.category_id);
                $('[name="title"]').val(data.title);
                $('[name="description"]').val(data.description);
                $('[name="status"]').val(data.status);

                if (data.image) {
                    $('#imagePreview').html('<img src="<?= base_url("public/uploads/galleries/"); ?>' + data.image + '" class="img-thumbnail" style="max-height: 150px;">');
                }

                $('#galleryModal .modal-title').text('Edit Gallery');
                $('#galleryModal').modal('show');
            },
            error: function() {
                toastr.error('Error loading gallery data');
            }
        });
    }

    function view_gallery(id) {
        $.ajax({
            url: "<?= base_url('admin/galeri/ajax_edit/'); ?>" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                let content = `
                <div class="row">
                    <div class="col-12 text-center mb-3">
                        ${data.images ? '<img src="<?= base_url("public/uploads/galleries/"); ?>' + data.images + '" class="img-fluid rounded border style="max-height: 300px;">' : '<div class="bg-light p-4 text-center rounded"><i class="fas fa-image fa-3x text-muted"></i></div>'}
                    </div>
                    <div class="col-12">
                        <h4>${data.title}</h4>
                        <p class="text-justify">${data.description || '<em class="text-muted">No description</em>'}</p>
                        <hr>
                        <table class="table table-sm table-clean">
                            <tr><th>Category</th><td>: ${data.category_name || 'N/A'}</td></tr>
                            <tr><th>Status</th><td>: <span class="badge badge-${data.status === 'Active' ? 'success' : 'danger'}">${data.status}</span></td></tr>
                        </table>
                    </div>
                </div>`;
                $('#galleryViewContent').html(content);
                $('#viewGalleryModal').modal('show');
            },
            error: function() {
                toastr.error('Error loading gallery details');
            }
        });
    }

    function save_gallery() {
        $('#btnSave').text('Saving...').prop('disabled', true);
        const formData = new FormData($('#galleryForm')[0]);
        const url = save_method === 'add' ? "<?= base_url('admin/galeri/ajax_add'); ?>" : "<?= base_url('admin/galeri/ajax_update'); ?>";

        formData.append('csrf_token_jkt3', getCsrfToken());

        $.ajax({
            url: url,
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            dataType: "JSON",
            beforeSend: function(xhr) {
                xhr.setRequestHeader('X-CSRF-Token', getCsrfToken());
            },
            success: function(data) {
                if (data.status) {
                    if (data.csrf_token) {
                        document.cookie = "csrf_cookie_jkt3=" + data.csrf_token + "; path=/";
                    }
                    $('#galleryModal').modal('hide');
                    table.ajax.reload(null, false);
                    toastr.success('Gallery saved successfully');
                } else {
                    if (data.inputerror) {
                        $('.form-group').removeClass('has-error');
                        $('.help-block').empty();

                        data.inputerror.forEach(function(field, index) {
                            let inputEl = $('[name="' + field + '"]');
                            inputEl.closest('.form-group').addClass('has-error');
                            inputEl.siblings('.help-block').text(data.error_string[index]);
                        });
                    }
                    toastr.error(data.message || 'Failed to save gallery');
                }
            },
            error: function() {
                toastr.error('Error saving gallery data');
            },
            complete: function() {
                $('#btnSave').text('Save').prop('disabled', false);
            }
        });
    }

    function delete_gallery(id) {
        if (confirm('Are you sure you want to delete this gallery item?')) {
            $.ajax({
                url: "<?= base_url('admin/galeri/ajax_delete/'); ?>" + id,
                type: "POST",
                data: {
                    csrf_token_jkt3: getCsrfToken()
                },
                dataType: "JSON",
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', getCsrfToken());
                },
                success: function(data) {
                    if (data.status) {
                        if (data.csrf_token) {
                            document.cookie = "csrf_cookie_jkt3=" + data.csrf_token + "; path=/";
                        }
                        table.ajax.reload(null, false);
                        toastr.success('Gallery item deleted successfully');
                    } else {
                        toastr.error(data.message || 'Failed to delete gallery item');
                    }
                },
                error: function() {
                    toastr.error('Error deleting gallery item');
                }
            });
        }
    }
</script>