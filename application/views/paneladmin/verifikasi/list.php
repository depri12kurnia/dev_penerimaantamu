<!-- Main Content -->
<section class="content">
    <div class="container-fluid">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-check-circle mr-2"></i>
                    Verifikasi Reservasi Kunjungan
                </h3>
            </div>
            <div class="card-body">
                <!-- Filter Form -->
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="filter_status">Status:</label>
                        <select id="filter_status" class="form-control form-control-sm">
                            <option value="">Semua Status</option>
                            <option value="pending">Menunggu Verifikasi</option>
                            <option value="approved">Disetujui</option>
                            <option value="rejected">Ditolak</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="filter_date">Tanggal Kunjungan:</label>
                        <input type="month" id="filter_date" class="form-control form-control-sm">
                    </div>
                    <div class="col-md-3">
                        <label>&nbsp;</label><br>
                        <button type="button" id="btn_filter" class="btn btn-primary btn-sm">Filter</button>
                        <button type="button" id="btn_reset" class="btn btn-secondary btn-sm">Reset</button>
                    </div>
                    <div class="col-md-3 text-right">
                        <label>&nbsp;</label><br>
                        <button type="button" id="btn_export_excel" class="btn btn-success btn-sm">
                            <i class="fas fa-file-excel"></i> Export ke Excel
                        </button>
                    </div>
                </div>
                <!-- End Filter Form -->
                <div class="table-responsive">
                    <table id="reservasi_table" class="table table-bordered table-striped small">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>No Ticket</th>
                                <th>Nama Pemohon</th>
                                <th>Instansi</th>
                                <th>Tanggal Kunjungan</th>
                                <th>Jumlah Peserta</th>
                                <th>Status</th>
                                <th>Tgl. Pengajuan</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal Detail & Verifikasi Reservasi -->
