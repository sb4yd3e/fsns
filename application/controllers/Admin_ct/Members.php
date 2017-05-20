<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');


class News extends CI_Controller {

	public $render_data = array();

	public function __construct() {
		parent::__construct();

		$this->template->set_template('admin');
		$this->load->model('News_model','news');
	}


	public function index() {
		if(!is_group('admin')){
			redirect('admin');
			exit();
		}
		//******* Defalut ********//
		$render_data['user'] = $this->session->userdata('fnsn');
		$this->template->write('title', 'News');
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
					"url": "'.base_url('admin/news/ajax').'",
					"type": "POST"
				},
				"columnDefs": [
				{ 
					"targets": [2], 
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
		
		$this->template->write('js', $js);
		$this->template->write_view('content', 'admin/news/index', $render_data);
		$this->template->render();

	}

	public function ajax(){
		if (!$this->input->is_ajax_request() || !is_group('admin')) {
			exit('No direct script access allowed');
		}

		$list = $this->news->get_all_news();
		$data = array();
		$no = $this->input->post('start');
		foreach ($list as $news) {
			$no++;
			$row = array();
			$row[] = '<img src="'.base_url('timthumb.php?src=').base_url('uploads/news/'.$news->cover).'&w=150&h=150&z=c" style="width:150px; height:auto;">';
			$row[] = '<a href="'.base_url('news/'.$news->id.'/'.url_title($news->title)).'" target="_blank"><strong>'.$news->title.'</strong></a><br><i>'.short_content($news->body,200).'</i>';
			$row[] = '<a href="'.base_url('admin/news/edit/'.$news->id).'" class="label label-warning"><i class="fa fa-pencil"></i> Edit</a> 
			<a href="'.base_url('admin/news/delete/'.$news->id).'" class="label label-danger"  onclick="return confirm(\'Are you sure?\')"><i class="fa fa-times-circle"></i> Delete</a>';
			$data[] = $row;
		}

		$output = array(
			"draw" => $this->input->post('draw'),
			"recordsTotal" => $this->news->count_all(),
			"recordsFiltered" => $this->news->count_filtered(),
			"data" => $data,
			);
		echo json_encode($output);
	}

	function add()
	{   
		$this->load->library('form_validation');
		if(!is_group(array('admin'))){
			redirect('admin');
			exit();
		}
		$this->load->library('upload');

		$config['upload_path'] = './' . NEWS_PATH . '/';
		$config['allowed_types'] = 'gif|jpg|png';
		$config['encrypt_name'] = true;
		$this->upload->initialize($config);
		$this->form_validation->set_error_delimiters('<div class="alert alert-warning">', '</div>');
		$this->form_validation->set_rules('title', 'Content title', 'required|max_length[255]');
		$this->form_validation->set_rules('body', 'Content Description', 'required|min_length[10]');
		if($this->form_validation->run())     
		{   
			$data_create = array(
				'title' => $this->input->post('title', TRUE),
				'body' => $this->input->post('body', FALSE),
				'created_at' => time(),
				'modified_at' => time()
				);

			if ($this->upload->do_upload('cover')) {
                    //Get Cover DATA
				$upload_data = $this->upload->data();
				$data_create['cover'] = $upload_data['file_name'];
				$this->news->add_news($data_create);
				redirect('admin/news?add=true');
			} else {
				redirect('admin/news/add?upload=error');
			}

		}
		else
		{            
			$js = '  $(function(){
				CKEDITOR.replace( "body" ,{
					filebrowserBrowseUrl : "'.base_url('js/ckfinder/ckfinder.html').'",
					filebrowserImageBrowseUrl : "'.base_url('js/ckfinder/ckfinder.html?type=Images').'",
					filebrowserFlashBrowseUrl : "'.base_url('js/ckfinder/ckfinder.html?type=Flash').'",
					filebrowserUploadUrl : "'.base_url('js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files').'",
					filebrowserImageUploadUrl : "'.base_url('js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images').'",
					filebrowserFlashUploadUrl : "'.base_url('js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash').'"
				});
				$("#slideshow_upload").hide();
				if ($("#is_slideshow").attr("checked") == "checked")
				{
					$("#slideshow_upload").show();
				}
				$("#is_slideshow").click(function(){
					if ($(this).attr("checked") == "checked")
					{
						$("#slideshow_upload").slideDown();
					}
					else
					{
						$("#slideshow_upload").slideUp();
					}
				});  
			});';
			if($this->input->get('upload')=="error"){
				$js .= '$.notify("Can\'n upload cover!.", "warning");';
			}
			$this->template->write('js', $js);
        //******* Defalut ********//
			$render_data['user'] = $this->session->userdata('fnsn');
			$this->template->write('title', 'Add new content');
			$this->template->write('user_id', $render_data['user']['aid']);
			$this->template->write('user_name', $render_data['user']['name']);
			$this->template->write('user_group', $render_data['user']['group']);
        //******* Defalut ********//
			$this->template->write_view('content', 'admin/news/add', $render_data);
			$this->template->render();
		}

	}

	function edit($id)
	{   
		$content = $this->news->get_news($id);
		$this->load->library('form_validation');
		if(!is_group(array('admin')) || !$content){
			redirect('admin');
			exit();
		}
		$this->load->library('upload');

		$config['upload_path'] = './' . NEWS_PATH . '/';
		$config['allowed_types'] = 'gif|jpg|png';
		$config['encrypt_name'] = true;
		$this->upload->initialize($config);
		$this->form_validation->set_error_delimiters('<div class="alert alert-warning">', '</div>');
		$this->form_validation->set_rules('title', 'Content title', 'required|max_length[255]');
		$this->form_validation->set_rules('body', 'Content Description', 'required|min_length[10]');
		if($this->form_validation->run())     
		{   
			$data_update = array(
				'title' => $this->input->post('title', TRUE),
				'body' => $this->input->post('body', FALSE),
				'modified_at' => time()
				);
			if($_FILES['cover']){
				if ($this->upload->do_upload('cover')) {
                    //Get Cover DATA
					$upload_data = $this->upload->data();
					$data_update['cover'] = $upload_data['file_name'];
					$this->news->update_news($id,$data_update);
					redirect('admin/news/edit/'.$id.'?save=true');
				} else {
					redirect('admin/news/edit/'.$id.'?upload=error');
				}
			}else{
				$this->news->update_news($id,$data_update);
				redirect('admin/news/edit/'.$id.'?save=true');
			}

		}
		else
		{            
			$js = '$(function(){
				CKEDITOR.replace( "body" ,{
					filebrowserBrowseUrl : "'.base_url('js/ckfinder/ckfinder.html').'",
					filebrowserImageBrowseUrl : "'.base_url('js/ckfinder/ckfinder.html?type=Images').'",
					filebrowserFlashBrowseUrl : "'.base_url('js/ckfinder/ckfinder.html?type=Flash').'",
					filebrowserUploadUrl : "'.base_url('js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files').'",
					filebrowserImageUploadUrl : "'.base_url('js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images').'",
					filebrowserFlashUploadUrl : "'.base_url('js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash').'"
				});
				$("#slideshow_upload").hide();
				if ($("#is_slideshow").attr("checked") == "checked")
				{
					$("#slideshow_upload").show();
				}
				$("#is_slideshow").click(function(){
					if ($(this).attr("checked") == "checked")
					{
						$("#slideshow_upload").slideDown();
					}
					else
					{
						$("#slideshow_upload").slideUp();
					}
				});  
			});';
			if($this->input->get('upload')=="error"){
				$js .= '$.notify("Can\'n upload cover!.", "warning");';
			}
			if($this->input->get('save')=="true"){
				$js .= '$.notify("Save data success.", "success");';
			}
			$render_data['content'] = $content;
			$this->template->write('js', $js);
        //******* Defalut ********//
			$render_data['user'] = $this->session->userdata('fnsn');
			$this->template->write('title', 'Edit content');
			$this->template->write('user_id', $render_data['user']['aid']);
			$this->template->write('user_name', $render_data['user']['name']);
			$this->template->write('user_group', $render_data['user']['group']);
        //******* Defalut ********//
			$this->template->write_view('content', 'admin/news/edit', $render_data);
			$this->template->render();
		}

	}


	function delete($id){
		if(!is_group(array('admin'))){
			redirect('admin');
			exit();
		}
		$news = $this->news->get_news($id);
		if(isset($news['id']))
		{
			$this->news->delete_news($id);
			redirect('admin/news?delete=true');
		}
		else{
			show_error('The admin you are trying to delete does not exist.');
		}
	}

}

?>