<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CustomerBaseController {

    public function __construct() {
        parent::__construct();
        $this->load->model('Transaction_model');
        $this->load->model('Customer_model');
        $this->load->model('Discount_model');
        $this->load->model('Feedback_model');
    }

    public function index() {
        $customer_id = $this->customer_data['customer_id'];
        
        $data['title'] = 'Dashboard Saya';
        $data['customer'] = $this->Customer_model->get_by_id($customer_id);
        $data['recent_orders'] = $this->Transaction_model->get_by_customer($customer_id, 5);
        $data['recommendations'] = $this->Customer_model->get_recommendations($customer_id, 4);
        $data['available_vouchers'] = $this->Discount_model->get_for_customer(
            $this->customer_data['segment'], 'Online'
        );
        $data['total_spent'] = $this->Transaction_model->get_customer_total_spent($customer_id);
        $data['total_orders'] = $this->Transaction_model->count_by_customer($customer_id);
        $data['my_feedback'] = $this->Feedback_model->get_by_customer($customer_id);

        $this->render_customer('customer/dashboard/index', $data);
    }

    public function profile() {
        $customer_id = $this->customer_data['customer_id'];
        $data['title'] = 'Profil Saya';
        $data['customer'] = $this->Customer_model->get_by_id($customer_id);

        $this->form_validation->set_rules('nama', 'Nama', 'required|trim');
        $this->form_validation->set_rules('no_hp', 'No. HP', 'trim');

        if ($this->form_validation->run() == FALSE) {
            $this->render_customer('customer/dashboard/profile', $data);
        } else {
            $input = array(
                'nama' => $this->input->post('nama', TRUE),
                'no_hp' => $this->input->post('no_hp', TRUE),
                'alamat' => $this->input->post('alamat', TRUE),
            );
            if ($this->Customer_model->update($customer_id, $input)) {
                $this->session->set_userdata('customer_nama', $input['nama']);
                $this->session->set_flashdata('success', 'Profil berhasil diperbarui!');
            } else {
                $this->session->set_flashdata('error', 'Gagal memperbarui profil!');
            }
            redirect('customer/dashboard/profile');
        }
    }

    public function orders() {
        $customer_id = $this->customer_data['customer_id'];
        $data['title'] = 'Pesanan Saya';
        $data['transactions'] = $this->Transaction_model->get_by_customer($customer_id);
        $this->render_customer('customer/dashboard/orders', $data);
    }

    public function order_detail($id) {
        $customer_id = $this->customer_data['customer_id'];
        $data['title'] = 'Detail Pesanan';
        $data['transaction'] = $this->Transaction_model->get_by_id($id);
        $data['items'] = $this->Transaction_model->get_items($id);

        if (!$data['transaction'] || $data['transaction']->customer_id != $customer_id) {
            $this->session->set_flashdata('error', 'Pesanan tidak ditemukan!');
            redirect('customer/dashboard/orders');
        }
        $this->render_customer('customer/dashboard/order_detail', $data);
    }

    public function upload_payment($id) {
        $customer_id = $this->customer_data['customer_id'];
        $transaction = $this->Transaction_model->get_by_id($id);

        if (!$transaction || $transaction->customer_id != $customer_id) {
            $this->session->set_flashdata('error', 'Pesanan tidak ditemukan!');
            redirect('customer/dashboard/orders');
        }

        if ($transaction->status !== 'pending') {
            $this->session->set_flashdata('error', 'Pesanan ini tidak bisa diupload bukti pembayaran!');
            redirect('customer/dashboard/order_detail/' . $id);
        }

        if (!empty($_FILES['bukti_transfer']['name'])) {
            $config = array(
                'upload_path' => './uploads/payments/',
                'allowed_types' => 'gif|jpg|jpeg|png|webp',
                'max_size' => 2048,
                'encrypt_name' => TRUE,
            );
            $this->upload->initialize($config);

            if ($this->upload->do_upload('bukti_transfer')) {
                $file_name = $this->upload->data('file_name');
                $this->Transaction_model->update($id, array(
                    'bukti_transfer' => $file_name,
                    'status' => 'paid',
                ));
                $this->session->set_flashdata('success', 'Bukti pembayaran berhasil diupload! Menunggu verifikasi.');
            } else {
                $this->session->set_flashdata('error', $this->upload->display_errors());
            }
        } else {
            $this->session->set_flashdata('error', 'Pilih file terlebih dahulu!');
        }
        redirect('customer/dashboard/order_detail/' . $id);
    }
}