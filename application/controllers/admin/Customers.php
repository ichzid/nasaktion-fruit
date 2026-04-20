<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customers extends AdminBaseController {

    public function __construct() {
        parent::__construct();
        $this->load->model('Customer_model');
        $this->load->model('Transaction_model');
    }

    public function index() {
        $data['title'] = 'Kelola Pelanggan';
        $data['customers'] = $this->Customer_model->get_all();
        $data['total_baru'] = $this->Customer_model->count_by_segment('Baru');
        $data['total_silver'] = $this->Customer_model->count_by_segment('Silver');
        $data['total_gold'] = $this->Customer_model->count_by_segment('Gold');
        $data['total_platinum'] = $this->Customer_model->count_by_segment('Platinum');
        $this->render_admin('admin/customers/index', $data);
    }

    public function view($id) {
        $data['title'] = 'Detail Pelanggan';
        $data['customer'] = $this->Customer_model->get_by_id($id);
        if (!$data['customer']) {
            $this->session->set_flashdata('error', 'Pelanggan tidak ditemukan!');
            redirect('admin/customers');
        }
        $data['transactions'] = $this->Transaction_model->get_by_customer($id);
        $data['recommendations'] = $this->Customer_model->get_recommendations($id, 4);
        $this->render_admin('admin/customers/view', $data);
    }

    public function edit($id) {
        $data['customer'] = $this->Customer_model->get_by_id($id);
        if (!$data['customer']) {
            $this->session->set_flashdata('error', 'Pelanggan tidak ditemukan!');
            redirect('admin/customers');
        }

        $this->form_validation->set_rules('nama', 'Nama', 'required|trim');
        $this->form_validation->set_rules('no_hp', 'No. HP', 'trim');

        if ($this->form_validation->run() == FALSE) {
            $data['title'] = 'Edit Pelanggan';
            $this->render_admin('admin/customers/form', $data);
        } else {
            $input = array(
                'nama' => $this->input->post('nama', TRUE),
                'no_hp' => $this->input->post('no_hp', TRUE),
                'alamat' => $this->input->post('alamat', TRUE),
            );
            if ($this->Customer_model->update($id, $input)) {
                $this->session->set_flashdata('success', 'Data pelanggan berhasil diperbarui!');
            } else {
                $this->session->set_flashdata('error', 'Gagal memperbarui data pelanggan!');
            }
            redirect('admin/customers');
        }
    }

    public function delete($id) {
        if ($this->Customer_model->delete($id)) {
            $this->session->set_flashdata('success', 'Pelanggan berhasil dihapus!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus pelanggan!');
        }
        redirect('admin/customers');
    }

    public function search() {
        $keyword = $this->input->get('q', TRUE);
        $customers = $this->Customer_model->search($keyword);
        echo json_encode($customers);
    }

    /**
     * Find potential duplicate customers (same name/phone/email)
     */
    public function find_duplicates() {
        $data['title'] = 'Deteksi Pelanggan Duplikat';
        $data['duplicates'] = $this->Customer_model->find_potential_duplicates();
        $this->render_admin('admin/customers/duplicates', $data);
    }

    /**
     * Merge two customer records
     */
    public function merge() {
        $primary_id = $this->input->post('primary_id');
        $secondary_id = $this->input->post('secondary_id');

        if (!$primary_id || !$secondary_id || $primary_id == $secondary_id) {
            $this->session->set_flashdata('error', 'Data tidak valid untuk penggabungan!');
            redirect('admin/customers/find_duplicates');
        }

        if ($this->Customer_model->merge_customers($primary_id, $secondary_id)) {
            $this->session->set_flashdata('success', 'Pelanggan berhasil digabungkan! Data transaksi, poin, dan riwayat telah disatukan.');
        } else {
            $this->session->set_flashdata('error', 'Gagal menggabungkan pelanggan!');
        }
        redirect('admin/customers/find_duplicates');
    }
}
