<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Feedback extends AdminBaseController {

    public function __construct() {
        parent::__construct();
        $this->admin_only();
        $this->load->model('Feedback_model');
    }

    public function index() {
        $data['title'] = 'Kelola Feedback';
        $data['feedbacks'] = $this->Feedback_model->get_all();
        $data['unread_count'] = $this->Feedback_model->count_unread();
        $data['avg_rating'] = $this->Feedback_model->get_average_rating();
        $data['rating_dist'] = $this->Feedback_model->get_rating_distribution();
        $this->render_admin('admin/feedback/index', $data);
    }

    public function view($id) {
        $data['title'] = 'Detail Feedback';
        $data['feedback'] = $this->Feedback_model->get_by_id($id);
        if (!$data['feedback']) {
            $this->session->set_flashdata('error', 'Feedback tidak ditemukan!');
            redirect('admin/feedback');
        }
        // Mark as read
        $this->Feedback_model->mark_as_read($id);
        $this->render_admin('admin/feedback/view', $data);
    }

    public function reply($id) {
        $this->form_validation->set_rules('balasan_admin', 'Balasan', 'required|trim');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Balasan tidak boleh kosong!');
        } else {
            $balasan = $this->input->post('balasan_admin', TRUE);
            if ($this->Feedback_model->reply($id, $balasan)) {
                $this->session->set_flashdata('success', 'Balasan berhasil dikirim!');
            } else {
                $this->session->set_flashdata('error', 'Gagal mengirim balasan!');
            }
        }
        redirect('admin/feedback');
    }

    public function delete($id) {
        if ($this->Feedback_model->delete($id)) {
            $this->session->set_flashdata('success', 'Feedback berhasil dihapus!');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus feedback!');
        }
        redirect('admin/feedback');
    }
}