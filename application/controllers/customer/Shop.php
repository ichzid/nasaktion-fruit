<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shop extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Product_model');
        $this->load->model('Category_model');
        $this->load->model('Feedback_model');
    }

    public function index() {
        $data['title'] = 'Belanja Buah Segar';
        $data['products'] = $this->Product_model->get_available();
        $data['categories'] = $this->Category_model->get_all();
        $data['featured'] = $this->Product_model->get_featured(8);

        // Filter by category
        $category_id = $this->input->get('category');
        if ($category_id) {
            $data['products'] = $this->Product_model->get_by_category($category_id);
        }

        // Filter by type
        $jenis = $this->input->get('jenis');
        if ($jenis) {
            $data['products'] = $this->Product_model->get_by_jenis($jenis);
        }

        // Search
        $search = $this->input->get('search', TRUE);
        if ($search) {
            $data['products'] = $this->Product_model->search($search);
        }

        $this->load->view('customer/shop/index', $data);
    }

    public function product($id) {
        $data['product'] = $this->Product_model->get_by_id($id);
        if (!$data['product'] || !$data['product']->is_active) {
            show_404();
        }
        $data['title'] = $data['product']->nama_buah;
        $data['related'] = $this->Product_model->get_related($data['product']->category_id, $id, 4);
        $data['reviews'] = $this->Feedback_model->get_by_product($id);
        $data['avg_rating'] = $this->Feedback_model->get_product_avg_rating($id);
        $this->load->view('customer/shop/product', $data);
    }
}