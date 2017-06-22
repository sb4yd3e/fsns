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
        if ($this->form_validation->run()) {
            $data_create = array(
                'shipping_name' => $this->input->post('shipping_name'),
                'shipping_province' => $this->input->post('shipping_province'),
                'shipping_zip' => $this->input->post('shipping_zip'),
                'shipping_address' => $this->input->post('shipping_address')
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
            $this->render_data['web_title'] = 'My Delivery Infomation';
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
        $this->load->model('members_model', 'members');
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
                'shipping_zip' => $user_data['shipping_zip']
            ));

            //cal order
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
	<h3 style="margin: 20px 0px 0px 1px; font-size: 30px;">Your order is pending.</h3>
</div>

<div style="margin-top:20px;">
Dear FSNS Thailand Customer,<br><br><br>
Thank you for your order .... no text
</div>
<div>
<a href="' . base_url('order/print/' . $id) . '" target="_blank" style="display: block;padding:10px;color: #ffffff;text-decoration: none;background: #C50802;border-bottom: 3px solid #8E0501;font-size: 20px; max-width: 300px;text-align: center;
margin-top: 20px;">View order detail</a><br>
If you can\'t click the button,Please copy link to open in your browser address.<br>
<a href="' . base_url('order/print/' . $id) . '" target="_blank">
' . base_url('order/print/' . $id) . '
</a>
</div>
<div style="margin-top:50px;">
Thanks for beging a FSNS Thailand customer.
</div>';
            add_order_process($id, 'status', 'pending', '');
            $this->db->trans_complete();
            if ($this->db->trans_status() === true) {
                $this->session->set_userdata('timestamp', 'true');
                $this->__sendmail($user_data['email'], 'Your order is pending.', $html);
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
        if (!is_login()) {
            redirect('');
        }
        $this->render_data['active_menu'] = 'member';
        $this->load->library('form_validation');
        $user = $this->session->userdata('fnsn');
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
            if ($this->render_data['order'] = $this->order->get_user_order($user['uid'], $oid)) {
                $this->render_data['my_documents'] = $this->order->list_files($oid, "customer_all");
                $this->render_data['seller_documents'] = $this->order->list_files($oid, "seller");
                $this->render_data['web_title'] = 'Documents #' . str_pad($oid, 6, "0", STR_PAD_LEFT);
                $this->template->write_view('content', 'frontend/document', $this->render_data);
                $this->template->render();
            } else {
                redirect('');
            }
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
        if(!$order){
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
                        เบอร์โทรศัพท์ : ' . $this->input->post('phone') . '<br>
                        จำนวนเงิน : ' . $this->input->post('amount') . '<br>
                        วัน เวลา : ' . $this->input->post('date') . '<br>
                        ช่องทาง : ' . $this->input->post('gateway') . '<br>
                        ข้อความ : '.nl2br($this->input->post('note')).'
                        ';


                //upload document
                $fid = '';
                if (!empty($_FILES['slip']['name'])) {
                    $this->load->library('upload');
                    $path_parts = pathinfo($_FILES["slip"]["name"]);
                    $extension = $path_parts['extension'];
                    $config = array();
                    $config['upload_path'] = './' . ORDER_PATH . '/';
                    $config['allowed_types'] = 'pdf|doc|jpg|png';
                    $config['encrypt_name'] = true;
                    $params = array(
                        'file_title' => 'หลักฐานการชำระ',
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
                        $this->order->save_status(array('status' => 'wait_payment', 'at_date' => time(), 'text' => $html, 'owner' => $user['name'], 'oid' => $oid));
                        add_log($user['name'], "Change status to : wait_payment", "order_" . $oid);
                        add_order_process($oid, 'status', 'wait_payment', $html);
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
                $this->load->model('members_model', 'members');
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

    private function __sendmail($email, $title, $message)
    {
        $filename = 'img/logo.png';
        $this->load->library('email');
        $this->email->attach($filename);
        $this->email->subject($title);
        $this->email->from($this->setting_data['email_for_member'], 'FSNS Thailand');
        $this->email->to($email);
        $img = $this->email->attachment_cid($filename);
        $this->email->set_mailtype("html");

        $html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><!--[if IE]><html xmlns="http://www.w3.org/1999/xhtml" class="ie"><![endif]--><html style="margin: 0;padding: 0;" xmlns="http://www.w3.org/1999/xhtml"><head> <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/> <title></title> <meta http-equiv="X-UA-Compatible" content="IE=edge"/> <meta name="viewport" content="width=device-width"/> <style type="text/css"> @media only screen and (min-width: 620px){.wrapper{min-width: 600px !important}.wrapper h1{}.wrapper h1{font-size: 26px !important; line-height: 34px !important}.wrapper h2{}.wrapper h2{font-size: 20px !important; line-height: 28px !important}.wrapper h3{}.column{}.wrapper .size-8{font-size: 8px !important; line-height: 14px !important}.wrapper .size-9{font-size: 9px !important; line-height: 16px !important}.wrapper .size-10{font-size: 10px !important; line-height: 18px !important}.wrapper .size-11{font-size: 11px !important; line-height: 19px !important}.wrapper .size-12{font-size: 12px !important; line-height: 19px !important}.wrapper .size-13{font-size: 13px !important; line-height: 21px !important}.wrapper .size-14{font-size: 14px !important; line-height: 21px !important}.wrapper .size-15{font-size: 15px !important; line-height: 23px !important}.wrapper .size-16{font-size: 16px !important; line-height: 24px !important}.wrapper .size-17{font-size: 17px !important; line-height: 26px !important}.wrapper .size-18{font-size: 18px !important; line-height: 26px !important}.wrapper .size-20{font-size: 20px !important; line-height: 28px !important}.wrapper .size-22{font-size: 22px !important; line-height: 31px !important}.wrapper .size-24{font-size: 24px !important; line-height: 32px !important}.wrapper .size-26{font-size: 26px !important; line-height: 34px !important}.wrapper .size-28{font-size: 28px !important; line-height: 36px !important}.wrapper .size-30{font-size: 30px !important; line-height: 38px !important}.wrapper .size-32{font-size: 32px !important; line-height: 40px !important}.wrapper .size-34{font-size: 34px !important; line-height: 43px !important}.wrapper .size-36{font-size: 36px !important; line-height: 43px !important}.wrapper .size-40{font-size: 40px !important; line-height: 47px !important}.wrapper .size-44{font-size: 44px !important; line-height: 50px !important}.wrapper .size-48{font-size: 48px !important; line-height: 54px !important}.wrapper .size-56{font-size: 56px !important; line-height: 60px !important}.wrapper .size-64{font-size: 64px !important; line-height: 63px !important}}</style> <style type="text/css"> body{margin: 0; padding: 0;}table{border-collapse: collapse; table-layout: fixed;}*{line-height: inherit;}[x-apple-data-detectors], [href^="tel"], [href^="sms"]{color: inherit !important; text-decoration: none !important;}.wrapper .footer__share-button a:hover, .wrapper .footer__share-button a:focus{color: #ffffff !important;}.btn a:hover, .btn a:focus, .footer__share-button a:hover, .footer__share-button a:focus, .email-footer__links a:hover, .email-footer__links a:focus{opacity: 0.8;}.preheader, .header, .layout, .column{transition: width 0.25s ease-in-out, max-width 0.25s ease-in-out;}.layout, div.header{max-width: 600px !important; -fallback-width: 95% !important; width: calc(100% - 20px) !important;}div.preheader{max-width: 360px !important; -fallback-width: 90% !important; width: calc(100% - 60px) !important;}.snippet, .webversion{Float: none !important;}.column{max-width: 600px !important; width: 100% !important;}.fixed-width.has-border{max-width: 402px !important;}.fixed-width.has-border .layout__inner{box-sizing: border-box;}.snippet, .webversion{width: 50% !important;}.ie .btn{width: 100%;}[owa] .column div, [owa] .column button{display: block !important;}.ie .column, [owa] .column, .ie .gutter, [owa] .gutter{display: table-cell; float: none !important; vertical-align: top;}.ie div.preheader, [owa] div.preheader, .ie .email-footer, [owa] .email-footer{max-width: 560px !important; width: 560px !important;}.ie .snippet, [owa] .snippet, .ie .webversion, [owa] .webversion{width: 280px !important;}.ie div.header, [owa] div.header, .ie .layout, [owa] .layout, .ie .one-col .column, [owa] .one-col .column{max-width: 600px !important; width: 600px !important;}.ie .fixed-width.has-border, [owa] .fixed-width.has-border, .ie .has-gutter.has-border, [owa] .has-gutter.has-border{max-width: 602px !important; width: 602px !important;}.ie .two-col .column, [owa] .two-col .column{max-width: 300px !important; width: 300px !important;}.ie .three-col .column, [owa] .three-col .column, .ie .narrow, [owa] .narrow{max-width: 200px !important; width: 200px !important;}.ie .wide, [owa] .wide{width: 600px !important;}.ie .two-col.has-gutter .column, [owa] .two-col.x_has-gutter .column{max-width: 290px !important; width: 290px !important;}.ie .three-col.has-gutter .column, [owa] .three-col.x_has-gutter .column, .ie .has-gutter .narrow, [owa] .has-gutter .narrow{max-width: 188px !important; width: 188px !important;}.ie .has-gutter .wide, [owa] .has-gutter .wide{max-width: 394px !important; width: 394px !important;}.ie .two-col.has-gutter.has-border .column, [owa] .two-col.x_has-gutter.x_has-border .column{max-width: 292px !important; width: 292px !important;}.ie .three-col.has-gutter.has-border .column, [owa] .three-col.x_has-gutter.x_has-border .column, .ie .has-gutter.has-border .narrow, [owa] .has-gutter.x_has-border .narrow{max-width: 190px !important; width: 190px !important;}.ie .has-gutter.has-border .wide, [owa] .has-gutter.x_has-border .wide{max-width: 396px !important; width: 396px !important;}.ie .fixed-width .layout__inner{border-left: 0 none white !important; border-right: 0 none white !important;}.ie .layout__edges{display: none;}.mso .layout__edges{font-size: 0;}.layout-fixed-width, .mso .layout-full-width{background-color: #ffffff;}@media only screen and (min-width: 620px){.column, .gutter{display: table-cell; Float: none !important; vertical-align: top;}div.preheader, .email-footer{max-width: 560px !important; width: 560px !important;}.snippet, .webversion{width: 280px !important;}div.header, .layout, .one-col .column{max-width: 600px !important; width: 600px !important;}.fixed-width.has-border, .fixed-width.ecxhas-border, .has-gutter.has-border, .has-gutter.ecxhas-border{max-width: 602px !important; width: 602px !important;}.two-col .column{max-width: 300px !important; width: 300px !important;}.three-col .column, .column.narrow{max-width: 200px !important; width: 200px !important;}.column.wide{width: 600px !important;}.two-col.has-gutter .column, .two-col.ecxhas-gutter .column{max-width: 290px !important; width: 290px !important;}.three-col.has-gutter .column, .three-col.ecxhas-gutter .column, .has-gutter .narrow{max-width: 188px !important; width: 188px !important;}.has-gutter .wide{max-width: 394px !important; width: 394px !important;}.two-col.has-gutter.has-border .column, .two-col.ecxhas-gutter.ecxhas-border .column{max-width: 292px !important; width: 292px !important;}.three-col.has-gutter.has-border .column, .three-col.ecxhas-gutter.ecxhas-border .column, .has-gutter.has-border .narrow, .has-gutter.ecxhas-border .narrow{max-width: 190px !important; width: 190px !important;}.has-gutter.has-border .wide, .has-gutter.ecxhas-border .wide{max-width: 396px !important; width: 396px !important;}}@media (max-width: 321px){.fixed-width.has-border .layout__inner{border-width: 1px 0 !important;}.layout, .column{min-width: 320px !important; width: 320px !important;}.border{display: none;}}.mso div{border: 0 none white !important;}.mso .w560 .divider{Margin-left: 260px !important; Margin-right: 260px !important;}.mso .w360 .divider{Margin-left: 160px !important; Margin-right: 160px !important;}.mso .w260 .divider{Margin-left: 110px !important; Margin-right: 110px !important;}.mso .w160 .divider{Margin-left: 60px !important; Margin-right: 60px !important;}.mso .w354 .divider{Margin-left: 157px !important; Margin-right: 157px !important;}.mso .w250 .divider{Margin-left: 105px !important; Margin-right: 105px !important;}.mso .w148 .divider{Margin-left: 54px !important; Margin-right: 54px !important;}.mso .size-8, .ie .size-8{font-size: 8px !important; line-height: 14px !important;}.mso .size-9, .ie .size-9{font-size: 9px !important; line-height: 16px !important;}.mso .size-10, .ie .size-10{font-size: 10px !important; line-height: 18px !important;}.mso .size-11, .ie .size-11{font-size: 11px !important; line-height: 19px !important;}.mso .size-12, .ie .size-12{font-size: 12px !important; line-height: 19px !important;}.mso .size-13, .ie .size-13{font-size: 13px !important; line-height: 21px !important;}.mso .size-14, .ie .size-14{font-size: 14px !important; line-height: 21px !important;}.mso .size-15, .ie .size-15{font-size: 15px !important; line-height: 23px !important;}.mso .size-16, .ie .size-16{font-size: 16px !important; line-height: 24px !important;}.mso .size-17, .ie .size-17{font-size: 17px !important; line-height: 26px !important;}.mso .size-18, .ie .size-18{font-size: 18px !important; line-height: 26px !important;}.mso .size-20, .ie .size-20{font-size: 20px !important; line-height: 28px !important;}.mso .size-22, .ie .size-22{font-size: 22px !important; line-height: 31px !important;}.mso .size-24, .ie .size-24{font-size: 24px !important; line-height: 32px !important;}.mso .size-26, .ie .size-26{font-size: 26px !important; line-height: 34px !important;}.mso .size-28, .ie .size-28{font-size: 28px !important; line-height: 36px !important;}.mso .size-30, .ie .size-30{font-size: 30px !important; line-height: 38px !important;}.mso .size-32, .ie .size-32{font-size: 32px !important; line-height: 40px !important;}.mso .size-34, .ie .size-34{font-size: 34px !important; line-height: 43px !important;}.mso .size-36, .ie .size-36{font-size: 36px !important; line-height: 43px !important;}.mso .size-40, .ie .size-40{font-size: 40px !important; line-height: 47px !important;}.mso .size-44, .ie .size-44{font-size: 44px !important; line-height: 50px !important;}.mso .size-48, .ie .size-48{font-size: 48px !important; line-height: 54px !important;}.mso .size-56, .ie .size-56{font-size: 56px !important; line-height: 60px !important;}.mso .size-64, .ie .size-64{font-size: 64px !important; line-height: 63px !important;}</style> <style type="text/css"> body{background-color: #fbfbfb}.logo a:hover, .logo a:focus{color: #1e2e3b !important}.mso .layout-has-border{border-top: 1px solid #c8c8c8; border-bottom: 1px solid #c8c8c8}.mso .layout-has-bottom-border{border-bottom: 1px solid #c8c8c8}.mso .border, .ie .border{background-color: #c8c8c8}.mso h1, .ie h1{}.mso h1, .ie h1{font-size: 26px !important; line-height: 34px !important}.mso h2, .ie h2{}.mso h2, .ie h2{font-size: 20px !important; line-height: 28px !important}.mso h3, .ie h3{}.mso .layout__inner, .ie .layout__inner{}.mso .footer__share-button p{}.mso .footer__share-button p{font-family: Georgia, serif}</style> <meta name="robots" content="noindex,nofollow"/> <meta property="og:title" content="Email"/></head><!--[if mso]><body class="mso"><![endif]--><body class="full-padding" style="margin: 0;padding: 0;-webkit-text-size-adjust: 100%;"><table class="wrapper" style="border-collapse: collapse;table-layout: fixed;min-width: 320px;width: 100%;background-color: #fbfbfb;" cellpadding="0" cellspacing="0" role="presentation"> <tbody> <tr> <td> <div role="banner"> <div class="header" style="Margin: 0 auto;max-width: 600px;min-width: 320px; width: 320px;width: calc(28000% - 167400px);" id="emb-email-header-container"><!--[if (mso)|(IE)]> <table align="center" class="header" cellpadding="0" cellspacing="0" role="presentation"> <tr> <td style="width: 600px"><![endif]--> <div class="logo emb-logo-margin-box" style="font-size: 26px;line-height: 32px;Margin-top: 6px;Margin-bottom: 20px;color: #41637e;font-family: Avenir,sans-serif;Margin-left: 20px;Margin-right: 20px;" align="center"> <div class="logo-center" align="center" id="emb-email-header"><img style="display: block;height: auto;width: 100%;border: 0;max-width: 201px;" src="cid:' . $img . '" alt="" width="201"/></div></div></div></div><div role="section"> <div class="layout one-col fixed-width" style="Margin: 0 auto;max-width: 600px;min-width: 320px; width: 320px;width: calc(28000% - 167400px);overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;"> <div class="layout__inner" style="border-collapse: collapse;display: table;width: 100%;background-color: #ffffff;" emb-background-style><!--[if (mso)|(IE)]> <table align="center" cellpadding="0" cellspacing="0" role="presentation"> <tr class="layout-fixed-width" emb-background-style> <td style="width: 600px" class="w560"><![endif]--> <div class="column" style="text-align: left;color: #565656;font-size: 14px;line-height: 21px;font-family: Georgia,serif;max-width: 600px;min-width: 320px; width: 320px;width: calc(28000% - 167400px);"> <div style="Margin-left: 20px;Margin-right: 20px;Margin-top: 24px;Margin-bottom: 24px;"> <p style="Margin-top: 20px;Margin-bottom: 0;"> [message] </p></div></div></div></div><div style="line-height:20px;font-size:20px;">&nbsp;</div><div style="width: 100%; max-width: 600px; color: #ccc; font-size: 14px; margin: 20px auto; text-align: center;"> Food Service and Solution Co.,Ltd 29 S.Chalaemnimit, Bangkhlo, Bangkorlaem, Bangkok 10120 </div><div style="line-height:40px;font-size:40px;">&nbsp;</div></div></td></tr></tbody></table></body></html>';
        $html = str_replace('[message]', $message, $html);
        $this->email->message($html);
        $this->email->send(FALSE);
    }


}