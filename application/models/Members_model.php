<?php


class Members_model extends CI_Model
{
    var $table = 'users';
    var $column_order = array('email', 'name', 'account_type', 'staff_id', 'is_active', null);
    var $column_search = array('email', 'name', 'account_type', 'staff_id', 'is_active');
    var $order = array('uid' => 'desc');

    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    private function _get_datatables_query()
    {

        $this->db->from($this->table);

        $i = 0;
        $user = $this->session->userdata('fnsn');
        foreach ($this->column_search as $item) // loop column 
        {
            if ($_POST['search']['value']) // if datatable send POST for search
            {

                if ($i === 0) // first loop
                {
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i) //last loop
                    $this->db->group_end(); //close bracket
            }
            $i++;
        }


        if (isset($_POST['is_active']) && $_POST['is_active'] != "") {
            $this->db->where("is_active", $_POST['is_active']);
        }


        if (isset($_POST['account_type']) && $_POST['account_type'] != "") {
            $this->db->where("account_type", $_POST['account_type']);
        }


        if (isset($_POST['staff_id']) && $_POST['staff_id'] != "" && $_POST['staff_id'] != "0") {
            if (is_group('sale')) {
                $this->db->where("staff_id", $user['aid']);
            } else {
                $this->db->where("staff_id", $_POST['staff_id']);
            }

        } else {
            if (is_group('sale')) {
                $this->db->where("staff_id", $user['aid']);
            }
        }


        if (isset($_POST['order'])) // here order processing
        {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    /*
     * Get admin by aid
     */
    function get_members($id)
    {
        return $this->db->get_where('users', array('uid' => $id))->row_array();
    }

    /*
     * Get all admins
     */
    function get_all_members()
    {
        $this->_get_datatables_query();
        if ($_POST['length'] != -1)
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

    /*
     * function to add new admin
     */
    function add_members($params)
    {
        $this->db->insert('users', $params);
        return $this->db->insert_id();
    }

    /*
     * function to update admin
     */
    function update_members($id, $params)
    {
        $this->db->where('uid', $id);
        return $this->db->update('users', $params);

    }

    /*
     * function to delete admin
     */
    function delete_members($id)
    {
        return $response = $this->db->delete('users', array('uid' => $id));

    }

    function get_all_admins()
    {
        return $this->db->where('admin_group','sale')->get('admins')->result_array();
    }

    function get_list_members()
    {
        return $this->db->get('users')->result_array();
    }

    function get_user_by_token($token)
    {
        if ($this->db->select('uid')->where('token', $token)->get('users')->row_array()) {
            return $this->db->where('token', $token)->update('users', array('token' => time(), 'is_active' => 1));
        } else {
            return false;
        }
    }

    function get_user_by_token_forgot($token)
    {
        if ($d = $this->db->select('uid')->where('token', $token)->get('users')->row_array()) {
            return $d['uid'];
        } else {
            return false;
        }
    }

    function forgot_password($email)
    {
        if ($this->db->select('uid')->where('email', $email)->get('users')->row_array()) {
            $t = md5(time());
            $this->db->where('email', $email)->update('users', array('token' => $t));
            return $t;
        } else {
            return false;
        }
    }

    function reset_password($uid)
    {
        $pass = rand(100000, 10000000);
        $this->db->where('uid', $uid)->update('users', array('password' => md5($pass), 'token' => time()));
        return $pass;
    }
    function transfer_order($old_id,$new_id){
        return $this->db->where('sale_id',$old_id)->where('order_status !=','success')->where('order_status !=','cancel')->update('orders',array('sale_id'=>$new_id));
    }
}
