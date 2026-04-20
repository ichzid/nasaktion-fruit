<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_all() {
        $this->db->order_by('nama_kategori', 'ASC');
        return $this->db->get('categories')->result();
    }

    public function get_by_id($id) {
        return $this->db->get_where('categories', array('id' => $id))->row();
    }

    public function insert($data) {
        return $this->db->insert('categories', $data);
    }

    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('categories', $data);
    }

    public function delete($id) {
        return $this->db->delete('categories', array('id' => $id));
    }

    public function count_all() {
        return $this->db->count_all('categories');
    }
}