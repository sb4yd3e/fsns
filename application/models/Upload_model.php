<?php
/* 
 * Generated by CRUDigniter v3.0 Beta 
 * www.crudigniter.com
 */
 
class Upload_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    /*
     * Get upload by ufid
     */
    function get_upload($ufid)
    {
        return $this->db->get_where('uploads',array('ufid'=>$ufid))->row_array();
    }
    
    /*
     * Get all uploads
     */
    function get_all_uploads()
    {
        return $this->db->get('uploads')->result_array();
    }
    
    /*
     * function to add new upload
     */
    function add_upload($params)
    {
        $this->db->insert('uploads',$params);
        return $this->db->insert_id();
    }
    
    /*
     * function to update upload
     */
    function update_upload($ufid,$params)
    {
        $this->db->where('ufid',$ufid);
        $response = $this->db->update('uploads',$params);
        if($response)
        {
            return "upload updated successfully";
        }
        else
        {
            return "Error occuring while updating upload";
        }
    }
    
    /*
     * function to delete upload
     */
    function delete_upload($ufid)
    {
        $response = $this->db->delete('uploads',array('ufid'=>$ufid));
        if($response)
        {
            return "upload deleted successfully";
        }
        else
        {
            return "Error occuring while deleting upload";
        }
    }
}