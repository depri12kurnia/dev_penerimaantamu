<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_galeri extends CI_Model
{
    var $table = 'galleries';
    var $table_categories = 'g_categories';

    // Disesuaikan dengan kolom yang benar-benar ada di kedua tabel
    var $column_order = array('g.id', 'g.title', 'g.description', 'g.image_url');
    var $column_search = array('g.title', 'g.description', 'gc.name');
    var $order = array('g.created_at' => 'desc'); // Default order diganti ke data terbaru

    public function __construct()
    {
        parent::__construct();
    }

    // ==========================================
    // METHODS FOR DATATABLES (ADMIN)
    // ==========================================

    private function _get_datatables_query()
    {
        $this->db->select('g.*, gc.name as category_name');
        $this->db->from($this->table . ' g');
        $this->db->join($this->table_categories . ' gc', 'g.category_id = gc.id', 'left');

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
            }
            $i++;
        }

        if (isset($_POST['order'])) {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
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

    // ==========================================
    // CRUD OPERATIONS
    // ==========================================

    public function get_by_id($id)
    {
        $this->db->select('g.*, gc.name as category_name');
        $this->db->from($this->table . ' g');
        $this->db->join($this->table_categories . ' gc', 'g.category_id = gc.id', 'left');
        $this->db->where('g.id', $id);
        $query = $this->db->get();
        return $query->row();
    }

    public function insert_galeri($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function update_galeri($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    public function delete_galeri($id)
    {
        return $this->db->delete($this->table, ['id' => $id]);
    }

    public function generate_slug($title, $id = null)
    {
        $slug = url_title($title, 'dash', TRUE);
        $original_slug = $slug;
        $count = 1;

        while (TRUE) {
            $this->db->where('slug', $slug);
            if ($id) {
                $this->db->where('id !=', $id);
            }
            $query = $this->db->get($this->table);

            if ($query->num_rows() == 0) {
                break;
            }

            $slug = $original_slug . '-' . $count;
            $count++;
        }

        return $slug;
    }
    // ==========================================
    // PUBLIC METHODS (FOR FRONTEND)
    // ==========================================

    public function get_all($limit = 0)
    {
        $this->db->select('g.*, gc.name as category_name');
        $this->db->from($this->table . ' g');
        $this->db->join($this->table_categories . ' gc', 'g.category_id = gc.id', 'left');
        $this->db->order_by('g.created_at', 'desc');
        if ($limit > 0) {
            $this->db->limit($limit);
        }
        $query = $this->db->get();
        return $query->result();
    }

    public function get_by_category($category_id, $limit = 0)
    {
        $this->db->select('g.*, gc.name as category_name');
        $this->db->from($this->table . ' g');
        $this->db->join($this->table_categories . ' gc', 'g.category_id = gc.id', 'left');
        $this->db->where('g.category_id', $category_id);
        $this->db->order_by('g.created_at', 'desc');
        if ($limit > 0) {
            $this->db->limit($limit);
        }
        $query = $this->db->get();
        return $query->result();
    }

    // ==========================================
    // CATEGORY METHODS
    // ==========================================

    public function get_categories()
    {
        $this->db->select('gc.*, COUNT(g.id) as galeri_count');
        $this->db->from($this->table_categories . ' gc');
        $this->db->join($this->table . ' g', 'gc.id = g.category_id', 'left');
        $this->db->group_by('gc.id');
        $this->db->order_by('gc.name', 'asc');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_category_by_id($id)
    {
        $this->db->select('gc.*, COUNT(g.id) as galeri_count');
        $this->db->from($this->table_categories . ' gc');
        $this->db->join($this->table . ' g', 'gc.id = g.category_id', 'left');
        $this->db->where('gc.id', $id);
        $this->db->group_by('gc.id');
        $query = $this->db->get();
        return $query->row();
    }

    public function get_category_by_slug($slug)
    {
        $this->db->where('slug', $slug);
        $query = $this->db->get($this->table_categories);
        return $query->row();
    }

    public function insert_category($data)
    {
        return $this->db->insert($this->table_categories, $data);
    }

    public function update_category($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update($this->table_categories, $data);
    }

    public function delete_category($id)
    {
        return $this->db->delete($this->table_categories, ['id' => $id]);
    }

    // ==========================================
    // UTILITY METHODS
    // ==========================================

    public function search_galeri($keyword, $category_id = null, $limit = 0)
    {
        $this->db->select('g.*, gc.name as category_name');
        $this->db->from($this->table . ' g');
        $this->db->join($this->table_categories . ' gc', 'g.category_id = gc.id', 'left');

        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('g.title', $keyword);
            $this->db->or_like('g.description', $keyword);
            $this->db->group_end();
        }

        if (!empty($category_id)) {
            $this->db->where('g.category_id', $category_id);
        }

        $this->db->order_by('g.created_at', 'desc');

        if ($limit > 0) {
            $this->db->limit($limit);
        }

        $query = $this->db->get();
        return $query->result();
    }

    // ==========================================
    // METHODS FOR FRONTEND PAGINATION
    // ==========================================

    public function get_all_paginated($limit, $offset)
    {
        $this->db->select('g.*, gc.name as category_name');
        $this->db->from($this->table . ' g');
        $this->db->join($this->table_categories . ' gc', 'g.category_id = gc.id', 'left');
        $this->db->order_by('g.created_at', 'desc');
        $this->db->limit($limit, $offset);
        $query = $this->db->get();
        return $query->result();
    }

    public function get_by_category_paginated($category_id, $limit, $offset, $sort = 'newest')
    {
        $this->db->select('g.*, gc.name as category_name');
        $this->db->from($this->table . ' g');
        $this->db->join($this->table_categories . ' gc', 'g.category_id = gc.id', 'left');
        $this->db->where('g.category_id', $category_id);

        switch ($sort) {
            case 'oldest':
                $this->db->order_by('g.created_at', 'asc');
                break;
            case 'title_asc':
                $this->db->order_by('g.title', 'asc');
                break;
            case 'title_desc':
                $this->db->order_by('g.title', 'desc');
                break;
            case 'newest':
            default:
                $this->db->order_by('g.created_at', 'desc');
                break;
        }

        $this->db->limit($limit, $offset);
        $query = $this->db->get();
        return $query->result();
    }
}
