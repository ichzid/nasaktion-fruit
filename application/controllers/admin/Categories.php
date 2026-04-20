<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Categories extends AdminBaseController {

    public function __construct() {
        parent::__construct();
        $this->load->model('Category_model');
        $this->admin_only();
    }

    public function index() {
        $data['title'] = 'Kelola Kategori';
        $data['categories'] = $this->Category_model->get_all();
        $this->render_admin('admin/categories/index', $data);
    }

    public function create() {
        $this->form_validation->set_rules('nama_kategori', 'Nama Kategori', 'required|trim');
        $this->form_validation->set_rules('deskripsi', 'Deskripsi', 'trim');

        if ($this->form_validation->run() == FALSE) {
            $data['title'] = 'Tambah Kategori';
            $this->render_admin('admin/categories/form', $data);
        } else {
            $input = array(
                'nama_kategori' => $this->input->post('nama_kategori', TRUE),
                'deskripsi' => $this->input->post('deskripsi', TRUE),
            );
            if ($this->Category_model->insert($input)) {
                $this->session->set_flashdata('success', 'Kategori berhasil ditambahkan!');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan kategori!');
            }
            redirect('admin/categories');
        }
    }

    public function edit($id) {
        $data['category'] = $this->Category_model->get_by_id($id);
        if (!$data['category']) {
            $this->session->set_flashdata('error', 'Kategori tidak ditemukan!');
            redirect('admin/categories');
        }

        $this->form_validation->set_rules('nama_kategori', 'Nama Kategori', 'required|trim');

        if ($this->form_validation->run() == FALSE) {
            $data['title'] = 'Edit Kategori';
            $this->render_admin('admin/categories/form', $data);
        } else {
            $input = array(
                'nama_kategori' => $this->input->post('nama_kategori', TRUE),
                'deskripsi' => $this->input->post('deskripsi', TRUE),
            );
            if ($this->Category_model->update($id, $input)) {
                $this->session->set_flashdata('success', 'Kategori berhasil diperbarui!');
            } else {
                $this->session->set_flashdata('error', 'Gagal memperbarui kategori!');
            }
            redirect('admin/categories');
        }
    }

    public function delete($id) {
        if ($this->Category_model->delete($id)) {
            $this->session->set_flashdata('success', 'Kategori berhasil dihapus!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus kategori! Mungkin masih ada produk yang menggunakan kategori ini.');
        }
        redirect('admin/categories');
    }
}