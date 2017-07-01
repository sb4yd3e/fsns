<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: attapon
 * Date: 6/13/2017 AD
 * Time: 00:54
 */
class Orders extends CI_Controller
{
    public $render_data = array();

    public function __construct()
    {
        parent::__construct();
        $this->load->model('taxonomy_model');
        $this->load->model('orders_model', 'order');
        $this->load->model('members_model', 'members');
        $this->render_data['product_category'] = $this->taxonomy_model->get_taxonomy_term('product_category');
    }

    public function index()
    {
        if (!is_login()) {
            redirect('');
        }
        $this->render_data['active_menu'] = 'member';
        $this->render_data['web_title'] = 'My Orders';
        $this->template->write_view('content', 'frontend/my_orders', $this->render_data);
        $this->template->render();
    }

    function carts()
    {
        if (!is_login()) {
            redirect('login');
        }
        $this->render_data['active_menu'] = 'cart';
        $this->render_data['web_title'] = 'My shopping carts';
        $this->template->write_view('content', 'frontend/my_carts', $this->render_data);
        $this->template->render();
    }

    function delivery()
    {
        if (!is_login()) {
            redirect('login');
        }
        $user = $this->session->userdata('fnsn');
        $this->render_data['active_menu'] = 'cart';
        $this->load->library('form_validation');
        $this->config->set_item('csrf_protection', true);
        $this->load->helper('security');

        $this->form_validation->set_rules('shipping_name', 'Shipping Name', 'required|max_length[200]');
        $this->form_validation->set_rules('shipping_province', 'Shipping Province', 'required');
        $this->form_validation->set_rules('shipping_zip', 'Shipping Zip', 'required|numeric|min_length[5]|max_length[5]');
        $this->form_validation->set_rules('shipping_address', 'Shipping Address', 'required');
        $this->form_validation->set_rules('billing_name', 'Billing Name', 'required|max_length[200]');
        $this->form_validation->set_rules('billing_province', 'Billing Province', 'required');
        $this->form_validation->set_rules('billing_zip', 'Billing Zip', 'required|numeric|min_length[5]|max_length[5]');
        $this->form_validation->set_rules('billing_address', 'Billing Address', 'required');
        if ($this->form_validation->run()) {
            $data_create = array(
                'shipping_name' => $this->input->post('shipping_name'),
                'shipping_province' => $this->input->post('shipping_province'),
                'shipping_zip' => $this->input->post('shipping_zip'),
                'shipping_zip' => $this->input->post('shipping_zip'),
                'shipping_address' => $this->input->post('shipping_address'),
                'billing_name' => $this->input->post('billing_name'),
                'billing_province' => $this->input->post('billing_province'),
                'billing_zip' => $this->input->post('billing_zip'),
                'billing_address' => $this->input->post('billing_address')
            );
            $this->load->model('members_model', 'members');
            $this->members->update_members($user['uid'], $data_create);
            echo json_encode(array('status' => 'success'));
        } else {
            if ($this->input->is_ajax_request()) {
                echo json_encode(array('status' => 'error', 'message' => validation_errors()));
                exit();
            }

            $this->render_data['shipping'] = $this->order->get_shipping_address($user['uid']);
            $this->render_data['web_title'] = 'My Shipping Information';
            $this->template->write_view('content', 'frontend/my_delivery', $this->render_data);
            $this->template->render();
        }
    }

    function payment()
    {
        if (!is_login()) {
            redirect('login');
        }
        $this->render_data['active_menu'] = 'cart';
        $user = $this->session->userdata('fnsn');
        $this->render_data['shipping'] = $this->order->get_shipping_address($user['uid']);
        $this->render_data['web_title'] = 'Confirm order and payment';
        $this->template->write_view('content', 'frontend/payment', $this->render_data);
        $this->template->render();
    }

