<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_dashboard extends CI_Model
{

    // Mengambil semua data reservasi untuk kalender
    public function get_all_reservations()
    {
        $this->db->select('id, nama_pemohon, nama_instansi, tanggal_berkunjung, jam_kunjungan, status, topik');
        $this->db->from('reservations');
        return $this->db->get()->result();
    }

    // Mengambil data ringkasan untuk widget di dashboard
    public function get_summary()
    {
        $query = $this->db->query("
            SELECT 
                COUNT(id) as total_reservasi,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as total_pending,
                SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as total_approved,
                SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as total_rejected
            FROM reservations
        ");
        return $query->row();
    }

    public function get_approved_reservations()
    {
        $this->db->select('id, nama_pemohon, nama_instansi, tanggal_berkunjung, jam_kunjungan, klasifikasi, jumlah_peserta, lokasi, topik');
        $this->db->from('reservations');
        $this->db->where('status', 'approved'); // Hanya mengambil data yang sudah di-approve
        $this->db->order_by('jam_kunjungan', 'ASC'); // Urutkan berdasarkan jam berkunjung
        return $this->db->get()->result();
    }
}
