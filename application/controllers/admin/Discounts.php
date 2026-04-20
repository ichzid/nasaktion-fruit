<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Discounts extends AdminBaseController {

    public function __construct() {
        parent::__construct();
        $this->load->model('Discount_model');
        $this->admin_only();
    }

    public function index() {
        $data['title'] = 'Kelola Diskon & Voucher';
        $data['discounts'] = $this->Discount_model->get_all();
        $data['active_count'] = $this->Discount_model->count_active();
        $this->render_admin('admin/discounts/index', $data);
    }

    public function create() {
        if ($this->input->post('nama_promo')) {
            $input = array(
                'kode_voucher'   => strtoupper($this->input->post('kode_voucher', TRUE) ?: substr(md5(time()), 0, 8)),
                'nama_promo'     => $this->input->post('nama_promo', TRUE),
                'tipe'           => $this->input->post('tipe'),
                'nilai'          => $this->input->post('nilai'),
                'min_belanja'    => $this->input->post('min_belanja') ?: 0,
                'target'         => 'semua',
                'tanggal_mulai'  => $this->input->post('tanggal_mulai'),
                'tanggal_selesai'=> $this->input->post('tanggal_selesai'),
                'max_pemakaian'  => $this->input->post('max_pemakaian') ?: null,
                'created_at'     => date('Y-m-d H:i:s'),
                'is_active'      => 1,
            );
            if ($this->Discount_model->insert($input)) {
                $this->session->set_flashdata('success', 'Voucher berhasil ditambahkan!');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan voucher!');
            }
        }
        redirect('admin/discounts');
    }

    public function toggle($id) {
        $discount = $this->Discount_model->get_by_id($id);
        if (!$discount) {
            $this->session->set_flashdata('error', 'Voucher tidak ditemukan!');
            redirect('admin/discounts');
        }
        $new_status = $discount->is_active ? 0 : 1;
        $this->Discount_model->update($id, ['is_active' => $new_status]);
        $this->session->set_flashdata('success', 'Status voucher berhasil diperbarui!');
        redirect('admin/discounts');
    }

    public function delete($id) {
        if ($this->Discount_model->delete($id)) {
            $this->session->set_flashdata('success', 'Diskon berhasil dihapus!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus diskon!');
        }
        redirect('admin/discounts');
    }
}