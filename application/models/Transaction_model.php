<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaction_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_all($limit = null, $offset = null) {
        $this->db->select('t.*, c.nama, a.nama_lengkap');
        $this->db->from('transactions t');
        $this->db->join('customers c', 'c.id = t.customer_id', 'left');
        $this->db->join('admins a', 'a.id = t.admin_id', 'left');
        $this->db->order_by('t.created_at', 'DESC');
        if ($limit) $this->db->limit($limit, $offset);
        return $this->db->get()->result();
    }

    public function get_by_id($id) {
        $this->db->select('t.*, c.nama, c.no_hp, a.nama_lengkap');
        $this->db->from('transactions t');
        $this->db->join('customers c', 'c.id = t.customer_id', 'left');
        $this->db->join('admins a', 'a.id = t.admin_id', 'left');
        $this->db->where('t.id', $id);
        return $this->db->get()->row();
    }

    public function get_by_invoice($invoice_no) {
        return $this->db->get_where('transactions', array('invoice_no' => $invoice_no))->row();
    }

    public function get_by_customer($customer_id) {
        $this->db->where('customer_id', $customer_id);
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get('transactions')->result();
    }

    public function get_items($transaction_id) {
        $this->db->select('ti.*, p.nama_buah, p.foto, p.satuan');
        $this->db->from('transaction_items ti');
        $this->db->join('products p', 'p.id = ti.product_id');
        $this->db->where('ti.transaction_id', $transaction_id);
        return $this->db->get()->result();
    }

    public function insert($data) {
        return $this->db->insert('transactions', $data);
    }

    public function insert_items($items) {
        return $this->db->insert_batch('transaction_items', $items);
    }

    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('transactions', $data);
    }

    public function delete($id) {
        return $this->db->delete('transactions', array('id' => $id));
    }

    public function get_customer_total_spent($customer_id) {
        $this->db->select_sum('total');
        $this->db->where('customer_id', $customer_id);
        $this->db->where('status !=', 'cancelled');
        $result = $this->db->get('transactions')->row();
        return $result->total ? $result->total : 0;
    }

    public function count_by_customer($customer_id) {
        return $this->db->where('customer_id', $customer_id)->count_all_results('transactions');
    }

    public function generate_invoice() {
        $prefix = 'NKF';
        $date = date('Ymd');
        $this->db->like('invoice_no', $prefix . $date);
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $last = $this->db->get('transactions')->row();

        if ($last) {
            $last_num = (int)substr($last->invoice_no, -4);
            $new_num = $last_num + 1;
        } else {
            $new_num = 1;
        }

        return $prefix . $date . str_pad($new_num, 4, '0', STR_PAD_LEFT);
    }

    public function count_all() {
        return $this->db->count_all('transactions');
    }

    public function count_pending() {
        return $this->db->where('status', 'pending')->count_all_results('transactions');
    }

    public function count_by_status($status) {
        return $this->db->where('status', $status)->count_all_results('transactions');
    }

    public function count_by_jenis($jenis) {
        return $this->db->where('jenis_order', $jenis)->count_all_results('transactions');
    }

    public function get_today_sales() {
        $this->db->select_sum('total');
        $this->db->where('DATE(tgl)', date('Y-m-d'));
        $this->db->where('status !=', 'cancelled');
        $result = $this->db->get('transactions')->row();
        return $result->total ? $result->total : 0;
    }

    public function get_month_sales() {
        $this->db->select_sum('total');
        $this->db->where('MONTH(tgl)', date('m'));
        $this->db->where('YEAR(tgl)', date('Y'));
        $this->db->where('status !=', 'cancelled');
        $result = $this->db->get('transactions')->row();
        return $result->total ? $result->total : 0;
    }

    public function get_sales_chart_data($days = 7) {
        $this->db->select('DATE(tgl) as date, SUM(total) as total_sales, COUNT(*) as total_orders');
        $this->db->where('tgl >=', date('Y-m-d', strtotime("-{$days} days")));
        $this->db->where('status !=', 'cancelled');
        $this->db->group_by('DATE(tgl)');
        $this->db->order_by('date', 'ASC');
        return $this->db->get('transactions')->result();
    }

    public function get_top_products($limit = 5) {
        $this->db->select('p.nama_buah, SUM(ti.qty) as total_qty, SUM(ti.subtotal) as total_revenue');
        $this->db->from('transaction_items ti');
        $this->db->join('products p', 'p.id = ti.product_id');
        $this->db->join('transactions t', 't.id = ti.transaction_id');
        $this->db->where('t.status !=', 'cancelled');
        $this->db->group_by('ti.product_id');
        $this->db->order_by('total_qty', 'DESC');
        $this->db->limit($limit);
        return $this->db->get()->result();
    }

    public function get_recent($limit = 5) {
        $this->db->select('t.*, c.nama');
        $this->db->from('transactions t');
        $this->db->join('customers c', 'c.id = t.customer_id', 'left');
        $this->db->order_by('t.created_at', 'DESC');
        $this->db->limit($limit);
        return $this->db->get()->result();
    }
}