<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Admin_model');
    }

    public function login() {
        redirect('login');
    }

    public function logout() {
        $this->session->unset_userdata(array('admin_logged_in', 'admin_id', 'admin_username', 'admin_nama', 'admin_role'));
        $this->session->sess_destroy();
        redirect('login');
    }
}