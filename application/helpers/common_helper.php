<?php
/**
 * Created by PhpStorm.
 * User: miltone
 * Date: 5/13/17
 * Time: 9:04 AM
 */


/**
 * Get callback URL
 *
 * @return bool
 */
function get_callback()
{
    if (isset($_GET['callback'])) {
        return $_GET['callback'];
    }

    return FALSE;
}

/**
 * Check if callback URL is set
 *
 * @return bool|string
 */
function is_callback_set()
{
    if (isset($_GET['callback'])) {
        return '?callback=' . $_GET['callback'];
    }

    return FALSE;
}


/**
 * Encode table ID to large string of numbers
 *
 * @param $id
 * @return string
 */
function encode_id($id)
{
    $string = "ABCDEFGHIJKLMNOPQRSTUVXWZ";
    $rand = str_split($string);
    $left = array_rand($rand, 1);
    $right = array_rand($rand, 1);

    $build_query = $left . '_' . $id . '_' . $right;
    $strt_arry = str_split($build_query);
    $arry = array();
    foreach ($strt_arry as $kx => $vx) {
        $re = unpack('C*', $vx);
        if (strlen($re[1]) === 3) {
            $arry[] = $re[1];
        } else {
            $arry[] = '0' . $re[1];
        }
    }

    return $parameter = implode('', $arry);
}


/**
 * Decode string to normal table ID
 *
 * @param $string
 * @return null
 */
function decode_id($string)
{
    $str = join(array_map('chr', str_split($string, 3)));
    $exp = explode('_', $str);
    if (count($exp) == 3) {
        return $exp[1];
    } else {
        return NULL;
    }
}

/**
 * @author  Kevin van Zonneveld <kevin@vanzonneveld.net>
 * @author  Simon Franz
 * @author  Deadfish
 * @copyright 2008 Kevin van Zonneveld (http://kevin.vanzonneveld.net)
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD Licence
 * @version   SVN: Release: $Id: alphaID.inc.php 344 2009-06-10 17:43:59Z kevin $
 * @link      http://kevin.vanzonneveld.net/
 *
 * @param mixed $in String or long input to translate
 * @param boolean $to_num Reverses translation when true
 * @param mixed $pad_up Number or boolean padds the result up to a specified length
 * @param string $passKey Supplying a password makes it harder to calculate the original ID
 *
 * @return mixed string or long
 */
function alphaID($in, $to_num = false, $pad_up = false, $passKey = null)
{

    $index = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    if ($passKey !== null) {

        for ($n = 0; $n < strlen($index); $n++) {
            $i[] = substr($index, $n, 1);
        }

        $passhash = hash('sha256', $passKey);
        $passhash = (strlen($passhash) < strlen($index)) ? hash('sha512', $passKey) : $passhash;

        for ($n = 0; $n < strlen($index); $n++) {
            $p[] = substr($passhash, $n, 1);
        }

        array_multisort($p, SORT_DESC, $i);
        $index = implode($i);
    }

    $base = strlen($index);

    if ($to_num) {
        // Digital number  <<--  alphabet letter code
        $in = strrev($in);
        $out = 0;
        $len = strlen($in) - 1;
        for ($t = 0; $t <= $len; $t++) {
            $bcpow = bcpow($base, $len - $t);
            $out = $out + strpos($index, substr($in, $t, 1)) * $bcpow;
        }

        if (is_numeric($pad_up)) {
            $pad_up--;
            if ($pad_up > 0) {
                $out -= pow($base, $pad_up);
            }
        }
        $out = sprintf('%F', $out);
        $out = substr($out, 0, strpos($out, '.'));
    } else {
        // Digital number  -->>  alphabet letter code
        if (is_numeric($pad_up)) {
            $pad_up--;
            if ($pad_up > 0) {
                $in += pow($base, $pad_up);
            }
        }

        $out = "";
        for ($t = floor(log($in, $base)); $t >= 0; $t--) {
            $bcp = bcpow($base, $t);
            $a = floor($in / $bcp) % $base;
            $out = $out . substr($index, $a, 1);
            $in = $in - ($a * $bcp);
        }
        $out = strrev($out); // reverse
    }

    return $out;
}


