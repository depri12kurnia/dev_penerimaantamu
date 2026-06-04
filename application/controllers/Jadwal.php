<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Jadwal extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_settings');
        $this->load->model('M_reservasi');

        // Load library Ion Auth agar status login bisa terbaca di View Beranda
        $this->load->library('ion_auth');
    }

    public function index()
    {
        $data['website'] = $this->M_settings->get_all_settings();
        $data['title'] = 'Jadwal Kegiatan - Penerimaan Tamu Poltekkes Jakarta III';
        $data['content'] = 'jadwal';

        // Get approved reservations for calendar
        $data['approved_reservations'] = $this->M_reservasi->get_approved_reservations();

        $this->load->view('layouts/userlte3', $data);
    }

    /**
     * Get approved reservations as JSON (for AJAX if needed)
     */
    public function get_events()
    {
        $events = $this->M_reservasi->get_approved_reservations();
        echo json_encode($events);
    }
}
