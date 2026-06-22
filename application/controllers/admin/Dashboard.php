<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends AdminBaseController {

    public function __construct() {
        parent::__construct();
        $this->admin_only();
        $this->load->model('Transaction_model');
        $this->load->model('Product_model');
        $this->load->model('Customer_model');
        $this->load->model('Feedback_model');
    }

    public function index() {
        $data['title'] = 'Dashboard';
        $data['total_transactions'] = $this->Transaction_model->count_all();
        $data['total_products'] = $this->Product_model->count_active();
        $data['total_customers'] = $this->Customer_model->count_all();
        $data['total_feedback'] = $this->Feedback_model->count_all();
        $data['pending_orders'] = $this->Transaction_model->count_pending();
        $data['unread_feedback'] = $this->Feedback_model->count_unread();
        $data['today_sales'] = $this->Transaction_model->get_today_sales();
        $data['month_sales'] = $this->Transaction_model->get_month_sales();
        $data['low_stock_products'] = $this->Product_model->get_low_stock(10);
        $data['recent_transactions'] = $this->Transaction_model->get_recent(5);
        $data['top_products'] = $this->Transaction_model->get_top_products(5);
        $data['sales_chart'] = $this->Transaction_model->get_sales_chart_data(7);
        $data['avg_rating'] = $this->Feedback_model->get_average_rating();

        // Customer segments
        $data['segment_baru'] = $this->Customer_model->count_by_segment('Baru');
        $data['segment_silver'] = $this->Customer_model->count_by_segment('Silver');
        $data['segment_gold'] = $this->Customer_model->count_by_segment('Gold');
        $data['segment_platinum'] = $this->Customer_model->count_by_segment('Platinum');

        $this->render_admin('admin/dashboard/index', $data);
    }
}