<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


class Admin extends CI_Controller
{

    public $render_data = array();

    public function __construct()
    {
        parent::__construct();

        $this->template->set_template('admin');
    }


    public function index()
    {
        $this->config->set_item('csrf_protection', true);
        $this->load->helper('security');
        if (is_group(array('admin', 'co-sale', 'sale'))) {
            redirect('admin/orders');
            exit();
        }
        $this->load->library('form_validation');
        $this->form_validation->set_rules('username', 'Username', 'required|max_length[50]');
        $this->form_validation->set_rules('password', 'Password', 'required|max_length[50]');
        $this->form_validation->set_rules('g-recaptcha-response', 'Verify recaptcha', 'required|callback_captcha');
        $this->form_validation->set_error_delimiters('<div class="alert alert-warning">', '</div>');
        $this->load->helper('form');
        $this->load->model('auth_model', 'auth');
        if ($this->form_validation->run()) {
            $params = array(
                'password' => $this->input->post('password'),
                'username' => $this->input->post('username')
            );

            if (!$userdata = $this->auth->admin_login($params)) {
                redirect('admin');
            } else {
                $session = array(
                    'aid' => $userdata['aid'],
                    'group' => $userdata['admin_group'],
                    'username' => $userdata['username'],
                    'name' => $userdata['name'],
                    'phone' => $userdata['phone'],
                    'email' => $userdata['email']
                );
//                add_log($userdata['username'],"Login to system.");
                $this->db->where('aid', $userdata['aid'])->update('admins', array('last_login' => time()));
                $this->session->set_userdata('fnsn', $session);
                redirect('admin/orders');
            }
        } else {
            $this->load->view('admin/login');
        }

    }


    public function captcha($res)
    {
        if (verify_recaptcha($res)) {
            return TRUE;
        } else {
            $this->form_validation->set_message('captcha', 'Please verify captcha.');
            return FALSE;
        }
    }

    public function chk_online()
    {
        if ($this->input->is_ajax_request() && is_group(array('admin', 'co-sale', 'sale'))) {
            $userdata = $this->session->userdata('fnsn');
            $this->db->where('aid', $userdata['aid'])->update('admins', array('last_login' => time()));
        }
    }

    public function logout()
    {
        @session_destroy();
        @$this->session->sess_destroy();

        redirect('admin');
    }


    public function product_pdf_download($product_id)
    {
        $query = $this->db->select('title,pdf')->where('id', $product_id)->get('products');
        if ($query->num_rows() > 0) {
            $product = $query->row_array();
            $data = file_get_contents('./' . PDF_PATH . '/' . $product['pdf']);
            $name = $product['title'] . PDF_PREFIX . '.pdf';
            $this->load->helper('download');
            force_download($name, $data);
        }
    }


}
