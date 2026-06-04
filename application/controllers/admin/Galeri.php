<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Galeri extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_settings');
        $this->load->model('M_log_user');
        $this->load->model('M_galeri');
        $this->load->library('upload');
        $this->load->helper(['text', 'url']);

        // Proteksi Hak Akses Admin via Ion Auth
        if (!$this->ion_auth->in_group('admin')) {
            redirect('page_errors');
        }
    }

    public function index()
    {
        $data['website'] = $this->M_settings->get_all_settings();
        $data['title'] = 'Galeri | Admin Panel';
        $data['content'] = 'paneladmin/galeri/list';
        $data['categories'] = $this->M_galeri->get_categories();
        $this->load->view('layouts/adminlte3', $data);
    }

    public function ajax_list()
    {
        $this->validate_csrf();

        $list = $this->M_galeri->get_datatables();
        $data = array();
        $no = isset($_POST['start']) ? $_POST['start'] : 0;

        foreach ($list as $x) {
            $no++;
            $row = array();
            $row[] = $no;

            // Kolom Image Preview (Menggunakan properti $x->images sesuai penyesuaian Anda)
            if ($x->images && file_exists('./public/uploads/galleries/' . $x->images)) {
                $row[] = '<img src="' . base_url('public/uploads/galleries/' . $x->images) . '" class="img-thumbnail" style="max-height: 50px;">';
            } else {
                $row[] = '<span class="text-muted"><i class="fas fa-image fa-2x"></i></span>';
            }

            // Kolom Category Name (Menggunakan htmlspecialchars untuk keamanan XSS)
            $row[] = '<span class="badge badge-info">' . htmlspecialchars($x->category_name, ENT_QUOTES, 'UTF-8') . '</span>';

            // Kolom Title (Menggunakan htmlspecialchars untuk keamanan XSS)
            $row[] = '<strong>' . htmlspecialchars($x->title, ENT_QUOTES, 'UTF-8') . '</strong>';

            // Kolom Status badge
            if ($x->status == 'Active') {
                $status_badge = '<span class="badge badge-success">Active</span>';
            } else {
                $status_badge = '<span class="badge badge-danger">Inactive</span>';
            }
            $row[] = $status_badge;

            // Tombol Aksi JS (view_gallery, edit_gallery, delete_gallery)
            $row[] = '<div class="btn-group" role="group">
                    <a class="btn btn-info btn-sm" href="javascript:void(0)" title="View" onclick="view_gallery(' . "'" . $x->id . "'" . ')">
                        <i class="fa fa-eye"></i>
                    </a>
                    <a class="btn btn-primary btn-sm" href="javascript:void(0)" title="Edit" onclick="edit_gallery(' . "'" . $x->id . "'" . ')">
                        <i class="fa fa-edit"></i>
                    </a>
                    <a class="btn btn-danger btn-sm" href="javascript:void(0)" title="Delete" onclick="delete_gallery(' . "'" . $x->id . "'" . ')">
                        <i class="fa fa-trash"></i>
                    </a>
                  </div>';

            $data[] = $row;
        }

        $output = array(
            "draw" => isset($_POST['draw']) ? $_POST['draw'] : 0,
            "recordsTotal" => $this->M_galeri->count_all(),
            "recordsFiltered" => $this->M_galeri->count_filtered(),
            "data" => $data,
            "csrf_token" => $this->security->get_csrf_hash()
        );
        echo json_encode($output);
    }

    public function ajax_add()
    {
        $this->validate_csrf();
        $this->_validate(true); // Kirim flag true untuk validasi upload wajib saat insert

        $user = $this->ion_auth->user()->row();

        // Handle upload image ke folder galleries
        $image = null;
        if (!empty($_FILES['images']['name'])) {
            $image = $this->_upload_image();
        }

        // Generate slug dari title galeri
        $slug = $this->M_galeri->generate_slug($this->input->post('title'));

        $data = array(
            'category_id' => $this->input->post('category_id'),
            'title'       => $this->input->post('title'),
            'slug'        => $slug,
            'description' => $this->input->post('description') ? $this->input->post('description') : null,
            'images'       => $image,
            'status'      => $this->input->post('status') ? $this->input->post('status') : 'Active',
        );

        $this->M_galeri->insert_galeri($data);
        $this->M_log_user->save_log($user->id, 'Add Gallery: ' . $this->input->post('title'));

        echo json_encode([
            "status" => TRUE,
            "csrf_token" => $this->security->get_csrf_hash()
        ]);
    }

    public function ajax_edit($id)
    {
        $data = $this->M_galeri->get_by_id($id);
        echo json_encode($data);
    }

    public function ajax_update()
    {
        $this->validate_csrf();
        $this->_validate(false); // Kirim flag false karena update gambar bersifat opsional

        $user = $this->ion_auth->user()->row();
        $id = $this->input->post('id');
        $galeri = $this->M_galeri->get_by_id($id);

        if (!$galeri) {
            echo json_encode(["status" => FALSE, "message" => "Gallery item not found"]);
            return;
        }

        // Handle upload image baru jika ada berkas yang dikirimkan
        $image = !empty($_FILES['images']['name']) ? $this->_upload_image($galeri->images) : $galeri->images;

        // Regenerate slug jika title mengalami perubahan data
        $slug = ($this->input->post('title') !== $galeri->title)
            ? $this->M_galeri->generate_slug($this->input->post('title'), $id)
            : $galeri->slug;

        $data = array(
            'category_id' => $this->input->post('category_id'),
            'title'       => $this->input->post('title'),
            'slug'        => $slug,
            'description' => $this->input->post('description') ? $this->input->post('description') : null,
            'images'       => $image,
            'status'      => $this->input->post('status'),
        );

        $this->M_galeri->update_galeri($id, $data);
        $this->M_log_user->save_log($user->id, 'Update Gallery: ' . $this->input->post('title'));

        echo json_encode([
            "status" => TRUE,
            "csrf_token" => $this->security->get_csrf_hash()
        ]);
    }

    public function ajax_delete($id)
    {
        $this->validate_csrf();

        $galeri = $this->M_galeri->get_by_id($id);
        if (!$galeri) {
            echo json_encode(["status" => FALSE, "message" => "Gallery item not found"]);
            return;
        }

        // Hapus fisik berkas lama di folder server
        if ($galeri->images && file_exists('./public/uploads/galleries/' . $galeri->images)) {
            unlink('./public/uploads/galleries/' . $galeri->images);
        }

        $this->M_galeri->delete_galeri($id);

        $user = $this->ion_auth->user()->row();
        $this->M_log_user->save_log($user->id, 'Delete Gallery: ' . $galeri->title);

        echo json_encode([
            "status" => TRUE,
            "csrf_token" => $this->security->get_csrf_hash()
        ]);
    }

    // --- Private Helper Methods ---

    private function validate_csrf()
    {
        $csrf_token = $this->input->server('HTTP_X_CSRF_TOKEN');
        if (!$csrf_token) {
            $csrf_token = $this->input->post('csrf_token_jkt3');
        }
        $valid_token = $this->security->get_csrf_hash();

        if ($csrf_token !== $valid_token) {
            echo json_encode(['status' => 'Error', 'message' => 'Invalid CSRF Token']);
            exit();
        }
    }

    private function _validate($is_insert = true)
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = TRUE;

        if ($this->input->post('category_id') == '') {
            $data['inputerror'][] = 'category_id';
            $data['error_string'][] = 'Category is required';
            $data['status'] = FALSE;
        }

        if ($this->input->post('title') == '') {
            $data['inputerror'][] = 'title';
            $data['error_string'][] = 'Title is required';
            $data['status'] = FALSE;
        }

        // Aturan: Wajib melampirkan file gambar baru saat membuat item galeri baru
        if ($is_insert && empty($_FILES['images']['name'])) {
            $data['inputerror'][] = 'images';
            $data['error_string'][] = 'Image file is required';
            $data['status'] = FALSE;
        }

        if ($data['status'] === FALSE) {
            echo json_encode($data);
            exit();
        }
    }

    private function _upload_image($old_image = null)
    {
        $config['upload_path']   = './public/uploads/galleries/';
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['max_size']      = 5048; // Diatur menjadi 2MB agar sinkron dengan info teks modal view
        $config['encrypt_name']  = TRUE;

        if (!is_dir($config['upload_path'])) {
            mkdir($config['upload_path'], 0755, true);
        }

        // Hapus fisik file lama dari server jika sedang dalam skema update data
        if ($old_image && file_exists($config['upload_path'] . $old_image)) {
            unlink($config['upload_path'] . $old_image);
        }

        $this->upload->initialize($config);

        if ($this->upload->do_upload('images')) {
            return $this->upload->data('file_name');
        } else {
            echo json_encode([
                "status" => FALSE,
                "inputerror" => ["images"],
                "error_string" => [$this->upload->display_errors('', '')]
            ]);
            exit();
        }
    }
}
