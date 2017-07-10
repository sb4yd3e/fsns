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

			redirect('orders');


	}

}

?>