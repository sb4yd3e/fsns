<?php
function is_admin()
{
    $ci = &get_instance();
    $user = $ci->session->userdata('fnsn');
    if ($user['group'] == '1') {
        return true;
    } else {
        return false;
    }
}

function is_group($role)
{
    $ci = &get_instance();
    $user = $ci->session->userdata('fnsn');
    if (is_array($role)) {
        if (in_array($user['group'], $role)) {
            return true;
        } else {
            return false;
        }
    } else {
        if ($user['group'] == $role) {
            return true;
        } else {
            return false;
        }
    }

}

function is_login()
{
    $ci = &get_instance();
    $user = $ci->session->userdata('fnsn');
    if ($user && $user['group'] == 'user') {
        return true;
    } else {
        return false;
    }
}

function save_cache($key, $value, $time = 100000)
{
    $ci = &get_instance();
    $ci->load->driver('cache', array('adapter' => 'file', 'backup' => 'file'));
    return $ci->cache->save($key, $value, $time);
}

function get_cache($key)
{
    $ci = &get_instance();
    $ci->load->driver('cache', array('adapter' => 'file', 'backup' => 'file'));
    return $ci->cache->get($key);
}

function short_content($text = '', $number = 100)
{
    $text = strip_tags($text);
    $text = iconv_substr($text, 0, $number, "UTF-8") . "...";
    return $text;
}

function short_title($text = '', $number = 40, $end_charector = '')
{
    $text = strip_tags($text);
    if (strlen($text) > $number) {
        $text = iconv_substr($text, 0, $number, "UTF-8") . $end_charector;
    }
    return $text;
}

function delete_cache($key)
{
    $ci = &get_instance();
    $ci->load->driver('cache', array('adapter' => 'file', 'backup' => 'file'));
    return $ci->cache->delete($key);
}

function error_json($array)
{
    $html = '';
    foreach ($array as $value) {
        $html .= $value . '<br>';
    }
    return $html;
}

function en_password($password)
{
    $ci = &get_instance();
    $newpass = $ci->encrypt->encode(md5($password));
    return $newpass;
}

function check_password($password, $password_db)
{
    $ci = &get_instance();
    if (md5($password) == $ci->encrypt->decode($password_db)) {
        return true;
    } else {
        return false;
    }
}

function redirect_ref()
{
    if (empty($_SERVER["HTTP_REFERER"])) {
        return '';
    } else {
        return $_SERVER["HTTP_REFERER"];
    }
}


function str_to_int($string)
{
    $integer = '';
    foreach (str_split($string) as $char) {
        $integer .= sprintf("%03s", ord($char));
    }
    return $integer;
}

function int_to_str($integer)
{
    $string = '';
    foreach (str_split($integer, 3) as $number) {
        $string .= chr($number);
    }
    return $string;
}

function discount($amount, $discount)
{
    return $amount * ($discount / 100);
}


function uploadfile($file, $file_temp)
{
    $ber = rand(100, 10000);
    $num = time();

    if (move_uploaded_file($file_temp, "uploads/invoice/" . $ber . $num . strstr($file, '.'))) {
        $pic = $ber . $num . strstr($file, '.');
        return $pic;
    } else {
        return false;
    }
}

function verify_recaptcha($res)
{
    $ci = &get_instance();
    if ($res != '') {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "secret=" . $ci->config->item('recaptcha_secret') . "&response=" . $res . "&remoteip=" . $ci->input->ip_address());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($response);
        if (!$data->success) {
            return false;
        } else {
            return TRUE;
        }
    } else {
        return FALSE;
    }
}

function is_active($status)
{
    if ($status == 1) {
        return '<label class="label label-success">Actived</label>';
    } else {
        return '<label class="label label-danger">Inactive</label>';
    }
}

function is_online($status)
{
    if ($status == 1) {
        return '<label class="label label-success">YES</label>';
    } else {
        return '<label class="label label-danger">NO</label>';
    }
}

function in_stock($status)
{
    if ($status == 1) {
        return '<label class="label label-success">In Stock</label>';
    } else {
        return '<label class="label label-danger">Out of stock</label>';
    }
}

