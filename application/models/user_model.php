<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User_model extends CI_Model
{
    public function login($email, $password)
    {
        //->where('email',$email)
        $query = $this->db->select('role')->where('password',md5($this->config->item('encryption_key').$password.$this->config->item('encryption_key')))->get('users');
        if ($query->num_rows()>0)
        {
            session_start ();
            $_SESSION['IsAuthorized'] = true;
            $this->session->set_userdata('role',$query->row()->role);
            return true;
        }
        return false;
    }
    
    public function get_current_role()
    {
        return $this->session->userdata('role');
    }
    
    
    public function logout()
    {
        $this->session->sess_destroy();
    }
}