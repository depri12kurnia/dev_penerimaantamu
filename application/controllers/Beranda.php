<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Beranda extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_settings');

		// Load library Ion Auth agar status login bisa terbaca di View Beranda
		$this->load->library('ion_auth');
	}

	public function index()
	{
		$data['website'] = $this->M_settings->get_all_settings();
		$data['title'] = 'Beranda - Penerimaan Tamu Poltekkes Jkt 3';
		$data['content'] = 'beranda';
		$this->load->view('layouts/userlte3', $data);
	}
}