function add_log($user, $detail, $key = '')
{
    $ci = &get_instance();
    $ci->load->database();
    $ci->db->insert("logs", array("log_date" => date("y-m-d H:i:s"), "user" => $user, "detail" => $detail, "key_log" => $key));
}

function add_order_process($oid, $type, $title, $detail = '')
{
    $ci = &get_instance();
    $ci->load->database();
    $ci->db->insert("order_process", array("at_date" => time(), "oid" => $oid, "process_type" => $type, "process_title" => $title, "process_detail" => $detail));
}

function list_logs($key = '')
{
    $ci = &get_instance();
    $ci->load->database();
    return $ci->db->where('key_log', $key)->limit(10)->order_by('lid', 'desc')->get('logs')->result_array();
}

function get_staff_username($aid = '')
{
    if ($aid != '' && $aid != 0) {
        $ci = &get_instance();
        $ci->load->database();
        $rs = $ci->db->select('name')->where('aid', $aid)->get('admins')->row_array();
        return $rs['name'];
    } else {
        return '-';
    }
}

function list_province()
{
    return array(
        "" => "==== Select ====",
        "กรุงเทพมหานคร" => "กรุงเทพมหานคร"
    , "กระบี่" => "กระบี่"
    , "กาญจนบุรี" => "กาญจนบุรี"
    , "กาฬสินธุ์" => "กาฬสินธุ์"
    , "กำแพงเพชร" => "กำแพงเพชร"
    , "ขอนแก่น" => "ขอนแก่น"
    , "จันทบุรี" => "จันทบุรี"
    , "ฉะเชิงเทรา" => "ฉะเชิงเทรา"
    , "ชัยนาท" => "ชัยนาท"
    , "ชัยภูมิ" => "ชัยภูมิ"
    , "ชุมพร" => "ชุมพร"
    , "ชลบุรี" => "ชลบุรี"
    , "เชียงใหม่" => "เชียงใหม่"
    , "เชียงราย" => "เชียงราย"
    , "ตรัง" => "ตรัง"
    , "ตราด" => "ตราด"
    , "ตาก" => "ตาก"
    , "นครนายก" => "นครนายก"
    , "นครปฐม" => "นครปฐม"
    , "นครพนม" => "นครพนม"
    , "นครราชสีมา" => "นครราชสีมา"
    , "นครศรีธรรมราช" => "นครศรีธรรมราช"
    , "นครสวรรค์" => "นครสวรรค์"
    , "นราธิวาส" => "นราธิวาส"
    , "น่าน" => "น่าน"
    , "นนทบุรี" => "นนทบุรี"
    , "บึงกาฬ" => "บึงกาฬ"
    , "บุรีรัมย์" => "บุรีรัมย์"
    , "ประจวบคีรีขันธ์" => "ประจวบคีรีขันธ์"
    , "ปทุมธานี" => "ปทุมธานี"
    , "ปราจีนบุรี" => "ปราจีนบุรี"
    , "ปัตตานี" => "ปัตตานี"
    , "พะเยา" => "พะเยา"
    , "พระนครศรีอยุธยา" => "พระนครศรีอยุธยา"
    , "พังงา" => "พังงา"
    , "พิจิตร" => "พิจิตร"
    , "พิษณุโลก" => "พิษณุโลก"
    , "เพชรบุรี" => "เพชรบุรี"
    , "เพชรบูรณ์" => "เพชรบูรณ์"
    , "แพร่" => "แพร่"
    , "พัทลุง" => "พัทลุง"
    , "ภูเก็ต" => "ภูเก็ต"
    , "มหาสารคาม" => "มหาสารคาม"
    , "มุกดาหาร" => "มุกดาหาร"
    , "แม่ฮ่องสอน" => "แม่ฮ่องสอน"
    , "ยโสธร" => "ยโสธร"
    , "ยะลา" => "ยะลา"
    , "ร้อยเอ็ด" => "ร้อยเอ็ด"
    , "ระนอง" => "ระนอง"
    , "ระยอง" => "ระยอง"
    , "ราชบุรี" => "ราชบุรี"
    , "ลพบุรี" => "ลพบุรี"
    , "ลำปาง" => "ลำปาง"
    , "ลำพูน" => "ลำพูน"
    , "เลย" => "เลย"
    , "ศรีสะเกษ" => "ศรีสะเกษ"
    , "สกลนคร" => "สกลนคร"
    , "สงขลา" => "สงขลา"
    , "สมุทรสาคร" => "สมุทรสาคร"
    , "สมุทรปราการ" => "สมุทรปราการ"
    , "สมุทรสงคราม" => "สมุทรสงคราม"
    , "สระแก้ว" => "สระแก้ว"
    , "สระบุรี" => "สระบุรี"
    , "สิงห์บุรี" => "สิงห์บุรี"
    , "สุโขทัย" => "สุโขทัย"
    , "สุพรรณบุรี" => "สุพรรณบุรี"
    , "สุราษฎร์ธานี" => "สุราษฎร์ธานี"
    , "สุรินทร์" => "สุรินทร์"
    , "สตูล" => "สตูล"
    , "หนองคาย" => "หนองคาย"
    , "หนองบัวลำภู" => "หนองบัวลำภู"
    , "อำนาจเจริญ" => "อำนาจเจริญ"
    , "อุดรธานี" => "อุดรธานี"
    , "อุตรดิตถ์" => "อุตรดิตถ์"
    , "อุทัยธานี" => "อุทัยธานี"
    , "อุบลราชธานี" => "อุบลราชธานี"
    , "อ่างทอง" => "อ่างทอง");
}


