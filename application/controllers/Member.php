<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Member extends CI_Controller
{

    public $render_data = array();

    public function __construct()
    {
        parent::__construct();
        $this->load->model('taxonomy_model');
        $this->render_data['product_category'] = $this->taxonomy_model->get_taxonomy_term('product_category');
        $this->render_data['active_menu'] = 'member';
        $this->load->model('members_model', 'members');
    }


    public function index()
    {
        if (!is_login()) {
            redirect('login');
        }


        $user = $this->session->userdata('fnsn');
        $this->render_data['user'] = $this->members->get_members($user['uid']);

        $this->load->library('form_validation');
        $this->config->set_item('csrf_protection', true);
        $this->load->helper('security');
        if ($this->input->post('password')) {
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]|max_length[50]');
            $this->form_validation->set_rules('re-password', 'Confirm Password', 'required|min_length[6]|max_length[50]|matches[password]');
        }

        $this->form_validation->set_rules('name', 'Name', 'required|max_length[100]');
        $this->form_validation->set_rules('phone', 'Phone', 'required|max_length[20]');
        $this->form_validation->set_rules('shipping_name', 'Shipping Name', 'required|max_length[200]');
        $this->form_validation->set_rules('shipping_province', 'Shipping Province', 'required');
        $this->form_validation->set_rules('shipping_zip', 'Shipping Zip', 'required|numeric|min_length[5]|max_length[5]');
        $this->form_validation->set_rules('shipping_address', 'Shipping Address', 'required');
        $this->form_validation->set_rules('billing_name', 'Billing Name', 'required|max_length[200]');
        $this->form_validation->set_rules('billing_province', 'Billing Province', 'required');
        $this->form_validation->set_rules('billing_zip', 'Billing Zip', 'required|numeric|min_length[5]|max_length[5]');
        $this->form_validation->set_rules('billing_address', 'Billing Address', 'required');


        if ($this->render_data['user']['account_type'] == 'business') {
            $this->form_validation->set_rules('business_name', 'Business Name', 'required|max_length[200]');
            $this->form_validation->set_rules('business_address', 'Business Address', 'required');
            $this->form_validation->set_rules('business_number', 'Business Tax ID', 'required|max_length[30]');
        }
        if ($this->form_validation->run()) {
            $data_create = array(
                'name' => $this->input->post('name'),
                'phone' => $this->input->post('phone'),
                'shipping_name' => $this->input->post('shipping_name'),
                'shipping_province' => $this->input->post('shipping_province'),
                'shipping_zip' => $this->input->post('shipping_zip'),
                'shipping_address' => $this->input->post('shipping_address'),
                'business_name' => $this->input->post('business_name'),
                'business_address' => $this->input->post('business_address'),
                'business_number' => $this->input->post('business_number'),
                'billing_name' => $this->input->post('billing_name'),
                'billing_province' => $this->input->post('billing_province'),
                'billing_zip' => $this->input->post('billing_zip'),
                'billing_address' => $this->input->post('billing_address')
            );
            if ($this->input->post('password')) {
                $data_create['password'] = md5($this->input->post('password'));
            }

            $this->members->update_members($user['uid'], $data_create);
            echo json_encode(array('status' => 'success'));
        } else {
            if ($this->input->is_ajax_request()) {
                echo json_encode(array('status' => 'error', 'message' => validation_errors()));
                exit();
            }
            $this->render_data['web_title'] = 'My Profile';

            $this->template->write_view('content', 'frontend/profile', $this->render_data);
            $this->template->render();
        }
    }

    function forgot_password()
    {
        $this->config->set_item('csrf_protection', true);
        $this->load->helper('security');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
//        $this->form_validation->set_rules('g-recaptcha-response', 'Verify recaptcha', 'required|callback_captcha');
        if ($this->form_validation->run() && $this->input->is_ajax_request()) {

            if ($d = $this->members->forgot_password($this->input->post('email'))) {

                $html = '<div style="margin-top:10px;background: #013A93;padding:20px;color:#fff;">
	<h4 style="margin:0px; font-size: 20px;">Action required:</h4>
	<h3 style="margin:0px; font-size: 30px;">Please confirm to reset password.</h3>
</div>

<div style="margin-top:20px;">
Dear FSNS Thailand Customer,<br><br><br>
You request reset password link from system.<br>
You can click link to confirm reset password and get new password.
</div>
<div>
<a href="' . base_url('reset-password/' . $d) . '" target="_blank" style="display: block;padding:10px;color: #ffffff;text-decoration: none;background: #C50802;border-bottom: 3px solid #8E0501;font-size: 20px; max-width: 300px;text-align: center;
margin-top: 20px;">Reset Password</a><br>
If you can\'t click the button,Please copy link to open in your browser address.<br>
<a href="' . base_url('reset-password/' . $d) . '" target="_blank">
' . base_url('reset-password/' . $d) . '
</a>
</div>
<div style="margin-top:50px;">
Thanks for beging a FSNS Thailand customer.
</div>';

                $this->__sendmail($this->input->post('email'), 'Please reset your password.', $html);

                echo json_encode(array('status' => 'success'));
            } else {
                echo json_encode(array('status' => 'error', 'message' => 'Thie email is not found.'));
            }

        } else {
            echo json_encode(array('status' => 'error', 'message' => validation_errors()));
        }
    }

    function reset_password($token = '')
    {
        if ($token == '' || !$dt = $this->members->get_user_by_token_forgot($token)) {
            $html = 'Your link is invalid or expired.';
        } else {
            $newpass = $this->members->reset_password($dt);
            $html = '<div style="margin-top:10px;background: #013A93;padding:20px;color:#fff;">
	<h3 style="margin:0px; font-size: 30px;">Your new password.</h3>
</div>

<div style="margin-top:20px;">
Dear FSNS Thailand Customer,<br><br><br>
Your new password has generated by system.<br>
You can use new password to sign in on FSNS Thailand.
</div>
<div>
<a href="' . base_url('login') . '" target="_blank" style="display: block;padding:10px;color: #ffffff;text-decoration: none;background: #C50802;border-bottom: 3px solid #8E0501;font-size: 20px; max-width: 300px;text-align: center;
margin-top: 20px;">Password : ' . $newpass . '</a><br>

</div>
<div style="margin-top:50px;">
Thanks for beging a FSNS Thailand customer.
</div>';

            $this->__sendmail($this->input->post('email'), 'Your new password.', $html);
            $html = 'Your new password has send to your email success.';
        }
        $this->render_data['html'] = $html;
        $this->render_data['web_title'] = 'Reset password.';
        $this->template->write_view('content', 'frontend/reset_password', $this->render_data);
        $this->template->render();
    }


    public function login()
    {
        if (is_login()) {
            redirect('profile');
        }
        $this->config->set_item('csrf_protection', true);
        $this->load->helper('security');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]|max_length[50]');
