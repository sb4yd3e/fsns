<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @property Taxonomy_model $taxonomy_model
 * @property Product_model $product
 * @property Content_model $content
 * @property Slideshow_model $slideshow
 */
class Frontend extends CI_Controller
{

    public $render_data = array();

    public function __construct()
    {
        parent::__construct();
        $this->load->model('taxonomy_model');
        $this->render_data['product_category'] = $this->taxonomy_model->get_taxonomy_term('product_category');
    }

    public function index()
    {
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

    public function news($content_id = null)
    {
        $this->render_data['active_menu'] = 'news';
        if ($content_id && $content_id != 'page') {
            $content_id = floor($content_id);
            $this->render_data['news'] = $this->db->where('id', $content_id)->get('contents')->row_array();
            $this->render_data['web_title'] = $this->render_data['news']['title'];
            $this->template->write_view('content', 'frontend/news', $this->render_data);
        } else {
            $this->load->helper('text');
            $this->render_data['web_title'] = 'News';
            $this->load->model('content_model');

            $this->load->library('pagination');
            $config['base_url'] = base_url('news/page') . '/';
            $config['total_rows'] = $this->content_model->count_news();
            $config['full_tag_open'] = '<ul class="pagination">';
            $config['full_tag_close'] = '</ul>';

            //num
            $config['cur_tag_open'] = '<li class="active"><a href="#">';
            $config['cur_tag_close'] = '</a></li>';
            $config['num_tag_open'] = '<li>';
            $config['num_tag_close'] = '</li>';

            //prev
            $config['prev_tag_open'] = '<li class="arrow">';
            $config['prev_tag_close'] = '</li>';
            $config['prev_link'] = '«';

            //next
            $config['next_tag_open'] = '<li class="arrow">';
            $config['next_tag_close'] = '</li>';
            $config['next_link'] = '»';

            // Go to first
            $config['first_tag_open'] = '<li>';
            $config['first_link'] = 'หน้าแรก';
            $config['first_tag_close'] = '</li>';

            // Go to last
            $config['last_tag_open'] = '<li>';
            $config['last_link'] = 'หน้าสุดท้าย';
            $config['last_tag_close'] = '</li>';

            $config['per_page'] = 9;
            $config['uri_segment'] = 3;
            $this->pagination->initialize($config);
            $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
            $content = $this->content_model->list_news($config['per_page'], $page);
            $links = $this->pagination->create_links();

            $this->render_data['news_list'] = $content;
            $this->render_data['links'] = $links;
            $this->template->write_view('content', 'frontend/news_list', $this->render_data);


        }
        $this->template->render();
    }

    public function services($page)
    {
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

    public function contact()
    {
        $this->render_data['web_title'] = 'ติดต่อเรา (Contact)';
        $this->render_data['active_menu'] = 'contact';
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

    public function catalog($category_id = null, $sub_category_id = null)
    {

        /* Load Category */
        $this->load->model('taxonomy_model');

        $this->render_data['category_id'] = floor($category_id) > 0 ? floor($category_id) : null;
        $this->render_data['sub_category_id'] = floor($sub_category_id) > 0 ? floor($sub_category_id) : null;


        $this->render_data['term'] = $this->taxonomy_model->get_taxonomy_id($this->render_data['sub_category_id']);

        $main_cat = $this->taxonomy_model->get_taxonomy_id($this->render_data['category_id']);
        $this->render_data['web_title'] = ucfirst(str_replace('<br/>', ' ', $main_cat['title'])) . ' - ' . ucfirst($this->render_data['term']['title']);

        if ($this->render_data['category_id'] == null || $this->render_data['sub_category_id'] == null || !$this->render_data['term'])
            redirect('/');


        // Get Product List //
        $this->load->model('products_model', 'product');
        $this->render_data['products'] = $this->product->get_product_list($sub_category_id);
        if ($sub_category_id == 42) {
            $this->template->write_view('content', 'frontend/product_pdf', $this->render_data);
        } else {
            $this->template->write_view('content', 'frontend/product', $this->render_data);
        }

        $this->template->render();
    }

    public function product_get($product_id)
    {
        $product_id = floor($product_id);
        if ($product_id <= 0) {
            redirect('frontend');
        }


        /* Load Category */
        $this->load->model('taxonomy_model', 'taxonomy');
        $this->render_data['product_category'] = $this->taxonomy->get_taxonomy_term('product_category');
        /* Load Product */
        $this->load->model('products_model', 'product');
        $this->render_data['products'] = $this->product->get_product_list_nav();


        $this->render_data['product_data'] = $this->product->get_product($product_id);
        if(!$this->render_data['product_data']){
            redirect('frontend');
        }
        $this->render_data['product_attr'] = $this->product->get_attribute($product_id);
        $this->render_data['product_list'] = $this->product->get_other_product($product_id, 5);
        $this->render_data['web_title'] = $this->render_data['product_data']['title'];
        $cat_title = $this->product->get_title_cat($this->render_data['product_data']['cat_id']);
        $this->render_data['web_nav'] = 'Home / <a href="'.base_url('catalog/'.$this->render_data['product_data']['cat_id'].'/'.url_title($cat_title).'/'.$this->render_data['product_data']['sub_cat_id'].'/'.url_title($this->render_data['product_data']['term_title'])).'">'.$cat_title.' > '.$this->render_data['product_data']['term_title'] .'</a> / '.$this->render_data['product_data']['title'];

        $this->template->write_view('content', 'frontend/product_view', $this->render_data);
        $this->template->render();
    }

    public function product_search()
    {
        $string = trim($this->input->post('search_txt'));
        /* Load Product */
        $this->load->model('products_model', 'product');
        $this->render_data['products_search'] = $this->product->get_product_search($string);
        $this->load->view('frontend/product_search', $this->render_data);
    }

    public function product_pdf_download($product_id, $hash, $title)
    {
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

    function ajax_check_product_data()
    {
        if ($this->input->is_ajax_request()) {
            $json = json_decode($this->input->post('products'));
            if ($json && count($json) > 0) {
                $product = array();
                foreach ($json as $k => $val) {
                    $rs = $this->db->select('product_attribute.*,products.title,products.cover')->from('product_attribute')->join('products','product_attribute.pid = products.id')->where('product_attribute.pa_id',$k)->get()->row_array();
                    if($rs){
                        if($rs['minimum'] > $val->qty){
                            $val->qty = $rs['minimum'];
                        }
                        if($rs['p_cover']!=""){
                            $val->image = $rs['p_cover'];
                        }else{
                            $val->image = $rs['cover'];
                        }
                        $product[$k] = array(
                          'pid'=>$rs['pid'],
                            'title'=>$rs['title'],
                            'code'=>$rs['code'],
                            'value'=>$rs['p_value'],
                            'price'=>$rs['normal_price'],
                            'sp_price'=>$rs['special_price'],
                            'qty'=>$val->qty,
                            'image'=>'/timthumb.php?src=/uploads/products/'. $val->image,
                            'minimum'=>$rs['minimum']
                        );
                    }
                }
                echo json_encode($product, JSON_NUMERIC_CHECK);
            }
        } else {
            show_404();
        }
    }

    function search(){
        $this->render_data['web_title'] = 'Search';
        $this->template->write_view('content', 'frontend/search', $this->render_data);
        $this->template->render();
    }

}
