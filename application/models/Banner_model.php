<?php


class Banner_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    /*
     * Get banner by bid
     */
    function get_banner()
    {
        return $this->db->get('banner')->row_array();
    }
    
    
    function update_banner($params)
    {
        return $this->db->update('banner',$params);
    }

}
