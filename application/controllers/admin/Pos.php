<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pos extends AdminBaseController {

    public function __construct() {
        parent::__construct();
        $this->load->model('Product_model');
        $this->load->model('Transaction_model');
        $this->load->model('Customer_model');
        $this->load->model('Discount_model');
        $this->load->model('Category_model');
    }

    public function index() {
        $data['title'] = 'Kasir';
        $data['products'] = $this->Product_model->get_available();
        $data['categories'] = $this->Category_model->get_all();
        $this->render_admin('admin/pos/index', $data);
    }

    // AJAX: Search customer by name/phone
    public function search_customer() {
        $this->output->set_content_type('application/json');
        $keyword = $this->input->get('q', TRUE);
        if (!$keyword) {
            echo json_encode(['results' => []]);
            return;
        }
        $customers = $this->Customer_model->search($keyword);
        $results = [];
        foreach ($customers as $c) {
            $results[] = [
                'id'    => $c->id,
                'nama'  => $c->nama,
                'no_hp' => $c->no_hp,
                'segment' => $c->segment,
            ];
        }
        echo json_encode(['results' => $results]);
    }

    // AJAX: Register new walk-in customer
    public function register_customer() {
        $this->output->set_content_type('application/json');
        $nama  = $this->input->post('nama', TRUE);
        $no_hp = $this->input->post('no_hp', TRUE);
        if (!$nama) {
            echo json_encode(['success' => false, 'message' => 'Nama wajib diisi!']);
            return;
        }
        $customer = $this->Customer_model->auto_unify_offline($nama, $no_hp);
        if ($customer) {
            echo json_encode([
                'success' => true,
                'id'      => $customer->id,
                'nama'    => $customer->nama,
                'no_hp'   => $customer->no_hp,
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal mendaftarkan pelanggan!']);
        }
    }

    // AJAX: Apply voucher
    public function apply_voucher() {
        $this->output->set_content_type('application/json');
        $code  = $this->input->get('code', TRUE);
        $total = (float) $this->input->get('total');
        if (!$code) {
            echo json_encode(['valid' => false, 'message' => 'Kode voucher kosong!']);
            return;
        }
        $discount = $this->Discount_model->get_by_code($code);
        if (!$discount) {
            echo json_encode(['valid' => false, 'message' => 'Voucher tidak ditemukan!']);
            return;
        }
        if (!$discount->is_active) {
            echo json_encode(['valid' => false, 'message' => 'Voucher sudah tidak aktif!']);
            return;
        }
        if ($total < $discount->min_belanja) {
            echo json_encode(['valid' => false, 'message' => 'Minimum belanja Rp ' . number_format($discount->min_belanja, 0, ',', '.')]);
            return;
        }
        $disc_amount = $this->Discount_model->calculate_discount($discount->id, $total);
        echo json_encode([
            'valid'      => true,
            'discount'   => $disc_amount,
            'discount_id'=> $discount->id,
            'nama_promo' => $discount->nama_promo,
        ]);
    }

    // AJAX: Process transaction
    public function process() {
        $this->output->set_content_type('application/json');

        // Parse items from POST
        $post_items = $this->input->post('items');
        $cart = [];
        if ($post_items) {
            $parsed = json_decode($post_items, true);
            if (is_array($parsed)) {
                foreach ($parsed as $item) {
                    $product = $this->Product_model->get_by_id($item['product_id']);
                    if (!$product) continue;
                    if ($product->stok < $item['qty']) {
                        echo json_encode(['success' => false, 'message' => "Stok {$product->nama_buah} tidak mencukupi! Sisa: {$product->stok}"]);
                        return;
                    }
                    $cart[] = [
                        'product_id' => (int)$product->id,
                        'nama_buah'  => $product->nama_buah,
                        'qty'        => (int)$item['qty'],
                        'harga'      => (float)$item['harga'],
                        'subtotal'   => (int)$item['qty'] * (float)$item['harga'],
                    ];
                }
            }
        }

        if (empty($cart)) {
            echo json_encode(['success' => false, 'message' => 'Keranjang kosong!']);
            return;
        }

        // Totals
        $subtotal = 0;
        foreach ($cart as $item) $subtotal += $item['subtotal'];

        // Discount
        $discount_id  = (int)$this->input->post('discount_id') ?: null;
        $diskon_amount= (float)$this->input->post('discount') ?: 0;
        $total = $subtotal - $diskon_amount;
        if ($total < 0) $total = 0;

        // Customer from POST (not session — cart is client-side)
        $customer_id_post = $this->input->post('customer_id');
        $pos_customer = $customer_id_post ? $this->Customer_model->get_by_id($customer_id_post) : null;

        // Generate invoice
        $invoice_no = $this->Transaction_model->generate_invoice();

        // Build transaction
        $transaction_data = [
            'invoice_no'    => $invoice_no,
            'customer_id'   => $pos_customer ? $pos_customer->id : null,
            'admin_id'      => $this->admin_data['admin_id'],
            'discount_id'   => $discount_id,
            'tgl'           => date('Y-m-d H:i:s'),
            'subtotal'      => $subtotal,
            'diskon_amount' => $diskon_amount,
            'total'         => $total,
            'jenis_order'   => 'Offline',
            'status'        => 'completed',
            'catatan'       => $this->input->post('catatan', TRUE),
        ];

        $this->db->trans_start();

        $this->Transaction_model->insert($transaction_data);
        $transaction_id = $this->db->insert_id();

        // Items & stock
        $items_data = [];
        foreach ($cart as $item) {
            $items_data[] = [
                'transaction_id' => $transaction_id,
                'product_id'     => $item['product_id'],
                'qty'            => $item['qty'],
                'harga'          => $item['harga'],
                'subtotal'       => $item['subtotal'],
            ];
            $this->Product_model->reduce_stock($item['product_id'], $item['qty']);
        }
        $this->Transaction_model->insert_items($items_data);

        // Loyalty
        if ($pos_customer) {
            $this->Customer_model->update_loyalty($pos_customer->id, $total);
        }

        $this->db->trans_complete();

        if ($this->db->trans_status()) {
            $receipt_items = [];
            foreach ($cart as $item) {
                $receipt_items[] = [
                    'nama'     => $item['nama_buah'],
                    'qty'      => $item['qty'],
                    'harga'    => (int)$item['harga'],
                    'subtotal' => (int)$item['subtotal'],
                ];
            }
            echo json_encode([
                'success'        => true,
                'invoice'        => $invoice_no,
                'tanggal'        => date('d M Y H:i:s'),
                'items'          => $receipt_items,
                'subtotal'       => (int)$subtotal,
                'diskon'         => (int)$diskon_amount,
                'total'          => (int)$total,
                'transaction_id' => $transaction_id,
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Gagal menyimpan transaksi ke database!']);
        }
    }

    public function print_receipt($id) {
        $data['transaction'] = $this->Transaction_model->get_by_id($id);
        $data['items'] = $this->Transaction_model->get_items($id);
        if(!$data['transaction']) {
            show_404();
            return;
        }
        $this->load->view('admin/pos/receipt', $data);
    }

    public function clear_cart() {
        redirect('admin/pos');
    }
}