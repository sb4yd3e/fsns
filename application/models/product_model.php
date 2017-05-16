<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Product_model extends CI_Model {

    public function get_product_list_nav() {
        $query = $this->db->select('products.id,products.title,taxonomy_terms.id as term_id')
                ->from('products')
                ->join('taxonomy_terms', 'products.taxonomy_term_id = taxonomy_terms.id')
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

    public function get_other_product($product_id, $limit) {
        $sql = "SELECT products.id,products.title,cover,taxonomy_terms.title as term_title FROM products JOIN taxonomy_terms WHERE products.taxonomy_term_id = taxonomy_terms.id AND products.id NOT IN(" . $product_id . ") ORDER BY RAND() LIMIT " . $limit;
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function get_product_list($taxonomy_term_id = 0) {

        if ($taxonomy_term_id == 0) {
            $query = $this->db->select('products.id,products.title,products.group,products.body,cover,pdf,taxonomy_terms.title as term_title')->order_by('id')->from('products')->join('taxonomy_terms', 'products.taxonomy_term_id = taxonomy_terms.id')->get();
        } else {
            $query = $this->db->select('products.id,products.title,products.group,products.body,cover,pdf,taxonomy_terms.title as term_title')
                    ->where('taxonomy_term_id', $taxonomy_term_id)
                    ->order_by('products.group')
                    ->join('taxonomy_terms', 'products.taxonomy_term_id = taxonomy_terms.id')
                    //->group_by('products.group')
                    ->get('products');
        }
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }
    
    public function get_product_list_backend($taxonomy_term_id = 0) {

        if ($taxonomy_term_id == 0) {
            $query = $this->db->select('products.id,products.title,products.group,products.body,cover,pdf,taxonomy_terms.title as term_title')->order_by('id','DESC')->from('products')->join('taxonomy_terms', 'products.taxonomy_term_id = taxonomy_terms.id')->get();
        } else {
            $query = $this->db->select('products.id,products.title,products.group,products.body,cover,pdf,taxonomy_terms.title as term_title')
                    ->where('taxonomy_term_id', $taxonomy_term_id)
                    ->order_by('products.id','DESC')
                    ->join('taxonomy_terms', 'products.taxonomy_term_id = taxonomy_terms.id')
                    //->group_by('products.group')
                    ->get('products');
        }
        if ($query->num_rows() > 0) {
            return $query->result_array();
        }
        return array();
    }

    public function get_product($product_id) {
        $query = $this->db->select('products.id,products.title,products.group,products.body,cover,pdf,taxonomy_terms.id as term_id,taxonomy_terms.title as term_title')->order_by('id')->from('products')->join('taxonomy_terms', 'products.taxonomy_term_id = taxonomy_terms.id')->where('products.id', $product_id)->get();
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        return array();
    }

    public function get_product_search($string) {
        //$string_array = explode(' ',$string);
        $this->db->select('products.id,products.title,cover,taxonomy_terms.title as term_title,taxonomy_terms.weight as weight')
                ->order_by('id')
                ->from('products')
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

    public function product_delete($product_id) {
        $query = $this->db->select('cover,pdf')->where('id', $product_id)->get('products');
        if ($query->num_rows() > 0) {
            $product = $query->row_array();
            // Unlink Cover //
            if (file_exists('./' . UPLOAD_PATH . '/' . $product['cover'])) {
                unlink('./' . UPLOAD_PATH . '/' . $product['cover']);
            }
            // Unlink PDF //
            if (file_exists('./' . PDF_PATH . '/' . $product['pdf'])) {
                unlink('./' . PDF_PATH . '/' . $product['pdf']);
            }
            // Delete product by id
            $this->db->where('id', $product_id);
            $this->db->delete('products');
        }
    }

}
