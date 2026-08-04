<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Customer_model');
        $this->load->model('Admin_model');
    }

    public function login() {
        if ($this->session->userdata('admin_logged_in')) {
            redirect($this->session->userdata('admin_role') === 'admin' ? 'admin/dashboard' : 'admin/pos');
        }

        if ($this->session->userdata('customer_logged_in')) {
            redirect('customer/dashboard');
        }

        $this->load->view('customer/auth/login');
    }

    public function login_process() {
        $identity = $this->input->post('identity', true);
        if (!$identity) {
            $identity = $this->input->post('no_hp', true) ?: $this->input->post('username', true);
        }
        $identity = trim((string) $identity);
        $password = $this->input->post('password');

        if (!$identity || !$password) {
            $this->session->set_flashdata('error', 'Nomor HP/username dan password wajib diisi!');
            redirect('login');
        }

        $admin = $this->Admin_model->verify_login($identity, $password);
        if ($admin) {
            $this->session->set_userdata(array(
                'admin_logged_in' => TRUE,
                'admin_id' => $admin->id,
                'admin_username' => $admin->username,
                'admin_nama' => $admin->nama_lengkap,
                'admin_role' => $admin->role,
            ));

            redirect($admin->role === 'admin' ? 'admin/dashboard' : 'admin/pos');
        }

        $customer = $this->Customer_model->get_by_phone($identity);
        if (!$customer || empty($customer->password) || !password_verify($password, $customer->password)) {
            $this->session->set_flashdata('error', 'Nomor HP/username atau password salah!');
            redirect('login');
        }

        $this->session->set_userdata(array(
            'customer_logged_in' => TRUE,
            'customer_id' => $customer->id,
            'customer_nama' => $customer->nama,
            'customer_no_hp' => $customer->no_hp,
            'customer_segment' => $customer->segment,
            'customer_avatar' => $customer->foto,
        ));

        $current_cart = $this->session->userdata('shop_cart') ?: [];
        $saved_cart = !empty($customer->cart_data) ? json_decode($customer->cart_data, true) : [];
        if (is_array($saved_cart)) {
            foreach ($current_cart as $pid => $item) {
                $saved_cart[$pid] = $item;
            }
            if (!empty($saved_cart)) {
                $this->session->set_userdata('shop_cart', $saved_cart);
                $this->db->where('id', $customer->id);
                $this->db->update('customers', array('cart_data' => json_encode($saved_cart)));
            }
        }

        $this->session->set_flashdata('success', 'Selamat datang kembali, ' . $customer->nama . '!');
        redirect('customer/dashboard');
    }

    public function register_process() {
        $nama = $this->input->post('nama', true);
        $no_hp = $this->input->post('no_hp', true);
        $password = $this->input->post('password');
        $password_conf = $this->input->post('password_conf');

        if (!$nama || !$no_hp || !$password || !$password_conf) {
            $this->session->set_flashdata('error', 'Semua kolom registrasi wajib diisi!');
            redirect('customer/login');
        }

        if ($password !== $password_conf) {
            $this->session->set_flashdata('error', 'Konfirmasi password tidak cocok!');
            redirect('customer/login');
        }

        // Cek apakah No HP sudah ada
        $existing = $this->Customer_model->get_by_phone($no_hp);

        if ($existing) {
            // Jika ada dan tipe offline (dibuat kasir), kita perbarui menjadi akun Unified
            if (empty($existing->password)) {
                $this->Customer_model->set_password($existing->id, $password);
                $this->session->set_flashdata('success', 'Akun dari toko fisik Anda telah diaktifkan untuk Online! Silakan login.');
                redirect('customer/login');
            } else {
                $this->session->set_flashdata('error', 'Nomor HP sudah terdaftar dan aktif! Silakan login.');
                redirect('customer/login');
            }
        } else {
            // Buat akun baru
            $current_cart = $this->session->userdata('shop_cart') ?: [];
            $customer_data = array(
                'nama' => $nama,
                'no_hp' => $no_hp,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'tipe_customer' => 'Unified', // Unified means they can use both
                'segment' => 'Baru',
                'cart_data' => empty($current_cart) ? NULL : json_encode($current_cart)
            );
            $this->Customer_model->insert($customer_data);
            $this->session->set_flashdata('success', 'Registrasi berhasil! Silakan login untuk mulai belanja.');
            redirect('customer/login');
        }
    }

    public function logout() {
        $this->session->unset_userdata(array(
            'customer_logged_in', 'customer_id', 'customer_nama',
            'customer_no_hp', 'customer_segment', 'customer_avatar', 'customer_email'
        ));
        $this->session->sess_destroy();
        redirect('login');
    }
}