<div class="modal fade" id="modal_verifikasi" role="dialog">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Detail Reservasi & Verifikasi</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body form">
                <form action="#" id="form_verifikasi" class="form-horizontal">
                    <input type="hidden" value="" name="reservasi_id" />
                    <div class="form-body">
                        <div class="row">
                            <!-- Data Pemohon -->
                            <div class="col-lg-6 col-12">
                                <h5 class="mb-3"><strong>Data Pemohon</strong></h5>
                                <div class="form-group">
                                    <label class="control-label col-md-12">Nama Pemohon</label>
                                    <div class="col-md-12">
                                        <input name="nama_pemohon" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-12">Email</label>
                                    <div class="col-md-12">
                                        <input name="email" class="form-control" type="email" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-12">No. HP</label>
                                    <div class="col-md-12">
                                        <input name="nomor_hp" class="form-control" type="tel" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-12">No. WhatsApp</label>
                                    <div class="col-md-12">
                                        <div class="row align-items-center">
                                            <div class="col-8 col-md-8">
                                                <input name="nomor_whatsapp" class="form-control" type="tel" readonly>
                                            </div>
                                            <div class="col-4 col-md-4">
                                                <a href="#" id="linkChatWhatsApp" target="_blank" class="btn btn-success btn-sm mb-0">
                                                    <i class="fab fa-whatsapp"></i> Chat via WhatsApp
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-12">Asal Tamu</label>
                                    <div class="col-md-12">
                                        <input name="asal_tamu" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-12">Provinsi</label>
                                    <div class="col-md-12">
                                        <input name="provinsi" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>

                            <!-- Data Instansi -->
                            <div class="col-lg-6 col-12">
                                <h5 class="mb-3"><strong>Data Instansi</strong></h5>
                                <div class="form-group">
                                    <label class="control-label col-md-12">Nama Instansi</label>
                                    <div class="col-md-12">
                                        <input name="nama_instansi" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-12">Alamat Instansi</label>
                                    <div class="col-md-12">
                                        <textarea name="alamat_instansi" class="form-control" rows="3" readonly></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-12">Nama Pimpinan</label>
                                    <div class="col-md-12">
                                        <input name="nama_pimpinan" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-12">Klasifikasi</label>
                                    <div class="col-md-12">
                                        <input name="klasifikasi" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-12">Jumlah Peserta</label>
                                    <div class="col-md-12">
                                        <input name="jumlah_peserta" class="form-control" type="number" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Detail Kunjungan -->
                            <div class="col-lg-6 col-12">
                                <h5 class="mb-3"><strong>Detail Kunjungan</strong></h5>
                                <div class="form-group">
                                    <label class="control-label col-md-12">Tanggal Kunjungan</label>
                                    <div class="col-md-12">
                                        <input name="tanggal_berkunjung" class="form-control" type="date" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-12">Jam Kunjungan</label>
                                    <div class="col-md-12">
                                        <input name="jam_kunjungan" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-12">Lokasi/Unit</label>
                                    <div class="col-md-12">
                                        <input name="lokasi" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-12">Alamat Hotel (jika menginap)</label>
                                    <div class="col-md-12">
                                        <input name="alamat_hotel" class="form-control" type="text" readonly>
                                    </div>
                                </div>
                            </div>

                            <!-- Topik & Dokumen -->
                            <div class="col-lg-6 col-12">
                                <h5 class="mb-3"><strong>Topik & Dokumen</strong></h5>
                                <div class="form-group">
                                    <label class="control-label col-md-12">Topik Diskusi</label>
                                    <div class="col-md-12">
                                        <textarea name="topik" class="form-control" rows="4" readonly></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-12">Surat Permohonan</label>
                                    <div class="col-md-12">
                                        <a href="#" id="downloadDokumen" target="_blank" style="display: none;">
                                            <button type="button" class="btn btn-warning btn-sm"><i class="fas fa-download"></i> Download Dokumen</button>
                                        </a>
                                        <span id="noDokumen" class="text-muted">Tidak ada dokumen</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- Verifikasi Section -->
                        <div class="row">
                            <div class="col-12">
                                <h5 class="mb-3"><strong>Verifikasi & Keputusan</strong></h5>
                                <div class="form-group">
                                    <label class="control-label col-md-12">Status Verifikasi</label>
                                    <div class="col-md-12">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input" id="status_approved" name="status_verifikasi" value="approved">
                                            <label class="custom-control-label" for="status_approved">
                                                <span class="badge badge-success">Disetujui</span>
                                            </label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input" id="status_rejected" name="status_verifikasi" value="rejected">
                                            <label class="custom-control-label" for="status_rejected">
                                                <span class="badge badge-danger">Ditolak</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Alasan Penolakan -->
                                <div class="form-group" id="alasan_group" style="display: none;">
                                    <label class="control-label col-md-12">Alasan Penolakan</label>
                                    <div class="col-md-12">
                                        <textarea name="comment" placeholder="Jelaskan alasan penolakan" class="form-control" rows="3"></textarea>
                                        <small class="form-text text-muted">Alasan ini akan dikirimkan ke pemohon</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-start">
                        <button type="button" class="btn btn-primary" id="btnSave">Simpan Verifikasi</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="csrf_token" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">

