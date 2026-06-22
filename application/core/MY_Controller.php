<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }
}

/**
 * Admin Base Controller - Guard for Admin & Kasir (Native Auth)
 */
class AdminBaseController extends MY_Controller {

    public $admin_data = array();

    public function __construct() {
        parent::__construct();
        $this->check_admin_auth();
        $this->load_admin_data();
    }

    private function check_admin_auth() {
        if (!$this->session->userdata('admin_logged_in')) {
            redirect('admin/login');
        }
    }

    private function load_admin_data() {
        $this->admin_data = array(
            'admin_id' => $this->session->userdata('admin_id'),
            'username' => $this->session->userdata('admin_username'),
            'nama_lengkap' => $this->session->userdata('admin_nama'),
            'role' => $this->session->userdata('admin_role'),
        );
    }

    protected function is_admin() {
        return $this->admin_data['role'] === 'admin';
    }

    protected function is_kasir() {
        return $this->admin_data['role'] === 'kasir';
    }

    protected function admin_only() {
        if (!$this->is_admin()) {
            $this->session->set_flashdata('error', 'Akses ditolak. Hanya admin yang diizinkan.');
            redirect('admin/pos');
        }
    }

    protected function render_admin($view, $data = array()) {
        $data['admin'] = $this->admin_data;
        $data['content'] = $view;
        $data['csrf'] = array(
            'name' => $this->security->get_csrf_token_name(),
            'hash' => $this->security->get_csrf_hash()
        );

        // Notification counts for sidebar badges
        $this->load->model('Feedback_model');
        $this->load->model('Transaction_model');
        $data['unread_feedback'] = $this->Feedback_model->count_unread();
        $data['pending_orders'] = $this->Transaction_model->count_pending();

        $this->load->view($view, $data);
    }
}

/**
 * Customer Base Controller - Guard for Pelanggan (Google OAuth)
 */
class CustomerBaseController extends MY_Controller {

    public $customer_data = array();

    public function __construct() {
        parent::__construct();
        $this->check_customer_auth();
        $this->load_customer_data();
    }

    private function check_customer_auth() {
        if (!$this->session->userdata('customer_logged_in')) {
            redirect('customer/login');
        }
    }

    private function load_customer_data() {
        $this->customer_data = array(
            'customer_id' => $this->session->userdata('customer_id'),
            'customer_nama' => $this->session->userdata('customer_nama'),
            'email' => $this->session->userdata('customer_email'),
            'segment' => $this->session->userdata('customer_segment'),
            'point_loyalitas' => $this->session->userdata('customer_points'),
        );
    }

    protected function render_customer($view, $data = array()) {
        $data['customer'] = $this->customer_data;
        $data['content'] = $view;
        $data['csrf'] = array(
            'name' => $this->security->get_csrf_token_name(),
            'hash' => $this->security->get_csrf_hash()
        );

        // Cart count
        $cart = $this->session->userdata('shop_cart') ? $this->session->userdata('shop_cart') : array();
        $data['cart_count'] = array_sum(array_column($cart, 'qty'));

        $this->load->view($view, $data);
    }
}

/**
 * Public Base Controller - No auth required
 */
class PublicController extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    protected function render_public($view, $data = array()) {
        $data['content'] = $view;
        $data['csrf'] = array(
            'name' => $this->security->get_csrf_token_name(),
            'hash' => $this->security->get_csrf_hash()
        );
        $this->load->view($view, $data);
    }
}