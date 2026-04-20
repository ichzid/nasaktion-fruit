<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Crm extends AdminBaseController {

    public function __construct() {
        parent::__construct();
        $this->load->model('Customer_model');
        $this->load->model('Transaction_model');
        $this->load->model('Feedback_model');
        $this->load->model('Discount_model');
    }

    public function index() {
        $data['title'] = 'CRM Dashboard';
        $data['total_customers'] = $this->Customer_model->count_all();
        $data['segment_baru'] = $this->Customer_model->count_by_segment('Baru');
        $data['segment_silver'] = $this->Customer_model->count_by_segment('Silver');
        $data['segment_gold'] = $this->Customer_model->count_by_segment('Gold');
        $data['segment_platinum'] = $this->Customer_model->count_by_segment('Platinum');
        $data['passive_customers'] = $this->Customer_model->get_passive_customers();
        $data['avg_rating'] = $this->Feedback_model->get_average_rating();
        $data['total_feedback'] = $this->Feedback_model->count_all();
        $data['active_vouchers'] = $this->Discount_model->count_active();
        $this->render_admin('admin/crm/index', $data);
    }

    public function loyalty() {
        $data['title'] = 'Analisis Loyalitas Pelanggan';
        $data['customers'] = $this->Customer_model->get_all_with_stats();
        $data['segment_baru'] = $this->Customer_model->count_by_segment('Baru');
        $data['segment_silver'] = $this->Customer_model->count_by_segment('Silver');
        $data['segment_gold'] = $this->Customer_model->count_by_segment('Gold');
        $data['segment_platinum'] = $this->Customer_model->count_by_segment('Platinum');
        $this->render_admin('admin/crm/loyalty', $data);
    }

    public function passive_customers() {
        $data['title'] = 'Pelanggan Pasif';
        $data['passive_customers'] = $this->Customer_model->get_passive_customers();
        $this->render_admin('admin/crm/passive', $data);
    }

    public function send_promo($customer_id) {
        $customer = $this->Customer_model->get_by_id($customer_id);
        if (!$customer) {
            $this->session->set_flashdata('error', 'Pelanggan tidak ditemukan!');
            redirect('admin/crm/passive_customers');
        }

        // Create a special promo voucher for this customer
        $voucher_code = 'PROMO' . strtoupper(substr(md5($customer_id . time()), 0, 6));
        $discount_data = array(
            'kode_voucher' => $voucher_code,
            'nama_promo' => 'Promo Khusus - ' . $customer->nama,
            'tipe' => 'persen',
            'nilai' => 15,
            'min_belanja' => 50000,
            'target' => 'semua',
            'segment_target' => $customer->segment,
            'tanggal_mulai' => date('Y-m-d'),
            'tanggal_selesai' => date('Y-m-d', strtotime('+30 days')),
            'is_active' => 1,
        );

        if ($this->Discount_model->insert($discount_data)) {
            $this->session->set_flashdata('success', "Voucher promo {$voucher_code} berhasil dibuat untuk {$customer->nama}! Bagikan kode voucher ini kepada pelanggan.");
        } else {
            $this->session->set_flashdata('error', 'Gagal membuat voucher promo!');
        }
        redirect('admin/crm/passive_customers');
    }

    public function recommendations($customer_id) {
        $data['title'] = 'Rekomendasi Produk';
        $data['customer'] = $this->Customer_model->get_by_id($customer_id);
        $data['recommendations'] = $this->Customer_model->get_recommendations($customer_id, 10);
        $data['purchase_history'] = $this->Transaction_model->get_by_customer($customer_id);
        $this->render_admin('admin/crm/recommendations', $data);
    }

    public function segments() {
        $data['title'] = 'Segmen Pelanggan';
        $data['baru'] = $this->Customer_model->get_by_segment('Baru');
        $data['silver'] = $this->Customer_model->get_by_segment('Silver');
        $data['gold'] = $this->Customer_model->get_by_segment('Gold');
        $data['platinum'] = $this->Customer_model->get_by_segment('Platinum');
        $this->render_admin('admin/crm/segments', $data);
    }
}