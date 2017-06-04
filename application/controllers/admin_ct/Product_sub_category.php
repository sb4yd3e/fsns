<?php

 
class Product_sub_category extends CI_Controller{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Product_sub_category_model');
    } 

    /*
     * Listing of product_sub_categories
     */
    function index()
    {
        $data['product_sub_categories'] = $this->Product_sub_category_model->get_all_product_sub_categories();

        $data['_view'] = 'product_sub_category/index';
        $this->load->view('layouts/main',$data);
    }

    /*
     * Adding a new product_sub_category
     */
    function add()
    {   
        $this->load->library('form_validation');

		$this->form_validation->set_rules('cid','Cid','required|integer');
		$this->form_validation->set_rules('sub_title','Sub Title','required|is_unique[sub_title]');
		$this->form_validation->set_rules('priority','Priority','required|integer');
		
		if($this->form_validation->run())     
        {   
            $params = array(
				'cid' => $this->input->post('cid'),
				'sub_title' => $this->input->post('sub_title'),
				'priority' => $this->input->post('priority'),
				'sub_description' => $this->input->post('sub_description'),
            );
            
            $product_sub_category_id = $this->Product_sub_category_model->add_product_sub_category($params);
            redirect('product_sub_category/index');
        }
        else
        {
			$this->load->model('Product_category_model');
			$data['all_product_categories'] = $this->Product_category_model->get_all_product_categories();
            
            $data['_view'] = 'product_sub_category/add';
            $this->load->view('layouts/main',$data);
        }
    }  

    /*
     * Editing a product_sub_category
     */
    function edit($sid)
    {   
        // check if the product_sub_category exists before trying to edit it
        $data['product_sub_category'] = $this->Product_sub_category_model->get_product_sub_category($sid);
        
        if(isset($data['product_sub_category']['sid']))
        {
            $this->load->library('form_validation');

			$this->form_validation->set_rules('cid','Cid','required|integer');
			$this->form_validation->set_rules('sub_title','Sub Title','required|is_unique[sub_title]');
			$this->form_validation->set_rules('priority','Priority','required|integer');
		
			if($this->form_validation->run())     
            {   
                $params = array(
					'cid' => $this->input->post('cid'),
					'sub_title' => $this->input->post('sub_title'),
					'priority' => $this->input->post('priority'),
					'sub_description' => $this->input->post('sub_description'),
                );

                $this->Product_sub_category_model->update_product_sub_category($sid,$params);            
                redirect('product_sub_category/index');
            }
            else
            {
				$this->load->model('Product_category_model');
				$data['all_product_categories'] = $this->Product_category_model->get_all_product_categories();

                $data['_view'] = 'product_sub_category/edit';
                $this->load->view('layouts/main',$data);
            }
        }
        else
            show_error('The product_sub_category you are trying to edit does not exist.');
    } 

    /*
     * Deleting product_sub_category
     */
    function remove($sid)
    {
        $product_sub_category = $this->Product_sub_category_model->get_product_sub_category($sid);

        // check if the product_sub_category exists before trying to delete it
        if(isset($product_sub_category['sid']))
        {
            $this->Product_sub_category_model->delete_product_sub_category($sid);
            redirect('product_sub_category/index');
        }
        else
            show_error('The product_sub_category you are trying to delete does not exist.');
    }
    
}
