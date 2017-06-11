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


        if ($this->render_data['user']['account_type'] == 'business') {
            $this->form_validation->set_rules('business_name', 'Business Name', 'required|max_length[200]');
            $this->form_validation->set_rules('business_address', 'Business Address', 'required');
            $this->form_validation->set_rules('business_number', 'Federal tax identification number', 'required|max_length[30]');
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
                'business_number' => $this->input->post('business_number')
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
        $this->form_validation->set_rules('g-recaptcha-response', 'Verify recaptcha', 'required|callback_captcha');
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
        $this->form_validation->set_rules('g-recaptcha-response', 'Verify recaptcha', 'required|callback_captcha');
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
            $this->form_validation->set_rules('business_number', 'Federal tax identification number', 'required|max_length[30]');
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
                'register_ip' => $this->input->ip_address(),
                'register_date' => time(),
                'token' => md5($this->input->post('email') . time()),
            );
            $in_id = $this->members->add_members($data_create);
            if ($in_id) {

                $html = '<div style="margin-top:10px;background: #013A93;padding:20px;color:#fff;">
	<h4 style="margin:0px; font-size: 20px;">Action required:</h4>
	<h3 style="margin:0px; font-size: 30px;">Please verify your email address.</h3>
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

        $this->load->library('email');
        $this->email->subject('');
        $this->email->from($this->setting_data['email_for_contact'], $title);
        $this->email->to($email);
        $this->email->set_mailtype("html");

        $html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><!--[if IE]><html xmlns="http://www.w3.org/1999/xhtml" class="ie"><![endif]--><html style="margin: 0;padding: 0;" xmlns="http://www.w3.org/1999/xhtml"><head> <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/> <title></title> <meta http-equiv="X-UA-Compatible" content="IE=edge"/> <meta name="viewport" content="width=device-width"/> <style type="text/css"> @media only screen and (min-width: 620px){.wrapper{min-width: 600px !important}.wrapper h1{}.wrapper h1{font-size: 26px !important; line-height: 34px !important}.wrapper h2{}.wrapper h2{font-size: 20px !important; line-height: 28px !important}.wrapper h3{}.column{}.wrapper .size-8{font-size: 8px !important; line-height: 14px !important}.wrapper .size-9{font-size: 9px !important; line-height: 16px !important}.wrapper .size-10{font-size: 10px !important; line-height: 18px !important}.wrapper .size-11{font-size: 11px !important; line-height: 19px !important}.wrapper .size-12{font-size: 12px !important; line-height: 19px !important}.wrapper .size-13{font-size: 13px !important; line-height: 21px !important}.wrapper .size-14{font-size: 14px !important; line-height: 21px !important}.wrapper .size-15{font-size: 15px !important; line-height: 23px !important}.wrapper .size-16{font-size: 16px !important; line-height: 24px !important}.wrapper .size-17{font-size: 17px !important; line-height: 26px !important}.wrapper .size-18{font-size: 18px !important; line-height: 26px !important}.wrapper .size-20{font-size: 20px !important; line-height: 28px !important}.wrapper .size-22{font-size: 22px !important; line-height: 31px !important}.wrapper .size-24{font-size: 24px !important; line-height: 32px !important}.wrapper .size-26{font-size: 26px !important; line-height: 34px !important}.wrapper .size-28{font-size: 28px !important; line-height: 36px !important}.wrapper .size-30{font-size: 30px !important; line-height: 38px !important}.wrapper .size-32{font-size: 32px !important; line-height: 40px !important}.wrapper .size-34{font-size: 34px !important; line-height: 43px !important}.wrapper .size-36{font-size: 36px !important; line-height: 43px !important}.wrapper .size-40{font-size: 40px !important; line-height: 47px !important}.wrapper .size-44{font-size: 44px !important; line-height: 50px !important}.wrapper .size-48{font-size: 48px !important; line-height: 54px !important}.wrapper .size-56{font-size: 56px !important; line-height: 60px !important}.wrapper .size-64{font-size: 64px !important; line-height: 63px !important}}</style> <style type="text/css"> body{margin: 0; padding: 0;}table{border-collapse: collapse; table-layout: fixed;}*{line-height: inherit;}[x-apple-data-detectors], [href^="tel"], [href^="sms"]{color: inherit !important; text-decoration: none !important;}.wrapper .footer__share-button a:hover, .wrapper .footer__share-button a:focus{color: #ffffff !important;}.btn a:hover, .btn a:focus, .footer__share-button a:hover, .footer__share-button a:focus, .email-footer__links a:hover, .email-footer__links a:focus{opacity: 0.8;}.preheader, .header, .layout, .column{transition: width 0.25s ease-in-out, max-width 0.25s ease-in-out;}.layout, div.header{max-width: 600px !important; -fallback-width: 95% !important; width: calc(100% - 20px) !important;}div.preheader{max-width: 360px !important; -fallback-width: 90% !important; width: calc(100% - 60px) !important;}.snippet, .webversion{Float: none !important;}.column{max-width: 600px !important; width: 100% !important;}.fixed-width.has-border{max-width: 402px !important;}.fixed-width.has-border .layout__inner{box-sizing: border-box;}.snippet, .webversion{width: 50% !important;}.ie .btn{width: 100%;}[owa] .column div, [owa] .column button{display: block !important;}.ie .column, [owa] .column, .ie .gutter, [owa] .gutter{display: table-cell; float: none !important; vertical-align: top;}.ie div.preheader, [owa] div.preheader, .ie .email-footer, [owa] .email-footer{max-width: 560px !important; width: 560px !important;}.ie .snippet, [owa] .snippet, .ie .webversion, [owa] .webversion{width: 280px !important;}.ie div.header, [owa] div.header, .ie .layout, [owa] .layout, .ie .one-col .column, [owa] .one-col .column{max-width: 600px !important; width: 600px !important;}.ie .fixed-width.has-border, [owa] .fixed-width.has-border, .ie .has-gutter.has-border, [owa] .has-gutter.has-border{max-width: 602px !important; width: 602px !important;}.ie .two-col .column, [owa] .two-col .column{max-width: 300px !important; width: 300px !important;}.ie .three-col .column, [owa] .three-col .column, .ie .narrow, [owa] .narrow{max-width: 200px !important; width: 200px !important;}.ie .wide, [owa] .wide{width: 600px !important;}.ie .two-col.has-gutter .column, [owa] .two-col.x_has-gutter .column{max-width: 290px !important; width: 290px !important;}.ie .three-col.has-gutter .column, [owa] .three-col.x_has-gutter .column, .ie .has-gutter .narrow, [owa] .has-gutter .narrow{max-width: 188px !important; width: 188px !important;}.ie .has-gutter .wide, [owa] .has-gutter .wide{max-width: 394px !important; width: 394px !important;}.ie .two-col.has-gutter.has-border .column, [owa] .two-col.x_has-gutter.x_has-border .column{max-width: 292px !important; width: 292px !important;}.ie .three-col.has-gutter.has-border .column, [owa] .three-col.x_has-gutter.x_has-border .column, .ie .has-gutter.has-border .narrow, [owa] .has-gutter.x_has-border .narrow{max-width: 190px !important; width: 190px !important;}.ie .has-gutter.has-border .wide, [owa] .has-gutter.x_has-border .wide{max-width: 396px !important; width: 396px !important;}.ie .fixed-width .layout__inner{border-left: 0 none white !important; border-right: 0 none white !important;}.ie .layout__edges{display: none;}.mso .layout__edges{font-size: 0;}.layout-fixed-width, .mso .layout-full-width{background-color: #ffffff;}@media only screen and (min-width: 620px){.column, .gutter{display: table-cell; Float: none !important; vertical-align: top;}div.preheader, .email-footer{max-width: 560px !important; width: 560px !important;}.snippet, .webversion{width: 280px !important;}div.header, .layout, .one-col .column{max-width: 600px !important; width: 600px !important;}.fixed-width.has-border, .fixed-width.ecxhas-border, .has-gutter.has-border, .has-gutter.ecxhas-border{max-width: 602px !important; width: 602px !important;}.two-col .column{max-width: 300px !important; width: 300px !important;}.three-col .column, .column.narrow{max-width: 200px !important; width: 200px !important;}.column.wide{width: 600px !important;}.two-col.has-gutter .column, .two-col.ecxhas-gutter .column{max-width: 290px !important; width: 290px !important;}.three-col.has-gutter .column, .three-col.ecxhas-gutter .column, .has-gutter .narrow{max-width: 188px !important; width: 188px !important;}.has-gutter .wide{max-width: 394px !important; width: 394px !important;}.two-col.has-gutter.has-border .column, .two-col.ecxhas-gutter.ecxhas-border .column{max-width: 292px !important; width: 292px !important;}.three-col.has-gutter.has-border .column, .three-col.ecxhas-gutter.ecxhas-border .column, .has-gutter.has-border .narrow, .has-gutter.ecxhas-border .narrow{max-width: 190px !important; width: 190px !important;}.has-gutter.has-border .wide, .has-gutter.ecxhas-border .wide{max-width: 396px !important; width: 396px !important;}}@media (max-width: 321px){.fixed-width.has-border .layout__inner{border-width: 1px 0 !important;}.layout, .column{min-width: 320px !important; width: 320px !important;}.border{display: none;}}.mso div{border: 0 none white !important;}.mso .w560 .divider{Margin-left: 260px !important; Margin-right: 260px !important;}.mso .w360 .divider{Margin-left: 160px !important; Margin-right: 160px !important;}.mso .w260 .divider{Margin-left: 110px !important; Margin-right: 110px !important;}.mso .w160 .divider{Margin-left: 60px !important; Margin-right: 60px !important;}.mso .w354 .divider{Margin-left: 157px !important; Margin-right: 157px !important;}.mso .w250 .divider{Margin-left: 105px !important; Margin-right: 105px !important;}.mso .w148 .divider{Margin-left: 54px !important; Margin-right: 54px !important;}.mso .size-8, .ie .size-8{font-size: 8px !important; line-height: 14px !important;}.mso .size-9, .ie .size-9{font-size: 9px !important; line-height: 16px !important;}.mso .size-10, .ie .size-10{font-size: 10px !important; line-height: 18px !important;}.mso .size-11, .ie .size-11{font-size: 11px !important; line-height: 19px !important;}.mso .size-12, .ie .size-12{font-size: 12px !important; line-height: 19px !important;}.mso .size-13, .ie .size-13{font-size: 13px !important; line-height: 21px !important;}.mso .size-14, .ie .size-14{font-size: 14px !important; line-height: 21px !important;}.mso .size-15, .ie .size-15{font-size: 15px !important; line-height: 23px !important;}.mso .size-16, .ie .size-16{font-size: 16px !important; line-height: 24px !important;}.mso .size-17, .ie .size-17{font-size: 17px !important; line-height: 26px !important;}.mso .size-18, .ie .size-18{font-size: 18px !important; line-height: 26px !important;}.mso .size-20, .ie .size-20{font-size: 20px !important; line-height: 28px !important;}.mso .size-22, .ie .size-22{font-size: 22px !important; line-height: 31px !important;}.mso .size-24, .ie .size-24{font-size: 24px !important; line-height: 32px !important;}.mso .size-26, .ie .size-26{font-size: 26px !important; line-height: 34px !important;}.mso .size-28, .ie .size-28{font-size: 28px !important; line-height: 36px !important;}.mso .size-30, .ie .size-30{font-size: 30px !important; line-height: 38px !important;}.mso .size-32, .ie .size-32{font-size: 32px !important; line-height: 40px !important;}.mso .size-34, .ie .size-34{font-size: 34px !important; line-height: 43px !important;}.mso .size-36, .ie .size-36{font-size: 36px !important; line-height: 43px !important;}.mso .size-40, .ie .size-40{font-size: 40px !important; line-height: 47px !important;}.mso .size-44, .ie .size-44{font-size: 44px !important; line-height: 50px !important;}.mso .size-48, .ie .size-48{font-size: 48px !important; line-height: 54px !important;}.mso .size-56, .ie .size-56{font-size: 56px !important; line-height: 60px !important;}.mso .size-64, .ie .size-64{font-size: 64px !important; line-height: 63px !important;}</style> <style type="text/css"> body{background-color: #fbfbfb}.logo a:hover, .logo a:focus{color: #1e2e3b !important}.mso .layout-has-border{border-top: 1px solid #c8c8c8; border-bottom: 1px solid #c8c8c8}.mso .layout-has-bottom-border{border-bottom: 1px solid #c8c8c8}.mso .border, .ie .border{background-color: #c8c8c8}.mso h1, .ie h1{}.mso h1, .ie h1{font-size: 26px !important; line-height: 34px !important}.mso h2, .ie h2{}.mso h2, .ie h2{font-size: 20px !important; line-height: 28px !important}.mso h3, .ie h3{}.mso .layout__inner, .ie .layout__inner{}.mso .footer__share-button p{}.mso .footer__share-button p{font-family: Georgia, serif}</style> <meta name="robots" content="noindex,nofollow"/> <meta property="og:title" content="Email"/></head><!--[if mso]><body class="mso"><![endif]--><body class="full-padding" style="margin: 0;padding: 0;-webkit-text-size-adjust: 100%;"><table class="wrapper" style="border-collapse: collapse;table-layout: fixed;min-width: 320px;width: 100%;background-color: #fbfbfb;" cellpadding="0" cellspacing="0" role="presentation"> <tbody> <tr> <td> <div role="banner"> <div class="header" style="Margin: 0 auto;max-width: 600px;min-width: 320px; width: 320px;width: calc(28000% - 167400px);" id="emb-email-header-container"><!--[if (mso)|(IE)]> <table align="center" class="header" cellpadding="0" cellspacing="0" role="presentation"> <tr> <td style="width: 600px"><![endif]--> <div class="logo emb-logo-margin-box" style="font-size: 26px;line-height: 32px;Margin-top: 6px;Margin-bottom: 20px;color: #41637e;font-family: Avenir,sans-serif;Margin-left: 20px;Margin-right: 20px;" align="center"> <div class="logo-center" align="center" id="emb-email-header"><img style="display: block;height: auto;width: 100%;border: 0;max-width: 201px;" src="data:image/png;base64,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" alt="" width="201"/></div></div></div></div><div role="section"> <div class="layout one-col fixed-width" style="Margin: 0 auto;max-width: 600px;min-width: 320px; width: 320px;width: calc(28000% - 167400px);overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;"> <div class="layout__inner" style="border-collapse: collapse;display: table;width: 100%;background-color: #ffffff;" emb-background-style><!--[if (mso)|(IE)]> <table align="center" cellpadding="0" cellspacing="0" role="presentation"> <tr class="layout-fixed-width" emb-background-style> <td style="width: 600px" class="w560"><![endif]--> <div class="column" style="text-align: left;color: #565656;font-size: 14px;line-height: 21px;font-family: Georgia,serif;max-width: 600px;min-width: 320px; width: 320px;width: calc(28000% - 167400px);"> <div style="Margin-left: 20px;Margin-right: 20px;Margin-top: 24px;Margin-bottom: 24px;"> <h1 style="Margin-top: 0;Margin-bottom: 0;font-style: normal;font-weight: normal;color: #565656;font-size: 22px;line-height: 31px;font-family: Avenir,sans-serif;"> [header] </h1> <p style="Margin-top: 20px;Margin-bottom: 0;"> [message] </p></div></div></div></div><div style="line-height:20px;font-size:20px;">&nbsp;</div><div style="width: 100%; max-width: 600px; color: #ccc; font-size: 14px; margin: 20px auto; text-align: center;"> Food Service and Solution Co.,Ltd 29 S.Chalaemnimit, Bangkhlo, Bangkorlaem, Bangkok 10120 </div><div style="line-height:40px;font-size:40px;">&nbsp;</div></div></td></tr></tbody></table></body></html>';
        $html = str_replace(array('[title]', '[message]'), array($title, $message), $html);
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
