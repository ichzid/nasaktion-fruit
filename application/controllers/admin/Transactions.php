<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transactions extends AdminBaseController {

    public function __construct() {
        parent::__construct();
        $this->load->model('Transaction_model');
        $this->load->model('Product_model');
    }

    public function index() {
        $data['title'] = 'Riwayat Transaksi';
        $data['transactions'] = $this->Transaction_model->get_all();
        $this->render_admin('admin/transactions/index', $data);
    }

    public function view($id) {
        $data['title'] = 'Detail Transaksi';
        $data['transaction'] = $this->Transaction_model->get_by_id($id);
        $data['items'] = $this->Transaction_model->get_items($id);
        if (!$data['transaction']) {
            $this->session->set_flashdata('error', 'Transaksi tidak ditemukan!');
            redirect('admin/transactions');
        }
        $this->render_admin('admin/transactions/view', $data);
    }

    public function verify($id) {
        $transaction = $this->Transaction_model->get_by_id($id);
        if (!$transaction) {
            $this->session->set_flashdata('error', 'Transaksi tidak ditemukan!');
            redirect('admin/transactions');
        }

        if ($transaction->status !== 'paid') {
            $this->session->set_flashdata('error', 'Hanya transaksi berstatus paid yang bisa diverifikasi!');
            redirect('admin/transactions/view/' . $id);
        }

        // Verify and reduce stock
        $items = $this->Transaction_model->get_items($id);
        $this->db->trans_start();
        foreach ($items as $item) {
            $this->Product_model->reduce_stock($item->product_id, $item->qty);
        }
        $this->Transaction_model->update($id, array('status' => 'verified'));
        $this->db->trans_complete();

        if ($this->db->trans_status()) {
            $this->session->set_flashdata('success', 'Transaksi berhasil diverifikasi!');
        } else {
            $this->session->set_flashdata('error', 'Gagal verifikasi transaksi!');
        }
        redirect('admin/transactions/view/' . $id);
    }

    public function cancel($id) {
        $transaction = $this->Transaction_model->get_by_id($id);
        if (!$transaction) {
            $this->session->set_flashdata('error', 'Transaksi tidak ditemukan!');
            redirect('admin/transactions');
        }

        // Restore stock if was verified
        if ($transaction->status === 'verified' || $transaction->status === 'paid') {
            $items = $this->Transaction_model->get_items($id);
            foreach ($items as $item) {
                $this->Product_model->restore_stock($item->product_id, $item->qty);
            }
        }

        $this->Transaction_model->update($id, array('status' => 'cancelled'));
        $this->session->set_flashdata('success', 'Transaksi berhasil dibatalkan!');
        redirect('admin/transactions');
    }

    public function print_receipt($id) {
        $data['transaction'] = $this->Transaction_model->get_by_id($id);
        $data['items'] = $this->Transaction_model->get_items($id);
        $this->load->view('admin/pos/receipt', $data);
    }
}