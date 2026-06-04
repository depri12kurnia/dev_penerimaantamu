<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Kategori_galeri extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        // Wajib
        $this->load->model('M_settings');
        $this->load->model('M_log_user');
        // End Wajib
        $this->load->model('M_gcategory');

        // if (!$this->ion_auth->is_admin()) {
        //     redirect('auth/login');
        // }
        if (!$this->ion_auth->in_group('admin')) {
            redirect('page_errors');
        }
    }

    public function index()
    {
        $data['website'] = $this->M_settings->get_all_settings();
        $data['title'] = 'Category | Admin Panel';
        $data['content'] = 'paneladmin/category/list';
        $this->load->view('layouts/adminlte3', $data);
    }

    public function ajax_list()
    {
        $csrf_token = $this->input->server('HTTP_X_CSRF_TOKEN');
        $valid_token = $this->security->get_csrf_hash();

        // log_message('debug', 'CSRF Token dari request POST: ' . ($csrf_token ?: 'TIDAK ADA'));
        // log_message('debug', 'CSRF Token yang valid: ' . $valid_token);
        // log_message('debug', 'Session CSRF Token: ' . $this->session->categorydata('csrf_token_jkt3'));


        if (empty($csrf_token)) {
            log_message('error', 'CSRF Token kosong, periksa apakah dikirim dari frontend.');
        }

        if ($csrf_token !== $valid_token) {
            echo json_encode(['status' => 'Error', 'message' => 'Invalid CSRF Token']);
            exit();
        }

        $list = $this->M_gcategory->get_datatables();
        $data = array();
        $no = $_POST['start'] + 1;
        foreach ($list as $x) {
            $row = array();
            $row[] = $no++;
            $row[] = $x->name;
            $row[] = $x->slug;
            $row[] = '<a class="btn btn-primary btn-sm" href="javascript:void(0)" title="Edit" onclick="edit_category(' . "'" . $x->id . "'" . ')"><i class="fa fa-edit"></i></a>
                      <a class="btn btn-danger btn-sm" href="javascript:void(0)" title="Delete" onclick="delete_category(' . "'" . $x->id . "'" . ')"><i class="fa fa-trash"></i></a>';
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_gcategory->count_all(),
            "recordsFiltered" => $this->M_gcategory->count_filtered(),
            "data" => $data,
            "csrf_token" => $this->security->get_csrf_hash() // Kirim token CSRF baru
        );
        echo json_encode($output);
    }

    public function ajax_edit($id)
    {
        $data = $this->M_gcategory->get_by_id($id);
        echo json_encode($data);
    }

    public function ajax_add()
    {
        $this->validate_csrf();

        $this->_validate(); // Pindahkan validasi ke awal

        $name = $this->input->post('name');
        $slug  = $this->input->post('slug');

        // Jika slug kosong, buat dari name
        if (empty($slug)) {
            $slug = url_title($name, 'dash', TRUE);
        }

        // Bersihkan slug agar aman
        $slug = strtolower(trim(preg_replace('/[^a-z0-9-]+/', '-', $slug), '-'));

        // Cek apakah slug sudah ada di tabel `g_categories`
        $this->db->where('slug', $slug);
        $exists = $this->db->get('g_categories')->row();

        if ($exists) {
            $slug .= '-' . time(); // Tambahkan timestamp jika slug sudah ada
        }

        // Simpan data dengan slug yang telah diproses
        $data = array(
            'name' => $name,
            'slug' => $slug
        );

        $this->M_gcategory->insert_category($data);

        // Mendapatkan user yang login
        $user = $this->ion_auth->user()->row();

        // Menyimpan log aktivitas
        $this->M_log_user->save_log($user->id, 'Add Category Gallery: ' . $name);

        echo json_encode([
            "status" => TRUE,
            "csrf_token" => $this->security->get_csrf_hash() // Kirim token CSRF baru
        ]);
    }


    public function ajax_update()
    {
        $this->validate_csrf();

        $this->_validate();
        $data = array(
            'name' => $this->input->post('name'),
            'slug'    => $this->input->post('slug')
        );

        $this->M_gcategory->update_category($this->input->post('id'), $data);

        // Mendapatkan user yang login
        $user = $this->ion_auth->user()->row();
        // Menyimpan log aktivitas login
        $this->M_log_user->save_log($user->id, 'Update Category Gallery');

        echo json_encode([
            "status" => TRUE,
            "csrf_token" => $this->security->get_csrf_hash() // Kirim token CSRF baru
        ]);
    }

    public function ajax_delete($id)
    {
        $this->validate_csrf();

        $this->M_gcategory->delete_category($id);

        // Mendapatkan user yang login
        $user = $this->ion_auth->user()->row();
        // Menyimpan log aktivitas login
        $this->M_log_user->save_log($user->id, 'Delete Category Gallery');

        echo json_encode([
            "status" => TRUE,
            "csrf_token" => $this->security->get_csrf_hash() // Kirim token CSRF baru
        ]);
    }

    private function _validate()
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        $name = $this->input->post('name');
        $slug = $this->input->post('slug');

        if (empty($name)) {
            $data['inputerror'][] = 'name';
            $data['error_string'][] = 'name is required';
            $data['status'] = FALSE;
        }

        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit();
        }
    }


    private function validate_csrf()
    {
        $csrf_token = $this->input->server('HTTP_X_CSRF_TOKEN');
        $valid_token = $this->security->get_csrf_hash();

        if ($csrf_token !== $valid_token) {
            echo json_encode(['status' => 'Error', 'message' => 'Invalid CSRF Token']);
            exit();
        }
    }
}