//        $this->form_validation->set_rules('g-recaptcha-response', 'Verify recaptcha', 'required|callback_captcha');
        if ($this->form_validation->run()) {
            $this->load->model('auth_model', 'auth');
            $param = array('email' => $this->input->post('email'), 'password' => $this->input->post('password'));
            if ($userdata = $this->auth->member_login($param)) {

                $session = array(
                    'uid' => $userdata['uid'],
                    'type' => $userdata['account_type'],
                    'group' => 'user',
                    'name' => $userdata['name'],
                    'phone' => $userdata['phone'],
                    'staff_id' => $userdata['staff_id'],
                    'email' => $userdata['email']
                );
                $this->db->where('uid', $userdata['uid'])->update('users', array('last_login' => time()));
                $this->session->set_userdata('fnsn', $session);
                echo json_encode(array('status' => 'success'));
            } else {
                echo json_encode(array('status' => 'error', 'message' => 'เข้าสู่ระบบผิดพลาด'));
            }

        } else {
            if ($this->input->is_ajax_request()) {
                echo json_encode(array('status' => 'error', 'message' => validation_errors()));
                exit();
            }
            $this->render_data['web_title'] = 'Login';
            $this->template->write_view('content', 'frontend/login', $this->render_data);
            $this->template->render();
        }
    }

    public function register()
    {
        if (is_login()) {
            redirect('profile');
        }
        $this->load->library('form_validation');
        $this->config->set_item('csrf_protection', true);
        $this->load->helper('security');
        $this->form_validation->set_rules('type', 'Account Type', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]|max_length[50]');
        $this->form_validation->set_rules('re-password', 'Confirm Password', 'required|min_length[6]|max_length[50]|matches[password]');

        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
        $this->form_validation->set_rules('name', 'Name', 'required|max_length[100]');
        $this->form_validation->set_rules('phone', 'Phone', 'required|max_length[20]');
        $this->form_validation->set_rules('shipping_name', 'Shipping Name', 'required|max_length[200]');
        $this->form_validation->set_rules('shipping_province', 'Shipping Province', 'required');
        $this->form_validation->set_rules('shipping_zip', 'Shipping Zip', 'required|numeric|min_length[5]|max_length[5]');
        $this->form_validation->set_rules('shipping_address', 'Shipping Address', 'required');
        $this->form_validation->set_rules('g-recaptcha-response', 'Verify recaptcha', 'required|callback_captcha');

        if ($this->input->post('type') == 'business') {
            $this->form_validation->set_rules('business_name', 'Business Name', 'required|max_length[200]');
            $this->form_validation->set_rules('business_address', 'Business Address', 'required');
            $this->form_validation->set_rules('business_number', 'Business Tax ID', 'required|max_length[30]');
        }
        if ($this->form_validation->run()) {

            $data_create = array(
                'account_type' => $this->input->post('type'),
                'staff_id' => 0,
                'password' => md5($this->input->post('password')),
                'email' => $this->input->post('email'),
                'name' => $this->input->post('name'),
                'is_active' => 0,
                'phone' => $this->input->post('phone'),
                'shipping_name' => $this->input->post('shipping_name'),
                'shipping_province' => $this->input->post('shipping_province'),
                'shipping_zip' => $this->input->post('shipping_zip'),
                'shipping_address' => $this->input->post('shipping_address'),
                'business_name' => $this->input->post('business_name'),
                'business_address' => $this->input->post('business_address'),
                'business_number' => $this->input->post('business_number'),
                'billing_name' => $this->input->post('shipping_name'),
                'billing_province' => $this->input->post('shipping_province'),
                'billing_zip' => $this->input->post('shipping_zip'),
                'billing_address' => $this->input->post('shipping_address'),
                'register_ip' => $this->input->ip_address(),
                'register_date' => time(),
                'token' => md5($this->input->post('email') . time()),
            );
            $in_id = $this->members->add_members($data_create);
            if ($in_id) {

                $html = '<div style="margin-top:10px;background: #013A93;padding:20px;color:#fff;">
	<h4 style="margin:0px; font-size: 20px;">Action required:</h4>
	<h3 style="margin: 20px 0px 0px 1px; font-size: 30px;">Please verify your email address.</h3>
</div>

<div style="margin-top:20px;">
Dear FSNS Thailand Customer,<br><br><br>
We noticed that you need to verify your email address. To do so,simply click the button below.<br>
You will not be asked to log in to your FSNS Account - we are simply verifying ownership of this email address.
</div>
<div>
<a href="' . base_url('verify-email/' . $data_create['token']) . '" target="_blank" style="display: block;padding:10px;color: #ffffff;text-decoration: none;background: #C50802;border-bottom: 3px solid #8E0501;font-size: 20px; max-width: 300px;text-align: center;
margin-top: 20px;">Verify your email address</a><br>
If you can\'t click the button,Please copy link to open in your browser address.<br>
<a href="' . base_url('verify-email/' . $data_create['token']) . '" target="_blank">
' . base_url('verify-email/' . $data_create['token']) . '
</a>
</div>
<div style="margin-top:50px;">
Thanks for beging a FSNS Thailand customer.
</div>';

                $this->__sendmail($this->input->post('email'), 'Please verify your email address.', $html);
                $rs = array('status' => 'success');
            } else {
                $rs = array('status' => 'error', 'message' => 'Server error!');
            }
            echo json_encode($rs);
        } else {
            if ($this->input->is_ajax_request()) {
                echo json_encode(array('status' => 'error', 'message' => validation_errors()));
                exit();
            }
            $this->render_data['web_title'] = 'Register';
            $this->template->write_view('content', 'frontend/register', $this->render_data);
            $this->template->render();
        }
    }

    public function captcha($res)
    {
        if (verify_recaptcha($res)) {
            return TRUE;
        } else {
            $this->form_validation->set_message('captcha', 'Please verify captcha.');
            return FALSE;
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

    function verify_email($token = '')
    {
        if ($token == '' || !$dt = $this->members->get_user_by_token($token)) {
            $html = 'Your link is invalid or expired.';
        } else {
            $html = 'Your account is verify succes. <br>Please <a href="' . base_url('login') . '">click here</a> to login.';
        }
        $this->render_data['html'] = $html;
        $this->render_data['web_title'] = 'Verify email address.';
        $this->template->write_view('content', 'frontend/verify_email', $this->render_data);
        $this->template->render();
    }

    public function logout()
    {
        @session_destroy();
        @$this->session->sess_destroy();

        redirect('');
    }
}
