<?php
function is_admin() {
    $ci = & get_instance();
    $user = $ci->session->userdata('fnsn');
    if ($user['group'] == '1') {
        return true;
    } else {
        return false;
    }
}

function is_group($role) {
    $ci = & get_instance();
    $user = $ci->session->userdata('fnsn');
    if(is_array($role)){
        if(in_array($user['group'], $role)){
            return true;
        }else{
           return false;
       }
   }else{
    if ($user['group'] == $role) {
        return true;
    } else {
        return false;
    }
}

}

function is_login() {
    $ci = & get_instance();
    $user = $ci->session->userdata('fnsn');
    if ($user) {
        return true;
    } else {
        return false;
    }
}

function save_cache($key, $value, $time = 100000) {
    $ci = & get_instance();
    $ci->load->driver('cache', array('adapter' => 'file', 'backup' => 'file'));
    return $ci->cache->save($key, $value, $time);
}

function get_cache($key) {
    $ci = & get_instance();
    $ci->load->driver('cache', array('adapter' => 'file', 'backup' => 'file'));
    return $ci->cache->get($key);
}

function short_content($text = '', $number = 100) {
    $text = strip_tags($text);
    $text = iconv_substr($text, 0, $number, "UTF-8") . "...";
    return $text;
}

function short_title($text = '', $number = 40, $end_charector = '') {
    $text = strip_tags($text);
    if (strlen($text) > $number) {
        $text = iconv_substr($text, 0, $number, "UTF-8") . $end_charector;
    }
    return $text;
}

function delete_cache($key) {
    $ci = & get_instance();
    $ci->load->driver('cache', array('adapter' => 'file', 'backup' => 'file'));
    return $ci->cache->delete($key);
}
function error_json($array) {
    $html = '';
    foreach ($array as $value) {
        $html .= $value . '<br>';
    }
    return $html;
}
function en_password($password) {
    $ci = & get_instance();
    $newpass = $ci->encrypt->encode(md5($password));
    return $newpass;
}

function check_password($password, $password_db) {
    $ci = & get_instance();
    if (md5($password) == $ci->encrypt->decode($password_db)) {
        return true;
    } else {
        return false;
    }
}

function redirect_ref() {
    if (empty($_SERVER["HTTP_REFERER"])) {
        return '';
    } else {
        return $_SERVER["HTTP_REFERER"];
    }
}


function str_to_int($string) {
    $integer = '';
    foreach (str_split($string) as $char) {
        $integer .= sprintf("%03s", ord($char));
    }
    return $integer;
}

function int_to_str($integer) {
    $string = '';
    foreach (str_split($integer, 3) as $number) {
        $string .= chr($number);
    }
    return $string;
}

function discount($amount, $discount) {
    return $amount * ($discount / 100);
}



function uploadfile($file, $file_temp) {
    $ber = rand(100, 10000);
    $num = time();

    if (move_uploaded_file($file_temp, "uploads/invoice/" . $ber . $num . strstr($file, '.'))) {
        $pic = $ber . $num . strstr($file, '.');
        return $pic;
    } else {
        return false;
    }
}

function verify_recaptcha($res) {
    $ci = &get_instance();
    if($res!=''){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "secret=" . $ci->config->item('recaptcha_secret') . "&response=" . $res . "&remoteip=" . $ci->input->ip_address());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($response);
        if(!$data->success){
            return false;
        }else{
            return TRUE;
        }
    }else{
        return FALSE;
    }
}

function is_active($status){
    if($status==1){
        return '<label class="label label-success">Actived</label>';
    }else{
        return '<label class="label label-danger">Inactive</label>';
    }
}

function is_online($status){
    if($status==1){
        return '<label class="label label-success">YES</label>';
    }else{
        return '<label class="label label-danger">NO</label>';
    }
}

function in_stock($status){
    if($status==1){
        return '<label class="label label-success">In Stock</label>';
    }else{
        return '<label class="label label-danger">Out of stock</label>';
    }
}
function add_log($user,$detail,$key=''){
    $ci = &get_instance();
    $ci->load->database();
    $ci->db->insert("logs",array("log_date"=>date("y-m-d H:i:s"),"user"=>$user,"detail"=>$detail,"key_log"=>$key));
}
function list_logs($key=''){
    $ci = &get_instance();
    $ci->load->database();
    return $ci->db->where('key_log',$key)->limit(10)->order_by('lid','desc')->get('logs')->result_array();
}
function get_staff_username($aid=''){
    if($aid!='' && $aid!=0){
        $ci = &get_instance();
        $ci->load->database();
        $rs = $ci->db->select('name')->where('aid',$aid)->get('admins')->row_array();
        return $rs['name'];
    }else{
        return '-';
    }
}

