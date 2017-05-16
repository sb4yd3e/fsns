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
    foreach ($str as $value) {
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


function add_log($user,$detail){
    $ci = &get_instance();
    $ci->load->database();
    $ci->db->insert("logs",array("log_date"=>date("y-m-d H:i:s"),"user"=>$user,"detail"=>$detail));
}
