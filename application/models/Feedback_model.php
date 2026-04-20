<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Feedback_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_all() {
        $this->db->select('f.*, c.nama, c.no_hp');
        $this->db->from('feedback f');
        $this->db->join('customers c', 'c.id = f.customer_id', 'left');
        $this->db->order_by('f.created_at', 'DESC');
        return $this->db->get()->result();
    }

    public function get_by_id($id) {
        $this->db->select('f.*, c.nama, c.no_hp');
        $this->db->from('feedback f');
        $this->db->join('customers c', 'c.id = f.customer_id', 'left');
        $this->db->where('f.id', $id);
        return $this->db->get()->row();
    }

    public function get_by_customer($customer_id) {
        $this->db->where('customer_id', $customer_id);
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get('feedback')->result();
    }

    public function get_by_product($product_id) {
        $this->db->select('f.*, c.nama, c.foto');
        $this->db->from('feedback f');
        $this->db->join('customers c', 'c.id = f.customer_id', 'left');
        $this->db->join('transaction_items ti', 'ti.transaction_id = f.transaction_id');
        $this->db->where('ti.product_id', $product_id);
        $this->db->group_by('f.id');
        $this->db->order_by('f.created_at', 'DESC');
        return $this->db->get()->result();
    }

    public function get_product_avg_rating($product_id) {
        $this->db->select_avg('f.rating', 'avg_rating');
        $this->db->from('feedback f');
        $this->db->join('transaction_items ti', 'ti.transaction_id = f.transaction_id');
        $this->db->where('ti.product_id', $product_id);
        $result = $this->db->get()->row();
        return $result && $result->avg_rating ? round($result->avg_rating, 1) : 0;
    }

    public function get_unread() {
        $this->db->select('f.*, c.nama');
        $this->db->from('feedback f');
        $this->db->join('customers c', 'c.id = f.customer_id', 'left');
        $this->db->where('f.is_read', 0);
        $this->db->order_by('f.created_at', 'DESC');
        return $this->db->get()->result();
    }

    public function insert($data) {
        return $this->db->insert('feedback', $data);
    }

    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('feedback', $data);
    }

    public function delete($id) {
        return $this->db->delete('feedback', array('id' => $id));
    }

    public function mark_as_read($id) {
        $this->db->where('id', $id);
        return $this->db->update('feedback', array('is_read' => 1));
    }

    public function reply($id, $balasan) {
        $this->db->where('id', $id);
        return $this->db->update('feedback', array(
            'balasan_admin' => $balasan,
            'balasan_at' => date('Y-m-d H:i:s'),
            'is_read' => 1
        ));
    }

    public function count_all() {
        return $this->db->count_all('feedback');
    }

    public function count_unread() {
        return $this->db->where('is_read', 0)->count_all_results('feedback');
    }

    public function get_average_rating() {
        $this->db->select_avg('rating');
        $result = $this->db->get('feedback')->row();
        return $result->rating ? round($result->rating, 1) : 0;
    }

    public function get_rating_distribution() {
        $this->db->select('rating, COUNT(*) as count');
        $this->db->group_by('rating');
        $this->db->order_by('rating', 'DESC');
        return $this->db->get('feedback')->result();
    }
}