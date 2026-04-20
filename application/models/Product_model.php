<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_all($active_only = false) {
        $this->db->select('p.*, c.nama_kategori');
        $this->db->from('products p');
        $this->db->join('categories c', 'c.id = p.category_id', 'left');
        if ($active_only) {
            $this->db->where('p.is_active', 1);
        }
        $this->db->order_by('p.created_at', 'DESC');
        return $this->db->get()->result();
    }

    public function get_by_id($id) {
        $this->db->select('p.*, c.nama_kategori');
        $this->db->from('products p');
        $this->db->join('categories c', 'c.id = p.category_id', 'left');
        $this->db->where('p.id', $id);
        return $this->db->get()->row();
    }

    public function get_related($category_id, $exclude_id, $limit = 4) {
        $this->db->select('p.*, c.nama_kategori');
        $this->db->from('products p');
        $this->db->join('categories c', 'c.id = p.category_id', 'left');
        $this->db->where('p.category_id', $category_id);
        $this->db->where('p.id !=', $exclude_id);
        $this->db->where('p.is_active', 1);
        $this->db->where('p.stok >', 0);
        $this->db->order_by('RAND()');
        $this->db->limit($limit);
        return $this->db->get()->result();
    }

    public function get_by_category($category_id) {
        $this->db->where('category_id', $category_id);
        $this->db->where('is_active', 1);
        $this->db->where('stok >', 0);
        return $this->db->get('products')->result();
    }

    public function get_available() {
        $this->db->where('is_active', 1);
        $this->db->where('stok >', 0);
        $this->db->order_by('nama_buah', 'ASC');
        return $this->db->get('products')->result();
    }

    public function insert($data) {
        $data['created_at'] = date('Y-m-d H:i:s');
        return $this->db->insert('products', $data);
    }

    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('products', $data);
    }

    public function delete($id) {
        return $this->db->delete('products', array('id' => $id));
    }

    public function reduce_stock($product_id, $qty) {
        $this->db->set('stok', 'stok - ' . (int)$qty, FALSE);
        $this->db->where('id', $product_id);
        $this->db->where('stok >=', $qty);
        return $this->db->update('products');
    }

    public function restore_stock($product_id, $qty) {
        $this->db->set('stok', 'stok + ' . (int)$qty, FALSE);
        $this->db->where('id', $product_id);
        return $this->db->update('products');
    }

    public function count_all() {
        return $this->db->count_all('products');
    }

    public function count_active() {
        return $this->db->where('is_active', 1)->count_all_results('products');
    }

    public function count_low_stock($threshold = 5) {
        return $this->db->where('stok <=', $threshold)->where('is_active', 1)->count_all_results('products');
    }

    public function get_low_stock($threshold = 5) {
        $this->db->where('stok <=', $threshold);
        $this->db->where('is_active', 1);
        return $this->db->get('products')->result();
    }

    public function get_featured($limit = 8) {
        $this->db->select('p.*, c.nama_kategori');
        $this->db->from('products p');
        $this->db->join('categories c', 'c.id = p.category_id', 'left');
        $this->db->where('p.is_active', 1);
        $this->db->where('p.stok >', 0);
        $this->db->order_by('p.created_at', 'DESC');
        $this->db->limit($limit);
        return $this->db->get()->result();
    }

    public function search($keyword) {
        $this->db->like('nama_buah', $keyword);
        $this->db->or_like('jenis', $keyword);
        $this->db->where('is_active', 1);
        return $this->db->get('products')->result();
    }
}