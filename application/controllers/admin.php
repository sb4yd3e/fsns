<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


class Admin extends CI_Controller {

    public $render_data = array();

    public function __construct() {
        parent::__construct();

        $this->template->set_template('admin');
    }


    public function index() {
        $this->config->set_item('csrf_protection', true);
        $this->load->helper('security');
        if(is_group(array('admin','staff','sale'))){
            redirect('admin/dashboard');
            exit();
        }
        $this->load->library('form_validation');
        $this->form_validation->set_rules('username','Username','required|max_length[50]');
        $this->form_validation->set_rules('password','Password','required|max_length[50]');
        $this->form_validation->set_rules('g-recaptcha-response','Verify recaptcha','required|callback_captcha');
        $this->form_validation->set_error_delimiters('<div class="alert alert-warning">', '</div>');
        $this->load->helper('form');
        $this->load->model('auth_model', 'auth');
        if($this->form_validation->run())     
        {   
            $params = array(
                'password' => $this->input->post('password'),
                'username' => $this->input->post('username')
                );

            if(!$userdata = $this->auth->admin_login($params)){
                redirect('admin');
            }else{
                $session = array(
                    'aid' => $userdata['aid'],
                    'group' => $userdata['admin_group'],
                    'username' => $userdata['username'],
                    'name' => $userdata['name'],
                    'phone' => $userdata['phone'],
                    'email' => $userdata['email']
                    );
                add_log($userdata['username'],"Login to system.");
                $this->db->where('aid',$userdata['aid'])->update('admins',array('last_login'=>time()));
                $this->session->set_userdata('fnsn', $session);
                redirect('admin/dashboard');
            }
        }else{
            $this->load->view('admin/login');
        }

    }


    public function captcha($res){
        if(verify_recaptcha($res)){
            return TRUE;
        }else{
            $this->form_validation->set_message('captcha', 'Please verify captcha.');
            return FALSE;
        }
    }

    public function chk_online(){
     if ($this->input->is_ajax_request() && is_group(array('admin','staff','sale'))) {
        $userdata = $this->session->userdata('fnsn');
        $this->db->where('aid',$userdata['aid'])->update('admins',array('last_login'=>time()));
    }
}

public function logout(){
    @session_destroy();
    @$this->session->sess_destroy();

    redirect('admin');
}













    
    public function product_pdf_download($product_id) {
        $query = $this->db->select('title,pdf')->where('id', $product_id)->get('products');
        if ($query->num_rows() > 0) {
            $product = $query->row_array();
            $data = file_get_contents('./' . PDF_PATH . '/' . $product['pdf']);
            $name = $product['title'] . PDF_PREFIX . '.pdf';
            $this->load->helper('download');
            force_download($name, $data);
        }
    }

    public function product_delete($product_id) {
        if ($this->session->userdata('role') == '' && floor($product_id) <= 0) {
            redirect('admin');
        }

        $this->load->model('product_model', 'product');
        $this->product->product_delete($product_id);

        redirect('admin/product_list?status=delete_complete');
    }

    public function product_list() {

        if ($this->session->userdata('role') == '') {
            redirect('admin');
        }
        $this->template->write('title', 'Products Management');
        //$this->load->model('taxonomy_model', 'taxonomy');
        //$this->render_data['product_category'] = $this->taxonomy->get_taxonomy_term('product_category');
        $this->load->model('Product_model', 'product');

        $taxonomy_term_id = $this->input->get('taxonomy_term_id');
        if (floor($taxonomy_term_id) <= 0) {
            $taxonomy_term_id = 0;
        }

        $this->load->model('taxonomy_model', 'taxonomy');
        $this->load->helper('form');
        $this->render_data['product_category'] = $this->taxonomy->get_taxonomy_term('product_category');

        $this->render_data['product_list'] = $this->product->get_product_list_backend($taxonomy_term_id);
        $this->template->write_view('content', 'admin/product_list', $this->render_data);
        $this->template->render();
    }

