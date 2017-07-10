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

	function member_login($param){
//        $this->output->enable_profiler(TRUE);
        $query= $this->db->select("uid,email,account_type,name,phone,staff_id,business_name")
            ->where("email",$param['email'])
            ->where("password",md5($param['password']))
            ->where("is_active",1)
            ->get("users")
            ->row_array();
        return $query;
//        print_r($query);

    }

}
?>