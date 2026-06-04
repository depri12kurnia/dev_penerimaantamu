<?php
class M_verifikasi extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }


    var $table = 'reservations';
    var $column_order = array('reservations.id', 'reservations.user_id', 'reservations.no_ticket', 'reservations.asal_tamu',  'reservations.nomor_whatsapp', 'reservations.nama_pemohon', 'reservations.email', 'reservations.nama_instansi', 'reservations.provinsi', 'reservations.nomor_hp', 'reservations.alamat_instansi', 'reservations.jumlah_peserta', 'reservations.klasifikasi', 'reservations.nama_pimpinan', 'reservations.tanggal_berkunjung', 'reservations.jam_kunjungan', 'reservations.lokasi', 'reservations.topik', 'reservations.alamat_hotel', 'reservations.document_path', 'reservations.status', 'reservations.created_at', null);
    var $column_search = array('reservations.id', 'reservations.user_id', 'reservations.no_ticket', 'reservations.asal_tamu',  'reservations.nomor_whatsapp', 'reservations.nama_pemohon', 'reservations.email', 'reservations.nama_instansi', 'reservations.provinsi', 'reservations.nomor_hp', 'reservations.alamat_instansi', 'reservations.jumlah_peserta', 'reservations.klasifikasi', 'reservations.nama_pimpinan', 'reservations.tanggal_berkunjung', 'reservations.jam_kunjungan', 'reservations.lokasi', 'reservations.topik', 'reservations.document_path', 'reservations.status', 'reservations.created_at');
    var $order = array('reservations.id' => 'desc');

    private function _get_datatables_query()
    {
        $this->db->select('reservations.id, users.email, reservations.user_id, reservations.no_ticket, reservations.asal_tamu, reservations.nomor_whatsapp, reservations.nama_pemohon, reservations.email, reservations.nama_instansi, reservations.provinsi, reservations.nomor_hp, reservations.alamat_instansi, reservations.jumlah_peserta, reservations.klasifikasi, reservations.nama_pimpinan, reservations.tanggal_berkunjung, reservations.jam_kunjungan, reservations.lokasi, reservations.topik, reservations.alamat_hotel, reservations.document_path, reservations.status, reservations.created_at');
        $this->db->from($this->table);
        $this->db->join('users', 'users.id = reservations.user_id');
        $this->db->order_by('reservations.id', 'desc');

        $i = 0;

        foreach ($this->column_search as $item) {
            if ($_POST['search']['value']) {
                if ($i === 0) {
                    $this->db->group_start();
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i) {
                    $this->db->group_end();
                }
                $i++;
            }
        }

        if (isset($_POST['order'])) {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }

        // Filter by status
        if (!empty($_POST['status'])) {
            $this->db->where('reservations.status', $_POST['status']);
        }

        // Filter by tanggal_berkunjung (YYYY-MM month filter or full date)
        if (!empty($_POST['tanggal_berkunjung'])) {
            $filter_date = $_POST['tanggal_berkunjung'];
            if (preg_match('/^\d{4}-\d{2}$/', $filter_date)) {
                $this->db->where("DATE_FORMAT(reservations.tanggal_berkunjung, '%Y-%m') = ", $filter_date);
            } else {
                $this->db->where('reservations.tanggal_berkunjung', $filter_date);
            }
        }
    }

    function get_datatables()
    {
        $this->_get_datatables_query();
        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function insert_reservations($data)
    {
        return $this->db->insert('reservations', $data);
    }
    public function get_by_user($user_id)
    {
        return $this->db->where('user_id', $user_id)
            ->get('reservations')
            ->row();
    }

    public function get_by_id($id)
    {
        $this->db->select('reservations.*, users.email');
        $this->db->from($this->table);
        $this->db->join('users', 'users.id = reservations.user_id');
        $this->db->where('reservations.id', $id);
        $query = $this->db->get();
        return $query->row();
    }
    public function update_by_user($user_id, $data)
    {
        return $this->db->where('user_id', $user_id)
            ->update('reservations', $data);
    }

    public function update_reservations($id, $data)
    {
        return $this->db->where('id', $id)
            ->update('reservations', $data);
    }

    public function get_all($id = null)
    {
        if ($id !== null) {
            $this->db->where('user_id', $id);
        }

        return $this->db->order_by('created_at', 'DESC')
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