function get_user_group($id = null)
{
    $CI = &get_instance();

    $id || $id = $CI->session->userdata('sims_online_user_id');

    $groupinfo = $CI->ion_auth_model->get_users_groups($id)->row();

    if ($groupinfo) {
        return $groupinfo;
    }

    return false;
}


/**
 * Update Users Access Level
 *
 * @param $user_id
 * @param $module_id
 * @param $link
 * @param $action
 * @return bool
 */
function update_access($group_id, $module_id, $section, $role, $action)
{

    $CI = &get_instance();
    if ($action == 'ADD') {
        if (!has_role($module_id, $group_id, $section, $role)) {

            $CI->db->where("group_id", $group_id);
            $CI->db->where("module_id", $module_id);
            $CI->db->where("section", $section);
            $row = $CI->db->get("module_group_role")->row();

            if ($row) {
                $json = json_decode($row->role, true);

                if (is_array($json) && !in_array($role, $json)) {

                    array_push($json, $role);
                    $array = array('role' => json_encode(array_values($json)));
                    return $CI->db->update("module_group_role", $array, array('group_id' => $group_id, 'module_id' => $module_id, 'section' => $section));
                }
                return true;
            } else {
                $array = array(
                    'group_id' => $group_id,
                    'module_id' => $module_id,
                    'section' => $section,
                    'role' => json_encode(array($role))
                );

                return $CI->db->insert("module_group_role", $array);
            }
        }
    } else if ($action == 'DELETE') {
        if (has_role($module_id, $group_id, $section, $role)) {
            $CI->db->where("group_id", $group_id);
            $CI->db->where("module_id", $module_id);
            $CI->db->where("section", $section);
            $row = $CI->db->get("module_group_role")->row();
            if ($row) {
                $json = json_decode($row->role, true);
                if (is_array($json) && in_array($role, $json)) {
                    if (($key = array_search($role, $json)) !== false) {
                        unset($json[$key]);
                    }

                    $array = array('role' => json_encode(array_values($json)));
                    if (count($json) == 0) {
                        return $CI->db->update("module_group_role", $array, array('group_id' => $group_id, 'module_id' => $module_id, 'section' => $section));
                    } else {
                        return $CI->db->update("module_group_role", $array, array('group_id' => $group_id, 'module_id' => $module_id, 'section' => $section));
                    }

                }
                return false;
            }
        }
    }


}


/**
 * Check if user has role in a certain link
 * @param $module_id
 * @param $link
 * @return bool
 */

function has_role($module_id, $group_id, $section, $role)
{

    $CI = &get_instance();
    $CI->db->where("group_id", $group_id);
    $CI->db->where("module_id", $module_id);
    $CI->db->where("section", $section);
    $row = $CI->db->get("module_group_role")->row();

    if ($row) {

        $json = json_decode($row->role, true);
        if (is_array($json) && in_array($role, $json)) {
            return true;
        }
        return false;

    }
    return FALSE;

}

/**
 * User has role in section
 * @param $module_id
 * @param $group_id
 * @param $section_id
 * @return bool
 */
function has_section_role($module_id, $group_id, $section)
{

    $CI = &get_instance();
    $CI->db->where("group_id", $group_id);
    $CI->db->where("module_id", $module_id);
    $CI->db->where("section", $section);
    $row = $CI->db->get("module_group_role")->row();
    if ($row) {
        $json = json_decode($row->role, true);
        if (count($json) > 0) {
            return true;
        }
        return false;

    }
    return FALSE;

}

function sims_syncronise($datatype)
{
    $CI = &get_instance();

    $send_data = array(
        'datatype' => $datatype,
        'key' => SIMS_API_KEY
    );

    $send_json_string = json_encode($send_data);

    $CI->curl->create(SIMS_API_URL);
    $CI->curl->options(
        array(
            CURLOPT_HTTPHEADER => array('Content-Type: application/xml'),
            CURLOPT_POSTFIELDS => $send_json_string,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_POST => 1
        )
    );
    $response = $CI->curl->execute();
    $response = json_decode($response);

    if (json_last_error() === JSON_ERROR_NONE) {
        return $response;
    } else {
        return false;
    }


}

