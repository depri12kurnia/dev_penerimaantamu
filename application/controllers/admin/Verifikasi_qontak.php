<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Verifikasi extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_settings');
        $this->load->model('M_verifikasi');
        $this->load->model('M_log_user');
        $this->load->model('M_users');

        if (!$this->ion_auth->in_group(array('admin'))) {
            redirect('admin/page_errors');
        }
    }

    public function index()
    {
        $data['website'] = $this->M_settings->get_all_settings();
        $data['groups'] = $this->M_users->get_groups();
        $data['title'] = 'Verifikasi Management | Admin Panel';
        $data['content'] = 'paneladmin/verifikasi/list';
        $this->load->view('layouts/adminlte3', $data);
    }

    public function ajax_list()
    {
        $this->validate_csrf();

        $list = $this->M_verifikasi->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $verification) {
            $no++;
            $row = array();
            $row[] = $no++;
            $row[] = $verification->no_ticket;
            $row[] = $verification->nama_pemohon;
            $row[] = $verification->nama_instansi;
            $row[] = $verification->tanggal_berkunjung;
            $row[] = $verification->jumlah_peserta;

            if ($verification->status == 'pending') {
                $status_icon = '<i class="fas fa-paper-plane text-primary" title="Pending"></i>';
            } elseif ($verification->status == 'rejected') {
                $status_icon = '<i class="fas fa-times-circle text-danger" title="Rejected"></i>';
            } elseif ($verification->status == 'approved' || $verification->status == 'accepted') {
                $status_icon = '<i class="fas fa-check-circle text-success" title="Approved"></i>';
            } else {
                $status_icon = $verification->status;
            }
            $row[] = $status_icon;

            $row[] = $verification->created_at;


            $row[] = '<a class="btn btn-primary btn-sm" href="javascript:void(0)" title="Verify" onclick="viewVerification(' . "'" . $verification->id . "'" . ')"><i class="fa fa-eye"></i></a>
                      ';
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_verifikasi->count_all(),
            "recordsFiltered" => $this->M_verifikasi->count_filtered(),
            "data" => $data,
            "csrf_token" => $this->security->get_csrf_hash()
        );
        echo json_encode($output);
    }

    public function ajax_view($id)
    {
        $data = $this->M_verifikasi->get_by_id($id);
        echo json_encode($data);
    }

    public function ajax_detail($id)
    {
        $this->ajax_view($id);
    }

    public function ajax_verify()
    {
        log_message('debug', 'Verifikasi::ajax_verify POST=' . print_r($_POST, true));
        log_message('debug', 'Verifikasi::ajax_verify token_name=' . $this->security->get_csrf_token_name() . ' token_value=' . $this->input->post($this->security->get_csrf_token_name()));

        $this->validate_csrf();

        $id      = $this->input->post('id');
        $status  = $this->input->post('status');
        $comment = $this->input->post('comment');
        $updated_at = date('Y-m-d H:i:s');

        $data = ['status' => $status, 'updated_at' => $updated_at];
        if ($status === 'rejected') {
            $data['comment'] = $comment;
        }

        // 1. Jalankan Update Database
        $this->M_verifikasi->update_reservations($id, $data);

        // log user activity
        $user = $this->ion_auth->user()->row();
        $this->M_log_user->save_log($user->id, "Verify verification ID: $id -> $status");

        // =========================================================================
        // PROSES INTEGRASI MEKARI QONTAK WHATSAPP
        // =========================================================================

        $reservation = $this->M_verifikasi->get_reservation_by_id($id);

        $nama_pemohon = $reservation->nama_pemohon; // {{1}}
        $nama_instansi = $reservation->nama_instansi; // {{2}}
        $jumlah_peserta = $reservation->jumlah_peserta; // {{3}}
        $topik = $reservation->topik; // {{4}}
        $no_ticket = $reservation->no_ticket; // {{5}}
        $tanggal_berkunjung = $reservation->tanggal_berkunjung; // {{6}}
        $jam_kunjungan = $reservation->jam_kunjungan; // {{7}}
        $lokasi = $reservation->lokasi; // {{8}}
        $comment = $reservation->comment ?? '';

        $wa_status_sent = false;
        $wa_error_msg   = '';

        if ($reservation) {
            $this->load->library('qontak');

            $nomor_mentah = $reservation->nomor_whatsapp;
            $nomor_bersih = preg_replace('/[^0-9]/', '', $nomor_mentah);
            $nomor_bersih = str_replace(['o', 'O'], '0', $nomor_bersih);

            if (substr($nomor_bersih, 0, 1) === '0') {
                $nomor_tujuan = '62' . substr($nomor_bersih, 1);
            } elseif (substr($nomor_bersih, 0, 2) === '62') {
                $nomor_tujuan = $nomor_bersih;
            } elseif (substr($nomor_bersih, 0, 1) === '8') {
                $nomor_tujuan = '62' . $nomor_bersih;
            } else {
                $nomor_tujuan = $nomor_bersih;
            }

            $nama_pemohon = $reservation->nama_pemohon;

            // Atur template ID dan isi variabel
            if ($status === 'approved') {
                $template_id  = '88e7c2c3-a4bb-4246-8d94-3ec3ab287d3f'; // ID Qontak Template untuk approved
                $isi_variabel = [
                    $nama_pemohon, // {{1}}
                    $nama_instansi, // {{2}}
                    $jumlah_peserta, // {{3}}
                    $topik, // {{4}}
                    $no_ticket, // {{5}}
                    $tanggal_berkunjung, // {{6}}
                    $jam_kunjungan, // {{7}}
                    $lokasi, // {{8}}
                    $status // {{9}}
                ];
            } else {
                $template_id  = '0a36dd46-5afc-4726-a5b0-688b39c25c4f'; // ID Qontak Template untuk rejected
                $isi_variabel = [
                    $nama_pemohon, // {{1}}
                    $nama_instansi, // {{2}}
                    $no_ticket, // {{3}}
                    $comment // {{4}}
                ];
            }

            // PERBAIKAN: Format array variabel agar sesuai standar API Qontak
            $formatted_parameters = [];
            foreach ($isi_variabel as $index => $val) {
                $formatted_parameters[] = [
                    "key" => (string)($index + 1), // {{1}}, {{2}}, dst
                    "value" => "variabel_" . ($index + 1),
                    "value_text" => (string)$val // Pastikan berbentuk string
                ];
            }

            // PERBAIKAN: Pemanggilan disesuaikan dengan urutan parameter di Library
            // send_message($to_name, $to_number, $template_id, $parameters)
            $kirim_wa = $this->qontak->send_message(
                $nama_pemohon,
                $nomor_tujuan,
                $template_id,
                $formatted_parameters
            );

            // PERBAIKAN: Cek response berdasarkan JSON balasan Qontak
            if (isset($kirim_wa['status']) && $kirim_wa['status'] === 'success') {
                $wa_status_sent = true;
            } else {
                log_message('error', 'Gagal kirim WA via Qontak: ' . json_encode($kirim_wa));
                // Opsional: Ambil pesan error spesifik dari Qontak jika ada
                $error_detail = isset($kirim_wa['error']['message']) ? $kirim_wa['error']['message'] : 'Pesan WA gagal terkirim.';
                $wa_error_msg = $error_detail;
            }
        } else {
            log_message('error', "Gagal kirim WA: Data reservasi dengan ID $id tidak ditemukan.");
            $wa_error_msg = 'Data reservasi tidak ditemukan untuk pengiriman WA.';
        }

        // =========================================================================

        echo json_encode([
            "status"      => TRUE,
            "wa_sent"     => $wa_status_sent,
            "wa_message"  => $wa_error_msg,
            "csrf_token"  => $this->security->get_csrf_hash()
        ]);
    }

    public function export_excel()
    {
        $category = $this->input->get('category');
        $status = $this->input->get('status');

        // Get filtered data
        $this->db->select('reservations.id, users.email, reservations.user_id, reservations.no_ticket, reservations.asal_tamu, reservations.nomor_whatsapp, reservations.nama_pemohon, reservations.email, reservations.nama_instansi, reservations.provinsi, reservations.nomor_hp, reservations.alamat_instansi, reservations.jumlah_peserta, reservations.klasifikasi, reservations.nama_pimpinan, reservations.tanggal_berkunjung, reservations.jam_kunjungan, reservations.lokasi, reservations.topik, reservations.alamat_hotel, reservations.document_path, reservations.status, reservations.comment, reservations.created_at, reservations.updated_at');
        $this->db->from('reservations');
        $this->db->join('users', 'users.id = reservations.user_id');

        if (!empty($category)) {
            $this->db->where('reservations.category', $category);
        }
        if (!empty($status)) {
            $this->db->where('reservations.status', $status);
        }

        $this->db->order_by('reservations.id', 'desc');
        $query = $this->db->get();
        $verifications = $query->result();

        // Load PhpSpreadsheet library
        require_once APPPATH . '../vendor/autoload.php';
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set document properties
        $spreadsheet->getProperties()->setCreator("Gains Admin")
            ->setLastModifiedBy("Penerimaan Tamu Admin")
            ->setTitle("Verifications Export")
            ->setSubject("Verifications Export")
            ->setDescription("Export of verifications data");

        // Add header
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'No Ticket');
        $sheet->setCellValue('C1', 'Email');
        $sheet->setCellValue('D1', 'Asal Tamu');
        $sheet->setCellValue('E1', 'Nomor WhatsApp');
        $sheet->setCellValue('F1', 'Nama Pemohon');
        $sheet->setCellValue('G1', 'Email');
        $sheet->setCellValue('H1', 'Nama Instansi');
        $sheet->setCellValue('I1', 'Provinsi');
        $sheet->setCellValue('J1', 'Nomor HP');
        $sheet->setCellValue('K1', 'Alamat Instansi');
        $sheet->setCellValue('L1', 'Jumlah Peserta');
        $sheet->setCellValue('M1', 'Klasifikasi');
        $sheet->setCellValue('N1', 'Nama Pimpinan');
        $sheet->setCellValue('O1', 'Tanggal Berkunjung');
        $sheet->setCellValue('P1', 'Jam Kunjungan');
        $sheet->setCellValue('Q1', 'Lokasi');
        $sheet->setCellValue('R1', 'Topik');
        $sheet->setCellValue('S1', 'Alamat Hotel');
        $sheet->setCellValue('T1', 'Document Path');
        $sheet->setCellValue('U1', 'Status');
        $sheet->setCellValue('V1', 'Comment');
        $sheet->setCellValue('W1', 'Created At');
        $sheet->setCellValue('X1', 'Updated At');

        // Add data
        $row = 2;
        foreach ($verifications as $value) {
            $sheet->setCellValue('A' . $row, $value->id);
            $sheet->setCellValue('B' . $row, $value->no_ticket);
            $sheet->setCellValue('C' . $row, $value->email);
            $sheet->setCellValue('D' . $row, $value->asal_tamu);
            $sheet->setCellValue('E' . $row, $value->nomor_whatsapp);
            $sheet->setCellValue('F' . $row, $value->nama_pemohon);
            $sheet->setCellValue('G' . $row, $value->email);
            $sheet->setCellValue('H' . $row, $value->nama_instansi);
            $sheet->setCellValue('I' . $row, $value->provinsi);
            $sheet->setCellValue('J' . $row, $value->nomor_hp);
            $sheet->setCellValue('K' . $row, $value->alamat_instansi);
            $sheet->setCellValue('L' . $row, $value->jumlah_peserta);
            $sheet->setCellValue('M' . $row, $value->klasifikasi);
            $sheet->setCellValue('N' . $row, $value->nama_pimpinan);
            $sheet->setCellValue('O' . $row, $value->tanggal_berkunjung);
            $sheet->setCellValue('P' . $row, $value->jam_kunjungan);
            $sheet->setCellValue('Q' . $row, $value->lokasi);
            $sheet->setCellValue('R' . $row, $value->topik);
            $sheet->setCellValue('S' . $row, $value->alamat_hotel);
            $sheet->setCellValue('T' . $row, $value->document_path);
            $sheet->setCellValue('U' . $row, $value->status);
            $sheet->setCellValue('V' . $row, $value->comment);
            $sheet->setCellValue('W' . $row, $value->created_at);
            $sheet->setCellValue('X' . $row, $value->updated_at);
            $row++;
        }

        // Rename worksheet
        $sheet->setTitle('Verifications');

        // Set headers for download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="verifications_' . date('Y-m-d_H-i-s') . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    private function get_request_csrf_token()
    {
        $token_name = $this->security->get_csrf_token_name();
        $csrf = $this->input->post($token_name);
        if (empty($csrf)) {
            $csrf = $this->input->post('csrf_token');
        }
        if (empty($csrf)) {
            $csrf = $this->input->server('HTTP_X_CSRF_TOKEN');
        }
        return $csrf;
    }

    private function validate_csrf()
    {
        $csrf = $this->get_request_csrf_token();
        $valid = $this->security->get_csrf_hash();

        if ($csrf !== $valid) {
            // log_message('debug', 'Verifikasi::validate_csrf invalid token received=' . ($csrf ?: 'NULL') . ' expected=' . $valid);
            echo json_encode([
                "status" => FALSE,
                "message" => "Invalid CSRF",
                "received_csrf" => $csrf,
                "expected_csrf" => substr($valid, 0, 8) . '...'
            ]);
            exit();
        }
    }
}
