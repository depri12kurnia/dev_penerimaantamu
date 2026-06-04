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

        $this->M_verifikasi->update_reservations($id, $data);

        // log
        $user = $this->ion_auth->user()->row();
        $this->M_log_user->save_log($user->id, "Verify verification ID: $id -> $status");

        echo json_encode([
            "status" => TRUE,
            "csrf_token" => $this->security->get_csrf_hash()
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