function get_nta_levelname($level, $years_4 = null)
{
    if ($years_4 == '4YPR') {
        return 'UQF : 6';
    } else {
        if ($level == 4) {
            return 'NTA LEVEL : 4';
        } else if ($level == 5) {
            return 'NTA LEVEL : 5';
        } else if ($level == 6) {
            return 'NTA LEVEL  : 6';
        } else if ($level == 7) {
            return 'NTA LEVEL : 7';
        } else if ($level == 8) {
            return 'NTA LEVEL  : 8';
        } else {
            return 'NTA LEVEL : ' . $level;
        }
    }
}

function get_application_deadline()
{
    $CI = &get_instance();
    $row = $CI->common_model->get_application_deadline()->row();
    if ($row) {
        return $row->deadline;
    } else {
        return date('Y-m-d', strtotime('2020-01-01'));
    }
}

function entry_type($val = null)
{
    $array = array(
        '1' => 'Form IV',
        '1.5'=>'VETA Level III',
        '2' => 'Form VI',
        '3' => 'NTA Level 4 / Technician',
        '4' => 'Diploma',
        '7' => 'Degree',
        '8' => 'Masters'
    );
    if (!is_null($val)) {
        return $array[$val];
    }
    return $array;
}

function addition_certificate($val = null,$application_type=null)
{
    $array = array(
        100 => 'Birth Certificate',
        101 => 'Others',
        102 => ' Internship Certificate',
        103 => 'Professional Registration',
        104 => 'Curriculum  Vitae (CV)',
    );
    if($application_type < 3){
        unset($array[102]);
        unset($array[103]);
        unset($array[104]);
    }

    if (!is_null($val)) {
        if (!array_key_exists($val, $array)) {
            return $array[$val];
        }
    }

    return $array;


}

function entry_type_certificate($val = null)
{
    $array = array(
        '1' => 'O-Level Certificate',
        '1.5'=>'VETA Level III',
        '2' => 'A-Level Certificate',
        '3' => 'NTA Level 4 Certificate',
        '4' => 'Diploma Certificate / Transcript',
        '7' => 'Degree Certificate / Transcript',
        '8' => 'Masters'
    );

    if (!is_null($val)) {
        if($val > 101) {
            $array = $array + addition_certificate(null,3);
        }else{
            $array = $array + addition_certificate();
        }


        /*if(!array_key_exists($val,$array)){
           switch ($val){
               case 100:
                   return '';
                   break;
               case 101:
                   return 'Others';
                   break;
               case 102:
                   return 'Birth Certificate';
                   break;
           }
        }else {*/
        return $array[$val];
        // }
    }
    return $array;
}

function entry_type_human($val = null)
{
    $array = array(
        '1' => 'O-Level',
        '1.5'=>'VETA Level III',
        '2' => 'A-Level',
        '3' => 'NTA Level 4',
        '4' => 'Diploma',
        '7' => 'Degree',
        '8' => 'Masters'
    );
    if (!is_null($val)) {
        if (!array_key_exists($val, $array)) {
            return '';
        } else {
            return $array[$val];
        }
    }
    return $array;
}

function certificate_by_entry_type($type)
{
    $return = entry_type_certificate();
    switch ($type) {
        case 1:
            unset($return[2]);
            unset($return['1.5']);
            unset($return[3]);
            unset($return[4]);
            unset($return[7]);
            unset($return[8]);
            break;
        case 1.5:
            unset($return[2]);
            unset($return[1]);
            unset($return[3]);
            unset($return[4]);
            unset($return[7]);
            unset($return[8]);
            break;
        case 2:
            unset($return[3]);
            unset($return['1.5']);
            unset($return[4]);
            unset($return[7]);
            unset($return[8]);
            break;
        case 3:
            unset($return[2]);
            unset($return['1.5']);
            unset($return[4]);
            unset($return[7]);
            unset($return[8]);
            break;
        case 4:
            unset($return[3]);
            unset($return[7]);
            unset($return[8]);
            break;
        case 7:
            unset($return[8]);
            break;
    }
    return $return;
}

