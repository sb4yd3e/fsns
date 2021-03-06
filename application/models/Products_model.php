<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Products_model extends CI_Model
{
    var $table = 'products';
    var $column_order = array(null, 'title', 'model_code', 'group', 'online', 'normal_price', 'special_price', 'in_stock', null);
    var $column_search = array('title', 'model_code', 'group');
    var $order = array('at_update' => 'desc');

    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }


    private function _get_datatables_query()
    {

        $this->db->from($this->table);
        $i = 0;
        foreach ($this->column_search as $item) {
            if ($_POST['search']['value']) {
                if ($i === 0) {
                    $this->db->group_start();
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if (count($this->column_search) - 1 == $i)
                    $this->db->group_end();
            }
            $i++;
        }

        if (isset($_POST['group']) && $_POST['group'] != "") {
            $this->db->where("group", $_POST['group']);
        }

        if (isset($_POST['online']) && $_POST['online'] != "") {
            $this->db->where("online", $_POST['online']);
        }
        if (isset($_POST['in_stock']) && $_POST['in_stock'] != "") {
            $this->db->where("in_stock", $_POST['in_stock']);
        }
        $this->db->where("is_active", "T");
        if (isset($_POST['order'])) {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    public function get_all()
    {
        $this->_get_datatables_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    public function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->from($this->table);
        $this->db->where("is_active", "T");
        return $this->db->count_all_results();
    }


    function add_product($data_create)
    {
        $this->db->insert('products', $data_create);
        return $this->db->insert_id();
    }

    function add_product_alt($data_create)
    {
        $this->db->insert('product_attribute', $data_create);
        return $this->db->insert_id();
    }

    function get_attribute($pid)
    {
        return $this->db->where('pid', $pid)->where("is_active", "T")->get('product_attribute')->result_array();
    }

    public function group_all()
    {
        return $this->db->select('group')->group_by('group')->get($this->table)->result_array();
    }

    public function get_product_list_nav()
    {
        $query = $this->db->select('products.id,products.title,taxonomy_terms.id as term_id')
            ->from('products')
            ->join('taxonomy_terms', 'products.taxonomy_term_id = taxonomy_terms.id')
            ->where("products.is_active", "T")
            ->get();
        if ($query->num_rows() > 0) {
            $return_array = array();
            $result = $query->result_array();
            foreach ($result as $product) {
                $return_array[$product['term_id']][] = $product;
            }
            return $return_array;
        }
        return array();
    }

    public function get_other_product($product_id, $limit)
    {
        $sql = "SELECT products.id,products.title,cover,taxonomy_terms.title as term_title FROM products  JOIN taxonomy_terms WHERE products.taxonomy_term_id = taxonomy_terms.id AND products.is_active = 'T' AND products.id NOT IN(" . $product_id . ") ORDER BY RAND() LIMIT " . $limit;
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function get_product_list($taxonomy_term_id = 0)
    {

        if ($taxonomy_term_id == 0) {
            $query = $this->db->select('products.id,products.model_code,products.title,products.special_price,products.normal_price,products.online,products.group,products.body,products.online,cover,pdf,taxonomy_terms.title as term_title')->order_by('products.title')->from('products')->where("products.is_active", "T")->join('taxonomy_terms', 'products.taxonomy_term_id = taxonomy_terms.id')->get();
        } else {
            $query = $this->db->select('products.id,products.model_code,products.title,products.special_price,products.normal_price,products.online,products.group,products.body,products.online,cover,pdf,taxonomy_terms.title as term_title')
                ->where('taxonomy_term_id', $taxonomy_term_id)
                ->where("products.is_active", "T")
                ->order_by('products.group')
                ->order_by('products.title')
                ->join('taxonomy_terms', 'products.taxonomy_term_id = taxonomy_terms.id')
                //->group_by('products.group')
                ->get('products');
        }
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }

    public function get_product_list_backend($taxonomy_term_id = 0)
    {

        if ($taxonomy_term_id == 0) {
            $query = $this->db->select('products.id,products.title,products.model_code,products.group,products.body,cover,pdf,taxonomy_terms.title as term_title')->order_by('id', 'DESC')->from('products')->where("products.is_active", "T")->join('taxonomy_terms', 'products.taxonomy_term_id = taxonomy_terms.id')->get();
        } else {
            $query = $this->db->select('products.id,products.title,products.model_code,products.group,products.body,cover,pdf,taxonomy_terms.title as term_title')
                ->where('taxonomy_term_id', $taxonomy_term_id)
                ->where("products.is_active", "T")
                ->order_by('products.id', 'DESC')
                ->join('taxonomy_terms', 'products.taxonomy_term_id = taxonomy_terms.id')
                //->group_by('products.group')
                ->get('products');
        }
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }

    public function get_product($product_id)
    {
        $query = $this->db->select('products.*,taxonomy_terms.id as term_id,taxonomy_terms.title as term_title,taxonomy_terms.id as sub_cat_id,taxonomy_terms.parent_id as cat_id')->order_by('id')->from('products')->where("products.is_active", "T")->join('taxonomy_terms', 'products.taxonomy_term_id = taxonomy_terms.id')->where('products.id', $product_id)->get();
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        return null;
    }

    public function get_product_alt($id)
    {
        return $this->db->where('pid', $id)->where("is_active", "T")->order_by('pa_id', 'asc')->get('product_attribute')->result_array();
    }

    public function get_product_search($string)
    {
        //$string_array = explode(' ',$string);
        $this->db->select('products.id,products.title,cover,taxonomy_terms.title as term_title,taxonomy_terms.weight as weight')
            ->order_by('id')
            ->from('products')
            ->where('products.is_active','T')
            ->join('taxonomy_terms', 'products.taxonomy_term_id = taxonomy_terms.id');

        $this->db->or_like('products.body', $string);
        $this->db->order_by('weight');
        /*
          foreach($string_array as $each_string)
          {

          }
         * 
         */
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $result = $query->result_array();
            $found = array();
            $not_found = array();
            foreach ($result as $product) {
                if (strstr(strtolower($product['term_title']), $string) !== false) {

                    $found[] = $product;
                } else {
                    $not_found[] = $product;
                }
            }
            return array_merge($found, $not_found);
        }
        return array();
    }

    public function product_delete($product_id)
    {
        $query = $this->db->select('cover,pdf')->where('id', $product_id)->get('products');
        if ($query->num_rows() > 0) {
            $product = $query->row_array();
            // Unlink Cover //
//            if (file_exists('./' . UPLOAD_PATH . '/' . $product['cover'])) {
//                @unlink('./' . UPLOAD_PATH . '/' . $product['cover']);
//            }
            // Unlink PDF //
//            if (file_exists('./' . PDF_PATH . '/' . $product['pdf'])) {
//                @unlink('./' . PDF_PATH . '/' . $product['pdf']);
//            }
            // Delete product by id
            $this->db->where('id', $product_id);
            $this->db->update('products', array('is_active'=>'F'));
        }
    }

    public function save_product($pid, $param)
    {
        return $this->db->where('id', $pid)->update('products', $param);
    }

    public function save_product_alt($paid, $param)
    {
        return $this->db->where('pa_id', $paid)->update('product_attribute', $param);
    }

    public function delete_alt($key)
    {
        return $this->db->where('pa_id', $key)->update('product_attribute', array('is_active'=>'F'));
    }

    public function get_title_cat($cid)
    {
        $db = $this->db->select('title')->where('id', $cid)->get('taxonomy_terms')->row_array();
        return $db['title'];
    }
}
