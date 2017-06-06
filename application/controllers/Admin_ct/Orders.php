<?php

class Orders extends CI_Controller
{
    public $render_data = array();

    function __construct()
    {
        parent::__construct();
        $this->template->set_template('admin');
        $this->load->model('Orders_model', 'orders');
    }

    public function index()
    {
        if (!is_group(array('admin', 'staff', 'sale'))) {
            redirect('admin');
            exit();
        }

        //******* Defalut ********//
        $render_data['user'] = $this->session->userdata('fnsn');
        $this->template->write('title', 'Order management');
        $this->template->write('user_id', $render_data['user']['aid']);
        $this->template->write('user_name', $render_data['user']['name']);
        $this->template->write('user_group', $render_data['user']['group']);
        //******* Defalut ********//


        // ====== Java script Data tabale ======= //
        $js = 'var table;
        $(document).ready(function() {
            table = $("#table").DataTable({ 
                "processing": true,
                "serverSide": true,
                "order": [],
                "ajax": {
                    "url": "' . base_url('admin/orders/ajax') . '",
                    "type": "POST",
                    data:function(data){
                        data.status = $("#status").val();
                        data.order_type = $("#order_type").val();
                        data.uid = $("#uid").val();
                    }
                },
				"columnDefs": [
				{ 
					"targets": [6], 
					"orderable": false,
				},
				],
            });
            
            $("#show").change(function () {
                $("#table_length select").val($(this).val());
                $("#table_length select").trigger("change");
            });
            $("#search").on("keyup", function () {
                $("#table_filter input[type=\"search\"]").val($(this).val());
                $("#table_filter input[type=\"search\"]").trigger("keyup");
            });
            $("#status").change(function(){
                var table = $("#table").DataTable();
                table.ajax.reload();
            });$("#ordet_type").change(function(){
                var table = $("#table").DataTable();
                table.ajax.reload();
            });$("#uid").change(function(){
                var table = $("#table").DataTable();
                table.ajax.reload();
            });
            
            
            $(document).on("click",".ajax-user",function(){
            var uid = $(this).data("uid");
            var oid = $(this).data("oid");
            $("#ajax-result").html("Loading...");
            $.ajax({
              method: "POST",
              url: "' . base_url('admin/orders/ajax_user') . '",
              data: { uid: uid,oid:oid}
            })
              .done(function( msg ) {
                $("#ajax-result").html(msg);
              });
            });
            
            $(document).on("click",".ajax-product",function(){
            var oid = $(this).data("oid");
            $("#ajax-result").html("Loading...");
            $.ajax({
              method: "POST",
              url: "' . base_url('admin/orders/ajax_product') . '",
              data: { oid: oid}
            })
              .done(function( msg ) {
                $("#ajax-result").html(msg);
              });
            });
            
            $(document).on("click",".ajax-status",function(){
            var oid = $(this).data("oid");
            $("#ajax-result").html("Loading...");
            $.ajax({
              method: "POST",
              url: "' . base_url('admin/orders/ajax_status') . '",
              data: { oid: oid}
            })
              .done(function( msg ) {
                $("#ajax-result").html(msg);
              });
            });
            
            
             $(document).on("click",".ajax-file",function(){
            var oid = $(this).data("oid");
            $("#ajax-result").html("Loading...");
            $.ajax({
              method: "POST",
              url: "' . base_url('admin/orders/ajax_file') . '",
              data: { oid: oid,uid:$(this).data("uid")}
            })
              .done(function( msg ) {
                $("#ajax-result").html(msg);
              });
            });
            
        });';

        if ($this->input->get('uid')) {
            $js .= '$("#uid").val("' . $this->input->get('uid') . '").trigger("change");';
        }
        if ($this->input->get('add') == "true") {
            $js .= '$.notify("Add new order success.", "success");';
        }
        if ($this->input->get('delete') == "true") {
            $js .= '$.notify("Delete order success", "success");';
        }
        if ($this->input->get('save') == "true") {
            $js .= '$.notify("Save order success.", "success");';
        }
        $this->load->model("Members_model", "members");
        $members = $this->members->get_list_members();
        $arr_member = array('' => 'Show All');
        foreach ($members as $member) {
            $arr_member[$member['uid']] = $member['name'];
        }
        $render_data['members'] = $arr_member;
        $this->template->write('js', $js);
        $this->template->write_view('content', 'admin/orders/index', $render_data);
        $this->template->render();
    }