    public function ajax_get_coupon()
    {
        if (!$this->input->is_ajax_request() || !is_login()) {
            exit(json_encode(array('status' => 'error')));
        }
        $code = strtolower($this->input->post('code'));

        $coupon = $this->order->get_coupon($code);
        if ($coupon) {
            $a = array('status' => 'success', 'code' => $code, 'discount' => $coupon['discount']);
        } else {
            $a = array('status' => 'error');
        }
        echo json_encode($a);

    }

    function confirm_order()
    {
        $user = $this->session->userdata('fnsn');

        $user_data = $this->members->get_members($user['uid']);
        if (!is_login()) {
            redirect('');
        }

        if ($this->input->is_ajax_request()) {


            $products = json_decode($this->input->post('products'));
            $coupon = strtolower($this->input->post('coupon_code'));

            $zip_arr = $this->setting_data['shipping_zip'];
            if (in_array($user_data['shipping_zip'], explode(',', $zip_arr))) {
                $shipping = $this->setting_data['shipping_inarea'];
            } else {
                $shipping = $this->setting_data['shipping_outarea'];
            }
            $product_html = '';
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
            if ($coupon_data = $this->order->get_coupon($coupon)) {
                $discount_code_value = $coupon_data['discount'];
            } else {
                $coupon = '';
            }
//add order
            $this->db->trans_start();
            $id = $this->order->add_order(array(
                'uid' => $user['uid'],
                'sale_id' => $user_data['staff_id'],
                'at_date' => time(),
                'at_ip' => $this->input->ip_address(),
                'order_status' => 'pending',
                'total_product' => '0',
                'total_qty' => '0',
                'discount_100k' => '0',
                'shipping_amount' => '0',
                'vat_amount' => '0',
                'total_amount' => '0',
                'order_type' => $user_data['account_type'],
                'shipping_name' => $user_data['shipping_name'],
                'shipping_address' => $user_data['shipping_address'],
                'shipping_province' => $user_data['shipping_province'],
                'shipping_zip' => $user_data['shipping_zip'],
                'billing_name' => $user_data['billing_name'],
                'billing_address' => $user_data['billing_address'],
                'billing_province' => $user_data['billing_province'],
                'billing_zip' => $user_data['billing_zip']
            ));

//cal order
            $k = 0;
            foreach ($products as $key => $product) {
                $paid = $key;
                if ($product->qty <= 0) {
                    $product->qty = 1;
                }

                $product_data = $this->order->get_attribute($paid);
                $total_normal = $total_normal + ($product_data['normal_price'] * $product->qty);
                if ($product_data['special_price'] > 0) {
                    $total_sp_discount = $total_sp_discount + ($product_data['normal_price'] - $product_data['special_price']) * $product->qty;
                }
                $total_qty = $total_qty + $product->qty;
                $total_product++;


                //add product order
                $this->order->add_order_product($id, array(
                    'pid' => $product_data['pid'],
                    'pa_id' => $paid,
                    'oid' => $id,
                    'product_title' => $product->title,
                    'product_code' => $product_data['code'],
                    'product_value' => $product_data['p_value'],
                    'product_amount' => $product_data['normal_price'],
                    'product_spacial_amount' => $product_data['special_price'],
                    'product_qty' => $product->qty,
                    'total_amount' => $product_data['normal_price'] * $product->qty,
                    'status' => 'pending'));

                $product_html .= '<tr><td>' . ($k + 1) . '</td>
    <td>' . $product_data['code'] . '</td>
    <td>' . $product->title . ' - ' . $product_data['p_value'] . '</td>
    <td style="font-weight: bold;">
        ' . number_format($product->qty) . '
    </td>
    <td>' . number_format($product_data['normal_price'], 2) . '</td>
    <td>';
                $total = 0;
                if ($product_data['special_price'] > 0) {
                    $total = $total + $product_data['special_price'];
                    $product_html .= number_format(($product_data['normal_price'] - $product_data['special_price']) * $product->qty, 2);
                } else {
                    $product_html .= 0;
                    $total = $total + $product_data['normal_price'];
                };
                $product_html .= '
    </td>
    <td>' . number_format($total, 2) . '</td>
</tr>';
                $k++;
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

//save order detail
            $this->order->save_order($id, array(
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

            add_log($user['name'], 'Confirm order.', 'order_' . $id);
            $html = '<div style="margin-top:10px;background: #013A93;padding:20px;color:#fff;">
	<h3 style="margin: 0px; font-size: 20px;">Your order is pending.</h3>
</div>

<div style="margin-top:10px;margin-bottom:10px;">
เรียนสมาชิก FSNS Thailand<br><br><br>
รายการสั่งซื้อ #' . str_pad($id, 6, "0", STR_PAD_LEFT) . ' ถูกสร้างขึ้น. <br>สมาชิกจะได้รับอีเมลแจ้งเตือนเมื่อเมื่อคำสั่งซื้อได้รับการยืนยัน
</div>
<div>
<table style="border:1px solid #e0e0e0;margin: 0px;width: 100%;" border="1">
                <tr style="background-color:#e0e0e0;font-weight: bold;text-align: center">
                    <td width=\'50\' class="cart_t cart_r cart_l">ลำดับ</td>
                    <td width=\'100\' class="cart_t cart_r cart_l">รหัสสินค้า</td>
                    <td class="cart_t cart_r">รายการสินค้า</td>
                    <td width=\'50\' class="cart_t cart_r">จำนวน</td>
                    <td width=\'120\' class="cart_t cart_r">ราคา / หน่วย</td>
                    <td width=\'50\' class="cart_t cart_r">ส่วนลด</td>
                    <td width=\'100\' class="cart_t cart_r">จำนวนเงินรวม</td>
                </tr>' . $product_html;
            $html .= '
<tr style="background-color: #fff;   height:35px;">
    <td colspan="5" class="right font_bold cart_t cart_r cart_l cart_b" style="text-align:right">
        รวมราคาสินค้า (บาท)
    </td>
    <td class="right font_bold font_discount cart_t  cart_b">' . number_format($total_sp_discount, 2) . '</td>
    <td class="right font_bold font_total cart_t cart_r cart_l cart_b">' . number_format($total_amount, 2) . '</td>
</tr>
<tr style="background-color: #fff;   height:35px;">
    <td colspan="4"></td>
    <td colspan="2" class="line_under right font_bold">คูปองส่วนลด (บาท)</td>
    <td class="right line_under font_bold">' . number_format($total_discount, 2) . '
    </td>
</tr>
<tr style="background-color: #fff;   height:35px;">
    <td colspan="4"></td>
    <td colspan="2" class="line_under right font_bold">ภาษีมูลค่าเพิ่ม 7% (บาท)</td>
    <td class="right line_under font_bold">' . number_format($total_vat, 2) . '</td>
</tr>
<tr style="background-color: #fff;   height:35px;">
    <td colspan="4"></td>
    <td colspan="2" class="line_under right font_bold">ค่าจัดส่ง (บาท)</td>
    <td class="right line_under font_bold">' . number_format($shipping, 2) . '</td>
</tr>

<tr style="background-color: #fff;   height:35px;">
    <td colspan="4"></td>
    <td colspan="2" class="line_under right font_bold">รวมเป็นเงินทั้งสิ้น</td>
    <td class="right font_total line_under font_underline font_bold">' . number_format($total, 2) . '</td>
</tr>
</table>
</div>
<div style="margin-top:50px;">
    ด้วยความเคารพ<br>FSNS Thailand
</div>';
            add_order_process($id, 'status', 'pending', '');
            $this->db->trans_complete();
            if ($this->db->trans_status() === true) {
                $this->session->set_userdata('timestamp', 'true');
                if ($user_data['staff_id'] != 0) {
                    $email_to = $user_data['email'] . ',' . get_email_sale($user_data['staff_id']);
                } else {
                    $email_to = $user_data['email'];
                }
                send_mail($email_to, $this->setting_data['email_for_contact'], $this->setting_data['email_for_order'], 'Your order is pending.', $html);
                echo json_encode(array('status' => 'success'));
            } else {
                echo json_encode(array('status' => 'error'));
            }


        } else {
            $t = $this->session->userdata('timestamp');
            if (!$t) {
                redirect('');
            }

            $this->session->unset_userdata('timestamp');
            $this->render_data['type'] = $user_data['account_type'];
            $this->render_data['web_title'] = 'Confirmed order success.';
            $this->template->write_view('content', 'frontend/confirm_order', $this->render_data);
            $this->template->render();
        }
    }

    function my_orders()
    {
        if (!is_login()) {
            redirect('login');
        }
        $this->render_data['active_menu'] = 'member';
        $user = $this->session->userdata('fnsn');
        $this->render_data['orders'] = $this->order->list_order_by_user($user['uid']);
        $this->render_data['web_title'] = 'My orders';
        $this->template->write_view('content', 'frontend/my_orders', $this->render_data);
        $this->template->render();
    }

    function document($oid)
    {
        $user = $this->session->userdata('fnsn');
        if (!is_login() || !$this->render_data['order'] = $this->order->get_user_order($user['uid'], $oid)) {
            redirect('');
        }
        if ($this->render_data['order']['order_type'] != 'business') {
            redirect('');
        }
        $this->render_data['active_menu'] = 'member';
        $this->load->library('form_validation');

        $this->form_validation->set_rules('title', 'File title', 'required');
        if ($this->form_validation->run() && $this->input->is_ajax_request()) {
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
                    'uid' => $user['uid'],
                    'aid' => 0,
                    'oid' => $oid
                );
                $this->upload->initialize($config);
                if ($this->upload->do_upload('file')) {
                    $upload_data = $this->upload->data();
                    $params['file_paht'] = $upload_data['file_name'];
                    $fid = $this->order->add_document($params);
                    add_log($user['name'], "Upload document : " . $this->input->post('title'), "order_" . $oid);
                    add_order_process($oid, 'document', $this->input->post('title'), $fid);
                    $user_data = $this->members->get_members($user['uid']);
                    $email_to = $user_data['email'];
                    if ($user_data['staff_id'] != 0) {
                        $email_sale = get_email_sale($user_data['staff_id']);
                    } else {
                        $email_sale = false;
                    }


                    $html_email = '<div style="margin-top:10px;background: #013A93;padding:20px;color:#fff;">
                    <h3 style="margin: 0px; font-size: 20px;">You uploaded new document.</h3>
                </div>
                
                <div style="margin-top:10px;margin-bottom:10px;">
                เรียนสมาชิก FSNS Thailand<br><br><br>
                คุณได้อัพโหลดเอกสารที่เกี่ยวกับคำสั่งซื้อ #' . str_pad($oid, 6, "0", STR_PAD_LEFT) . '.
                <br>สมาชิกสามารถตรวจสอบรายละเอียดที่เมนู My Orders
                </div>
                <div>
                
                </div>
                <div style="margin-top:50px;">
                    ด้วยความเคารพ<br>FSNS Thailand
                </div>';
                    send_mail($email_to, $this->setting_data['email_for_contact'], false, 'You uploaded new document.', $html_email);

                    if ($email_sale) {
                        $html_email = '<div style="margin-top:10px;background: #013A93;padding:20px;color:#fff;">
                    <h3 style="margin: 0px; font-size: 20px;">คุณได้รับเอกสารใหม่จากลูกค้า.</h3>
                </div>
                
                <div style="margin-top:10px;margin-bottom:10px;">
                เรียนสมาชิก FSNS Thailand<br><br><br>
                คำสั่งซื้อ #' . str_pad($oid, 6, "0", STR_PAD_LEFT) . '.
                <br>คุณสามารถดาวน์โหลดไฟล์ได้จากลิงค์ด้านล่าง
                </div>
                <div>
                <a href="' . base_url('admin/orders/download_file/' . $fid) . '" target="_blank" style="display: block;padding:10px;color: #ffffff;text-decoration: none;background: #C50802;border-bottom: 3px solid #8E0501;font-size: 20px; max-width: 300px;text-align: center;
margin-top: 20px;">Download file</a><br>
                </div>
                <div style="margin-top:50px;">
                    ด้วยความเคารพ<br>FSNS Thailand
                </div>';
                        send_mail($email_to, $this->setting_data['email_for_contact'], $this->setting_data['email_for_order'], 'You uploaded new document.', $html_email);
                    }

                    $a = array('status' => 'success');
                } else {
                    $a = array('status' => 'error', 'message' => $this->upload->display_errors());
                }
            } else {
                $a = array('status' => 'error', 'message' => 'Can\'t upload document.');
            }

            echo json_encode($a);
        } else {
            if ($this->input->is_ajax_request()) {
                echo json_encode(array('status' => 'error', 'message' => validation_errors()));
                exit();
            }

            $this->render_data['my_documents'] = $this->order->list_files($oid, "customer_all");
            $this->render_data['seller_documents'] = $this->order->list_files($oid, "seller");
            $this->render_data['web_title'] = 'Documents #' . str_pad($oid, 6, "0", STR_PAD_LEFT);
            $this->template->write_view('content', 'frontend/document', $this->render_data);
            $this->template->render();

        }
    }

    function download_file($fid)
    {
        if (!is_login()) {
            exit('No direct script access allowed');
        }
        $user = $this->session->userdata('fnsn');
        $file = $this->order->get_file($fid);
        $order = $this->order->get_user_order($user['uid'], $file['oid']);
        if (!$order) {
            exit('No direct script access allowed');
        }
        redirect(ORDER_PATH . '/' . $file['file_paht']);
    }

    function view($oid)
    {
        if (!is_login()) {
            redirect('');
        }
        $this->render_data['active_menu'] = 'member';
        $user = $this->session->userdata('fnsn');
        if ($this->render_data['order'] = $this->order->get_user_order($user['uid'], $oid)) {
            $this->render_data['timelines'] = $this->order->list_timeline($oid);
            $this->render_data['web_title'] = 'My orders #' . str_pad($oid, 6, "0", STR_PAD_LEFT);
            $this->template->write_view('content', 'frontend/view_order', $this->render_data);
            $this->template->render();
        } else {
            redirect('');
        }
    }

    function print_file($oid)
    {
        if (!is_login()) {
            redirect('');
        }
        $user = $this->session->userdata('fnsn');
        if ($render_data['order'] = $this->order->get_user_order($user['uid'], $oid)) {
            $render_data['products'] = $this->order->list_products($oid);
            $this->load->view('frontend/print_order', $render_data);

        } else {
            redirect('');
        }
    }

    function confirm_payment($oid)
    {
        if (!is_login()) {
            redirect('');
        }
        $this->render_data['active_menu'] = 'member';
        $user = $this->session->userdata('fnsn');
        if ($this->render_data['order'] = $this->order->get_user_order($user['uid'], $oid)) {
            if ($this->render_data['order']['order_status'] != 'confirmed') {
                redirect('my-orders');
            }
            $this->load->library('form_validation');
            $this->config->set_item('csrf_protection', true);
            $this->load->helper('security');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
            $this->form_validation->set_rules('name', 'Name', 'required|max_length[100]');
            $this->form_validation->set_rules('phone', 'Phone', 'required|max_length[20]');
            $this->form_validation->set_rules('amount', 'Amount', 'required');
            $this->form_validation->set_rules('date', 'Date', 'required');
            $this->form_validation->set_rules('gateway', 'Payment gateway', 'required');
            if ($this->form_validation->run() && $this->input->is_ajax_request()) {
//confirm payment
                $html = 'ชื่อ : ' . $this->input->post('name') . '<br>
Email : ' . $this->input->post('email') . '<br>
Phone : ' . $this->input->post('phone') . '<br>
Amount : ' . $this->input->post('amount') . '<br>
Date : ' . $this->input->post('date') . '<br>
Gateway : ' . $this->input->post('gateway') . '<br>
Message : ' . nl2br($this->input->post('note')) . '
';


                //upload document

                if (!empty($_FILES['slip']['name'])) {
                    $this->load->library('upload');
                    $path_parts = pathinfo($_FILES["slip"]["name"]);
                    $extension = $path_parts['extension'];
                    $config = array();
                    $config['upload_path'] = './' . ORDER_PATH . '/';
                    $config['allowed_types'] = 'pdf|doc|jpg|png';
                    $config['encrypt_name'] = true;
                    $params = array(
                        'file_title' => 'Proof of payment',
                        'file_date' => date('Y-m-d H:i:s'),
                        'file_size' => ($_FILES['slip']['size'] / 1024),
                        'file_type' => $extension,
                        'uid' => $user['uid'],
                        'aid' => 0,
                        'oid' => $oid
                    );
                    $this->upload->initialize($config);
                    if ($this->upload->do_upload('slip')) {
                        $upload_data = $this->upload->data();
                        $params['file_paht'] = $upload_data['file_name'];
                        $fid = $this->order->add_document($params);
                    } else {
                        $a = array('status' => 'error', 'message' => $this->upload->display_errors());
                        echo json_encode($a);
                        exit();
                    }
                }
                $this->order->save_status(array('status' => 'wait_payment', 'at_date' => time(), 'text' => $html, 'owner' => $user['name'], 'oid' => $oid));
                add_log($user['name'], "Change status to : wait_payment", "order_" . $oid);
                add_order_process($oid, 'status', 'wait_payment', $html);
                $a = array('status' => 'success');
                $user_data = $this->members->get_members($user['uid']);
                if ($user_data['staff_id'] != 0) {
                    $email_to = $user_data['email'] . ',' . get_email_sale($user_data['staff_id']);
                } else {
                    $email_to = $user_data['email'];
                }

                $html_email = '<div style="margin-top:10px;background: #013A93;padding:20px;color:#fff;">
                    <h3 style="margin: 0px; font-size: 20px;">Your order is confirmed payment.</h3>
                </div>
                
                <div style="margin-top:10px;margin-bottom:10px;">
                เรียนสมาชิก FSNS Thailand<br><br><br>
                คำสั่งซื้อ #' . str_pad($oid, 6, "0", STR_PAD_LEFT) . ' ได้รับคำสั่งแจ้งยืนยันการชำระเงินแล้ว. <br>
                สมาชิกจะได้รับอีเมล์ยืนยันการแจ้งชำระเงินอีครั้งเมื่อได้รับการตรวจสอบ
                </div>
                <div>
                ' . $html . '
                </div>
                <div style="margin-top:50px;">
                    ด้วยความเคารพ<br>FSNS Thailand
                </div>';
                send_mail($email_to, $this->setting_data['email_for_contact'], $this->setting_data['email_for_order'], 'Your order is confirmed payment.', $html_email);

                echo json_encode($a);
            } else {
                if ($this->input->is_ajax_request()) {
                    echo json_encode(array('status' => 'error', 'message' => validation_errors()));
                    exit();
                }
                $user_data = $this->members->get_members($user['uid']);
                $this->render_data['user_data'] = $user_data;
                $this->render_data['web_title'] = 'แจ้งชำระค่าสินค้า #' . str_pad($oid, 6, "0", STR_PAD_LEFT);
                $this->template->write_view('content', 'frontend/confirm_payment', $this->render_data);
                $this->template->render();
            }

        } else {
            redirect('');
        }
    }


}