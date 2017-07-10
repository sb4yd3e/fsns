<?php


class Product_category_model extends CI_Model
{
    public function delete_taxonomy_term($internal_key, $term_id) {
        $query = $this->db->select('id')->where('internal_key', $internal_key)->get('taxonomy_vocabularies');
        if ($query->num_rows() > 0) {
            $vocab_id = $query->row()->id;
        } else {
            return false;
        }

        $query = $this->db->select('header_img, cover_img')->where('vocabulary_id', $vocab_id)->get('taxonomy_terms');
        if ($query->num_rows() > 0) {
            $header_img = $query->row()->header_img;
            $cover_img = $query->row()->cover_img;

            /* Delete Ext. Img */
            if ($header_img != '') {
                @unlink('./uploads/category_header_img/' . $header_img);
            }
            if ($cover_img != '') {
                @unlink('./uploads/category_cover_img/' . $cover_img);
            }
        } else {
            return false;
        }

        $this->db->where('id', $term_id)
        ->where('vocabulary_id', $vocab_id)
        ->delete('taxonomy_terms');
        return true;
    }

    public function edit_taxonomy_term($internal_key, $data, $term_id) {
        $query = $this->db->select('id')->where('internal_key', $internal_key)->get('taxonomy_vocabularies');
        if ($query->num_rows() > 0) {
            $data['vocabulary_id'] = $query->row()->id;

            // Delete Old Image //
            if (isset($data['term_cover'])) {
                $query = $this->db->select('header_img, cover_img')->where('id', $term_id)->get('taxonomy_terms');
                if ($query->num_rows() > 0) {
                    $header_img = $query->row()->header_img;
                    $cover_img = $query->row()->cover_img;

                    /* Delete Ext. Img */
                    if ($header_img != '' && isset($data['header_img'])) {
                        @unlink('./uploads/category_header_img/' . $header_img);
                    }
                    if ($cover_img != '' && isset($data['cover_img'])) {
                        @unlink('./uploads/category_cover_img/' . $cover_img);
                    }
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }



        $this->db->where('id', $term_id);
        $this->db->update('taxonomy_terms', $data);
        return $this->db->insert_id();
    }

    public function add_taxonomy_term($internal_key, $data) {
        $query = $this->db->select('id')->where('internal_key', $internal_key)->get('taxonomy_vocabularies');
        if ($query->num_rows() > 0) {
            $data['vocabulary_id'] = $query->row()->id;
        } else {
            return false;
        }

        $this->db->insert('taxonomy_terms', $data);
        return $this->db->insert_id();
    }
    
    public function get_taxonomy_id($term_id)
    {
        return $this->db->where('id',$term_id)->get('taxonomy_terms')->row_array();
    }
    
    public function get_taxonomy_term($internal_key) {
        $terms_array = array();

        // Load Vocab id
        $vocab = $this->db
        ->select('taxonomy_terms.id, taxonomy_terms.title,taxonomy_terms.body,taxonomy_terms.header_img,taxonomy_terms.cover_img, parent_id, weight')
        ->from('taxonomy_vocabularies')
        ->join('taxonomy_terms', 'taxonomy_terms.vocabulary_id = taxonomy_vocabularies.id')
        ->where('internal_key', $internal_key)
        ->order_by('id', 'asc')
        ->get();

        if ($vocab->num_rows() > 0) {
            $tmp_terms = $vocab->result_array();
            // return $tmp_terms;
            // Manipulate Terms //
            foreach ($tmp_terms as $term) {
                if ($term['parent_id'] == null) {
                    $terms_array[$term['id']] = array(
                        'term_id' => $term['id'],
                        'title' => $term['title'],
                        'header_img' => $term['header_img'],
                        'cover_img' => $term['cover_img'],
                        'body' => $term['body'],
                        'weight' => $term['weight'],
                        'children' => array()
                        );
                } else {
                    if (isset($terms_array[$term['parent_id']])) { // Lv 2
                        $terms_array[$term['parent_id']]['children'][$term['id']] = array(
                            'term_id' => $term['id'],
                            'title' => $term['title'],
                            'header_img' => $term['header_img'],
                            'cover_img' => $term['cover_img'],
                            'body' => $term['body'],
                            'weight' => $term['weight'],
                            );
                    } else { // Lv 3
                        foreach ($terms_array as $key => $terms_array_row) {
                            if (isset($terms_array_row['children'][$term['parent_id']])) {
                                $terms_array[$key]['children'][$term['parent_id']]['children'][$term['id']] = array(
                                    'term_id' => $term['id'],
                                    'title' => $term['title'],
                                    'header_img' => $term['header_img'],
                                    'cover_img' => $term['cover_img'],
                                    'body' => $term['body'],
                                    'weight' => $term['weight'],
                                    );
                            }
                        }
                    }
                }
            }
        }
        // Sort by Weight //
        $tmp_array = array();
        $back_array = array();

        foreach ($terms_array as $key => $row) {
            $tmp_array[$key] = $row['weight'];

            $tmp2_array = array();
            $back2_array = array();

            if (isset($row['children'])) {

                foreach ($row['children'] as $key2 => $row2) {
                    $tmp2_array[$key2] = $row2['weight'];

                    if (isset($row2['children'])) {
                        $tmp3_array = array();
                        $back3_array = array();
                        foreach ($row2['children'] as $key3 => $row3) {
                            $tmp3_array[$key3] = $row3['weight'];
                        }
                        asort($tmp3_array);

                        foreach ($tmp3_array as $tmpkey => $tmprow) {
                            $back3_array[] = $terms_array[$key]['children'][$key2]['children'][$tmpkey];
                        }
                        $terms_array[$key]['children'][$key2]['children'] = $back3_array;
                    }
                }
                asort($tmp2_array);
                foreach ($tmp2_array as $tmpkey => $tmprow) {
                    $back2_array[] = $terms_array[$key]['children'][$tmpkey];
                }
                $terms_array[$key]['children'] = $back2_array;
            }
        }

        asort($tmp_array);
        foreach ($tmp_array as $key => $row) {
            $back_array[] = $terms_array[$key];
        }
        $terms_array = $back_array;

        return $terms_array;
    }
}
