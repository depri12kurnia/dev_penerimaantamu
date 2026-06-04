<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Galeri extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_settings');
        $this->load->model('M_galeri');

        // Load library Ion Auth agar status login bisa terbaca di View Beranda
        $this->load->library('ion_auth');
    }

    public function index()
    {
        // Ambil semua kategori dari g_categories
        $data['categories'] = $this->M_galeri->get_categories();
        // Ambil data galleries dengan status 'Active' saja
        $this->db->where('status', 'Active');
        $data['galleries'] = $this->db->get('galleries')->result();

        $data['website'] = $this->M_settings->get_all_settings();
        $data['title'] = 'Galeri - Penerimaan Tamu Poltekkes Jakarta III';
        $data['content'] = 'galeri';
        $this->load->view('layouts/userlte3', $data);
    }
}