    public function product_edit($product_id) {
        if ($this->session->userdata('role') == '' || floor($product_id) <= 0) {
            redirect('admin');
        }

        $this->load->model('product_model', 'product');
        $this->render_data['product'] = $this->product->get_product($product_id);

        $this->load->library('form_validation');

        if ($_POST) {
            $this->load->library('upload');
            $config['upload_path'] = './' . PRODUCT_PATH . '/';
            $config['allowed_types'] = 'gif|jpg|png';
            $config['encrypt_name'] = true;
            $config['file_name'] = $this->render_data['product']['cover'];
            $config['overwrite'] = true;

            $this->upload->initialize($config);



            $this->form_validation->set_rules('title', 'Product title', 'required|max_length[80]');
            $this->form_validation->set_rules('body', 'Product Description', 'required|min_length[10]');


            $this->form_validation->set_error_delimiters('<div class="ui-state-error ui-corner-all" style="padding: 1em;"><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>', '</div>');

            if ($this->form_validation->run() == TRUE) {
                $data_update = array(
                    'title' => $this->input->post('title', TRUE),
                    'taxonomy_term_id' => $this->input->post('taxonomy_term_id', TRUE),
                    'body' => $this->input->post('body', FALSE),
                    'group' => strtolower($this->input->post('group',TRUE)),
                    );

                if ($this->upload->do_upload('cover')) {
                    //Get Cover DATA
                    $upload_data = $this->upload->data();
                    $data_update['cover'] = $upload_data['file_name'];
                }

                // Upload PDF //
                $config = array();
                $config['upload_path'] = './' . PDF_PATH . '/';
                $config['allowed_types'] = 'pdf';

                if ($this->render_data['product']['pdf'] != '') {
                    $config['overwrite'] = true;
                    $config['file_name'] = $this->render_data['product']['pdf'];
                } else {
                    $config['encrypt_name'] = true;
                }

                $this->upload->initialize($config);

                if ($this->upload->do_upload('pdf')) {
                    $upload_data = $this->upload->data();
                    $data_update['pdf'] = $upload_data['file_name'];

                    $this->db->where('id', $product_id);
                    $this->db->update('products', $data_update);
                    redirect('admin/product_list?status=update_complete');
                } else {
                    $this->db->where('id', $product_id);
                    $this->db->update('products', $data_update);
                    redirect('admin/product_list?status=update_complete');
                }
            }
        }



        $this->template->write('title', 'Edit product');
        $this->load->model('taxonomy_model', 'taxonomy');
        $this->render_data['product_category'] = $this->taxonomy->get_taxonomy_term('product_category');





        $this->template->add_js('js/ckeditor/ckeditor.js');



        $this->template->write_view('content', 'admin/product_edit', $this->render_data);
        $this->template->render();
    }

