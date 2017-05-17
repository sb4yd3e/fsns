<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Setting_model extends CI_Model
{
    public function get_setting()
    {
        $query = $this->db->limit(1)->get('setting');
        if ($query->num_rows() > 0)
        {
            return $query->row_array();
        }
        else
        {
            return array(
                'email_for_contact' => '',
                'contact_page_id' => NULL,
                'profile_page_id' => NULL,
                'distributor_page_id' => NULL
                );
        }
    }

    public function update_setting($param){
        return $this->db->update('setting',$param);
    }
}