<script>
    var table;
    var csrfName = '<?= $this->security->get_csrf_token_name(); ?>';

    function getCsrfToken() {
        var token = $('#csrf_token').val();
        // console.log('CSRF token read', csrfName, token);
        return token;
    }

    function viewVerification(id) {
        viewReservasi(id);
    }

    function viewReservasi(id) {
        $('#form_verifikasi')[0].reset();
        $('.form-group').removeClass('has-error');
        $('.help-block').empty();

        $.ajax({
            url: "<?php echo site_url('admin/verifikasi/ajax_view/') ?>" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $('[name="reservasi_id"]').val(data.id);
                $('[name="nama_pemohon"]').val(data.nama_pemohon);
                $('[name="email"]').val(data.email);
                $('[name="nomor_hp"]').val(data.nomor_hp);
                $('[name="nomor_whatsapp"]').val(data.nomor_whatsapp);
                $('[name="asal_tamu"]').val(data.asal_tamu);
                $('[name="provinsi"]').val(data.provinsi);
                $('[name="nama_instansi"]').val(data.nama_instansi);
                $('[name="alamat_instansi"]').val(data.alamat_instansi);
                $('[name="nama_pimpinan"]').val(data.nama_pimpinan);
                $('[name="klasifikasi"]').val(data.klasifikasi);
                $('[name="jumlah_peserta"]').val(data.jumlah_peserta);
                $('[name="tanggal_berkunjung"]').val(data.tanggal_berkunjung);
                $('[name="jam_kunjungan"]').val(data.jam_kunjungan);
                $('[name="lokasi"]').val(data.lokasi);
                $('[name="alamat_hotel"]').val(data.alamat_hotel);
                $('[name="topik"]').val(data.topik);
                document.getElementById('linkChatWhatsApp').href = "https://wa.me/" + data.nomor_whatsapp.replace(/[^0-9]/g, '').replace(/^0/, '62');

                // Set dokumen link
                if (data.document_path) {
                    $('#downloadDokumen').attr('href', '<?= base_url(); ?>' + data.document_path).show();
                    $('#noDokumen').hide();
                } else {
                    $('#downloadDokumen').hide();
                    $('#noDokumen').show();
                }

                // Set status verifikasi
                $('input[name="status_verifikasi"]').prop('checked', false);
                if (data.status) {
                    $('input[name="status_verifikasi"][value="' + data.status + '"]').prop('checked', true);
                }

                toggleAlasanField();
                $('#modal_verifikasi').modal('show');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error loading data: ' + textStatus);
                console.log(jqXHR.responseText);
            }
        });
    }

    function verifyReservasi(id, status, comment) {
        if (status === 'rejected' && (!comment || !comment.trim())) {
            alert('Harap berikan alasan penolakan');
            return;
        }

        if (confirm('Konfirmasi verifikasi reservasi?')) {
            var postData = {
                id: id,
                status: status,
                comment: comment
            };
            postData[csrfName] = getCsrfToken();
            console.log('verifyReservasi AJAX payload', postData);

            $.ajax({
                url: "<?= site_url('admin/verifikasi/ajax_verify'); ?>",
                type: "POST",
                data: postData,
                headers: {
                    'X-CSRF-TOKEN': getCsrfToken()
                },
                dataType: "json",
                success: function(res) {
                    if (!res.status) {
                        alert('Error: ' + (res.message || 'Unable to verify'));
                        return;
                    }

                    if (res.csrf_token) {
                        $('#csrf_token').val(res.csrf_token);
                    }
                    alert('Verifikasi berhasil');
                    table.ajax.reload(null, false);
                    $('#modal_verifikasi').modal('hide');
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('AJAX error: ' + textStatus);
                    console.log(jqXHR.responseText);
                }
            });
        }
    }

    function toggleAlasanField() {
        var selected = $('input[name="status_verifikasi"]:checked').val();
        if (selected === 'rejected') {
            $('#alasan_group').show();
            $('[name="comment"]').prop('readonly', false);
        } else {
            $('#alasan_group').hide();
            $('[name="comment"]').prop('readonly', true);
        }
    }

    $(document).ready(function() {
        if (!$.fn.DataTable.isDataTable('#reservasi_table')) {
            table = $('#reservasi_table').DataTable({
                "processing": true,
                "serverSide": true,
                "responsive": false,
                "autoWidth": false,
                "pageLength": 10,
                "ajax": {
                    "url": "<?php echo site_url('admin/verifikasi/ajax_list') ?>",
                    "type": "POST",
                    "headers": {
                        'X-CSRF-TOKEN': getCsrfToken()
                    },
                    "data": function(d) {
                        d[csrfName] = getCsrfToken();
                        // console.log('DataTable AJAX payload CSRF field', csrfName, d[csrfName]);
                        d.status = $('#filter_status').val();
                        d.tanggal_berkunjung = $('#filter_date').val();
                    },
                    "dataSrc": function(json) {
                        if (json.csrf_token) {
                            $('#csrf_token').val(json.csrf_token);
                        }
                        return json.data;
                    },
                    "error": function(xhr, error, thrown) {
                        console.log('DataTable AJAX Error:', error, xhr.responseText);
                    }
                }
            });
        }

        // Filter button
        $('#btn_filter').click(function() {
            table.ajax.reload();
        });

        // Reset button
        $('#btn_reset').click(function() {
            $('#filter_status').val('');
            $('#filter_date').val('');
            table.ajax.reload();
        });

        // Export Excel
        $('#btn_export_excel').click(function() {
            var status = $('#filter_status').val();
            var tanggal = $('#filter_date').val();
            var url = "<?php echo site_url('admin/verifikasi/export_excel') ?>?status=" + encodeURIComponent(status) + "&tanggal=" + encodeURIComponent(tanggal);
            window.open(url, '_blank');
        });

        // Toggle alasan field when status changes
        $('input[name="status_verifikasi"]').on('change', toggleAlasanField);

        // Save button
        $('#btnSave').on('click', function() {
            var id = $('[name="reservasi_id"]').val();
            var status = $('input[name="status_verifikasi"]:checked').val();
            var comment = $('[name="comment"]').val();

            if (!status) {
                alert('Pilih status verifikasi');
                return;
            }

            verifyReservasi(id, status, comment);
        });
    });

    // cetak reservasi
    function printReservation(id) {
        if (!id) {
            alert("ID Reservasi tidak valid.");
            return;
        }
        // Arahkan ke URL controller untuk cetak PDF, parameter ID disisipkan
        // Sesuaikan 'admin/verifikasi/cetak_pdf' dengan route/struktur folder Anda
        var printUrl = "<?= base_url('admin/verifikasi/cetak_pdf/') ?>" + id;

        // Buka PDF di tab baru
        window.open(printUrl, '_blank');
    }

    // Kirim Survey
    function sendSurvey(id, nama_pemohon, nama_instansi) {
        let csrfName = "<?= $this->security->get_csrf_token_name(); ?>";
        let csrfToken = $('#csrf_token').val() || '<?= $this->security->get_csrf_hash(); ?>';

        Swal.fire({
            title: 'Konfirmasi',
            html: `Apakah Anda yakin ingin mengirimkan survey untuk <b>${nama_pemohon}</b> dari : <b>${nama_instansi}</b> ?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Kirim!'
        }).then((result) => {
            if (result.isConfirmed) {

                // Siapkan data yang akan dikirim
                let postData = {
                    id: id,
                    nama_pemohon: nama_pemohon,
                    nama_instansi: nama_instansi,
                    kirim_survey: '1'
                };
                postData[csrfName] = csrfToken;

                $.ajax({
                    url: "<?= site_url('admin/verifikasi/ajax_kirim_survey'); ?>",
                    type: "POST",
                    dataType: "JSON",
                    data: postData,
                    success: function(res) {
                        // PENTING: Update token CSRF di halaman web Anda setiap kali mendapat respon
                        if (res.csrf_token) {
                            $('#csrf_token').val(res.csrf_token); // Update jika pakai input hidden
                            // Atau jika Anda menggunakan function getCsrfToken, pastikan function tersebut terupdate nilainya
                        }

                        if (res.status === false) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: res.message,
                                confirmButtonText: 'Mengerti'
                            });
                        } else {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Survey telah dikirim.',
                                timer: 1500
                            });

                            table.ajax.reload(function() {
                                if (typeof updateData === "function") updateData();
                            }, false);
                        }
                    },
                    error: function(xhr, status, error) {
                        // Jika error (termasuk salah CSRF), amankan token baru jika dikirim lewat response header/body
                        console.log(xhr.responseText);
                    }
                });
            }
        });
    }
</script>