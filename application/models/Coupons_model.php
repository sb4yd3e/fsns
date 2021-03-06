<?php


class Coupons_model extends CI_Model
{
    var $table = 'coupons';
    var $column_order = array('code', 'discount','expired',null,null,null);
    var $column_search = array('code', 'discount','expired');
    var $order = array('coid' => 'desc');

    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    
    private function _get_datatables_query()
    {

        $this->db->from($this->table);

        $i = 0;

        foreach ($this->column_search as $item)
        {
            if($_POST['search']['value'])
            {
                if($i===0)
                {
                    $this->db->group_start();
                    $this->db->like($item, $_POST['search']['value']);
                }
                else
                {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if(count($this->column_search) - 1 == $i)
                    $this->db->group_end();
                }
                $i++;
            }

        if(isset($_POST['order']))
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } 
        else if(isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
    

    function get_coupon($id)
    {
        return $this->db->get_where($this->table,array('coid'=>$id))->row_array();
    }
    

    function get_all_coupons()
    {
        $this->_get_datatables_query();
        if($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    function add_coupon($params)
    {
        $this->db->insert($this->table,$params);
        return $this->db->insert_id();
    }
    

    function save_coupon($id,$params)
    {
        $this->db->where('coid',$id);
        return $this->db->update($this->table,$params);

    }
    

    function delete_coupon($id)
    {
        return $response = $this->db->delete($this->table,array('coid'=>$id));
        
    }

}
