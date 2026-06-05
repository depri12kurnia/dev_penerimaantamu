<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_settings');
        $this->load->model('M_dashboard');

        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login');
        }
    }

    public function index()
    {
        $data['summary'] = $this->M_dashboard->get_summary();
        $data['approved_reservations'] = $this->M_dashboard->get_approved_reservations();

        // --- 1. PROSES DATA PIE CHART (KLASIFIKASI) ---
        $data_klasifikasi = $this->M_dashboard->get_chart_klasifikasi();
        $pie_labels = [];
        $pie_data = [];
        // Palet warna khas AdminLTE
        $pie_colors = ['#dc3545', '#007bff', '#28a745', '#ffc107', '#17a2b8', '#d2d6de', '#6c757d'];

        foreach ($data_klasifikasi as $row) {
            $pie_labels[] = !empty($row->klasifikasi) ? $row->klasifikasi : 'Belum Ditentukan';
            $pie_data[] = $row->total;
        }

        $data['pie_chart_data'] = json_encode([
            'labels' => $pie_labels,
            'datasets' => [[
                'data' => $pie_data,
                'backgroundColor' => array_slice($pie_colors, 0, count($pie_data))
            ]]
        ]);


        // --- 2. PROSES DATA BAR CHART (APPROVED, PENDING dan REJECTED PER BULAN) ---
        $data_bulanan = $this->M_dashboard->get_chart_kunjungan_bulanan();
        $bulan_labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

        // Buat array default 0 untuk 12 bulan
        $approved_data = array_fill(0, 12, 0);
        $pending_data = array_fill(0, 12, 0);
        $rejected_data = array_fill(0, 12, 0);

        foreach ($data_bulanan as $row) {
            $index_bulan = $row->bulan - 1; // Kurangi 1 karena array dimulai dari 0
            $approved_data[$index_bulan] = (int)$row->total_approved;
            $pending_data[$index_bulan]  = (int)$row->total_pending;
            $rejected_data[$index_bulan] = (int)$row->total_rejected;
        }

        $data['bar_chart_data'] = json_encode([
            'labels' => $bulan_labels,
            'datasets' => [
                [
                    'label' => 'Approved',
                    'backgroundColor' => 'rgb(40, 167, 69)',
                    'borderColor' => 'rgb(40, 167, 69)',
                    'data' => $approved_data
                ],
                [
                    'label' => 'Pending',
                    'backgroundColor' => 'rgba(210, 214, 222, 1)',
                    'borderColor' => 'rgba(210, 214, 222, 1)',
                    'data' => $pending_data
                ],
                [
                    'label' => 'Rejected',
                    'backgroundColor' => 'rgba(220, 53, 69, 0.9)',
                    'borderColor' => 'rgba(220, 53, 69, 0.8)',
                    'data' => $rejected_data
                ]

            ]
        ]);

        $data['website'] = $this->M_settings->get_all_settings();
        $data['title'] = 'Dashboard | Admin Panel';
        $data['content'] = 'paneladmin/dashboard';
        $this->load->view('layouts/adminlte3', $data);
    }
}
