<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Product_model');
        $this->load->model('Category_model');
        $this->load->model('Feedback_model');
    }

    public function index() {
        $data['title'] = 'Nasaktion Fruit - Toko Buah Segar';
        $data['featured_products'] = $this->Product_model->get_featured(8);
        $data['categories'] = $this->Category_model->get_all();
        $data['testimonials'] = $this->Feedback_model->get_all();
        $data['google_login_url'] = '';
        
        // If Google OAuth is configured
        if ($this->session->userdata('customer_logged_in')) {
            $data['customer_nama'] = $this->session->userdata('customer_nama');
        }
        
        $this->load->view('home/index', $data);
    }
}