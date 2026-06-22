<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends AdminBaseController {

    public function __construct() {
        parent::__construct();
        $this->admin_only();
        $this->load->model('Product_model');
        $this->load->model('Category_model');
    }

    public function index() {
        $data['title'] = 'Kelola Produk';
        $data['products'] = $this->Product_model->get_all();
        $data['low_stock'] = $this->Product_model->get_low_stock();
        $this->render_admin('admin/products/index', $data);
    }

    public function create() {
        $data['title'] = 'Tambah Produk';
        $data['categories'] = $this->Category_model->get_all();

        $this->form_validation->set_rules('nama_buah', 'Nama Buah', 'required|trim');
        $this->form_validation->set_rules('category_id', 'Kategori', 'required');
        $this->form_validation->set_rules('jenis', 'Jenis', 'required');
        $this->form_validation->set_rules('harga_beli', 'Harga Beli', 'required|numeric');
        $this->form_validation->set_rules('harga_jual', 'Harga Jual', 'required|numeric');
        $this->form_validation->set_rules('stok', 'Stok', 'required|numeric');

        if ($this->form_validation->run() == FALSE) {
            $this->render_admin('admin/products/form', $data);
        } else {
            $input = array(
                'nama_buah' => $this->input->post('nama_buah', TRUE),
                'category_id' => $this->input->post('category_id'),
                'jenis' => $this->input->post('jenis'),
                'deskripsi' => $this->input->post('deskripsi', TRUE),
                'harga_beli' => $this->input->post('harga_beli'),
                'harga_jual' => $this->input->post('harga_jual'),
                'stok' => $this->input->post('stok'),
                'satuan' => $this->input->post('satuan', TRUE) ?: 'kg',
                'is_active' => 1,
            );

            // Handle photo upload
            if (!empty($_FILES['foto']['name'])) {
                $upload = $this->_do_upload('foto');
                if ($upload) {
                    $input['foto'] = $upload;
                }
            }

            if ($this->Product_model->insert($input)) {
                $this->session->set_flashdata('success', 'Produk berhasil ditambahkan!');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan produk!');
            }
            redirect('admin/products');
        }
    }

    public function edit($id) {
        $data['title'] = 'Edit Produk';
        $data['product'] = $this->Product_model->get_by_id($id);
        $data['categories'] = $this->Category_model->get_all();

        if (!$data['product']) {
            $this->session->set_flashdata('error', 'Produk tidak ditemukan!');
            redirect('admin/products');
        }

        $this->form_validation->set_rules('nama_buah', 'Nama Buah', 'required|trim');
        $this->form_validation->set_rules('category_id', 'Kategori', 'required');
        $this->form_validation->set_rules('jenis', 'Jenis', 'required');
        $this->form_validation->set_rules('harga_beli', 'Harga Beli', 'required|numeric');
        $this->form_validation->set_rules('harga_jual', 'Harga Jual', 'required|numeric');
        $this->form_validation->set_rules('stok', 'Stok', 'required|numeric');

        if ($this->form_validation->run() == FALSE) {
            $this->render_admin('admin/products/form', $data);
        } else {
            $input = array(
                'nama_buah' => $this->input->post('nama_buah', TRUE),
                'category_id' => $this->input->post('category_id'),
                'jenis' => $this->input->post('jenis'),
                'deskripsi' => $this->input->post('deskripsi', TRUE),
                'harga_beli' => $this->input->post('harga_beli'),
                'harga_jual' => $this->input->post('harga_jual'),
                'stok' => $this->input->post('stok'),
                'satuan' => $this->input->post('satuan', TRUE) ?: 'kg',
                'is_active' => $this->input->post('is_active') ? 1 : 0,
            );

            // Handle photo upload
            if (!empty($_FILES['foto']['name'])) {
                $upload = $this->_do_upload('foto');
                if ($upload) {
                    // Delete old photo
                    if ($data['product']->foto && file_exists('./uploads/products/' . $data['product']->foto)) {
                        unlink('./uploads/products/' . $data['product']->foto);
                    }
                    $input['foto'] = $upload;
                }
            }

            if ($this->Product_model->update($id, $input)) {
                $this->session->set_flashdata('success', 'Produk berhasil diperbarui!');
            } else {
                $this->session->set_flashdata('error', 'Gagal memperbarui produk!');
            }
            redirect('admin/products');
        }
    }

    public function delete($id) {
        $product = $this->Product_model->get_by_id($id);
        if ($product && $product->foto && file_exists('./uploads/products/' . $product->foto)) {
            unlink('./uploads/products/' . $product->foto);
        }
        if ($this->Product_model->delete($id)) {
            $this->session->set_flashdata('success', 'Produk berhasil dihapus!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus produk!');
        }
        redirect('admin/products');
    }

    private function _do_upload($field) {
        $config = array(
            'upload_path' => './uploads/products/',
            'allowed_types' => 'gif|jpg|jpeg|png|webp',
            'max_size' => 2048,
            'encrypt_name' => TRUE,
        );
        $this->upload->initialize($config);

        if ($this->upload->do_upload($field)) {
            return $this->upload->data('file_name');
        }
        return false;
    }
}