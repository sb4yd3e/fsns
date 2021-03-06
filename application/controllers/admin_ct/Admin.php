<?php


class Admin extends CI_Controller{
    public $render_data = array();

    public function __construct() {
        parent::__construct();

        $this->template->set_template('admin');
        $this->load->model('Admin_model','admin');
    }


    /*
     * Listing of admins
     */
    function index()
    {
        if(!is_group(array('admin'))){
            redirect('admin');
            exit();
        }
        //******* Defalut ********//
        $render_data['user'] = $this->session->userdata('fnsn');
        $this->template->write('title', 'Admin/Sale Management');
        $this->template->write('user_id', $render_data['user']['aid']);
        $this->template->write('user_name', $render_data['user']['name']);
        $this->template->write('user_group', $render_data['user']['group']);
        //******* Defalut ********//

        // ====== Java script Data tabale ======= //
        $js = 'var table;
        $(document).ready(function() {
            table = $("#table").DataTable({ 
                "processing": true,
                "serverSide": true,
                "order": [],
                "ajax": {
                    "url": "'.base_url('admin/admin/ajax').'",
                    "type": "POST"
                },
                "columnDefs": [
                { 
                    "targets": [4], 
                    "orderable": false,
                },
                ],
            });
        });';
        if($this->input->get('add')=="true"){
            $js .= '$.notify("Add new data success.", "success");';
        }
        if($this->input->get('delete')=="true"){
            $js .= '$.notify("Delete data success", "success");';
        }
        if($this->input->get('save')=="true"){
            $js .= '$.notify("Save data success.", "success");';
        }
        // ====== Java script Data tabale ======= //
        $this->template->write('js', $js);
        $this->template->write_view('content', 'admin/admin/index', $render_data);
        $this->template->render();

    }


    public function ajax(){
        if (!$this->input->is_ajax_request() || !is_group('admin')) {
         exit('No direct script access allowed');
     }

     $list = $this->admin->get_all_admins();
     $data = array();
     $no = $this->input->post('start');
     foreach ($list as $admins) {
        $no++;
        $row = array();
        $row[] = $admins->username;
        $row[] = $admins->name;
        $row[] = $admins->admin_group;
        $admins->last_login = $admins->last_login?$admins->last_login:0;
        if($admins->last_login > 0 && strtotime("+30 minutes",$admins->last_login) > time()){
            $online = date("d/m/Y H:i:s",$admins->last_login).' <i class="label label-success"> online</i>';
        }elseif($admins->last_login<=0){
            $online = ' <i class="label label-info"> Never login</i>';
        }else{
            $online = date("d/m/Y H:i:s",$admins->last_login).' <i class="label label-warning"> Offline</i>';;
        }
        $row[] = $online;
        $row[] = '<a href="'.base_url('admin/admin/edit/'.$admins->aid).'" class="label label-warning"><i class="fa fa-pencil"></i> Edit</a> 
        <a href="'.base_url('admin/admin/delete/'.$admins->aid).'" class="label label-danger"  onclick="return confirm(\'Are you sure?\')"><i class="fa fa-times-circle"></i> Delete</a>';
        $data[] = $row;
    }

    $output = array(
        "draw" => $this->input->post('draw'),
        "recordsTotal" => $this->admin->count_all(),
        "recordsFiltered" => $this->admin->count_filtered(),
        "data" => $data,
        );
        //output to json format
    echo json_encode($output);

}