    public function ajax()
    {
        if (!$this->input->is_ajax_request() || !is_group(array('admin', 'staff', 'sale'))) {
            exit('No direct script access allowed');
        }

        $list = $this->orders->get_all_orders();
        $data = array();
        $no = $this->input->post('start');
        foreach ($list as $order) {
            $no++;
            $row = array();

            $row[] = '#' . sprintf("%06d", $order->oid);
            $row[] = '<a href="#" class="ajax-user" data-uid="' . $order->uid . '" data-oid="' . $order->oid . '" data-toggle="modal" data-target="#ajaxModal">' . $order->shipping_name . '</a>';
            $row[] = order_type($order->order_type);
            $row[] = '<a href="#" class="ajax-product" data-oid="' . $order->oid . '" data-toggle="modal" data-target="#ajaxModal">' . number_format($order->total_product) . '</a>';
            $row[] = '<a href="#" class="ajax-status" data-oid="' . $order->oid . '" data-toggle="modal" data-target="#ajaxModal">' . order_status($order->order_status) . '</a>';
            $row[] = number_format($order->total_amount, 2);
            $row[] = '<a href="#" class="label label-info ajax-file" data-uid="' . $order->uid . '" data-oid="' . $order->oid . '" data-toggle="modal" data-target="#ajaxModal"><i class="fa fa-download"></i> ดาวน์โหลดเอกสารลูกค้า</a> 
            <a href="' . base_url('admin/orders/edit/' . $order->oid) . '" class="label label-warning"><i class="fa fa-pencil"></i> แก้ไขคำสั่งซื้อ</a> 
            ';
            $data[] = $row;
        }

        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->orders->count_all(),
            "recordsFiltered" => $this->orders->count_filtered(),
            "data" => $data,
        );
        echo json_encode($output);
    }


    function edit($id = '')
    {
        $this->load->library('form_validation');
        if (!is_group(array('admin', 'staff', 'sale'))) {
            redirect('admin');
            exit();
        }

        if ($id == "" || !$data = $this->orders->get_order($id)) {
            redirect('admin/orders');
            exit();
        }
        $render_data['data'] = $data;
        $this->form_validation->set_rules('products', 'Products', 'required');
        $this->form_validation->set_rules('shipping', 'shipping', 'required');
        if ($this->form_validation->run()) {

            $products = json_decode($this->input->post('products'));
            $coupon = $this->input->post('coupon');
            $shipping = $this->input->post('shipping');

            $total_normal = 0;
            $total_sp_discount = 0;
            $total_amount = 0;
            $discount_10k = 0;
            $discount_code_value = 0;
            $discount_code_amount = 0;
            $total_discount = 0;
            $total_before_vat = 0;
            $total_vat = 0;
            $total = 0;
            $total_product = 0;
            $total_qty = 0;
            $att = array();


            if ($shipping < 0) {
                $shipping = 0;
            }
            if ($coupon_data = $this->orders->get_coupon($coupon)) {
                $discount_code_value = $coupon_data['discount'];
            } else {
                $coupon = '';
            }

            //cal order
            foreach ($products as $key => $product) {
                $paid = str_replace('p', '', $key);
                $att[] = $paid;
                $product_data = $this->orders->get_attribute($paid);
                $total_normal = $total_normal + ($product->price * $product->qty);
                if ($product->sp_price > 0) {
                    $total_sp_discount = $total_sp_discount + ($product->price - $product->sp_price) * $product->qty;
                }
                $total_qty = $total_qty + $product->qty;
                $total_product++;

                if ($this->orders->get_order_product($id, $paid)) {
                    //save product order
                    $this->orders->save_order_product($id, $paid, array(
                        'product_value' => $product_data['p_value'],
                        'product_amount' => $product->price,
                        'product_spacial_amount' => $product->sp_price,
                        'product_qty' => $product->qty,
                        'total_amount' => $product->price * $product->qty
                    ));
                } else {
                    //add product order
                    $this->orders->add_order_product($id, array(
                        'pid' => $product_data['pid'],
                        'pa_id' => $paid,
                        'oid' => $id,
                        'product_title' => $product->title,
                        'product_code' => $product_data['code'],
                        'product_value' => $product_data['p_value'],
                        'product_amount' => $product->price,
                        'product_spacial_amount' => $product->sp_price,
                        'product_qty' => $product->qty,
                        'total_amount' => $product->price * $product->qty,
                        'status' => 'pending'));

                }

            }
            $tmp_10k = 0;
            $tmp_discount = 0;
            $total_amount = $total_normal - $total_sp_discount;
            if ($total_amount >= 100000) {
                $tmp_10k = ($total_amount / 100) * 5;
            }
            if ($discount_code_value > 0) {
                $tmp_discount = ($total_amount / 100) * $discount_code_value;
            }

            if ($tmp_10k > $tmp_discount) {
                $discount_10k = $tmp_10k;
                $discount_code_amount = 0;
                $total_discount = $tmp_10k;
            } else if ($tmp_10k < $tmp_discount) {
                $discount_10k = 0;
                $discount_code_amount = $tmp_discount;
                $total_discount = $tmp_discount;

            } else if ($tmp_10k == $tmp_discount) {
                $discount_10k = 0;
                $discount_code_amount = $tmp_discount;
                $total_discount = $tmp_discount;
            } else {
                $discount_10k = 0;
                $discount_code_amount = 0;
                $total_discount = 0;
            }

            $total_before_vat = $total_amount - $total_discount;
            $total_vat = ($total_before_vat / 100) * 7;
            $total = $total_before_vat + $total_vat + $shipping;

            //delete product not in $att
            $this->orders->delete_order_product($id, $att);

            //save order detail
            $this->orders->save_order($id, array(
                    'total_product' => $total_product,
                    'total_qty' => $total_qty,
                    'amount' => $total_normal,
                    'spacial_amount' => $total_sp_discount,
                    'coupon_code' => $coupon,
                    'discount' => $discount_code_value,
                    'discount_100k' => $discount_10k,
                    'shipping_amount' => $shipping,
                    'total_amount' => $total,
                    'vat_amount' => $total_vat)
            );
            $user = $this->session->userdata('fnsn');
            add_log($user['name'], 'Update order.', 'order_' . $id);
            echo json_encode(array('status' => 'success', 'debug' => $total_sp_discount));
        } else {
            if ($this->input->is_ajax_request()) {
                echo json_encode(array('status' => 'error'));
                exit();
            }
            $js = 'jQuery(\'#datetimepicker\').datetimepicker();';

            if ($this->input->get('save') == "true") {
                $js .= '$.notify("Save coupon success.", "success");';
            }

            $render_data['logs'] = list_logs("order_" . $id);

            $render_data['products'] = $this->orders->list_products($id);

            $tmp_js = 'var products = {';
            foreach ($render_data['products'] as $apid) {
                $tmp_js .= 'p' . $apid['pa_id'] . ': {
                    pid: ' . $apid['pid'] . ',
                    title: "' . $apid['product_title'] . '",
                    price: ' . $apid['product_amount'] . ',
                    sp_price: ' . $apid['product_spacial_amount'] . ',
                    qty: ' . $apid['product_qty'] . '
                },';
            }
            $tmp_js .= '};';

            $render_data['product_list'] = $this->orders->list_all_products();
            $js .= $tmp_js;
            $js .= '
            var discount_code = "' . $data['coupon_code'] . '";
            var discount_code_value = ' . $data['discount'] . ';
            var shipping = ' . $data['shipping_amount'] . ';
            init_order(); 
            
            function ajax_list_files(){
            $("#ajax-file-result").html("Loading...");
            $.ajax({
            method: "POST",
            data: {
            oid: "' . $id . '"
            },
            url: "' . base_url('/admin/orders/ajax_file_list') . '",
            }).done(function (status) {
            $("#ajax-file-result").html(status);
            });
            }
            ajax_list_files();';
            $this->template->write('js', $js);

            $render_data['status_list'] = $this->orders->list_status($id);
            $render_data['custom_files'] = $this->orders->list_files($id, 'customer_all');
            //******* Defalut ********//
            $render_data['user'] = $this->session->userdata('fnsn');
            $this->template->write('title', 'Edit Order #' . sprintf("%06d", $data['oid']));
            $this->template->write('user_id', $render_data['user']['aid']);
            $this->template->write('user_name', $render_data['user']['name']);
            $this->template->write('user_group', $render_data['user']['group']);
            //******* Defalut ********//
            $this->template->write_view('content', 'admin/orders/edit', $render_data);
            $this->template->render();
        }

    }

    /*   public function delete($id)
       {
           if (!is_group(array('admin', 'staff', 'sale'))) {
               redirect('admin');
               exit();
           }
           $coupon = $this->orders->get_coupon($id);
           if (isset($coupon['coid'])) {
               $this->orders->delete_coupon($id);
               redirect('admin/orders?delete=true');
           } else {
               show_error('The coupon you are trying to delete does not exist.');
           }
       }*/


    /// Ajax result


    public function ajax_user()
    {
        if (!$this->input->is_ajax_request() || !is_group(array('admin', 'staff', 'sale'))) {
            exit('No direct script access allowed');
        }
        $this->load->model("Members_model", "members");
        $member = $this->members->get_members($this->input->post('uid'));
        $order = $this->orders->get_order($this->input->post('oid'));
        $html = '<table class="table table-bordered"><tr><td><strong>Name : </strong></td><td>' . $member['name'] . '</td></tr>';
        $html .= '<tr><td><strong>Account type : </strong></td><td>' . order_type($member['account_type']) . '</td></tr>';
        $html .= '<tr><td><strong>Email ; </strong></td><td><a href="mailto:' . $member['email'] . '">' . $member['email'] . '</a></td></tr>';
        $html .= '<tr><td><strong>Phone : </strong></td><td><a href="tel:' . $member['phone'] . '">' . $member['phone'] . '</a></td></tr>';
        if ($member['account_type'] == 'bussiness') {
            $html .= '<tr><td><strong>Bussiness ACC : </strong></td><td>' . $member['bussiness_number'] . '</td></tr>';
            $html .= '<tr><td><strong>Bussiness Name : </strong></td><td>' . $member['bussiness_name'] . '</td></tr>';
            $html .= '<tr><td><strong>Bussiness Address : </strong></td><td>' . $member['bussiness_address'] . '</td></tr>';
        }
        $html .= '<tr><td colspan="2"><strong>Shipping Detail</strong></td></tr>';
        $html .= '<tr><td><strong>Name : </strong></td><td>' . $order['shipping_name'] . '</td></tr>';
        $html .= '<tr><td><strong>Address : </strong></td><td>' . $order['shipping_address'] . '</td></tr>';
        $html .= '<tr><td><strong>Province : </strong></td><td>' . $order['shipping_province'] . '</td></tr>';
        $html .= '<tr><td><strong>Zip : </strong></td><td>' . $order['shipping_zip'] . '</td></tr></table>';

        echo $html;

    }

    public function ajax_product()
    {
        if (!$this->input->is_ajax_request() || !is_group(array('admin', 'staff', 'sale'))) {
            exit('No direct script access allowed');
        }
        $oid = $this->input->post('oid');
        $product_list = $this->orders->list_products($oid);

        $html = '<table class="table table-bordered"><tr><th>Title</th><th>Price</th><th>Spacial Price</th><th>Qty</th><th>Amount</th></tr>';
        foreach ($product_list as $product) {
            $html .= '<tr><td><a href="' . base_url('product/' . $product['pid'] . '/' . url_title($product['product_title'])) . '" target="_blank">' . $product['product_title'] . '</a> [' . $product['product_code'] . ']</td>
                        <td>' . $product['product_amount'] . '</td><td>' . $product['product_spacial_amount'] . '</td><td>' . $product['product_qty'] . '</td><td>' . $product['total_amount'] . '</td></tr>';
        }
        $html .= '</table>';
        echo $html;

    }

    public function ajax_status()
    {
        if (!$this->input->is_ajax_request() || !is_group(array('admin', 'staff', 'sale'))) {
            exit('No direct script access allowed');
        }
        $oid = $this->input->post('oid');
        $status_list = $this->orders->list_status($oid);

        $html = '<table class="table table-bordered"><tr><th>Date</th><th>Status</th><th>Owner</th><th>Note</th></tr>';
        foreach ($status_list as $status) {
            $html .= '<tr><td>' . date("d/m/Y H:i:s", $status['at_date']) . '</td>
<td>' . order_status($status['status']) . '</td>
                        <td>' . $status['owner'] . '</td>
                        <td>' . nl2br($status['text']) . '</td>
                        </tr>';
        }
        $html .= '</table>';
        echo $html;

    }

    public function ajax_file()
    {
        if (!$this->input->is_ajax_request() || !is_group(array('admin', 'staff', 'sale'))) {
            exit('No direct script access allowed');
        }
        $oid = $this->input->post('oid');
        $files_list = $this->orders->list_files($oid, 'customer', $this->input->post('uid'));

        $html = '<table class="table table-bordered"><tr><th>Title</th><th>Type</th><th>Size</th><th>Date</th></tr>';
        foreach ($files_list as $fiile) {
            $html .= '<tr><td><a href="' . base_url('admin/orders/download_file/' . $fiile['ufid']) . '" target="_blank">' . $fiile['file_title'] . '</a></td>
                        <td>' . $fiile['file_type'] . '</td>
                        <td>' . number_format($fiile['file_size'] / 1024, 2) . ' MB</td>
                        <td>' . date("d/m/Y H:i:s", strtotime($fiile['file_date'])) . '</td></tr>';
        }
        $html .= '</table>';
        echo $html;

    }

    public function ajax_file_list()
    {
        if (!$this->input->is_ajax_request() || !is_group(array('admin', 'staff', 'sale'))) {
            exit('No direct script access allowed');
        }
        $oid = $this->input->post('oid');
        $files_list = $this->orders->list_files($oid, 'seller', 0, 0);

        $html = '';
        foreach ($files_list as $fiile) {
            $html .= '<tr>
                        <td>' . date("d/m/Y H:i:s", strtotime($fiile['file_date'])) . '</td>
                        <td>' . $fiile['file_title'] . '</td>
                        <td>' . $fiile['file_type'] . '</td>
                        <td>' . number_format($fiile['file_size'] / 1024, 2) . ' MB</td>
                        <td><a href="' . base_url('admin/orders/download_file/' . $fiile['ufid']) . '" class="label-info label" target="_blank">Download</a></td>
                        <td><a class="label label-success send-email-file" data-fid="' . $fiile['ufid'] . '">Send Email</a> <a href="#" class="label label-danger delete-file" data-fid="' . $fiile['ufid'] . '"><i class="fa fa-times-circle"></i></a></td>
                        </tr>';
        }

        echo $html;

    }

    public function ajax_get_attribute()
    {
        if (!$this->input->is_ajax_request() || !is_group(array('admin', 'staff', 'sale'))) {
            exit('No direct script access allowed');
        }
        $pid = $this->input->post('pid');

        $alt_list = $this->orders->list_all_attribute($pid);
        $a = array();
        foreach ($alt_list as $item) {
            $a[] = array(
                'pa_id' => $item['pa_id'],
                'code' => $item['code'],
                'color' => $item['color'],
                'p_value' => $item['p_value'],
                'normal_price' => $item['normal_price'],
                'special_price' => $item['special_price']
            );
        }
        echo json_encode($a);

    }

    public function ajax_get_coupon()
    {
        if (!$this->input->is_ajax_request() || !is_group(array('admin', 'staff', 'sale'))) {
            exit('No direct script access allowed');
        }
        $code = $this->input->post('code');

        $coupon = $this->orders->get_coupon($code);
        if ($coupon) {
            $a = array('status' => 'success', 'code' => $code, 'discount' => $coupon['discount']);
        } else {
            $a = array('status' => 'error');
        }
        echo json_encode($a);

    }

    function download_file($fid)
    {
        if (!is_group(array('admin', 'staff', 'sale'))) {
            exit('No direct script access allowed');
        }
        $file = $this->orders->get_file($fid);
        redirect(ORDER_PATH . '/' . $file['file_paht']);
    }

    function ajax_delete_file()
    {
        if (!$this->input->is_ajax_request() || !is_group(array('admin', 'staff', 'sale'))) {
            exit('No direct script access allowed');
        }
        $fid = $this->input->post('fid');
        $this->orders->delete_file($fid);
        echo 'success';
    }

    function save_status($id)
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('status', 'status', 'required');
        $this->form_validation->set_rules('submit', 'status', 'required');
        if ($this->form_validation->run()) {
            $user = $this->session->userdata('fnsn');
            switch ($this->input->post('submit')) {
                case "save":
                    $this->orders->save_status(array('status' => $this->input->post('status'), 'at_date' => time(), 'text' => $this->input->post('comment'), 'owner' => 'Seller', 'oid' => $id));
                    $this->__sendmail('status', $this->input->post('status'));
                    add_log($user['name'], "Change status to : " . order_status($this->input->post('status')), "order_" . $id);
                    break;
                case "nomail":
                    $this->orders->save_status(array('status' => $this->input->post('status'), 'at_date' => time(), 'text' => $this->input->post('comment'), 'owner' => 'Seller', 'oid' => $id));
                    add_log($user['name'], "Change status to : " . order_status($this->input->post('status')), "order_" . $id);
                    break;
                case "email":
                    $this->__sendmail('status', $this->input->post('status'));
                    break;
            }
            redirect('admin/orders/edit/' . $id);
        } else {
            redirect('admin/orders/edit/' . $id);
        }
    }

    function save_shipping()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'name', 'required');
        $this->form_validation->set_rules('address', 'address', 'required');
        $this->form_validation->set_rules('province', 'province', 'required');
        $this->form_validation->set_rules('zip', 'zip', 'required');
        $this->form_validation->set_rules('oid', 'oid', 'required');
        if ($this->form_validation->run()) {
            $user = $this->session->userdata('fnsn');
            $this->orders->save_order($this->input->post('oid'), array(
                'shipping_name' => $this->input->post('name'),
                'shipping_address' => $this->input->post('address'),
                'shipping_province' => $this->input->post('province'),
                'shipping_zip' => $this->input->post('zip')
            ));
            add_log($user['name'], "Change shipping address.", "order_" . $this->input->post('oid'));

            $a = array('status' => 'success');
        } else {
            $a = array('status' => 'error');
        }
        echo json_encode($a);
    }

    private function __sendmail($type, $data)
    {
        switch ($type) {
            case "file":

                break;
            case "order":

                break;
        }
    }

    public function upload_document($id)
    {
        if (!$this->input->is_ajax_request() || !is_group(array('admin', 'staff', 'sale'))) {
            exit('No direct script access allowed');
        }
        $user = $this->session->userdata('fnsn');
        if (!empty($_FILES['file']['name'])) {
            $this->load->library('upload');
            $path_parts = pathinfo($_FILES["file"]["name"]);
            $extension = $path_parts['extension'];
            $config = array();
            $config['upload_path'] = './' . ORDER_PATH . '/';
            $config['allowed_types'] = 'pdf|doc|jpg|png';
            $config['encrypt_name'] = true;
            $params = array(
                'file_title' => $this->input->post('title'),
                'file_date' => date('Y-m-d H:i:s'),
                'file_size' => ($_FILES['file']['size'] / 1024),
                'file_type' => $extension,
                'uid' => 0,
                'aid' => $user['aid'],
                'oid' => $id
            );
            $this->upload->initialize($config);
            if ($this->upload->do_upload('file')) {
                $upload_data = $this->upload->data();
                $params['file_paht'] = $upload_data['file_name'];

                $this->orders->add_document($params);
                add_log($user['name'], "Upload document : " . $this->input->post('title'), "order_" . $id);

                $a = array('status' => 'success');
            } else {
                $a = array('status' => 'error', 'message' => $this->upload->display_errors());
            }
        } else {
            $a = array('status' => 'error', 'message' => 'Can\'t upload document.');
        }

        echo json_encode($a);

    }

    function change_shipping($id)
    {
        if (!is_group(array('admin', 'staff', 'sale'))) {
            exit('No direct script access allowed');
        }
        $user = $this->session->userdata('fnsn');
        $odid = explode(',', $this->input->post('ids-product'));
        array_unique($odid);
        if ($this->input->post('type')) {
            if ($this->input->post('type') == 'save_all') {
                $param = array(
                    'status' => 'shipping',
                    'note' => $this->input->post('comment')
                );
                $this->orders->update_order_all_product_status($id, $param);
                add_log($user['name'], "Add shipping all product : " . $this->input->post('comment'), "order_" . $id);

            } else {
                foreach ($odid as $oid) {
                    $param = array(
                        'status' => 'shipping',
                        'note' => $this->input->post('comment')
                    );
                    $this->orders->update_order_product_status($oid, $param);
                    add_log($user['name'], "Add shipping product : " . $this->input->post('comment'), "order_" . $id);
                }
            }
        }
        redirect('admin/orders/edit/'.$id);
    }
}
