<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Admin_model');
    }

    public function login() {
        // If already logged in, redirect to dashboard
        if ($this->session->userdata('admin_logged_in')) {
            redirect('admin/dashboard');
        }

        $this->form_validation->set_rules('username', 'Username', 'required|trim');
        $this->form_validation->set_rules('password', 'Password', 'required|trim');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('admin/auth/login');
        } else {
            $username = $this->input->post('username', TRUE);
            $password = $this->input->post('password');

            $admin = $this->Admin_model->verify_login($username, $password);

            if ($admin) {
                $session_data = array(
                    'admin_logged_in' => TRUE,
                    'admin_id' => $admin->id,
                    'admin_username' => $admin->username,
                    'admin_nama' => $admin->nama_lengkap,
                    'admin_role' => $admin->role,
                );
                $this->session->set_userdata($session_data);
                redirect('admin/dashboard');
            } else {
                $this->session->set_flashdata('error', 'Username atau password salah!');
                redirect('admin/login');
            }
        }
    }

    public function logout() {
        $this->session->unset_userdata(array('admin_logged_in', 'admin_id', 'admin_username', 'admin_nama', 'admin_role'));
        $this->session->sess_destroy();
        redirect('admin/login');
    }
}