<?php

 
class Upload extends CI_Controller{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Upload_model');
    } 

    /*
     * Listing of uploads
     */
    function index()
    {
        $data['uploads'] = $this->Upload_model->get_all_uploads();

        $data['_view'] = 'upload/index';
        $this->load->view('layouts/main',$data);
    }

    /*
     * Adding a new upload
     */
    function add()
    {   
        if(isset($_POST) && count($_POST) > 0)     
        {   
            $params = array(
				'file_title' => $this->input->post('file_title'),
				'file_paht' => $this->input->post('file_paht'),
				'file_date' => $this->input->post('file_date'),
				'file_size' => $this->input->post('file_size'),
				'file_type' => $this->input->post('file_type'),
				'uid' => $this->input->post('uid'),
				'aid' => $this->input->post('aid'),
				'oid' => $this->input->post('oid'),
            );
            
            $upload_id = $this->Upload_model->add_upload($params);
            redirect('upload/index');
        }
        else
        {            
            $data['_view'] = 'upload/add';
            $this->load->view('layouts/main',$data);
        }
    }  

    /*
     * Editing a upload
     */
    function edit($ufid)
    {   
        // check if the upload exists before trying to edit it
        $data['upload'] = $this->Upload_model->get_upload($ufid);
        
        if(isset($data['upload']['ufid']))
        {
            if(isset($_POST) && count($_POST) > 0)     
            {   
                $params = array(
					'file_title' => $this->input->post('file_title'),
					'file_paht' => $this->input->post('file_paht'),
					'file_date' => $this->input->post('file_date'),
					'file_size' => $this->input->post('file_size'),
					'file_type' => $this->input->post('file_type'),
					'uid' => $this->input->post('uid'),
					'aid' => $this->input->post('aid'),
					'oid' => $this->input->post('oid'),
                );

                $this->Upload_model->update_upload($ufid,$params);            
                redirect('upload/index');
            }
            else
            {
                $data['_view'] = 'upload/edit';
                $this->load->view('layouts/main',$data);
            }
        }
        else
            show_error('The upload you are trying to edit does not exist.');
    } 

    /*
     * Deleting upload
     */
    function remove($ufid)
    {
        $upload = $this->Upload_model->get_upload($ufid);

        // check if the upload exists before trying to delete it
        if(isset($upload['ufid']))
        {
            $this->Upload_model->delete_upload($ufid);
            redirect('upload/index');
        }
        else
            show_error('The upload you are trying to delete does not exist.');
    }
    
}
