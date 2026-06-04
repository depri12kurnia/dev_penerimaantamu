<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Reservasi extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_settings');
        $this->load->model('M_reservasi');
        $this->load->model('M_log_user');
        $this->load->model('M_users');

        // 1. Load library Ion Auth
        $this->load->library('ion_auth');
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login');
        }
    }

    public function index()
    {
        // Tampilkan halaman reservasi dengan data yang konsisten
        $data['website'] = $this->M_settings->get_all_settings();
        $data['title'] = 'Reservasi - Penerimaan Tamu Poltekkes Jakarta III';
        $data['content'] = 'reservasi';

        // Pass current user to view for prefilling form
        $data['user'] = $this->ion_auth->user()->row();
        // Pass user's reservations
        $data['reservations'] = $this->M_reservasi->get_all_by_user(isset($data['user']->id) ? $data['user']->id : NULL);

        // Load view dengan data yang konsisten
        $this->load->view('layouts/userlte3', $data);
    }

    /**
     * Handle reservation form submission (AJAX / form POST)
     */
    public function submit()
    {
        if (!$this->input->is_ajax_request()) {
            // allow non-AJAX but POST only
        }

        $no_ticket = 'RES-' . date('YmdHis') . '-' . rand(1000, 9999);

        // 1. Kumpulkan Data (Payload)
        $payload = [
            'asal_tamu' => $this->input->post('asal_tamu'),
            'nomor_whatsapp' => $this->input->post('nomor_whatsapp'),
            'nama_pemohon' => $this->input->post('nama_pemohon'),
            'email' => $this->input->post('email'),
            'nama_instansi' => $this->input->post('nama_instansi'),
            'provinsi' => $this->input->post('provinsi'),
            'nomor_hp' => $this->input->post('nomor_hp'),
            'alamat_instansi' => $this->input->post('alamat_instansi'),
            'jumlah_peserta' => $this->input->post('jumlah_peserta'),
            'klasifikasi' => $this->input->post('klasifikasi'),
            'nama_pimpinan' => $this->input->post('nama_pimpinan'),
            'tanggal_berkunjung' => $this->input->post('tanggal_berkunjung'),
            'jam_kunjungan' => $this->input->post('jam_kunjungan'),
            'lokasi' => $this->input->post('lokasi'),
            'topik' => $this->input->post('topik'),
            'alamat_hotel' => $this->input->post('alamat_hotel'),
            'status' => 'pending',
            'no_ticket' => $no_ticket
        ];

        // 2. Handle File Upload
        $document_path = NULL;
        if (!empty($_FILES['document']) && $_FILES['document']['error'] !== UPLOAD_ERR_NO_FILE) {
            $upload_path = FCPATH . 'public/uploads/reservations/';
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0755, true);
            }

            $config['upload_path'] = $upload_path;
            $config['allowed_types'] = 'jpg|jpeg|png|pdf';
            $config['max_size'] = 3072; // 3MB
            $config['encrypt_name'] = TRUE;

            $this->load->library('upload', $config);

            if ($this->upload->do_upload('document')) {
                $fileData = $this->upload->data();
                $document_path = 'public/uploads/reservations/' . $fileData['file_name'];
                $payload['document_path'] = $document_path;
            } else {
                $error = $this->upload->display_errors();
                if ($this->input->is_ajax_request()) {
                    echo json_encode([
                        'status' => 'error',
                        'message' => strip_tags($error),
                        'csrf_name' => $this->security->get_csrf_token_name(),
                        'csrf_hash' => $this->security->get_csrf_hash()
                    ]);
                    return;
                } else {
                    $this->session->set_flashdata('message', strip_tags($error));
                    redirect('reservasi');
                }
            }
        }

        // 3. Simpan ke Database
        $user = $this->ion_auth->user()->row();
        $user_id = !empty($user->id) ? $user->id : 0; // Antisipasi jika tamu (belum login)

        if ($user_id > 0) {
            $payload['user_id'] = $user_id;
        }

        $insert_id = $this->M_reservasi->insert_reservation($payload);
        $this->M_log_user->save_log($user_id, "Submit reservation with No Ticket : $no_ticket ID: $user_id");


        // =========================================================================
        // 4. PROSES NOTIFIKASI WA KE ADMIN (Tereksekusi jika insert berhasil)
        // =========================================================================
        $wa_status_sent = false;
        $wa_error_msg   = '';

        if ($insert_id) {
            $this->load->library('qontak');

            $nomor_admin = '6281220094931'; // Nomor WA Admin
            $nama_admin  = 'Admin Penerimaan Reservasi Tamu'; // Nama penerima di sistem Qontak

            // Ambil data langsung dari $payload dan pastikan tidak kosong (fallback '-')
            $nama_pemohon = $payload['nama_pemohon'];
            $nama_instansi    = $payload['nama_instansi'];
            $no_ticket_wa = $no_ticket;

            $template_id  = 'fffbc9f6-c8ea-4b3c-8aef-63eabc436857'; // Ganti dengan ID Template Admin
            $isi_variabel = [$nama_pemohon, $nama_instansi, $no_ticket_wa];

            // Format parameter untuk Qontak
            $formatted_parameters = [];
            foreach ($isi_variabel as $index => $val) {
                $safe_val = (trim((string)$val) === '') ? '-' : (string)$val;
                $formatted_parameters[] = [
                    "key" => (string)($index + 1),
                    "value" => "variabel_" . ($index + 1),
                    "value_text" => $safe_val
                ];
            }

            // Eksekusi kirim pesan
            $kirim_wa = $this->qontak->send_message(
                $nama_admin, // Kirim atas nama "Admin Penerimaan Reservasi Tamu" sebagai tujuan
                $nomor_admin,
                $template_id,
                $formatted_parameters
            );

            if (isset($kirim_wa['status']) && $kirim_wa['status'] === 'success') {
                $wa_status_sent = true;
            } else {
                log_message('error', 'Gagal kirim WA Notifikasi ke Admin via Qontak: ' . json_encode($kirim_wa));
                $wa_error_msg = isset($kirim_wa['error']['message']) ? $kirim_wa['error']['message'] : 'Pesan WA gagal.';
            }
        }


        // =========================================================================
        // 5. KEMBALIKAN RESPONSE KE USER (AJAX ATAU REDIRECT)
        // =========================================================================
        if ($this->input->is_ajax_request()) {
            if ($insert_id) {
                echo json_encode([
                    'status'     => 'success',
                    'message'    => 'Reservasi berhasil disimpan',
                    'id'         => $insert_id,
                    'wa_sent'    => $wa_status_sent,  // Informasikan status WA
                    'wa_message' => $wa_error_msg,
                    'csrf_name'  => $this->security->get_csrf_token_name(),
                    'csrf_hash'  => $this->security->get_csrf_hash()
                ]);
            } else {
                echo json_encode([
                    'status'    => 'error',
                    'message'   => 'Gagal menyimpan reservasi',
                    'csrf_name' => $this->security->get_csrf_token_name(),
                    'csrf_hash' => $this->security->get_csrf_hash()
                ]);
            }
            return; // Akhiri fungsi jika AJAX
        }

        // Jika bukan AJAX (Form biasa)
        if ($insert_id) {
            $this->session->set_flashdata('message', 'Reservasi berhasil dikirim');
        } else {
            $this->session->set_flashdata('message', 'Gagal mengirim reservasi');
        }

        redirect('reservasi');
    }

    /**
     * AJAX endpoint to check if a given date+time has conflicts
     * Expects GET parameters: date (YYYY-MM-DD) and time (e.g. 09.00)
     */
    public function check_datetime()
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
            return;
        }

        $date = $this->input->get('date');
        $time = $this->input->get('time');

        if (empty($date) || empty($time)) {
            echo json_encode(['status' => 'error', 'message' => 'Parameter date/time wajib']);
            return;
        }

        $conflicts = $this->M_reservasi->get_reservations_by_datetime($date, $time);

        echo json_encode([
            'status' => 'success',
            'has_conflict' => !empty($conflicts),
            'count' => count($conflicts),
            'conflicts' => $conflicts
        ]);
    }

    /**
     * Check if user has a pending reservation (AJAX endpoint)
     */
    public function check_pending()
    {
        $user = $this->ion_auth->user()->row();
        $has_pending = false;

        if (!empty($user->id)) {
            $has_pending = $this->M_reservasi->has_pending_reservation($user->id);
        }

        header('Content-Type: application/json');
        echo json_encode([
            'has_pending' => $has_pending,
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        ]);
    }

    /**
     * Get reservation detail by ID (AJAX endpoint)
     */
    public function get_detail($id = null)
    {
        if (empty($id)) {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'ID tidak valid']);
            return;
        }

        $reservation = $this->M_reservasi->get_by_id($id);

        if (empty($reservation)) {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'Reservasi tidak ditemukan']);
            return;
        }

        // Verify user owns this reservation
        $user = $this->ion_auth->user()->row();
        if ($reservation->user_id != $user->id) {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'Anda tidak memiliki akses ke reservasi ini']);
            return;
        }

        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'success',
            'data' => $reservation,
            'csrf_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        ]);
    }

    public function cetak_pdf($id)
    {
        // 1. Pastikan user sudah login (opsional, sesuaikan dengan sistem auth Anda)
        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }

        // 2. Ambil data reservasi dari database
        $reservation = $this->M_reservasi->get_reservation_by_id($id);

        // 3. Validasi data
        if (!$reservation) {
            show_404(); // Tampilkan halaman 404 jika ID tidak ditemukan
        }

        // Keamanan ekstra: Pastikan hanya status 'approved' yang bisa dicetak
        if ($reservation->status !== 'approved') {
            // Tampilkan pesan error jika status bukan approved
            die("<h3>Akses Ditolak: Dokumen ini belum disetujui atau dibatalkan.</h3>");
        }

        // 4. Siapkan data untuk dikirim ke view PDF
        $data['row'] = $reservation;

        // 5. Load file HTML yang akan dijadikan PDF ke dalam variabel
        // (Kita akan buat file v_pdf_reservasi.php di step selanjutnya)
        $html = $this->load->view('reservasi_cetak', $data, TRUE);

        // =========================================================================
        // 6. PROSES RENDER PDF (MENGGUNAKAN DOMPDF)
        // =========================================================================

        // Catatan: Jika Anda sudah menginstal Dompdf via Composer, 
        // pastikan autoload composer sudah aktif di config.php ( $config['composer_autoload'] = TRUE; )

        // Inisialisasi Dompdf
        $options = new \Dompdf\Options();
        $options->set('isRemoteEnabled', true); // Aktifkan jika view memuat gambar dari URL/Asset

        $dompdf = new \Dompdf\Dompdf($options);

        // Masukkan HTML ke Dompdf
        $dompdf->loadHtml($html);

        // Atur ukuran dan orientasi kertas (A4, portrait)
        $dompdf->setPaper('A4', 'portrait');

        // Render HTML ke PDF
        $dompdf->render();

        // Format nama file saat di-download
        $nama_file = "Bukti_Kunjungan_" . $reservation->no_ticket . ".pdf";

        // Output PDF ke browser (Attachment => 0 berarti preview di tab baru, bukan langsung download)
        $dompdf->stream($nama_file, array("Attachment" => 0));
    }
}