function list_province(){
    return array(
        ""=>"==== Select ====",
        "กรุงเทพมหานคร"=>"กรุงเทพมหานคร"
        ,"กระบี่"=>"กระบี่"
        ,"กาญจนบุรี"=>"กาญจนบุรี"
        ,"กาฬสินธุ์"=>"กาฬสินธุ์"
        ,"กำแพงเพชร"=>"กำแพงเพชร"
        ,"ขอนแก่น"=>"ขอนแก่น"
        ,"จันทบุรี"=>"จันทบุรี"
        ,"ฉะเชิงเทรา"=>"ฉะเชิงเทรา"
        ,"ชัยนาท"=>"ชัยนาท"
        ,"ชัยภูมิ"=>"ชัยภูมิ"
        ,"ชุมพร"=>"ชุมพร"
        ,"ชลบุรี"=>"ชลบุรี"
        ,"เชียงใหม่"=>"เชียงใหม่"
        ,"เชียงราย"=>"เชียงราย"
        ,"ตรัง"=>"ตรัง"
        ,"ตราด"=>"ตราด"
        ,"ตาก"=>"ตาก"
        ,"นครนายก"=>"นครนายก"
        ,"นครปฐม"=>"นครปฐม"
        ,"นครพนม"=>"นครพนม"
        ,"นครราชสีมา"=>"นครราชสีมา"
        ,"นครศรีธรรมราช"=>"นครศรีธรรมราช"
        ,"นครสวรรค์"=>"นครสวรรค์"
        ,"นราธิวาส"=>"นราธิวาส"
        ,"น่าน"=>"น่าน"
        ,"นนทบุรี"=>"นนทบุรี"
        ,"บึงกาฬ"=>"บึงกาฬ"
        ,"บุรีรัมย์"=>"บุรีรัมย์"
        ,"ประจวบคีรีขันธ์"=>"ประจวบคีรีขันธ์"
        ,"ปทุมธานี"=>"ปทุมธานี"
        ,"ปราจีนบุรี"=>"ปราจีนบุรี"
        ,"ปัตตานี"=>"ปัตตานี"
        ,"พะเยา"=>"พะเยา"
        ,"พระนครศรีอยุธยา"=>"พระนครศรีอยุธยา"
        ,"พังงา"=>"พังงา"
        ,"พิจิตร"=>"พิจิตร"
        ,"พิษณุโลก"=>"พิษณุโลก"
        ,"เพชรบุรี"=>"เพชรบุรี"
        ,"เพชรบูรณ์"=>"เพชรบูรณ์"
        ,"แพร่"=>"แพร่"
        ,"พัทลุง"=>"พัทลุง"
        ,"ภูเก็ต"=>"ภูเก็ต"
        ,"มหาสารคาม"=>"มหาสารคาม"
        ,"มุกดาหาร"=>"มุกดาหาร"
        ,"แม่ฮ่องสอน"=>"แม่ฮ่องสอน"
        ,"ยโสธร"=>"ยโสธร"
        ,"ยะลา"=>"ยะลา"
        ,"ร้อยเอ็ด"=>"ร้อยเอ็ด"
        ,"ระนอง"=>"ระนอง"
        ,"ระยอง"=>"ระยอง"
        ,"ราชบุรี"=>"ราชบุรี"
        ,"ลพบุรี"=>"ลพบุรี"
        ,"ลำปาง"=>"ลำปาง"
        ,"ลำพูน"=>"ลำพูน"
        ,"เลย"=>"เลย"
        ,"ศรีสะเกษ"=>"ศรีสะเกษ"
        ,"สกลนคร"=>"สกลนคร"
        ,"สงขลา"=>"สงขลา"
        ,"สมุทรสาคร"=>"สมุทรสาคร"
        ,"สมุทรปราการ"=>"สมุทรปราการ"
        ,"สมุทรสงคราม"=>"สมุทรสงคราม"
        ,"สระแก้ว"=>"สระแก้ว"
        ,"สระบุรี"=>"สระบุรี"
        ,"สิงห์บุรี"=>"สิงห์บุรี"
        ,"สุโขทัย"=>"สุโขทัย"
        ,"สุพรรณบุรี"=>"สุพรรณบุรี"
        ,"สุราษฎร์ธานี"=>"สุราษฎร์ธานี"
        ,"สุรินทร์"=>"สุรินทร์"
        ,"สตูล"=>"สตูล"
        ,"หนองคาย"=>"หนองคาย"
        ,"หนองบัวลำภู"=>"หนองบัวลำภู"
        ,"อำนาจเจริญ"=>"อำนาจเจริญ"
        ,"อุดรธานี"=>"อุดรธานี"
        ,"อุตรดิตถ์"=>"อุตรดิตถ์"
        ,"อุทัยธานี"=>"อุทัยธานี"
        ,"อุบลราชธานี"=>"อุบลราชธานี"
        ,"อ่างทอง"=>"อ่างทอง");
}


function dateToTime($date){
    $date = str_replace('/','-',$date);
    $date = $date.":00";
    return strtotime($date);
}

function order_status($status){
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

function order_type($type){
    $s = array(
        'business'=>'นิติบุคคล',
        'personal'=>'บุคคลทั่วไป'
    );
    return $s[$type];
}