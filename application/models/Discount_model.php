<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Discount_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_all() {
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get('discounts')->result();
    }

    public function get_by_id($id) {
        return $this->db->get_where('discounts', array('id' => $id))->row();
    }

    public function get_by_code($code) {
        $this->db->where('kode_voucher', $code);
        $this->db->where('is_active', 1);
        // Handle NULL dates - treat NULL as always valid
        $this->db->group_start();
        $this->db->where('tanggal_mulai <=', date('Y-m-d'));
        $this->db->or_where('tanggal_mulai', NULL);
        $this->db->group_end();
        $this->db->group_start();
        $this->db->where('tanggal_selesai >=', date('Y-m-d'));
        $this->db->or_where('tanggal_selesai', NULL);
        $this->db->group_end();
        return $this->db->get('discounts')->row();
    }

    public function get_active() {
        $this->db->where('is_active', 1);
        // Handle NULL dates - treat NULL as always valid
        $this->db->group_start();
        $this->db->where('tanggal_mulai <=', date('Y-m-d'));
        $this->db->or_where('tanggal_mulai', NULL);
        $this->db->group_end();
        $this->db->group_start();
        $this->db->where('tanggal_selesai >=', date('Y-m-d'));
        $this->db->or_where('tanggal_selesai', NULL);
        $this->db->group_end();
        return $this->db->get('discounts')->result();
    }

    public function get_for_customer($customer_segment = 'Baru', $jenis_order = 'Online') {
        $this->db->where('is_active', 1);
        // Handle NULL dates - treat NULL as always valid
        $this->db->group_start();
        $this->db->where('tanggal_mulai <=', date('Y-m-d'));
        $this->db->or_where('tanggal_mulai', NULL);
        $this->db->group_end();
        $this->db->group_start();
        $this->db->where('tanggal_selesai >=', date('Y-m-d'));
        $this->db->or_where('tanggal_selesai', NULL);
        $this->db->group_end();
        $this->db->group_start();
        $this->db->where('target', 'semua');
        $this->db->or_where('target', $jenis_order);
        $this->db->or_where('target', 'loyal');
        $this->db->group_end();
        return $this->db->get('discounts')->result();
    }

    public function insert($data) {
        return $this->db->insert('discounts', $data);
    }

    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('discounts', $data);
    }

    public function delete($id) {
        $this->db->delete('discounts', array('id' => $id));
    }

    public function calculate_discount($discount_id, $subtotal) {
        $discount = $this->get_by_id($discount_id);
        if (!$discount) return 0;

        if ($subtotal < $discount->min_belanja) return 0;

        if ($discount->tipe === 'persen') {
            return $subtotal * ($discount->nilai / 100);
        } else {
            return $discount->nilai;
        }
    }

    public function count_all() {
        return $this->db->count_all('discounts');
    }

    public function count_active() {
        $this->db->where('is_active', 1);
        // Handle NULL dates - treat NULL as always valid
        $this->db->group_start();
        $this->db->where('tanggal_mulai <=', date('Y-m-d'));
        $this->db->or_where('tanggal_mulai', NULL);
        $this->db->group_end();
        $this->db->group_start();
        $this->db->where('tanggal_selesai >=', date('Y-m-d'));
        $this->db->or_where('tanggal_selesai', NULL);
        $this->db->group_end();
        return $this->db->count_all_results('discounts');
    }
}