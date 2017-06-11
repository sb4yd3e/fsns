<?php

class Banner extends CI_Controller{
    function __construct()
    {
        parent::__construct();
        $this->template->set_template('admin');
        $this->load->model('Banner_model','banner');
    } 

    public function index()
    {   
        if(!is_group('admin')){
            redirect('admin');
            exit();
        }
        $render_data['banner'] = $this->banner->get_banner();
        $this->load->library('form_validation');
        if(isset($render_data['banner']['bid']))
        {
            $this->form_validation->set_error_delimiters('<div class="alert alert-warning">', '</div>');
            $this->form_validation->set_rules('link', 'Link', 'required');
            $this->form_validation->set_rules('visible', 'Endable', 'required');
            $this->form_validation->set_rules('delay', 'Delay', 'required');

            if($this->form_validation->run())     
            {    
                $this->load->library('upload');

                $config['upload_path'] = './' . BANNER_PATH . '/';
                $config['allowed_types'] = 'gif|jpg|png';
                $config['encrypt_name'] = true;
                $data_update = array(
                    'visible'=>$this->input->post('visible'),
                    'link'=>$this->input->post('link'),
                    'delay'=>$this->input->post('delay')
                    );
                $this->upload->initialize($config);
                if($_FILES['image']['name']){
                    if ($this->upload->do_upload('image')) {
                        $upload_data = $this->upload->data();
                        $data_update['image'] = $upload_data['file_name'];
                    } else {
                        redirect('admin/banner/?upload=error');
                        exit();
                    }
                }
                $this->banner->update_banner($data_update);
                redirect('admin/banner/?save=true');

            }
            else
            {
               if($this->input->get('save')=="true"){
                $js = '$.notify("Save banner success.", "success");';
                $this->template->write('js', $js);
            }
            if($this->input->get('upload')=="error"){
                $js = '$.notify("Upload image error.", "warning");';
                $this->template->write('js', $js);
            }
                //******* Defalut ********//
            $render_data['user'] = $this->session->userdata('fnsn');
            $this->template->write('title', 'Banner setting');
            $this->template->write('user_id', $render_data['user']['aid']);
            $this->template->write('user_name', $render_data['user']['name']);
            $this->template->write('user_group', $render_data['user']['group']);
        //******* Defalut ********//
            $this->template->write_view('content', 'admin/banner/index', $render_data);
            $this->template->render();
        }
    }
    else{
        redirect('admin');
    }
}
}