function GetNumberOfDaysBetweenDates($from,$to='')
{
    $from=date('Y-m-d',strtotime($from));
    if($to=='')
    {
        $to=date('Y-m-d');

    }
    $earlier = new DateTime($from);
    $later = new DateTime($to);

    $diff = $later->diff($earlier)->format("%a");
    return $diff;
}

function remoteFileExists($url) {
    $curl = curl_init($url);

    //don't fetch the actual page, you only want to check the connection is ok
    curl_setopt($curl, CURLOPT_NOBODY, true);

    //do request
    $result = curl_exec($curl);

    $ret = false;

    //if request did not fail
    if ($result !== false) {
        //if request was ok, check response code
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ($statusCode == 200) {
            $ret = true;
        }
    }

    curl_close($curl);

    return $ret;
}


function sendDataOverPost($url,$postdata)
{

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output=curl_exec($ch);
    curl_close($ch);
    return $output;

}
function check_deadline()
{
    $CI = &get_instance();
    $row = $CI->common_model->get_application_deadline()->row();
    if ($row) {
        return $row;
    } else {
        return date('Y-m-d', strtotime('2015-01-01'));
    }
}

function application_type($val = null)
{
    $array = array(
        '1' => 'Certificate/Diploma',
        '2' => 'Bachelor',
        '3' => 'Post Graduate'
    );
    if(check_deadline()->post_graduate==0)
    {
        unset($array['3']);
    }
    if(check_deadline()->degree==0)
    {
        unset($array['2']);
    }
    if(check_deadline()->diploma==0)
    {
        unset($array['1']);
    }
    if (!is_null($val)) {
        return $array[$val];
    }
    return $array;
}

function attachment_required($inputname, $label = null)
{
    $CI = &get_instance();

    if (isset($_FILES[$inputname]['name']) && !empty($_FILES[$inputname]['name'])) {
        return true;
    }
    $CI->form_validation->set_rules($inputname, (!is_null($label) ? $label : $inputname), 'required');
    return false;

}

function grade_list()
{
    return array('A', 'B+', 'B', 'C', 'D', 'E', 'F', 'S');
}

function is_grade_greater_equal($grade1, $grade2)
{
    $grades = grade_list();
    $index_grade1 = array_search($grade1, $grades);
    $index_grade2 = array_search($grade2, $grades);
    if ($index_grade1 <= $index_grade2) {
        return true;
    }

    return false;
}

function grade_point($grade)
{
    array('A' => 10, 'B+' => 9, 'B' => 8, 'C' => 7, 'D' => 6, 'E' => 5, 'S' => 4, 'F' => 3);
}

function get_file_mimetype($filename)
{
    $CI = &get_instance();
    $CI->load->library("FileMimeType");
    return $CI->filemimetype->get_mime_type($filename);
}

function application_status($id = null)
{
    $array = array(
        '0' => 'Incomplete',
        '1' => 'Submitted',
        '2' => 'Rejected',
        '3' => 'Shortlisted',
        '4' => 'Selected'

    );

    if (!is_null($id)) {
        if (!array_key_exists($id, $array)) {
            return '';
        } else {
            return $array[$id];
        }
    }
    return $array;
}

function programme_duration($application_type, $entry)
{
    if ($application_type == 2) {
        return 3;
    } else {
        $d = 3;
        switch ($entry) {
            CASE 1 :
                $d = 3;
                break;
            case 2:
                $d = 2;
                break;
            case 3:
                $d = 2;
                break;
            default:
                $d = 3;
                break;
        }

        return $d;
    }
}

function experience($id = null)
{
    $array = array(
        '1' => 'Internship',
        '2' => 'Professional Training',
        '3' => 'Work Experience'
    );

    if (!is_null($id)) {
        return $array[$id];
    }
    return $array;
}

function yes_no($id=null){
    $array=array(
        '1'=>'Yes',
        '0'=>'No'
    );
    if(!is_null($id)){
        return $array[$id];
    }

    return $array;
}

