<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @property Taxonomy_model $taxonomy_model
 * @property Product_model $product
 * @property Content_model $content
 * @property Slideshow_model $slideshow
 */
class Frontend extends CI_Controller {

    public $render_data = array();

    public function __construct() {
        parent::__construct();
        $this->load->model('taxonomy_model');
        $this->render_data['product_category'] = $this->taxonomy_model->get_taxonomy_term('product_category');
    }

    public function index() {
        /* Web title */
        $this->render_data['web_title'] = 'Home';
        /* Load Category */
        $this->load->model('taxonomy_model', 'taxonomy');
        $this->render_data['product_category'] = $this->taxonomy->get_taxonomy_term('product_category');
        $this->load->helper('text');
        $this->render_data['news_list'] = $this->db->order_by('modified_at', 'desc')->limit(3)->get('contents')->result_array();
        $this->template->write_view('content', 'frontend/home', $this->render_data);


        $this->template->render();
    }

    public function news($content_id = null) {

        if ($content_id) {
            $content_id = floor($content_id);
            $this->render_data['news'] = $this->db->where('id', $content_id)->get('contents')->row_array();
            $this->render_data['web_title'] = $this->render_data['news']['title'];
            $this->template->write_view('content', 'frontend/news', $this->render_data);
        } else {
            $this->load->helper('text');
            $this->render_data['web_title'] = 'News';
            $this->load->model('content_model');
            $this->render_data['news_list'] = $this->content_model->get_content_list_by_created();
            $this->template->write_view('content', 'frontend/news_list', $this->render_data);
        }
        $this->template->render();
    }

    public function services($page) {
        $this->render_data['active_menu'] = 'services';

        switch ($page) {
            case 'food_safety_inspection':
                $this->render_data['web_title'] = 'Services - Food Safty Inspection';
                $this->template->write_view('content', 'frontend/services_food_safety_inspection', $this->render_data);
                break;
            case 'basic_food_safety':
                $this->render_data['web_title'] = 'Services - Basic Food Safety';
                $this->template->write_view('content', 'frontend/services_basic_food_safety', $this->render_data);
                break;
            case 'gmp_haccp_awareness':
                $this->render_data['web_title'] = 'Services - GMP HACCP Awareness';
                $this->template->write_view('content', 'frontend/services_gmp_haccp_awareness', $this->render_data);
                break;
            default:
                redirect('/');
        }

        $this->template->render();
    }

    public function contact() {
        $this->render_data['web_title'] = 'ติดต่อเรา (Contact)';

        if ($_POST) {
            $this->load->model('Setting_model', 'setting');
            $setting = $this->setting->get_setting();
            $name = $this->input->post('name');
            $company = $this->input->post('company');
            $email = $this->input->post('email');
            $phone = $this->input->post('phone');
            $message = $this->input->post('message');



            $config['charset'] = 'utf-8';
            $config['newline'] = "\r\n";
            $config['mailtype'] = 'html'; // or html
            $config['validation'] = TRUE; // bool whether to validate email or not      

            $this->load->library('email');
            $this->email->initialize($config);

            $this->email->from('noreply@hofcorindus.co.th', COMPANY_TITLE);
            $this->email->reply_to($email, $name);
            //$this->email->to('suwichalala@gmail.com');


            $email_list = explode(',', $setting['email_for_contact']);
            $count = 1;
            $email_cc = array();
            foreach ($email_list as $email_admin) {
                if ($count == 1) {
                    $this->email->to($email_admin);
                } else {
                    $email_cc[] = $email_admin;
                }
                $count++;
            }

            $this->email->cc($email_cc);

            $this->email->subject('[HOF Corindus website contact] – ' . $name);
            $this->email->message('
            <b>From: </b> ' . $name . ' (' . $email . ')<br/>
            <b>Company:</b>' . $company . '<br/>
            <b>Contact number: </b> ' . $phone . '<br/>
            <b>Message:</b><br/>
            ' . nl2br($message) . '
            ');

            $this->email->send();

            $this->load->helper('url');
            redirect('contact-us?done=true#done');
        }

        $this->render_data['map_position'] = '13.696760, 100.510442';
        $this->template->write_view('content', 'frontend/contact', $this->render_data);
        $this->template->render();
    }

    public function catalog($category_id = null, $sub_category_id = null) {

        /* Load Category */
        $this->load->model('taxonomy_model');

        $this->render_data['category_id'] = floor($category_id) > 0 ? floor($category_id) : null;
        $this->render_data['sub_category_id'] = floor($sub_category_id) > 0 ? floor($sub_category_id) : null;


        $this->render_data['term'] = $this->taxonomy_model->get_taxonomy_id($this->render_data['sub_category_id']);

        $main_cat = $this->taxonomy_model->get_taxonomy_id($this->render_data['category_id']);
        $this->render_data['web_title'] = ucfirst(str_replace('<br/>',' ',$main_cat['title'])) . ' - ' . ucfirst($this->render_data['term']['title']);

        if ($this->render_data['category_id'] == null || $this->render_data['sub_category_id'] == null || !$this->render_data['term'])
            redirect('/');


        // Get Product List //
        $this->load->model('product_model', 'product');
        $this->render_data['products'] = $this->product->get_product_list($sub_category_id);
        if ($sub_category_id == 42) {
            $this->template->write_view('content', 'frontend/product_pdf', $this->render_data);
        } else {
            $this->template->write_view('content', 'frontend/product', $this->render_data);
        }

        $this->template->render();
    }

    public function product_get($product_id) {
        $product_id = floor($product_id);
        if ($product_id <= 0) {
            redirect('frontend');
        }
        $this->template->set_template('product');

        /* Load Category */
        $this->load->model('taxonomy_model', 'taxonomy');
        $this->render_data['product_category'] = $this->taxonomy->get_taxonomy_term('product_category');
        /* Load Product */
        $this->load->model('product_model', 'product');
        $this->render_data['products'] = $this->product->get_product_list_nav();


        $this->render_data['product_data'] = $this->product->get_product($product_id);
        $this->render_data['product_category'] = $this->taxonomy->get_taxonomy_term('product_category');
        $this->render_data['product_list'] = $this->product->get_other_product($product_id, 5);

        $this->template->write_view('header', 'frontend/header', $this->render_data);
        $this->template->write_view('product_nav', 'frontend/product_nav', $this->render_data);
        $this->template->render();
    }

    public function product_search() {
        $string = trim($this->input->post('search_txt'));
        /* Load Product */
        $this->load->model('product_model', 'product');
        $this->render_data['products_search'] = $this->product->get_product_search($string);
        $this->load->view('frontend/product_search', $this->render_data);
    }

    public function product_pdf_download($product_id, $hash, $title) {
        if (md5($product_id . 'suwichalala') != $hash) {
            die();
        }
        $query = $this->db->select('title,pdf')->where('id', $product_id)->get('products');
        if ($query->num_rows() > 0) {
            $product = $query->row_array();
            //$data = file_get_contents();
            $name = url_title($product['title']) . PDF_PREFIX . '.pdf';
            header("Content-type:application/pdf");
            //header("Content-Disposition:attachment;filename='".$name."'");
            readfile('./' . PDF_PATH . '/' . $product['pdf']);
            //$this->load->helper('download');
            //force_download($name, $data);
        }
    }

}