function dateToTime($date)
{
    $date = str_replace('/', '-', $date);
    $date = $date . ":00";
    return strtotime($date);
}

function order_status($status)
{
    $s = array(
        'pending' => 'รอตรวจสอบการสั่งซื้อ',
        'confirmed' => 'ยืนยันการสั่งซื้อ',
        'wait_payment' => 'ลูกค้าชำระเงิน/ส่งเอกสาร',
        'confirm_payment' => 'ยืนยันการชำระ/ส่งเอกสาร',
        'shipping' => 'มีการจัดส่ง',
        'success' => 'สำเร็จ',
        'cancel' => 'ยกเลิก'
    );
    return $s[$status];
}

function order_type($type)
{
    $s = array(
        'business' => 'นิติบุคคล',
        'personal' => 'บุคคลทั่วไป'
    );
    return $s[$type];
}

function front_end_list_document($id)
{
    $ci = &get_instance();
    $ci->load->database();
    $rs = $ci->db->select('ufid,file_title')->where('ufid', $id)->get('order_files')->row_array();
    $html = '<a href="' . base_url('orders/download-file/' . $rs['ufid']) . '" class="label-info label" target="_blank">Download : ' . $rs['file_title'] . '</a>';

    return $html;
}

function get_sale_name($id)
{
    if ($id && $id != 0) {

        $ci = &get_instance();
        $ci->load->database();
        $rs = $ci->db->select('name')->from('admins')->where('aid', $id)->get()->row_array();
        if ($rs) {
            return $rs['name'];
        }
    }
}

function get_product_by_oid($text)
{
    $ci = &get_instance();
    $ci->load->database();
    $arr = explode(',', $text);
    array_unique($arr);
    $html = explode('|', $arr[0]);
    $html = '<strong>' . $html[1] . '</strong><br>';
    foreach ($arr as $v) {
        if ($v != '') {
            $id = explode('|', $v);
            $rs = $ci->db->select('product_title,product_code,product_value')->where('odid', $id[0])->get('order_details')->row_array();
            $html .= '- [' . $rs['product_code'] . '] ' . $rs['product_title'] . ' - ' . $rs['product_value'] . '<br>';
        }
    }
    return $html;
}
function get_email_sale($id){
    if ($id && $id != 0) {

        $ci = &get_instance();
        $ci->load->database();
        $rs = $ci->db->select('email')->from('admins')->where('aid', $id)->get()->row_array();
        if ($rs) {
            return $rs['email'];
        }
    }
}

