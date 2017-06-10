<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Content_model extends CI_Model
{

    public function count_news()
    {
        return $this->db->count_all_results('contents');
    }

    public function list_news($limit, $start)
    {
        $query = $this->db
            ->limit($limit, $start)
            ->order_by('created_at', 'desc')
            ->get('contents');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }

    public function get_content_list_by_created()
    {
        $query = $this->db->order_by('created_at', 'desc')->get('contents');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }

    public function get_content_list()
    {
        $query = $this->db->order_by('modified_at', 'desc')->get('contents');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }

    public function get_content_list_page_for_option()
    {
        $query = $this->db->select('id,title')->where('is_page', 1)->order_by('modified_at', 'desc')->get('contents');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }

    public function get_content_list_no_page()
    {
        $query = $this->db->where('is_page', 0)->order_by('modified_at', 'desc')->get('contents');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }

    public function get_content_slideshow()
    {
        $query = $this->db->where('is_slideshow', 1)->where('slideshow !=', '')->order_by('created_at')->get('contents');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }

    public function get_content($content_id)
    {
        $query = $this->db->where('id', $content_id)->get('contents');
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        return array();
    }

    public function content_delete($content_id)
    {
        $query = $this->db->select('cover,slideshow')->where('id', $content_id)->get('contents');
        if ($query->num_rows() > 0) {
            $content = $query->row_array();
            // Unlink Cover //
            if (file_exists('./' . UPLOAD_PATH . '/' . $content['cover'])) {
                unlink('./' . UPLOAD_PATH . '/' . $content['cover']);
            }
            // Unlink Slideshow //
            if (file_exists('./' . SLIDESHOW_PATH . '/' . $content['slideshow']) && $content['slideshow'] != '') {
                unlink('./' . SLIDESHOW_PATH . '/' . $content['slideshow']);
            }
            // Delete product by id
            $this->db->where('id', $content_id);
            $this->db->delete('contents');
        }

    }
}