function CSEE_type($id=null){
    $array=array(
        '1'=>'Tanzania (From NECTA)',
        '0'=>'Outside Tanzania (Foreign)'
    );
    if(!is_null($id)){
        return $array[$id];
    }

    return $array;
}

function get_country($id,$column='Country'){
    $CI = &get_instance();
    $row = get_value('nationality',$id,null);
    if($row){
        return $row->{$column};
    }
    return '';
}

function recommendation_rate($id=null){
    $array= array(
        '5'=>'Excellent',
        '4'=>'Very Good',
        '3'=>'Good',
        '2'=>'Average',
        '1'=>'Low',
    );

    if(!is_null($id)){
        return $array[$id];
    }

    return $array;
}

function convert_number_to_words($number) {

    $hyphen      = '-';
    $conjunction = ' and ';
    $separator   = ', ';
    $negative    = 'negative ';
    $decimal     = ' point ';
    $dictionary  = array(
        0                   => 'zero',
        1                   => 'one',
        2                   => 'two',
        3                   => 'three',
        4                   => 'four',
        5                   => 'five',
        6                   => 'six',
        7                   => 'seven',
        8                   => 'eight',
        9                   => 'nine',
        10                  => 'ten',
        11                  => 'eleven',
        12                  => 'twelve',
        13                  => 'thirteen',
        14                  => 'fourteen',
        15                  => 'fifteen',
        16                  => 'sixteen',
        17                  => 'seventeen',
        18                  => 'eighteen',
        19                  => 'nineteen',
        20                  => 'twenty',
        30                  => 'thirty',
        40                  => 'fourty',
        50                  => 'fifty',
        60                  => 'sixty',
        70                  => 'seventy',
        80                  => 'eighty',
        90                  => 'ninety',
        100                 => 'hundred',
        1000                => 'thousand',
        1000000             => 'million',
        1000000000          => 'billion',
        1000000000000       => 'trillion',
        1000000000000000    => 'quadrillion',
        1000000000000000000 => 'quintillion'
    );

    if (!is_numeric($number)) {
        return false;
    }

    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
        // overflow
        trigger_error(
            'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
            E_USER_WARNING
        );
        return false;
    }

    if ($number < 0) {
        return $negative . convert_number_to_words(abs($number));
    }

    $string = $fraction = null;

    if (strpos($number, '.') !== false) {
        list($number, $fraction) = explode('.', $number);
    }

    switch (true) {
        case $number < 21:
            $string = $dictionary[$number];
            break;
        case $number < 100:
            $tens   = ((int) ($number / 10)) * 10;
            $units  = $number % 10;
            $string = $dictionary[$tens];
            if ($units) {
                $string .= $hyphen . $dictionary[$units];
            }
            break;
        case $number < 1000:
            $hundreds  = $number / 100;
            $remainder = $number % 100;
            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
            if ($remainder) {
                $string .= $conjunction . convert_number_to_words($remainder);
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int) ($number / $baseUnit);
            $remainder = $number % $baseUnit;
            $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
            if ($remainder) {
                $string .= $remainder < 100 ? $conjunction : $separator;
                $string .= convert_number_to_words($remainder);
            }
            break;
    }

    if (null !== $fraction && is_numeric($fraction)) {
        $string .= $decimal;
        $words = array();
        foreach (str_split((string) $fraction) as $number) {
            $words[] = $dictionary[$number];
        }
        $string .= implode(' ', $words);
    }

    return $string;
}


function recommendation_overall($id=null){
    $array= array(
        '4'=>'Highly Recommended',
        '3'=>'Recommended',
        '2'=>'Recommended with some reservations',
        '1'=>'Not Recommended',
    );

    if(!is_null($id)){
        return $array[$id];
    }

    return $array;
}

function  GetFeeTypeDetails($code=null){
    $CI = &get_instance();
    if(!is_null($code))
    {
        $CI->db->where(array('code'=>$code,'hidden'=>0));
        return $CI->db->get('fee_type')->row();
    }else
    {
        $CI->db->where(array('hidden'=>0));
        return $CI->db->get('fee_type')->result();
    }

}

