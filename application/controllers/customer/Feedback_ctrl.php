<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Feedback_ctrl extends CustomerBaseController {

    public function __construct() {
        parent::__construct();
        $this->load->model('Feedback_model');
        $this->load->model('Transaction_model');
    }

    public function create() {
        $data['title'] = 'Beri Ulasan';
        $data['transaction_id'] = $this->input->get('transaction_id');
        $data['product_id'] = $this->input->get('product_id');

        $this->form_validation->set_rules('isi_ulasan', 'Ulasan', 'required|trim');
        $this->form_validation->set_rules('rating', 'Rating', 'required|numeric|greater_than[0]|less_than[6]');

        if ($this->form_validation->run() == FALSE) {
            $this->render_customer('customer/feedback/form', $data);
        } else {
            $feedback_data = array(
                'customer_id' => $this->customer_data['customer_id'],
                'product_id' => $this->input->post('product_id') ?: null,
                'transaction_id' => $this->input->post('transaction_id') ?: null,
                'isi_ulasan' => $this->input->post('isi_ulasan', TRUE),
                'rating' => $this->input->post('rating'),
                'is_read' => 0,
            );

            if ($this->Feedback_model->insert($feedback_data)) {
                $this->session->set_flashdata('success', 'Terima kasih! Ulasan Anda berhasil dikirim.');
            } else {
                $this->session->set_flashdata('error', 'Gagal mengirim ulasan!');
            }
            redirect('customer/dashboard');
        }
    }

    public function my_feedback() {
        $customer_id = $this->customer_data['customer_id'];
        $data['title'] = 'Ulasan Saya';
        $data['feedbacks'] = $this->Feedback_model->get_by_customer($customer_id);
        $this->render_customer('customer/feedback/list', $data);
    }
}