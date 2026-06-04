<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Faq extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_settings');
        $this->load->model('M_faq');

        // Load library Ion Auth agar status login bisa terbaca di View Beranda
        $this->load->library('ion_auth');
    }

    public function index()
    {
        $data['website'] = $this->M_settings->get_all_settings();
        $data['faqs'] = $this->M_faq->get_all_faqs();
        $data['title'] = 'Frequently Asked Questions - Penerimaan Tamu Poltekkes Jakarta III';
        $data['content'] = 'faq';
        $this->load->view('layouts/userlte3', $data);
    }
}
