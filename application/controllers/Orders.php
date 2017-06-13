<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: attapon
 * Date: 6/13/2017 AD
 * Time: 00:54
 */
class Orders extends CI_Controller
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
        if(!is_login()){
            redirect('');
        }
        $this->render_data['web_title'] = 'My Orders';
        $this->template->write_view('content', 'frontend/my_orders', $this->render_data);
        $this->template->render();
    }

    function carts(){
        $this->render_data['web_title'] = 'My shopping carts';
        $this->template->write_view('content', 'frontend/my_carts', $this->render_data);
        $this->template->render();
    }

}