<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');


class Dashboard extends CI_Controller {

	public $render_data = array();

	public function __construct() {
		parent::__construct();

		$this->template->set_template('admin');
	}


	public function index() {
		if(!is_group(array('admin','staff','sale'))){
			redirect('admin');
			exit();
		}
		//******* Defalut ********//
		$render_data['user'] = $this->session->userdata('fnsn');
		$this->template->write('title', 'Dashboard');
		$this->template->write('user_id', $render_data['user']['aid']);
		$this->template->write('user_name', $render_data['user']['name']);
		$this->template->write('user_group', $render_data['user']['group']);
		//******* Defalut ********//

		$this->template->write_view('content', 'admin/dashboard', $this->render_data);
		$this->template->render();

	}

}

?>