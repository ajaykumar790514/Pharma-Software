<?php
/*
 * Custom Helpers
 *
 */

//check admin
if (!function_exists('is_admin')) {
    function is_admin()
    {
        // Get a reference to the controller object
        $ci = &get_instance();
        return $ci->admin_model->is_admin();
    }
}
if (!function_exists('date_format_func')) {
    function date_format_func($date)
    {
            if($date == NULL)
        	{
        		return "";
        	}
            else if($date == '0000-00-00')
            {
                return "";
            }
        	else
        	{
        		return date('d-m-Y',strtotime($date));
		    }
    }
}
if (!function_exists('number_to_word')) {
    function number_to_word($number){
        $no = (int)floor($number);
        $point = (int)round(($number - $no) * 100);
        $hundred = null;
        $digits_1 = strlen($no);
        $i = 0;
        $str = array();
        $words = array('0' => '', '1' => 'one', '2' => 'two',
         '3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
         '7' => 'seven', '8' => 'eight', '9' => 'nine',
         '10' => 'ten', '11' => 'eleven', '12' => 'twelve',
         '13' => 'thirteen', '14' => 'fourteen',
         '15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
         '18' => 'eighteen', '19' =>'nineteen', '20' => 'twenty',
         '30' => 'thirty', '40' => 'forty', '50' => 'fifty',
         '60' => 'sixty', '70' => 'seventy',
         '80' => 'eighty', '90' => 'ninety');
        $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
        while ($i < $digits_1) {
          $divider = ($i == 2) ? 10 : 100;
          $number = floor($no % $divider);
          $no = floor($no / $divider);
          $i += ($divider == 10) ? 1 : 2;
     
     
          if ($number) {
             $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
             $hundred = ($counter == 1 && $str[0]) ? null : null;
             $str [] = ($number < 21) ? $words[$number] .
                 " " . $digits[$counter] . $plural . " " . $hundred
                 :
                 $words[floor($number / 10) * 10]
                 . " " . $words[$number % 10] . " "
                 . $digits[$counter] . $plural . " " . $hundred;
          } else $str[] = null;
       }
       $str = array_reverse($str);
       $result = implode('', $str);
     
     
       if ($point > 20) {
         $points = ($point) ?
           "" . $words[floor($point / 10) * 10] . " " . 
               $words[$point = $point % 10] : ''; 
       } else {
           $points = $words[$point];
       }
       if($points != ''){        
           echo ucwords($result . "Rupees and " . $points . " Paise Only");
       } else {
     
           echo ucwords($result . "Rupees Only");
       }
     
     }
}

if (!function_exists('logs')) {
    function logs($user,$item,$action,$alias) {
        $table='logs';
        $CI =& get_instance();
        $CI->load->database();
        $route_name = get_route_name();
        $logdata = array(
          'user_id'=>$user,
          'item_id'=>$item,
          'action'=>$action,
          'url'=>$route_name,
          'alias'=>$alias,
        );
        return $CI->db->insert($table, $logdata);
    }
}
 
if (!function_exists('get_route_name')) {
    function get_route_name() {
        $CI =& get_instance();
        $uri_string = $CI->uri->uri_string();
        return $uri_string;
    }
}

if (! function_exists('_prx')) {
    function _prx($array)
    {
        return "<pre>".print_r($array,true)."</pre>";
    }
}

/**
 * get full name bu fname, mname, and lname
 */
if(!function_exists('get_full_name')) {
    function get_full_name($fname, $mname = null, $lname = null) {
        $full_name = $fname;
        if(!empty($mname)) {
            $full_name .= ' '.$mname;
        }
        if(!empty($lname)) {
            $full_name .= ' '.$lname;
        }
        return $full_name;
    }
}

/**
 * get customer button class by status
 */
if(!function_exists('get_customer_button_class')) {
    function get_customer_button_class($status) {
        switch ($status) {
            case "PENDING":
                return 'btn-info';
            case "REJECTED":
                return 'btn-danger';
            case "VERIFIED":
                return 'btn-success';
            default:
                return 'btn-default';
        }
    }
}
function checkLogin(){
    $loggedin = false;
    if (get_cookie('63a490ed05b42') && get_cookie('63a490ed05b43') && get_cookie('63a490ed05b44')) {
        $user_id = value_encryption(get_cookie('63a490ed05b42'),'decrypt');
        $user_nm = value_encryption(get_cookie('63a490ed05b43'),'decrypt');
        $type    = value_encryption(get_cookie('63a490ed05b44'),'decrypt');
        if (is_numeric($user_id) && !is_numeric($user_nm)) {
            $check['id'] 	   = $user_id;
            $check['username'] = $user_nm;
            if ($type=='admin') {
                // $user = $this->admin_model->getRow('admin',$check);
                $CI =& get_instance();
               $user = $CI->db->get_where('admin',$check)->row();
            }
            else{
                $user = false;
            }

            if ($user) {
                if ($user->status==1) {
                    $user->type = $type;
                    $loggedin = true;
                }
            }
        }
    }
    if ($loggedin) {
        return $user;
    }
    else{
        delete_cookie('63a490ed05b42');	
        delete_cookie('63a490ed05b43');	
        delete_cookie('63a490ed05b44');	
        delete_cookie('63a490ed05b45');	
        redirect(base_url().'');
    }
}

 function checkCookie(){
    $loggedin = false;
    if (get_cookie('63a490ed05b42') && get_cookie('63a490ed05b43') && get_cookie('63a490ed05b44')) {
        $user_id = value_encryption(get_cookie('63a490ed05b42'),'decrypt');
        $user_nm = value_encryption(get_cookie('63a490ed05b43'),'decrypt');
        if (is_numeric($user_id) && !is_numeric($user_nm)) {
            $loggedin = true;
        }
    }

    if ($loggedin) {
        return true;
    }
    else{
        delete_cookie('63a490ed05b42');	
        delete_cookie('63a490ed05b43');	
        delete_cookie('63a490ed05b44');	
        delete_cookie('63a490ed05b45');	
        redirect(base_url().'');
    }

    function checkShopLogin(){
        $loggedin = false;
        if (get_cookie('63a490ed05b42') && get_cookie('63a490ed05b43') && get_cookie('63a490ed05b44') && get_cookie('63a490ed05b45')) {
            $user_id = value_encryption(get_cookie('63a490ed05b42'),'decrypt');
            $user_nm = value_encryption(get_cookie('63a490ed05b43'),'decrypt');
            $type    = value_encryption(get_cookie('63a490ed05b44'),'decrypt');
            if (is_numeric($user_id) && !empty($user_nm)) {
                $check['id'] 	   = $user_id;
                $check['contact'] = $user_nm;
                if ($type=='shop') {
                    // $user = $this->admin_model->getRow('admin',$check);
                    $CI =& get_instance();
                   $user = $CI->db->get_where('shops',$check)->row();
                }
                else{
                    $user = false;
                }

                if ($user) {
                    if ($user->isActive==1) {
                        $user->type = $type;
                        $loggedin = true;
                    }
                }
            }
        }
        if ($loggedin) {
            return $user;
        }
        else{
            delete_cookie('63a490ed05b42');	
            delete_cookie('63a490ed05b43');	
            delete_cookie('63a490ed05b44');	
            delete_cookie('63a490ed05b45');	
            redirect(base_url().'');
        }
    }
 }