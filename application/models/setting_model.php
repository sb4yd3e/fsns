<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Setting_model extends CI_Model
{
    public function get_setting()
    {
        $query = $this->db->limit(1)->get('setting');
        return $query->row_array();
    }

    public function update_setting($param){
        return $this->db->update('setting',$param);
    }
}
