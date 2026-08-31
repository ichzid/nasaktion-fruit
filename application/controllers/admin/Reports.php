<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends AdminBaseController {

    public function __construct() {
        parent::__construct();
        $this->owner_access();
        $this->load->model('Transaction_model');
        $this->load->model('Product_model');
    }

    public function index() {
        $this->sales();
    }

    public function sales() {
        list($start_date, $end_date) = $this->date_range();
        $data['title'] = 'Laporan Penjualan';
        $data['report_type'] = 'sales';
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $data['sales'] = $this->Transaction_model->get_sales_report($start_date, $end_date);
        $data['stock'] = array();
        $this->render_admin('admin/reports/index', $data);
    }

    public function stock() {
        $data['title'] = 'Laporan Stok';
        $data['report_type'] = 'stock';
        $data['start_date'] = '';
        $data['end_date'] = '';
        $data['sales'] = array();
        $data['stock'] = $this->Product_model->get_stock_report();
        $this->render_admin('admin/reports/index', $data);
    }

    public function export_sales() {
        list($start_date, $end_date) = $this->date_range();
        $rows = array(array('Invoice', 'Tanggal', 'Pelanggan', 'Admin/Kasir', 'Jenis', 'Status', 'Subtotal', 'Diskon', 'Total'));
        foreach ($this->Transaction_model->get_sales_report($start_date, $end_date) as $item) {
            $rows[] = array($item->invoice_no, $item->tgl, $item->nama ?: '-', $item->nama_lengkap ?: '-', $item->jenis_order, $item->status, $item->subtotal, $item->diskon_amount, $item->total);
        }
        $this->download_csv('laporan-penjualan-' . $start_date . '-' . $end_date . '.csv', $rows);
    }

    public function export_stock() {
        $rows = array(array('Produk', 'Kategori', 'Jenis', 'Stok', 'Satuan', 'Harga', 'Status', 'Terakhir Diperbarui'));
        foreach ($this->Product_model->get_stock_report() as $item) {
            $rows[] = array($item->nama_buah, $item->nama_kategori ?: '-', $item->jenis, $item->stok, $item->satuan, $item->harga, $item->is_active ? 'Aktif' : 'Nonaktif', $item->updated_at);
        }
        $this->download_csv('laporan-stok-' . date('Y-m-d') . '.csv', $rows);
    }

    private function date_range() {
        $start_date = $this->valid_date($this->input->get('start_date')) ?: date('Y-m-01');
        $end_date = $this->valid_date($this->input->get('end_date')) ?: date('Y-m-d');
        if ($start_date > $end_date) {
            $temp = $start_date;
            $start_date = $end_date;
            $end_date = $temp;
        }
        return array($start_date, $end_date);
    }

    private function valid_date($date) {
        $parsed = DateTime::createFromFormat('Y-m-d', (string) $date);
        return $parsed && $parsed->format('Y-m-d') === $date ? $date : null;
    }

    private function download_csv($filename, $rows) {
        $this->output->set_header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
        $this->output->set_header('Content-Disposition: attachment; filename="' . $filename . '"');
        $stream = fopen('php://temp', 'r+');
        fwrite($stream, "\xEF\xBB\xBF");
        foreach ($rows as $row) {
            fputcsv($stream, $row, ';');
        }
        rewind($stream);
        $content = stream_get_contents($stream);
        fclose($stream);
        $this->output->set_output($content);
    }
}
