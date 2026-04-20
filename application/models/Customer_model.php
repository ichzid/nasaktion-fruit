<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_all() {
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get('customers')->result();
    }

    public function get_by_id($id) {
        return $this->db->get_where('customers', array('id' => $id))->row();
    }

    public function get_by_phone($no_hp) {
        return $this->db->get_where('customers', array('no_hp' => $no_hp))->row();
    }

    public function search($keyword) {
        $this->db->like('nama', $keyword);
        $this->db->or_like('no_hp', $keyword);
        return $this->db->get('customers')->result();
    }

    public function insert($data) {
        $data['created_at'] = date('Y-m-d H:i:s');
        if (!isset($data['tipe_customer'])) {
            $data['tipe_customer'] = 'Offline'; // Default to offline unless specified
        }
        $this->db->insert('customers', $data);
        return $this->db->insert_id();
    }

    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update('customers', $data);
    }

    public function delete($id) {
        return $this->db->delete('customers', array('id' => $id));
    }

    public function update_loyalty($customer_id, $total_amount) {
        $customer = $this->get_by_id($customer_id);
        if ($customer) {
            $new_total_belanja = $customer->total_belanja + $total_amount;
            $new_total_transaksi = $customer->total_transaksi + 1;
            $new_points = $customer->point_loyalitas + floor($total_amount / 10000); // 1 point per 10.000

            // Determine segment
            $segment = $this->calculate_segment($new_total_transaksi, $new_total_belanja);

            $this->update($customer_id, array(
                'total_belanja' => $new_total_belanja,
                'total_transaksi' => $new_total_transaksi,
                'point_loyalitas' => $new_points,
                'segment' => $segment,
                'last_transaction_at' => date('Y-m-d H:i:s')
            ));
        }
    }

    public function calculate_segment($total_transaksi, $total_belanja) {
        if ($total_transaksi > 10 || $total_belanja > 5000000) {
            return 'Platinum';
        } elseif ($total_transaksi > 6 || $total_belanja > 2000000) {
            return 'Gold';
        } elseif ($total_transaksi > 3 || $total_belanja > 500000) {
            return 'Silver';
        }
        return 'Baru';
    }

    public function get_passive_customers($days = 30) {
        $this->db->where('last_transaction_at IS NOT NULL');
        $this->db->where("last_transaction_at < DATE_SUB(NOW(), INTERVAL {$days} DAY)");
        $this->db->order_by('last_transaction_at', 'ASC');
        return $this->db->get('customers')->result();
    }

    public function get_by_segment($segment) {
        return $this->db->get_where('customers', array('segment' => $segment))->result();
    }

    public function count_all() {
        return $this->db->count_all('customers');
    }

    public function count_by_segment($segment) {
        return $this->db->where('segment', $segment)->count_all_results('customers');
    }

    /**
     * Set password & set to Unified (for offline customers who transition to online)
     */
    public function set_password($id, $password) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        return $this->update($id, array(
            'password' => $hash,
            'tipe_customer' => 'Unified'
        ));
    }

    /**
     * Find potential duplicate customers (same name/phone)
     */
    public function find_potential_duplicates() {
        $customers = $this->get_all();
        $duplicates = array();

        for ($i = 0; $i < count($customers); $i++) {
            for ($j = $i + 1; $j < count($customers); $j++) {
                $a = $customers[$i];
                $b = $customers[$j];
                $match_reason = array();

                // Same phone number
                if (!empty($a->no_hp) && !empty($b->no_hp) && $a->no_hp === $b->no_hp) {
                    $match_reason[] = 'No HP sama: ' . $a->no_hp;
                }
                
                // Similar name (simple check)
                if (!empty($a->nama) && !empty($b->nama)) {
                    similar_text(strtolower($a->nama), strtolower($b->nama), $percent);
                    if ($percent > 80) {
                        $match_reason[] = 'Nama mirip: "' . $a->nama . '" ≈ "' . $b->nama . '" (' . round($percent) . '%)';
                    }
                }

                if (!empty($match_reason)) {
                    $duplicates[] = array(
                        'customer_a' => $a,
                        'customer_b' => $b,
                        'reasons' => $match_reason,
                    );
                }
            }
        }

        return $duplicates;
    }

    public function get_recommendations($customer_id, $limit = 4) {
        // Get categories the customer frequently buys
        $this->db->select('p.category_id, COUNT(*) as freq');
        $this->db->from('transaction_items ti');
        $this->db->join('products p', 'p.id = ti.product_id');
        $this->db->join('transactions t', 't.id = ti.transaction_id');
        $this->db->where('t.customer_id', $customer_id);
        $this->db->group_by('p.category_id');
        $this->db->order_by('freq', 'DESC');
        $categories = $this->db->get()->result();

        if (empty($categories)) {
            // If no history, return popular products
            $this->db->select('p.*, COUNT(ti.id) as sold');
            $this->db->from('products p');
            $this->db->join('transaction_items ti', 'ti.product_id = p.id', 'left');
            $this->db->where('p.is_active', 1);
            $this->db->where('p.stok >', 0);
            $this->db->group_by('p.id');
            $this->db->order_by('sold', 'DESC');
            $this->db->limit($limit);
            return $this->db->get()->result();
        }

        // Get products from preferred categories that customer hasn't bought recently
        $cat_ids = array_column($categories, 'category_id');
        $this->db->where_in('category_id', $cat_ids);
        $this->db->where('is_active', 1);
        $this->db->where('stok >', 0);
        $this->db->order_by('RAND()');
        $this->db->limit($limit);
        return $this->db->get('products')->result();
    }

    /**
     * Get all customers with their transaction stats joined
     */
    public function get_all_with_stats() {
        $this->db->select('c.*, 
            COALESCE(c.total_transaksi, 0) as total_transaksi,
            COALESCE(c.total_belanja, 0) as total_belanja,
            COALESCE(c.point_loyalitas, 0) as point_loyalitas,
            c.last_transaction_at as last_order,
            c.segment
        ');
        $this->db->from('customers c');
        $this->db->order_by('c.total_belanja', 'DESC');
        return $this->db->get()->result();
    }
}