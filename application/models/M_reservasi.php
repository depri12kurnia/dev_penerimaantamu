<?php defined('BASEPATH') or exit('No direct script access allowed');

class M_reservasi extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function insert_reservation($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $this->db->insert('reservations', $data);
        return $this->db->insert_id();
    }

    public function get_all()
    {
        return $this->db->order_by('created_at', 'DESC')->get('reservations')->result();
    }

    public function get_all_by_user($user_id)
    {
        if (empty($user_id)) return [];
        return $this->db->where('user_id', $user_id)->order_by('created_at', 'DESC')->get('reservations')->result();
    }

    public function get_by_id($id)
    {
        return $this->db->where('id', $id)->get('reservations')->row();
    }

    public function has_pending_reservation($user_id)
    {
        if (empty($user_id)) return false;
        $result = $this->db->where('user_id', $user_id)->where('status', 'pending')->get('reservations')->row();
        return !empty($result);
    }

    public function get_approved_reservations()
    {
        return $this->db->where('status', 'approved')->order_by('tanggal_berkunjung', 'ASC')->get('reservations')->result();
    }

    /**
     * Get reservations by exact date and time (to detect conflicts)
     * Includes both approved and pending to avoid overlapping bookings
     */
    public function get_reservations_by_datetime($date, $time)
    {
        return $this->db->where('tanggal_berkunjung', $date)
            ->where('jam_kunjungan', $time)
            ->where_in('status', ['approved', 'pending'])
            ->get('reservations')
            ->result();
    }


    public function get_reservation_by_id($id)
    {
        // Pastikan 'id' adalah nama kolom primary key di tabel Anda
        $this->db->where('id', $id);

        // GANTI 'nama_tabel_reservasi' dengan nama tabel asli Anda di database
        // (misalnya: 'reservasi', 'tabel_tamu', 'penerimaan_tamu', dll)
        $query = $this->db->get('reservations');

        // Mengembalikan data dalam bentuk Object (sesuai dengan cara kita memanggilnya di Controller)
        return $query->row();
    }
}