function payment_list(){
    return array(
        ''=>'Select',
        'BBL'=>'ธนาคารกรุงเทพ',
        'KBANK'=>'ธนาคารกสิกรไทย',
        'KTB'=>'ธนาคารกรุงไทย',
        'TMB'=>'ธนาคารทหารไทย',
        'SCB'=>'ธนาคารไทยพาณิชย์',
        'BAY'=>'ธนาคารกรุงศรีอยุธยา',
        'KKB'=>'ธนาคารเกียรตินาคิน',
        'CIMB'=>'ธนาคารซีไอเอ็มบีไทย',
        'TISCO'=>'ธนาคารทิสโก้',
        'TBANK'=>'ธนาคารธนชาต',
        'UOB'=>'ธนาคารยูโอบี',
        'SCBT'=>'ธนาคารสแตนดาร์ดชาร์เตอร์ด (ไทย)',
        'TCD'=>'ธนาคารไทยเครดิตเพื่อรายย่อย',
        'LHBANK'=>'ธนาคารแลนด์ แอนด์ เฮาส์',
        'ICBC'=>'ธนาคารไอซีบีซี (ไทย)',
        'SME'=>'ธนาคารพัฒนาวิสาหกิจขนาดกลางและขนาดย่อมแห่งประเทศไทย',
        'BAAC'=>'ธนาคารเพื่อการเกษตรและสหกรณ์การเกษตร',
        'EXIM'=>'ธนาคารเพื่อการส่งออกและนำเข้าแห่งประเทศไทย',
        'GSB'=>'ธนาคารออมสิน',
        'GHB'=>'ธนาคารอาคารสงเคราะห์',
        'ISALAM'=>'ธนาคารอิสลามแห่งประเทศไทย'
    );
}

