<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_all() {
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get('admins')->result();
    }

    public function get_by_id($id) {
        return $this->db->get_where('admins', array('id' => $id))->row();
    }

    public function get_by_username($username) {
        return $this->db->get_where('admins', array('username' => $username))->row();
    }

    public function insert($data) {
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $data['created_at'] = date('Y-m-d H:i:s');
        return $this->db->insert('admins', $data);
    }

    public function update($id, $data) {
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        } else {
            unset($data['password']);
        }
        $this->db->where('id', $id);
        return $this->db->update('admins', $data);
    }

    public function delete($id) {
        return $this->db->delete('admins', array('id' => $id));
    }

    public function verify_login($username, $password) {
        $admin = $this->get_by_username($username);
        if ($admin && password_verify($password, $admin->password)) {
            if ($admin->is_active) {
                return $admin;
            }
        }
        return false;
    }

    public function count_all() {
        return $this->db->count_all('admins');
    }

    public function count_by_role($role) {
        return $this->db->where('role', $role)->count_all_results('admins');
    }
}