    /*
     * Adding a new admin
     */
    function add()
    {   
        $this->load->library('form_validation');
        if(!is_group(array('admin'))){
            redirect('admin');
            exit();
        }
        $this->form_validation->set_rules('password','Password','required|min_length[6]|max_length[50]');
        $this->form_validation->set_rules('username','Username','required|max_length[100]|is_unique[admins.username]');
        $this->form_validation->set_rules('admin_group','Admin Group','required');
        $this->form_validation->set_rules('name','Name','required');
        $this->form_validation->set_rules('email','Email','required|valid_email|is_unique[admins.email]');
        $this->form_validation->set_rules('phone','Phone','required|max_length[20]');
        $this->form_validation->set_error_delimiters('<div class="alert alert-warning">', '</div>');

        if($this->form_validation->run())     
        {   
            $params = array(
                'admin_group' => $this->input->post('admin_group'),
                'password' => md5($this->input->post('password')),
                'username' => $this->input->post('username'),
                'name' => $this->input->post('name'),
                'email' => $this->input->post('email'),
                'phone' => $this->input->post('phone')
                );
            
            $admin_id = $this->admin->add_admin($params);
            redirect('admin/admin?add=true');
        }
        else
        {            

        //******* Defalut ********//
            $render_data['user'] = $this->session->userdata('fnsn');
            $this->template->write('title', 'Add new admin');
            $this->template->write('user_id', $render_data['user']['aid']);
            $this->template->write('user_name', $render_data['user']['name']);
            $this->template->write('user_group', $render_data['user']['group']);
        //******* Defalut ********//
            $this->template->write_view('content', 'admin/admin/add', $render_data);
            $this->template->render();
        }
    }  

    /*
     * Editing a admin
     */
    function edit($aid)
    {   
        $render_data['admin_data'] = $this->admin->get_admin($aid);
        $render_data['user'] = $this->session->userdata('fnsn');
        if(isset($render_data['admin_data']['aid']))
        {
            $this->load->library('form_validation');
            if($render_data['admin_data']['aid']!=$render_data['user']['aid']){
                if(!is_group(array('admin'))){
                    redirect('admin');
                    exit();
                }
            }
            
            $this->form_validation->set_error_delimiters('<div class="alert alert-warning">', '</div>');
            $this->form_validation->set_rules('admin_group','Admin Group','required');
            $this->form_validation->set_rules('name','Name','required');
            $this->form_validation->set_rules('phone','Phone','required|max_length[20]');
            if($this->input->post('username')!=$render_data['admin_data']['username']){
                $this->form_validation->set_rules('username','Username','required|max_length[100]|is_unique[admins.username]');
            }
            if($this->input->post('password')){
                $this->form_validation->set_rules('password','Password','required|min_length[6]|max_length[50]');
            }
            if($this->input->post('email')!=$render_data['admin_data']['email']){
                $this->form_validation->set_rules('email','Email','required|valid_email|is_unique[admins.email]');
            }
            if($this->form_validation->run())     
            {   
             if($this->input->post('password')){
                $password = md5($this->input->post('password'));
            }else{
                $password = $render_data['admin_data']['password'];
            }

            $params = array(
                'admin_group' => $this->input->post('admin_group'),
                'password' => $password,
                'username' => $this->input->post('username'),
                'name' => $this->input->post('name'),
                'email' => $this->input->post('email'),
                'phone' => $this->input->post('phone')
                );

            $admin_id = $this->admin->update_admin($aid,$params);
            redirect('admin/admin/edit/'.$aid.'?save=true');
        }
        else
        {    
            $js = '';        
            if($this->input->get('save')=="true"){
                $js = '$.notify("Save data success.", "success");';
            }
            $this->template->write('js', $js);
        //******* Defalut ********//
            $render_data['admin'] = $render_data['admin_data'];
            $this->template->write('title', 'Edit ');
            $this->template->write('user_id', $render_data['user']['aid']);
            $this->template->write('user_name', $render_data['user']['name']);
            $this->template->write('user_group', $render_data['user']['group']);
        //******* Defalut ********//
            $this->template->write_view('content', 'admin/admin/edit', $render_data);
            $this->template->render();
        }
    }
    else{
        redirect('admin/orders');
    }
} 

    /*
     * Deleting admin
     */
    function delete($aid){
     if(!is_group(array('admin'))){
        redirect('admin');
        exit();
    }
    $admin = $this->admin->get_admin($aid);
    if(isset($admin['aid']))
    {
        $this->admin->delete_admin($aid);
        redirect('admin/admin?delete=true');
    }
    else
        show_error('The admin you are trying to delete does not exist.');
}

}