function send_mail($email,$from,$cc,$title,$message){
    $ci = &get_instance();
    $filename = 'img/logo.png';
    $ci->load->library('email');
    $ci->email->attach($filename);
    $ci->email->subject($title);
    $ci->email->from($from, 'FSNS Thailand : '.$title);
    $ci->email->to($email);
    if($cc){
        $ci->email->cc($cc);
    }
    $img = $ci->email->attachment_cid($filename);
    $ci->email->set_mailtype("html");

    $html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><!--[if IE]><html xmlns="http://www.w3.org/1999/xhtml" class="ie"><![endif]--><html style="margin: 0;padding: 0;" xmlns="http://www.w3.org/1999/xhtml"><head> <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/> <title></title> <meta http-equiv="X-UA-Compatible" content="IE=edge"/> <meta name="viewport" content="width=device-width"/> <style type="text/css"> @media only screen and (min-width: 620px){.wrapper{min-width: 1000px !important}.wrapper h1{}.wrapper h1{font-size: 26px !important; line-height: 34px !important}.wrapper h2{}.wrapper h2{font-size: 20px !important; line-height: 28px !important}.wrapper h3{}.column{}.wrapper .size-8{font-size: 8px !important; line-height: 14px !important}.wrapper .size-9{font-size: 9px !important; line-height: 16px !important}.wrapper .size-10{font-size: 10px !important; line-height: 18px !important}.wrapper .size-11{font-size: 11px !important; line-height: 19px !important}.wrapper .size-12{font-size: 12px !important; line-height: 19px !important}.wrapper .size-13{font-size: 13px !important; line-height: 21px !important}.wrapper .size-14{font-size: 14px !important; line-height: 21px !important}.wrapper .size-15{font-size: 15px !important; line-height: 23px !important}.wrapper .size-16{font-size: 16px !important; line-height: 24px !important}.wrapper .size-17{font-size: 17px !important; line-height: 26px !important}.wrapper .size-18{font-size: 18px !important; line-height: 26px !important}.wrapper .size-20{font-size: 20px !important; line-height: 28px !important}.wrapper .size-22{font-size: 22px !important; line-height: 31px !important}.wrapper .size-24{font-size: 24px !important; line-height: 32px !important}.wrapper .size-26{font-size: 26px !important; line-height: 34px !important}.wrapper .size-28{font-size: 28px !important; line-height: 36px !important}.wrapper .size-30{font-size: 30px !important; line-height: 38px !important}.wrapper .size-32{font-size: 32px !important; line-height: 40px !important}.wrapper .size-34{font-size: 34px !important; line-height: 43px !important}.wrapper .size-36{font-size: 36px !important; line-height: 43px !important}.wrapper .size-40{font-size: 40px !important; line-height: 47px !important}.wrapper .size-44{font-size: 44px !important; line-height: 50px !important}.wrapper .size-48{font-size: 48px !important; line-height: 54px !important}.wrapper .size-56{font-size: 56px !important; line-height: 60px !important}.wrapper .size-64{font-size: 64px !important; line-height: 63px !important}}</style> <style type="text/css"> body{margin: 0; padding: 0;}table{border-collapse: collapse; table-layout: fixed;}*{line-height: inherit;}[x-apple-data-detectors], [href^="tel"], [href^="sms"]{color: inherit !important; text-decoration: none !important;}.wrapper .footer__share-button a:hover, .wrapper .footer__share-button a:focus{color: #ffffff !important;}.btn a:hover, .btn a:focus, .footer__share-button a:hover, .footer__share-button a:focus, .email-footer__links a:hover, .email-footer__links a:focus{opacity: 0.8;}.preheader, .header, .layout, .column{transition: width 0.25s ease-in-out, max-width 0.25s ease-in-out;}.layout, div.header{max-width: 1000px !important; -fallback-width: 95% !important; width: calc(100% - 20px) !important;}div.preheader{max-width: 360px !important; -fallback-width: 90% !important; width: calc(100% - 60px) !important;}.snippet, .webversion{Float: none !important;}.column{max-width: 1000px !important; width: 100% !important;}.fixed-width.has-border{max-width: 402px !important;}.fixed-width.has-border .layout__inner{box-sizing: border-box;}.snippet, .webversion{width: 50% !important;}.ie .btn{width: 100%;}[owa] .column div, [owa] .column button{display: block !important;}.ie .column, [owa] .column, .ie .gutter, [owa] .gutter{display: table-cell; float: none !important; vertical-align: top;}.ie div.preheader, [owa] div.preheader, .ie .email-footer, [owa] .email-footer{max-width: 960px !important; width: 960px !important;}.ie .snippet, [owa] .snippet, .ie .webversion, [owa] .webversion{width: 280px !important;}.ie div.header, [owa] div.header, .ie .layout, [owa] .layout, .ie .one-col .column, [owa] .one-col .column{max-width: 1000px !important; width: 1000px !important;}.ie .fixed-width.has-border, [owa] .fixed-width.has-border, .ie .has-gutter.has-border, [owa] .has-gutter.has-border{max-width: 602px !important; width: 602px !important;}.ie .two-col .column, [owa] .two-col .column{max-width: 300px !important; width: 300px !important;}.ie .three-col .column, [owa] .three-col .column, .ie .narrow, [owa] .narrow{max-width: 200px !important; width: 200px !important;}.ie .wide, [owa] .wide{width: 1000px !important;}.ie .two-col.has-gutter .column, [owa] .two-col.x_has-gutter .column{max-width: 290px !important; width: 290px !important;}.ie .three-col.has-gutter .column, [owa] .three-col.x_has-gutter .column, .ie .has-gutter .narrow, [owa] .has-gutter .narrow{max-width: 188px !important; width: 188px !important;}.ie .has-gutter .wide, [owa] .has-gutter .wide{max-width: 394px !important; width: 394px !important;}.ie .two-col.has-gutter.has-border .column, [owa] .two-col.x_has-gutter.x_has-border .column{max-width: 292px !important; width: 292px !important;}.ie .three-col.has-gutter.has-border .column, [owa] .three-col.x_has-gutter.x_has-border .column, .ie .has-gutter.has-border .narrow, [owa] .has-gutter.x_has-border .narrow{max-width: 190px !important; width: 190px !important;}.ie .has-gutter.has-border .wide, [owa] .has-gutter.x_has-border .wide{max-width: 396px !important; width: 396px !important;}.ie .fixed-width .layout__inner{border-left: 0 none white !important; border-right: 0 none white !important;}.ie .layout__edges{display: none;}.mso .layout__edges{font-size: 0;}.layout-fixed-width, .mso .layout-full-width{background-color: #ffffff;}@media only screen and (min-width: 620px){.column, .gutter{display: table-cell; Float: none !important; vertical-align: top;}div.preheader, .email-footer{max-width: 960px !important; width: 960px !important;}.snippet, .webversion{width: 280px !important;}div.header, .layout, .one-col .column{max-width: 1000px !important; width: 1000px !important;}.fixed-width.has-border, .fixed-width.ecxhas-border, .has-gutter.has-border, .has-gutter.ecxhas-border{max-width: 602px !important; width: 602px !important;}.two-col .column{max-width: 300px !important; width: 300px !important;}.three-col .column, .column.narrow{max-width: 200px !important; width: 200px !important;}.column.wide{width: 1000px !important;}.two-col.has-gutter .column, .two-col.ecxhas-gutter .column{max-width: 290px !important; width: 290px !important;}.three-col.has-gutter .column, .three-col.ecxhas-gutter .column, .has-gutter .narrow{max-width: 188px !important; width: 188px !important;}.has-gutter .wide{max-width: 394px !important; width: 394px !important;}.two-col.has-gutter.has-border .column, .two-col.ecxhas-gutter.ecxhas-border .column{max-width: 292px !important; width: 292px !important;}.three-col.has-gutter.has-border .column, .three-col.ecxhas-gutter.ecxhas-border .column, .has-gutter.has-border .narrow, .has-gutter.ecxhas-border .narrow{max-width: 190px !important; width: 190px !important;}.has-gutter.has-border .wide, .has-gutter.ecxhas-border .wide{max-width: 396px !important; width: 396px !important;}}@media (max-width: 321px){.fixed-width.has-border .layout__inner{border-width: 1px 0 !important;}.layout, .column{min-width: 320px !important; width: 320px !important;}.border{display: none;}}.mso div{border: 0 none white !important;}.mso .w560 .divider{Margin-left: 260px !important; Margin-right: 260px !important;}.mso .w360 .divider{Margin-left: 160px !important; Margin-right: 160px !important;}.mso .w260 .divider{Margin-left: 110px !important; Margin-right: 110px !important;}.mso .w160 .divider{Margin-left: 60px !important; Margin-right: 60px !important;}.mso .w354 .divider{Margin-left: 157px !important; Margin-right: 157px !important;}.mso .w250 .divider{Margin-left: 105px !important; Margin-right: 105px !important;}.mso .w148 .divider{Margin-left: 54px !important; Margin-right: 54px !important;}.mso .size-8, .ie .size-8{font-size: 8px !important; line-height: 14px !important;}.mso .size-9, .ie .size-9{font-size: 9px !important; line-height: 16px !important;}.mso .size-10, .ie .size-10{font-size: 10px !important; line-height: 18px !important;}.mso .size-11, .ie .size-11{font-size: 11px !important; line-height: 19px !important;}.mso .size-12, .ie .size-12{font-size: 12px !important; line-height: 19px !important;}.mso .size-13, .ie .size-13{font-size: 13px !important; line-height: 21px !important;}.mso .size-14, .ie .size-14{font-size: 14px !important; line-height: 21px !important;}.mso .size-15, .ie .size-15{font-size: 15px !important; line-height: 23px !important;}.mso .size-16, .ie .size-16{font-size: 16px !important; line-height: 24px !important;}.mso .size-17, .ie .size-17{font-size: 17px !important; line-height: 26px !important;}.mso .size-18, .ie .size-18{font-size: 18px !important; line-height: 26px !important;}.mso .size-20, .ie .size-20{font-size: 20px !important; line-height: 28px !important;}.mso .size-22, .ie .size-22{font-size: 22px !important; line-height: 31px !important;}.mso .size-24, .ie .size-24{font-size: 24px !important; line-height: 32px !important;}.mso .size-26, .ie .size-26{font-size: 26px !important; line-height: 34px !important;}.mso .size-28, .ie .size-28{font-size: 28px !important; line-height: 36px !important;}.mso .size-30, .ie .size-30{font-size: 30px !important; line-height: 38px !important;}.mso .size-32, .ie .size-32{font-size: 32px !important; line-height: 40px !important;}.mso .size-34, .ie .size-34{font-size: 34px !important; line-height: 43px !important;}.mso .size-36, .ie .size-36{font-size: 36px !important; line-height: 43px !important;}.mso .size-40, .ie .size-40{font-size: 40px !important; line-height: 47px !important;}.mso .size-44, .ie .size-44{font-size: 44px !important; line-height: 50px !important;}.mso .size-48, .ie .size-48{font-size: 48px !important; line-height: 54px !important;}.mso .size-56, .ie .size-56{font-size: 56px !important; line-height: 60px !important;}.mso .size-64, .ie .size-64{font-size: 64px !important; line-height: 63px !important;}</style> <style type="text/css"> body{background-color: #fbfbfb}.logo a:hover, .logo a:focus{color: #1e2e3b !important}.mso .layout-has-border{border-top: 1px solid #c8c8c8; border-bottom: 1px solid #c8c8c8}.mso .layout-has-bottom-border{border-bottom: 1px solid #c8c8c8}.mso .border, .ie .border{background-color: #c8c8c8}.mso h1, .ie h1{}.mso h1, .ie h1{font-size: 26px !important; line-height: 34px !important}.mso h2, .ie h2{}.mso h2, .ie h2{font-size: 20px !important; line-height: 28px !important}.mso h3, .ie h3{}.mso .layout__inner, .ie .layout__inner{}.mso .footer__share-button p{}.mso .footer__share-button p{font-family: Georgia, serif}</style> <meta name="robots" content="noindex,nofollow"/> <meta property="og:title" content="Email"/></head><!--[if mso]><body class="mso"><![endif]--><body class="full-padding" style="margin: 0;padding: 0;-webkit-text-size-adjust: 100%;"><table class="wrapper" style="border-collapse: collapse;table-layout: fixed;min-width: 320px;width: 100%;background-color: #fbfbfb;" cellpadding="0" cellspacing="0" role="presentation"> <tbody> <tr> <td> <div role="banner"> <div class="header" style="Margin: 0 auto;max-width: 1000px;min-width: 320px; width: 320px;width: calc(28000% - 167400px);" id="emb-email-header-container"><!--[if (mso)|(IE)]> <table align="center" class="header" cellpadding="0" cellspacing="0" role="presentation"> <tr> <td style="width: 1000px"><![endif]--> <div class="logo emb-logo-margin-box" style="font-size: 26px;line-height: 32px;Margin-top: 6px;Margin-bottom: 20px;color: #41637e;font-family: Avenir,sans-serif;Margin-left: 20px;Margin-right: 20px;" align="center"> <div class="logo-center" align="center" id="emb-email-header"><img style="display: block;height: auto;width: 100%;border: 0;max-width: 201px;" src="cid:' . $img . '" alt="" width="201"/></div></div></div></div><div role="section"> <div class="layout one-col fixed-width" style="Margin: 0 auto;max-width: 1000px;min-width: 320px; width: 320px;width: calc(28000% - 167400px);overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;"> <div class="layout__inner" style="border-collapse: collapse;display: table;width: 100%;background-color: #ffffff;" emb-background-style><!--[if (mso)|(IE)]> <table align="center" cellpadding="0" cellspacing="0" role="presentation"> <tr class="layout-fixed-width" emb-background-style> <td style="width: 1000px" class="w560"><![endif]--> <div class="column" style="text-align: left;color: #565656;font-size: 14px;line-height: 21px;font-family: Georgia,serif;max-width: 1000px;min-width: 320px; width: 320px;width: calc(28000% - 167400px);"> <div style="Margin-left: 20px;Margin-right: 20px;Margin-top: 24px;Margin-bottom: 24px;"> <p style="Margin-top: 20px;Margin-bottom: 0;"> [message] </p></div></div></div></div><div style="line-height:20px;font-size:20px;">&nbsp;</div><div style="width: 100%; max-width: 1000px; color: #ccc; font-size: 14px; margin: 20px auto; text-align: center;"> Food Service and Solution Co.,Ltd 29 S.Chalaemnimit, Bangkhlo, Bangkorlaem, Bangkok 10120 </div><div style="line-height:40px;font-size:40px;">&nbsp;</div></div></td></tr></tbody></table></body></html>';
    $html = str_replace('[message]', $message, $html);
    $ci->email->message($html);
    $ci->email->send(FALSE);
}