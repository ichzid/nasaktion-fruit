<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends AdminBaseController {

    public function __construct() {
        parent::__construct();
        $this->load->model('Admin_model');
        $this->admin_only();
    }

    public function index() {
        $data['title'] = 'Kelola User';
        $data['users'] = $this->Admin_model->get_all();
        $this->render_admin('admin/users/index', $data);
    }

    public function create() {
        $this->form_validation->set_rules('username', 'Username', 'required|trim|is_unique[admins.username]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        $this->form_validation->set_rules('nama_lengkap', 'Nama Lengkap', 'required|trim');
        $this->form_validation->set_rules('role', 'Role', 'required');

        if ($this->form_validation->run() == FALSE) {
            $data['title'] = 'Tambah User';
            $this->render_admin('admin/users/form', $data);
        } else {
            $input = array(
                'username' => $this->input->post('username', TRUE),
                'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                'nama_lengkap' => $this->input->post('nama_lengkap', TRUE),
                'role' => $this->input->post('role'),
                'is_active' => 1,
            );
            if ($this->Admin_model->insert($input)) {
                $this->session->set_flashdata('success', 'User berhasil ditambahkan!');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan user!');
            }
            redirect('admin/users');
        }
    }

    public function edit($id) {
        $data['user'] = $this->Admin_model->get_by_id($id);
        if (!$data['user']) {
            $this->session->set_flashdata('error', 'User tidak ditemukan!');
            redirect('admin/users');
        }

        $original_username = $data['user']->username;
        $posted_username = $this->input->post('username');
        if ($this->input->post() && $posted_username != $original_username) {
            $this->form_validation->set_rules('username', 'Username', 'required|trim|is_unique[admins.username]');
        } else {
            $this->form_validation->set_rules('username', 'Username', 'required|trim');
        }

        $this->form_validation->set_rules('nama_lengkap', 'Nama Lengkap', 'required|trim');
        $this->form_validation->set_rules('role', 'Role', 'required');

        if ($this->form_validation->run() == FALSE) {
            $data['title'] = 'Edit User';
            $this->render_admin('admin/users/form', $data);
        } else {
            $input = array(
                'username' => $this->input->post('username', TRUE),
                'nama_lengkap' => $this->input->post('nama_lengkap', TRUE),
                'role' => $this->input->post('role'),
            );
            if ($id != $this->session->userdata('admin_id')) {
                $input['is_active'] = $this->input->post('is_active') ? 1 : 0;
            }
            $password = $this->input->post('password');
            if (!empty($password)) {
                $input['password'] = password_hash($password, PASSWORD_DEFAULT);
            }
            if ($this->Admin_model->update($id, $input)) {
                $this->session->set_flashdata('success', 'User berhasil diperbarui!');
            } else {
                $this->session->set_flashdata('error', 'Gagal memperbarui user!');
            }
            redirect('admin/users');
        }
    }

    public function delete($id) {
        if ($id == $this->admin_data['admin_id']) {
            $this->session->set_flashdata('error', 'Tidak bisa menghapus akun sendiri!');
        } elseif ($this->Admin_model->delete($id)) {
            $this->session->set_flashdata('success', 'User berhasil dihapus!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus user!');
        }
        redirect('admin/users');
    }
}