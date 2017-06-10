<?php

class Startdata
{

    function initialize()
    {
        $ci = &get_instance();

        $ci->load->database();
        $setting = $ci->db->select('site_title,site_description,site_keyword,email_for_contact,google_plus,facebook,instagram')->get('setting')->row_array();
        $ci->setting_data = $setting;
        $banner = $ci->db->select('image,link,visible,delay')->get('banner')->row_array();
        $ci->banner_data = $banner;

    }

}
