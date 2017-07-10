<?php


class Payment_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    /*
     * Get upload by pm_id
     */
    function get_payment($pm_id)
    {
        return $this->db->get_where('payment_gateway', array('pm_id' => $pm_id))->row_array();
    }

    /*
     * Get all uploads
     */
    function get_all_payments()
    {
        return $this->db->get('payment_gateway')->result_array();
    }

    /*
     * function to add new upload
     */
    function add_payment($params)
    {
        $this->db->insert('payment_gateway', $params);
        return $this->db->insert_id();
    }

    /*
     * function to update upload
     */
    function update_payment($pm_id, $params)
    {
        $this->db->where('pm_id', $pm_id);
        $response = $this->db->update('payment_gateway', $params);
        if ($response) {
            return "upload updated successfully";
        } else {
            return "Error occuring while updating upload";
        }
    }

    /*
     * function to delete upload
     */
    function delete_payment($pm_id)
    {
        $response = $this->db->delete('payment_gateway', array('pm_id' => $pm_id));
        if ($response) {
            return "upload deleted successfully";
        } else {
            return "Error occuring while deleting upload";
        }
    }
}
