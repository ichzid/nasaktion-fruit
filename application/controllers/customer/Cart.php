<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cart extends CustomerBaseController {

    public function __construct() {
        parent::__construct();
        $this->load->model('Product_model');
        $this->load->model('Transaction_model');
        $this->load->model('Customer_model');
        $this->load->model('Discount_model');
    }

    private function _sync_cart_to_db($cart = array()) {
        if ($this->session->userdata('customer_id')) {
            $this->db->where('id', $this->session->userdata('customer_id'));
            $this->db->update('customers', ['cart_data' => empty($cart) ? NULL : json_encode($cart)]);
        }
    }

    private function _update_error($message) {
        if ($this->input->is_ajax_request() || $this->input->post('product_id')) {
            echo json_encode(array('success' => false, 'message' => $message));
            return;
        }

        $this->session->set_flashdata('error', $message);
        redirect('customer/cart');
    }

    public function index() {
        $data['title'] = 'Keranjang Belanja';
        $cart = $this->session->userdata('shop_cart') ? $this->session->userdata('shop_cart') : array();
        
        $cart_items = array();
        $subtotal = 0;
        foreach($cart as $c) {
            $item = (object)$c;
            $item->id = $item->product_id;
            $item->harga_jual = $item->harga;
            $cart_items[] = $item;
            $subtotal += $item->subtotal;
        }

        $discount = 0;
        $total = $subtotal - $discount;

        $data['cart_items'] = $cart_items;
        $data['subtotal'] = $subtotal;
        $data['discount'] = $discount;
        $data['total'] = $total;

        $data['vouchers'] = $this->Discount_model->get_for_customer(
            $this->customer_data['segment'], 'Online'
        );
        $this->render_customer('customer/cart/index', $data);
    }

    public function add() {
        $product_id = $this->input->post('product_id');
        $qty = (int)$this->input->post('qty') ?: 1;

        $product = $this->Product_model->get_by_id($product_id);
        if (!$product || !$product->is_active) {
            echo json_encode(array('success' => false, 'message' => 'Produk tidak tersedia'));
            return;
        }

        if ($product->stok < $qty) {
            echo json_encode(array('success' => false, 'message' => 'Stok tidak mencukupi! Tersisa ' . $product->stok . ' ' . $product->satuan));
            return;
        }

        $cart = $this->session->userdata('shop_cart') ? $this->session->userdata('shop_cart') : array();

        if (isset($cart[$product_id])) {
            $new_qty = $cart[$product_id]['qty'] + $qty;
            if ($new_qty > $product->stok) {
                echo json_encode(array('success' => false, 'message' => 'Stok tidak mencukupi!'));
                return;
            }
            $cart[$product_id]['qty'] = $new_qty;
            $cart[$product_id]['subtotal'] = $new_qty * $cart[$product_id]['harga'];
        } else {
            $cart[$product_id] = array(
                'product_id' => $product_id,
                'nama_buah' => $product->nama_buah,
                'harga' => $product->harga_jual,
                'qty' => $qty,
                'subtotal' => $qty * $product->harga_jual,
                'foto' => $product->foto,
                'satuan' => $product->satuan,
                'stok' => $product->stok,
            );
        }

        $this->session->set_userdata('shop_cart', $cart);
        $this->_sync_cart_to_db($cart);
        $cart_count = array_sum(array_column($cart, 'qty'));
        echo json_encode(array('success' => true, 'cart_count' => $cart_count, 'message' => $product->nama_buah . ' ditambahkan ke keranjang!'));
    }

    public function update($product_id = null, $qty = null) {
        if ($product_id === null) $product_id = $this->input->post('product_id');
        if ($qty === null) $qty = (int)$this->input->post('qty');
        
        $cart = $this->session->userdata('shop_cart') ? $this->session->userdata('shop_cart') : array();

        if (isset($cart[$product_id]) && $qty > 0) {
            $product = $this->Product_model->get_by_id($product_id);
            if (!$product || !$product->is_active) {
                $this->_update_error('Produk tidak tersedia.');
                return;
            }

            if ($qty > $product->stok) {
                $this->_update_error('Kuantitas melebihi stok. Stok tersedia: ' . $product->stok . ' ' . $product->satuan . '.');
                return;
            }

            $cart[$product_id]['qty'] = $qty;
            $cart[$product_id]['stok'] = $product->stok;
            $cart[$product_id]['subtotal'] = $qty * $cart[$product_id]['harga'];
        } elseif ($qty <= 0) {
            unset($cart[$product_id]);
        }

        $this->session->set_userdata('shop_cart', $cart);
        $this->_sync_cart_to_db($cart);

        if ($this->input->is_ajax_request() || $this->input->post('product_id')) {
            echo json_encode(array('success' => true));
        } else {
            redirect('customer/cart');
        }
    }

    public function clear() {
        $this->session->unset_userdata('shop_cart');
        $this->_sync_cart_to_db();
        $this->session->set_flashdata('success', 'Keranjang berhasil dikosongkan!');
        redirect('customer/cart');
    }

    public function remove($product_id) {
        $cart = $this->session->userdata('shop_cart') ? $this->session->userdata('shop_cart') : array();
        unset($cart[$product_id]);
        $this->session->set_userdata('shop_cart', $cart);
        $this->_sync_cart_to_db($cart);
        $this->session->set_flashdata('success', 'Produk dihapus dari keranjang!');
        redirect('customer/cart');
    }

    public function checkout() {
        $cart = $this->session->userdata('shop_cart');
        if (empty($cart)) {
            $this->session->set_flashdata('error', 'Keranjang belanja kosong!');
            redirect('customer/cart');
        }

        $data['title'] = 'Checkout';
        $data['cart'] = $cart;
        $data['customer_obj'] = $this->Customer_model->get_by_id($this->customer_data['customer_id']);
        $data['vouchers'] = $this->Discount_model->get_for_customer(
            $this->customer_data['segment'], 'Online'
        );
        $this->render_customer('customer/cart/checkout', $data);
    }

    public function process_checkout() {
        $cart = $this->session->userdata('shop_cart');
        if (empty($cart)) {
            $this->session->set_flashdata('error', 'Keranjang belanja kosong!');
            redirect('customer/cart');
        }

        $this->form_validation->set_rules('alamat_pengiriman', 'Alamat Pengiriman', 'required|trim');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('customer/cart/checkout');
        }

        // Calculate totals
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['subtotal'];
        }

        // Check voucher
        $discount_id = null;
        $diskon_amount = 0;
        $kode_voucher = $this->input->post('kode_voucher', TRUE);
        if ($kode_voucher) {
            $discount = $this->Discount_model->get_by_code($kode_voucher);
            if ($discount) {
                $discount_id = $discount->id;
                $diskon_amount = $this->Discount_model->calculate_discount($discount->id, $subtotal);
            }
        }

        $total = $subtotal - $diskon_amount;
        $invoice_no = $this->Transaction_model->generate_invoice();

        $no_hp_penerima = $this->input->post('no_hp_penerima', TRUE);
        $alamat_ori = $this->input->post('alamat_pengiriman', TRUE);
        $alamat_lengkap = "Penerima: {$this->customer_data['customer_nama']}\nWhatsApp: {$no_hp_penerima}\nAlamat: {$alamat_ori}";

        $transaction_data = array(
            'invoice_no' => $invoice_no,
            'customer_id' => $this->customer_data['customer_id'],
            'discount_id' => $discount_id,
            'tgl' => date('Y-m-d H:i:s'),
            'subtotal' => $subtotal,
            'diskon_amount' => $diskon_amount,
            'total' => $total,
            'jenis_order' => 'Online',
            'status' => 'pending',
            'alamat_pengiriman' => $alamat_lengkap,
            'catatan' => $this->input->post('catatan', TRUE),
        );

        $this->db->trans_start();

        $this->Transaction_model->insert($transaction_data);
        $transaction_id = $this->db->insert_id();

        // Create transaction items
        $items = array();
        foreach ($cart as $item) {
            $items[] = array(
                'transaction_id' => $transaction_id,
                'product_id' => $item['product_id'],
                'qty' => $item['qty'],
                'harga' => $item['harga'],
                'subtotal' => $item['subtotal'],
            );
        }
        $this->Transaction_model->insert_items($items);

        // Update customer loyalty
        $this->Customer_model->update_loyalty($this->customer_data['customer_id'], $total);

        $this->db->trans_complete();

        if ($this->db->trans_status()) {
            $this->session->unset_userdata('shop_cart');
            $this->_sync_cart_to_db();
            $this->session->set_flashdata('success', "Pesanan berhasil dibuat! Invoice: {$invoice_no}. Silakan upload bukti pembayaran.");
            redirect('customer/dashboard/order_detail/' . $transaction_id);
        } else {
            $this->session->set_flashdata('error', 'Gagal memproses pesanan!');
            redirect('customer/cart/checkout');
        }
    }

    public function get_cart_count() {
        $cart = $this->session->userdata('shop_cart') ? $this->session->userdata('shop_cart') : array();
        $count = array_sum(array_column($cart, 'qty'));
        echo json_encode(array('count' => $count));
    }
}