    public function product_add() {
        if ($this->session->userdata('role') == '') {
            redirect('admin');
        }

        $this->load->library('form_validation');

        if ($_POST) {

            $this->load->library('upload');

            $config['upload_path'] = './' . PRODUCT_PATH . '/';
            $config['allowed_types'] = 'gif|jpg|png';
            $config['encrypt_name'] = true;

            $this->upload->initialize($config);

            $this->form_validation->set_rules('title', 'Product title', 'required|max_length[80]');
            $this->form_validation->set_rules('body', 'Product Description', 'required|min_length[10]');


            $this->form_validation->set_error_delimiters('<div class="ui-state-error ui-corner-all" style="padding: 1em;"><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>', '</div>');

            if ($this->form_validation->run() == TRUE) {
                $data_create = array(
                    'title' => $this->input->post('title', TRUE),
                    'taxonomy_term_id' => $this->input->post('taxonomy_term_id', TRUE),
                    'body' => $this->input->post('body', FALSE),
                    'group' => strtolower($this->input->post('group',TRUE)),
                    );
                if ($this->upload->do_upload('cover')) {
                    //Get Cover DATA
                    $upload_data = $this->upload->data();
                    $data_create['cover'] = $upload_data['file_name'];

                    // Upload PDF //
                    $config = array();
                    $config['upload_path'] = './' . PDF_PATH . '/';
                    $config['allowed_types'] = 'pdf';
                    $config['encrypt_name'] = true;
                    $this->upload->initialize($config);
                    if ($this->upload->do_upload('pdf')) {
                        $upload_data = $this->upload->data();
                        $data_create['pdf'] = $upload_data['file_name'];

                        $this->db->insert('products', $data_create);
                        redirect('admin/product_list?status=add_complete');
                    } else {
                        $this->db->insert('products', $data_create);
                        redirect('admin/product_list?status=add_complete');
                    }
                } else {
                    $this->render_data['error_upload'] = '<div class="ui-state-error ui-corner-all" style="padding: 1em;"><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>' . $this->upload->display_errors() . '</div>';
                }
            }
        }

        $this->template->write('title', 'Create a new product');
        $this->load->model('taxonomy_model', 'taxonomy');
        $this->render_data['product_category'] = $this->taxonomy->get_taxonomy_term('product_category');




        //$this->template->add_js('js/ckeditor/ckeditor.js');



        $this->template->write_view('content', 'admin/product_add', $this->render_data);
        $this->template->render();
    }

    


    public function slideshow_list() {
        $this->render_data['slideshows'] = array();
        $query = $this->db->order_by('weight', 'asc')->get('slideshows');
        if ($query->num_rows() > 0) {
            $this->render_data['slideshows'] = $query->result_array();
        }

        $this->render_data['title'] = 'Slideshow';
        $this->template->write('title', 'Slideshow');

        $this->template->write_view('content', 'admin/slideshow_list', $this->render_data);
        $this->template->render();
    }

    public function slideshow_edit($slideshow_id) {
        if ($_POST) {

            if ($this->input->post('delete') != '') {
                $update_data = array(
                    'slideshow_image' => '',
                    'slideshow_caption' => '',
                    'slideshow_url' => '',
                    'weight' => 0,
                    );
                $this->db->where('slideshow_id', $slideshow_id);
                $this->db->update('slideshows', $update_data);
                redirect('admin/slideshow_list?status=update_complete');
            }

            $this->load->library('upload');

            //Get Cover DATA

            $update_data = array(
                'slideshow_caption' => trim(strip_tags($this->input->post('slideshow_caption', TRUE))),
                'slideshow_url' => trim(strip_tags($this->input->post('slideshow_url', TRUE))),
                'weight' => strip_tags($this->input->post('weight', TRUE)),
                );


            $config['upload_path'] = './' . UPLOAD_PATH . '/slideshow';
            $config['allowed_types'] = 'gif|jpg|png';
            $config['file_name'] = md5($slideshow_id);
            $config['overwrite'] = true;

            $this->upload->initialize($config);

            if ($this->upload->do_upload('userfile')) {
                $upload_data = $this->upload->data();
                $update_data['slideshow_image'] = $upload_data['file_name'];
            }
            $this->db->where('slideshow_id', $slideshow_id);
            $this->db->update('slideshows', $update_data);
        }


        redirect('admin/slideshow_list?status=update_complete');
    }
    
    public function ajax_get_group()
    {
        $term_id = $this->input->post('term_id');
        $keyword = $this->input->post('keyword');
        $result = $this->db->distinct()->select('group')
        ->where('taxonomy_term_id',$term_id)
        ->like('group',$keyword)->order_by('group')->get('products');
        
        if ($result->num_rows() > 0)
        {
            $return_list = $result->result_array();
            $return_array = array();
            foreach($return_list as $row)
            {
                $return_array[] = $row['group'];
            }
            echo json_encode($return_array);
        }
    }
    
    /*
    public function ajax_get_dropdown() {

        $this->load->helper('ecommerce');
        get_dropdown_from_script($_POST['dropdown_script'], $_POST['price_weight_script'], TRUE);
        die();
    }*/

}
