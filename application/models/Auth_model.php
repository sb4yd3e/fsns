<?php
class Auth_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	function admin_login($param){
		return $this->db->select("aid,username,password,name,email,phone,admin_group")
		->where("username",$param['username'])
		->where("password",md5($param['password']))
		->get("admins")
		->row_array();
	}

}
?>