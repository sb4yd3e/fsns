<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');


class Members extends CI_Controller {

	public $render_data = array();

	public function __construct() {
		parent::__construct();

		$this->template->set_template('admin');
		$this->load->model('Members_model','members');
	}


	public function index() {
		if(!is_group(array('admin','staff','sale'))){
			redirect('admin');
			exit();
		}
		//******* Defalut ********//
		$render_data['user'] = $this->session->userdata('fnsn');
		$this->template->write('title', 'Members ');
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
					"url": "'.base_url('admin/members/ajax').'",
					"type": "POST",
					data:function(data){
						data.account_type = $("#account_type").val();
						data.staff_id = $("#staff_id").val();
						data.is_active = $("#is_active").val();
					}
				},
				"columnDefs": [
				{ 
					"targets": [5], 
					"orderable": false,
				},
				],
			});
			$("#show").change(function () {
				$("#table_length select").val($(this).val());
				$("#table_length select").trigger("change");
			});
			$("#search").on("keyup", function () {
				$("#table_filter input[type=\"search\"]").val($(this).val());
				$("#table_filter input[type=\"search\"]").trigger("keyup");
			});
			$("#account_type").change(function(){
				var table = $("#table").DataTable();
				table.ajax.reload();
			});$("#staff_id").change(function(){
				var table = $("#table").DataTable();
				table.ajax.reload();
			});$("#is_active").change(function(){
				var table = $("#table").DataTable();
				table.ajax.reload();
			});
		});

		';
		if($this->input->get('add')=="true"){
			$js .= '$.notify("Add new member success.", "success");';
		}
		if($this->input->get('delete')=="true"){
			$js .= '$.notify("Delete member success", "success");';
		}
		if($this->input->get('save')=="true"){
			$js .= '$.notify("Save member success.", "success");';
		}
		if(is_group('sale')){
			$render_data['all_admins'] = array(0=>array('aid'=>$render_data['user']['aid'],'name'=>$render_data['user']['name']));
		}else{
			$render_data['all_admins'] = $this->members->get_all_admins();
		}
		$this->template->write('js', $js);
		$this->template->write_view('content', 'admin/members/index', $render_data);
		$this->template->render();

	}

	public function ajax(){
		if (!$this->input->is_ajax_request() || !is_group(array('admin','staff','sale'))) {
			exit('No direct script access allowed');
		}

		$list = $this->members->get_all_members();
		$data = array();
		$no = $this->input->post('start');
		foreach ($list as $member) {
			$no++;
			$row = array();
			$row[] = $member->email;
			$row[] = $member->name;
			$row[] = $member->account_type;
			$row[] = get_staff_username($member->staff_id);
			$row[] = is_active($member->is_active);
			$row[] = '<a href="'.base_url('admin/members/edit/'.$member->uid).'" class="label label-warning"><i class="fa fa-pencil"></i> Edit</a> 
			';
			$data[] = $row;
		}

		$output = array(
			"draw" => $this->input->post('draw'),
			"recordsTotal" => $this->members->count_all(),
			"recordsFiltered" => $this->members->count_filtered(),
			"data" => $data,
			);
		echo json_encode($output);
	}

	function add()
	{   
		$this->load->library('form_validation');
		if(!is_group(array('admin','staff','sale'))){
			redirect('admin');
			exit();
		}
		$render_data['user'] = $this->session->userdata('fnsn');
		$this->form_validation->set_error_delimiters('<div class="alert alert-warning">', '</div>');
		$this->form_validation->set_rules('account_type','Account Type','required');
		$this->form_validation->set_rules('password','Password','required|min_length[6]|max_length[50]');
		$this->form_validation->set_rules('email','Email','required|valid_email|is_unique[users.email]');
		$this->form_validation->set_rules('name','Name','required|max_length[100]');
		$this->form_validation->set_rules('phone','Phone','required|max_length[20]');
		$this->form_validation->set_rules('shiping_name','Shiping Name','required|max_length[200]');
		$this->form_validation->set_rules('shiping_province','Shiping Province','required');
		$this->form_validation->set_rules('shiping_zip','Shiping Zip','required|numeric|min_length[5]|max_length[5]');
		$this->form_validation->set_rules('shiping_address','Shiping Address','required');
		$this->form_validation->set_rules('is_active','Active member','required');

		if($this->input->post('account_type')=='bussiness'){
			$this->form_validation->set_rules('bussiness_name','Bussiness Name','required|max_length[200]');
			$this->form_validation->set_rules('bussiness_address','Bussiness Address','required');
			$this->form_validation->set_rules('bussiness_number','Federal tax identification number','required|max_length[30]');
		}
		if($this->form_validation->run())     
		{   
			$data_create = array(
				'account_type' => $this->input->post('account_type'),
				'staff_id' => $this->input->post('staff_id'),
				'password' => md5($this->input->post('password')),
				'email' => $this->input->post('email'),
				'name' => $this->input->post('name'),
				'is_active' => $this->input->post('is_active'),
				'phone' => $this->input->post('phone'),
				'shiping_name' => $this->input->post('shiping_name'),
				'shiping_province' => $this->input->post('shiping_province'),
				'shiping_zip' => $this->input->post('shiping_zip'),
				'shiping_address' => $this->input->post('shiping_address'),
				'bussiness_name' => $this->input->post('bussiness_name'),
				'bussiness_address' => $this->input->post('bussiness_address'),
				'bussiness_number' => $this->input->post('bussiness_number'),
				'register_ip' => $this->input->ip_address(),
				'register_date' => time(),
				'token' => md5($this->input->post('email').time()),
				);

			//for saler
			if(is_group('sale') && $this->input->post('staff_id')!="0"){
				$data_create['staff_id'] = $render_data['user']['aid'];
			}

			$in_id = $this->members->add_members($data_create);
			add_log($render_data['user']['name'],"Created","user_".$in_id);
			redirect('admin/members?add=true');
		}
		else
		{            
        //******* Defalut ********//
			$render_data['user'] = $this->session->userdata('fnsn');
			$this->template->write('title', 'Add new member');
			$this->template->write('user_id', $render_data['user']['aid']);
			$this->template->write('user_name', $render_data['user']['name']);
			$this->template->write('user_group', $render_data['user']['group']);
        //******* Defalut ********//
			$js = '
			if($("#account_type").val()=="bussiness"){
				$("#tab-bussiness").removeClass("disabled");
			}
			$("#account_type").change(function(){
				if($(this).val()!="bussiness"){
					$("#tab-bussiness").addClass("disabled");
					$("#bussiness_name").val("");
					$("#bussiness_address").val("");
					$("#bussiness_number").val("");
				}else{
					$("#tab-bussiness").removeClass("disabled");
				}
			});

			$("#tab-bussiness").click(function (e) {
				if($(this).hasClass("disabled")){
					e.preventDefault();
					return false;
				}
			});';
			$this->template->write('js', $js);
			if(is_group('sale')){
				$render_data['all_admins'] = array(0=>array('aid'=>$render_data['user']['aid'],'name'=>$render_data['user']['name']));
			}else{
				$render_data['all_admins'] = $this->members->get_all_admins();
			}
			$this->template->write_view('content', 'admin/members/add', $render_data);
			$this->template->render();
		}

	}

	function edit($id)
	{   
		$content = $this->members->get_members($id);
		$render_data['content'] = $content;
		
		if(!is_group(array('admin','staff','sale')) || !$content){
			redirect('admin');
			exit();
		}
		$this->load->library('form_validation');
		$render_data['user'] = $this->session->userdata('fnsn');
		$this->form_validation->set_error_delimiters('<div class="alert alert-warning">', '</div>');
		$this->form_validation->set_rules('account_type','Account Type','required');
		
		
		$this->form_validation->set_rules('name','Name','required|max_length[100]');
		$this->form_validation->set_rules('phone','Phone','required|max_length[20]');
		$this->form_validation->set_rules('shiping_name','Shiping Name','required|max_length[200]');
		$this->form_validation->set_rules('shiping_province','Shiping Province','required');
		$this->form_validation->set_rules('shiping_zip','Shiping Zip','required|numeric|min_length[5]|max_length[5]');
		$this->form_validation->set_rules('shiping_address','Shiping Address','required');
		$this->form_validation->set_rules('is_active','Active member','required');
		if($this->input->post('account_type')=='bussiness'){
			$this->form_validation->set_rules('bussiness_name','Bussiness Name','required|max_length[200]');
			$this->form_validation->set_rules('bussiness_address','Bussiness Address','required');
			$this->form_validation->set_rules('bussiness_number','Federal tax identification number','required|max_length[30]');
		}
		if($this->input->post('password')){
			$this->form_validation->set_rules('password','Password','required|min_length[6]|max_length[50]');
		}
		if($this->input->post('email') != $content['email']){
			$this->form_validation->set_rules('email','Email','required|valid_email|is_unique[users.email]');
		}
		if($this->form_validation->run())     
		{   
			$data_update = array(
				'account_type' => $this->input->post('account_type'),
				'staff_id' => $this->input->post('staff_id'),
				'email' => $this->input->post('email'),
				'name' => $this->input->post('name'),
				'is_active' => $this->input->post('is_active'),
				'phone' => $this->input->post('phone'),
				'shiping_name' => $this->input->post('shiping_name'),
				'shiping_province' => $this->input->post('shiping_province'),
				'shiping_zip' => $this->input->post('shiping_zip'),
				'bussiness_name' => $this->input->post('bussiness_name'),
				'bussiness_address' => $this->input->post('bussiness_address'),
				'bussiness_number' => $this->input->post('bussiness_number'),
				'shiping_address' => $this->input->post('shiping_address')
				);

			//for saler
			if(is_group('sale') && $this->input->post('staff_id')!="0"){
				$data_update['staff_id'] = $render_data['user']['aid'];
			}

			if($this->input->post('password')){
				$data_update['password'] = md5($this->input->post('password'));
			}

			$this->members->update_members($id,$data_update);
			add_log($render_data['user']['name'],"Change detail.","user_".$id);
			redirect('admin/members/edit/'.$id.'?save=true');
		}
		else
		{            
        //******* Defalut ********//
			$render_data['user'] = $this->session->userdata('fnsn');
			$this->template->write('title', 'Edit member');
			$this->template->write('user_id', $render_data['user']['aid']);
			$this->template->write('user_name', $render_data['user']['name']);
			$this->template->write('user_group', $render_data['user']['group']);
        //******* Defalut ********//
			$js = 'if($("#account_type").val()=="bussiness"){
				$("#tab-bussiness").removeClass("disabled");
			}
			$("#account_type").change(function(){
				if($(this).val()!="bussiness"){
					$("#tab-bussiness").addClass("disabled");
					$("#bussiness_name").val("");
					$("#bussiness_address").val("");
					$("#bussiness_number").val("");
				}else{
					$("#tab-bussiness").removeClass("disabled");
				}
			});

			$("#tab-bussiness").click(function (e) {
				if($(this).hasClass("disabled")){
					e.preventDefault();
					return false;
				}
			});';
			if($this->input->get('save')=="true"){
				$js .= '$.notify("Save member success.", "success");';
			}
			$this->template->write('js', $js);
			if(is_group('sale')){
				$render_data['all_admins'] = array(0=>array('aid'=>$render_data['user']['aid'],'name'=>$render_data['user']['name']));
			}else{
				$render_data['all_admins'] = $this->members->get_all_admins();
			}
			$render_data['logs'] = list_logs("user_".$id);
			$this->template->write_view('content', 'admin/members/edit', $render_data);
			$this->template->render();
		}

	}


	// function delete($id){
	// 	if(!is_group(array('admin'))){
	// 		redirect('admin');
	// 		exit();
	// 	}
	// 	$news = $this->news->get_news($id);
	// 	if(isset($news['id']))
	// 	{
	// 		$this->news->delete_news($id);
	// 		redirect('admin/news?delete=true');
	// 	}
	// 	else{
	// 		show_error('The admin you are trying to delete does not exist.');
	// 	}
	// }

}

?>