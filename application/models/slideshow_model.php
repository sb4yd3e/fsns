<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Slideshow_model extends CI_Model{
    function get_active_slideshow()
    {
        $query = $this->db->select('slideshow_image,slideshow_caption,slideshow_url')->where('slideshow_image <> ','')->where('slideshow_caption <> ','')->order_by('weight')->get('slideshows');

        if ($query->num_rows() > 0)
        {
            return $query->result_array();
        }
        return array();
            
    }
}

?>
