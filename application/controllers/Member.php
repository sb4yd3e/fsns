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
        $this->form_validation->set_rules('fax', 'Fax', 'max_length[20]');

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
            $this->form_validation->set_rules('business_number', 'Business Tax ID', 'required|max_length[13]|min_length[13]|callback_checkid');
            $this->form_validation->set_rules('business_province', 'Business Province', 'required|max_length[30]');
            $this->form_validation->set_rules('business_branch', 'Business Branch', 'required|max_length[5]|min_length[5]');
            $this->form_validation->set_rules('business_note', 'Business Note', 'max_length[200]');
        }
        if ($this->form_validation->run()) {
            $data_create = array(
                'name' => $this->input->post('name'),
                'phone' => $this->input->post('phone'),
                'fax' => $this->input->post('fax'),
                'shipping_name' => $this->input->post('shipping_name'),
                'shipping_province' => $this->input->post('shipping_province'),
                'shipping_zip' => $this->input->post('shipping_zip'),
                'shipping_address' => $this->input->post('shipping_address'),
                'business_name' => $this->input->post('business_name'),
                'business_address' => $this->input->post('business_address'),
                'business_number' => $this->input->post('business_number'),
                'business_branch' => $this->input->post('business_branch'),
                'business_note' => $this->input->post('business_note'),
                'business_province' => $this->input->post('business_province'),
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
	<h3 style="margin:0px; font-size: 20px;">ยืนยันการขอรหัสผ่านใหม่.</h3>
</div>

<div style="margin-top:20px;">
เรียนสมาชิก FSNS Thailand<br><br><br>
สมาชิกได้ทำการร้องขอเปลี่ยนแปลงรหัสผ่าน ผ่านระบบสมาชิกเว็บไซต์ FSNS<br>
สมาชิกสามารถคลิกลิงค์ด้านล่างเพื่อรับรหัสผ่านใหม่อีกครั้ง
</div>
<div>
<a href="' . base_url('reset-password/' . $d) . '" target="_blank" style="display: block;padding:10px;color: #ffffff;text-decoration: none;background: #C50802;border-bottom: 3px solid #8E0501;font-size: 20px; max-width: 300px;text-align: center;
margin-top: 20px;">ขอรหัสผ่านใหม่</a><br>
หากไม่สามารถคลิกลิงค์ได้ สมาชิกสามารถคัดลอกลิงค์ด้านล่างเพื่อนำไปเปิดในบราวเซอร์ได้<br>
<a href="' . base_url('reset-password/' . $d) . '" target="_blank">
' . base_url('reset-password/' . $d) . '
</a>
</div>
<div style="margin-top:50px;">
FSNS Thailand
</div>';

                send_mail($this->input->post('email'), $this->setting_data['email_for_member'], false, 'Please reset your password.', $html);

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
            $html = 'ลิงค์ยืนยันการเปลี่ยนรหัสผ่านไม่ถูกต้องกรุณาลองใหม่';
            $status = 'danger';
        } else {
            $newpass = $this->members->reset_password($dt['uid']);
            $status = 'success';
            $html = '<div style="margin-top:10px;background: #013A93;padding:18px;color:#fff;">
	<h3 style="margin:0px; font-size: 20px;">รหัสผ่านใหม่</h3>
</div>

<div style="margin-top:20px;">
เรียนสมาชิก FSNS Thailand<br><br><br>
รหัสผ่านใหม่ของสมาชิกได้ถูกสร้างแล้ว สมาชิกสามารถใช้รหัสผ่านใหม่นี้เข้าสู่ระบบได้ทันที<br>
เพื่อความปลอดภัย สมาชิกควรตั้งค่ารหัสผ่านใหม่หลังจากเข้าสู่ระบบแล้ว
</div>
<div>
<a href="' . base_url('login') . '" target="_blank" style="display: block;padding:10px;color: #ffffff;text-decoration: none;background: #C50802;border-bottom: 3px solid #8E0501;font-size: 20px; max-width: 300px;text-align: center;
margin-top: 20px;">รหัสผ่านใหม่คือ : ' . $newpass . '</a><br>

</div>
<div style="margin-top:50px;">
FSNS Thailand
</div>';

            send_mail($dt['email'], $this->setting_data['email_for_member'], false, 'Your new password.', $html);
            $html = 'รหัสผ่านใหม่ถูกสร้างขึ้นแล้ว กรุณาตรวจสอบอีเมลเพื่อรับรหัสผ่านใหม่';
        }
        $this->render_data['status'] = $status;
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
                if ($userdata['is_active'] == '1') {

                    $session = array(
                        'uid' => $userdata['uid'],
                        'type' => $userdata['account_type'],
                        'group' => 'user',
                        'name' => $userdata['name'],
                        'business_name' => $userdata['business_name'],
                        'phone' => $userdata['phone'],
                        'staff_id' => $userdata['staff_id'],
                        'email' => $userdata['email']
                    );
                    $this->db->where('uid', $userdata['uid'])->update('users', array('last_login' => time()));
                    $this->session->set_userdata('fnsn', $session);
                    echo json_encode(array('status' => 'success'));
                } else {
                    echo json_encode(array('status' => 'error', 'message' => 'กรุณายืนยันอีเมลก่อเข้าใช้งาน <br><a href="' . base_url('re-send-email/' . $this->input->post('email')) . '" class="link-alert">หรือคลิกที่นี่เพื่อรับลิงค์ยืนยันการใช้งานอีกครั้ง</a>'));
                }
            } else {
                echo json_encode(array('status' => 'error', 'message' => 'เข้าสู่ระบบผิดพลาด'));
            }

        } else {
            if ($this->input->is_ajax_request()) {
                echo json_encode(array('status' => 'error', 'message' => validation_errors()));
                exit();
            }
            $this->render_data['web_title'] = 'Member';
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
        $this->form_validation->set_rules('fax', 'Fax', 'max_length[20]');

        $this->form_validation->set_rules('shipping_name', 'Shipping Name', 'required|max_length[200]');
        $this->form_validation->set_rules('shipping_province', 'Shipping Province', 'required');
        $this->form_validation->set_rules('shipping_zip', 'Shipping Zip', 'required|numeric|min_length[5]|max_length[5]');
        $this->form_validation->set_rules('shipping_address', 'Shipping Address', 'required');

        $this->form_validation->set_rules('billing_name', 'Billing Name', 'required|max_length[200]');
        $this->form_validation->set_rules('billing_province', 'Billing Province', 'required');
        $this->form_validation->set_rules('billing_zip', 'Billing Zip', 'required|numeric|min_length[5]|max_length[5]');
        $this->form_validation->set_rules('billing_address', 'Billing Address', 'required');

        $this->form_validation->set_rules('g-recaptcha-response', 'Verify recaptcha', 'required|callback_captcha');

        if ($this->input->post('type') == 'business') {
            $this->form_validation->set_rules('business_name', 'Business Name', 'required|max_length[200]');
            $this->form_validation->set_rules('business_address', 'Business Address', 'required');
            $this->form_validation->set_rules('business_number', 'Business Tax ID', 'required|max_length[13]|min_length[13]|callback_checkid');
            $this->form_validation->set_rules('business_province', 'Business Province', 'required|max_length[30]');
            $this->form_validation->set_rules('business_branch', 'Business Branch', 'required|max_length[5]|min_length[5]');
            $this->form_validation->set_rules('business_note', 'Business Note', 'max_length[200]');
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
                'fax' => $this->input->post('fax'),
                'shipping_name' => $this->input->post('shipping_name'),
                'shipping_province' => $this->input->post('shipping_province'),
                'shipping_zip' => $this->input->post('shipping_zip'),
                'shipping_address' => $this->input->post('shipping_address'),
                'business_name' => $this->input->post('business_name'),
                'business_address' => $this->input->post('business_address'),
                'business_number' => $this->input->post('business_number'),
                'business_branch' => $this->input->post('business_branch'),
                'business_note' => $this->input->post('business_note'),
                'business_province' => $this->input->post('business_province'),
                'billing_name' => $this->input->post('billing_name'),
                'billing_province' => $this->input->post('billing_province'),
                'billing_zip' => $this->input->post('billing_zip'),
                'billing_address' => $this->input->post('billing_address'),
                'register_ip' => $this->input->ip_address(),
                'register_date' => time(),
                'token' => md5($this->input->post('email') . time()),
            );
            $in_id = $this->members->add_members($data_create);
            if ($in_id) {

                $html = '<div style="margin-top:10px;background: #013A93;padding:20px;color:#fff;">
	<h3 style="margin: 20px 0px 0px 1px; font-size: 20px;">กรุณายืนยันบัญชีเพื่อเข้าใช้งาน</h3>
</div>

<div style="margin-top:20px;">
เรียนสมาชิก FSNS Thailand<br><br><br>
ขอขอบคุณที่สมัครเป็นสมาชิกกับ FSNS Thailand<br>
เพื่อเป็นการยืนยันและเปิดใช้งานบัญชี กรุณายืนยันอีเมลโดยคลิกปุ่มด้านล่าง
</div>
<div>
<a href="' . base_url('verify-email/' . $data_create['token']) . '" target="_blank" style="display: block;padding:10px;color: #ffffff;text-decoration: none;background: #C50802;border-bottom: 3px solid #8E0501;font-size: 20px; max-width: 300px;text-align: center;
margin-top: 20px;">ยืนยันอีเมล</a><br>
หากไม่สามารถคลิกลิงค์ได้ สมาชิกสามารถคัดลอกลิงค์ด้านล่างเพื่อนำไปเปิดในบราวเซอร์ได้<br>
<a href="' . base_url('verify-email/' . $data_create['token']) . '" target="_blank">
' . base_url('verify-email/' . $data_create['token']) . '
</a>
</div>
<div style="margin-top:50px;">
FSNS Thailand
</div>';

                send_mail($this->input->post('email'), $this->setting_data['email_for_member'], false, 'Please verify your email address.', $html);
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

    function checkid($id)
    {
        if (strlen($id) != 13) {
            $this->form_validation->set_message('checkid', 'Please enter tax identification fill 13 digits.');
            return false;
        }
        for ($i = 0, $sum = 0; $i < 12; $i++)
            $sum += (int)($id{$i}) * (13 - $i);
        if ((11 - ($sum % 11)) % 10 == (int)($id{12})) {
            return true;
        } else {
            $this->form_validation->set_message('checkid', 'Invalid tax identification number');
            return false;
        }
    }

    function verify_email($token = '')
    {
        if ($token == '' || !$dt = $this->members->get_user_by_token($token)) {
            $html = 'การยืนยันอีเมลไม่ถูกต้องหรือเคยยืนยันแล้ว';
            $status = 'danger';
        } else {
            $html = 'ยืนยันอีเมลสำเร็จ. <br>กรุณา <a href="' . base_url('login') . '">คลิกที่นี่</a> เพื่อเข้าสู่ระบบ';
            $status = 'success';
        }
        $this->render_data['html'] = $html;
        $this->render_data['status'] = $status;
        $this->render_data['web_title'] = 'ยืนยันอีเมล';
        $this->template->write_view('content', 'frontend/verify_email', $this->render_data);
        $this->template->render();
    }

    function resendemail($email = '')
    {
        $token = $this->members->get_user_by_email($email);
        if ($token) {
            $html = '<div style="margin-top:10px;background: #013A93;padding:20px;color:#fff;">
	<h3 style="margin: 20px 0px 0px 1px; font-size: 20px;">กรุณายืนยันบัญชีเพื่อเข้าใช้งาน</h3>
</div>

<div style="margin-top:20px;">
เรียนสมาชิก FSNS Thailand<br><br><br>
ขอขอบคุณที่สมัครเป็นสมาชิกกับ FSNS Thailand<br>
เพื่อเป็นการยืนยันและเปิดใช้งานบัญชี กรุณายืนยันอีเมลโดยคลิกปุ่มด้านล่าง
</div>
<div>
<a href="' . base_url('verify-email/' . $token['token']) . '" target="_blank" style="display: block;padding:10px;color: #ffffff;text-decoration: none;background: #C50802;border-bottom: 3px solid #8E0501;font-size: 20px; max-width: 300px;text-align: center;
margin-top: 20px;">ยืนยันอีเมล</a><br>
หากไม่สามารถคลิกลิงค์ได้ สมาชิกสามารถคัดลอกลิงค์ด้านล่างเพื่อนำไปเปิดในบราวเซอร์ได้<br>
<a href="' . base_url('verify-email/' . $token['token']) . '" target="_blank">
' . base_url('verify-email/' . $token['token']) . '
</a>
</div>
<div style="margin-top:50px;">
FSNS Thailand
</div>';

            send_mail($email, $this->setting_data['email_for_member'], false, 'Please verify your email address.', $html);

        }
        $this->render_data['web_title'] = 'ส่งอีเมลยืนยันบัญชี';
        $this->template->write_view('content', 'frontend/resend_email', $this->render_data);
        $this->template->render();
    }

    public function logout()
    {
        @session_destroy();
        @$this->session->sess_destroy();

        redirect('');
    }
}
