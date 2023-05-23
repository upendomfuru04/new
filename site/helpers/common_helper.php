
<?php 
	if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	date_default_timezone_set("Africa/Dar_es_Salaam");

/*!
 * Common functions.php
 * php to php
 * http://tafuget.com
 * Version: 1.0.1
 *
 * Copyright 2017 Software Engineers at tafuget
 * At Mwanza, Tanzania, East Africa
 * Standard Function(API)
 * By Jeremiah Samwel, Software Engineer
 */

	/*$ci =& get_instance();
    $ci->load->database();*/
	
    function errMsg(){
        return '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true"> &times;</button>';
    }
    
    function sucCMsg(){
        return '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true"> &times;</button>';
    }

    function ErrorMsg($msg){
		return '<div class="alert alert-danger alert-dismissable"> 
			   <button type="button" class="close" data-dismiss="alert"  
			      aria-hidden="true"> 
			      &times; 
			   </button>'. $msg. '</div>';
	}

	function SuccessMsg($msg){
		return '<div class="alert alert-success alert-dismissable"> 
			   <button type="button" class="close" data-dismiss="alert"  
			      aria-hidden="true"> 
			      &times; 
			   </button>'. $msg. '</div>';
	}

	function dataSafe($val){
		$ci =& get_instance();
        $safeVal=$ci->security->xss_clean($val);
		// return trim(html_escape($safeVal));
		return trim($safeVal);
	}

	function sqlSafe($val){
		$ci =& get_instance();
        // $ci->load->database();
        $safeVal=$ci->security->xss_clean($val);
		// $safeVal=html_escape($safeVal);
		$safeVal=$ci->db->escape_str($safeVal);
		return trim($safeVal);
	}

	function imageSafe($file){
		$ci =& get_instance();
		return $ci->security->xss_clean($file, TRUE);
	}

	function sendEmail($to, $name, $email, $subject, $message){
		// $email="helpcenter@gmail.com";
		// $name="Site Help Center";
		/*$headers = "Reply-To: $name <$email>\r\n"; 
      	$headers .= "Return-Path: $name <$email>\r\n";
      	$headers .= "From: $name <$email>\r\n"; 

      	if(mail($to, $subject, $message, $headers)){
      		return "ok";
      	}*/

      	// $email="helpcenter@gmail.com";
		// $name="Site Help Center";
        $headers = "";
        $headers = "MIME-Version: 1.0 \r\n";
        $headers .= "Content-type: text/html;charset=utf-8 \r\n";
		$headers .= "Reply-To: $name <$email>\r\n"; 
      	$headers .= "Return-Path: $name <$email>\r\n";
      	$headers .= "From: $name <$email>\r\n";
        // $headers = "From: $name $email\r\n" . "X-Mailer: php";
        $headers .= "X-Priority: 3 \r\n";
        // $headers .= "X-Mailer: php";
        $headers .= "X-Mailer: PHP/".phpversion();

      	if(mail($to, $subject, $message, $headers)){
      		return "ok";
      	}
	}

	function receiveEmail($to, $name, $email,$subject, $message){
		$headers = "Reply-To: $name <$email>\r\n"; 
      	$headers .= "Return-Path: $name <$email>\r\n";
      	$headers .= "From: $name <$email>\r\n"; 

      	if(mail($to, $subject, $message, $headers)){
      		return "ok";
      	}
	}

	function timeDiffInSec($time1, $time2){
		$secInterval=round(($time2 - $time1));
		return $secInterval;
	}

	function timeDiffInMin($time1, $time2){
		$minInterval=round(($time2 - $time1)/60);
		return $minInterval;
	}

	function timeDiffInHours($time1, $time2){
		$hrsInterval=round(($time2 - $time1)/(60*60));
		return $hrsInterval;
	}

	function timeDiffInDays($time1, $time2){
		$daysInterval=intval(($time2 - $time1)/(60*60*24));
		return $daysInterval;
	}

	function timeDiffInWeeks($time1, $time2){
		$interval=intval(($time2 - $time1)/(7*60*60*24));
		return $interval;
	}

	function timeDiffInMonths($time1, $time2){
		$interval=intval(($time2 - $time1)/(30*60*60*24));
		return $interval;
	}

	function addDaysToDate($existingDate, $days){
		// $existingDate = "2013-09-11"; //existing date
		$newDate=date('Y-m-d', strtotime($existingDate .'+'.$days.' days'));
		return $newDate;
	}

	function addWeeksToDate($existingDate, $weeks){
		$newDate=date('Y-m-d', strtotime($existingDate .'+'.$weeks.' weeks'));
		return $newDate;
	}

	function addMonthsToDate($existingDate, $months){
		$newDate=date('Y-m-d', strtotime($existingDate .'+'.$months.' months'));
		return $newDate;
	}

	function addYearsToDate($existingDate, $years){
		$newDate=date('Y-m-d', strtotime($existingDate .'+'.$years.' years'));
		return $newDate;
	}

	function formatDate($format, $date){
		$result="";
		if($date!=""){
			$result=date($format, strtotime($date));
		}
		return $result;
	}

	function timeInStatus($time){
		$current=time();
		$yrDiff=date("Y", $current) - date("Y", $time);
		if($yrDiff > 0){
			return (date("d", $time).' '.getShortMonthName(date("m", $time)).', '.date("Y", $time));
		}else{
			$daysDiff=timeDiffInDays($time, $current);
			if($daysDiff > 1){
				return (date("d", $time).' '.getShortMonthName(date("m", $time)));
			}else if($daysDiff==1){
				return "Yesterday";
			}else if($daysDiff==0){
				$minDiff=timeDiffInMin($time, $current);
				$hrDiff=timeDiffInHours($time, $current);
				if($minDiff<6){
					return "Just now";
				}elseif($minDiff>5 && $minDiff<60){
					return $minDiff.' mins ago';
				}elseif($hrDiff<=2){
					if($hrDiff==1){
						return $hrDiff.' hour ago';
					}else{
						return $hrDiff.' hours ago';
					}
				}else{
					return date("h:i a", $time);
				}

			}else{
				return (date("d", $time).' '.getShortMonthName(date("m", $time)));
			}
		}
	}

	function timeInStatusWithTime($time){
		$current=time();
		$yrDiff=date("Y", $current) - date("Y", $time);
		if($yrDiff > 0){
			return date("d-m-Y h:i a", $time);
		}else{
			$daysDiff=timeDiffInDays($time, $current);
			if($daysDiff > 1){
				return date("d M h:i a", $time);
			}else if($daysDiff==1){
				return "Yesterday";
			}else if($daysDiff==0){
				$minDiff=timeDiffInMin($time, $current);
				$hrDiff=timeDiffInHours($time, $current);
				if($minDiff<6){
					return "Just now";
				}elseif($minDiff>5 && $minDiff<60){
					return $minDiff.' mins ago';
				}elseif($hrDiff<=2){
					if($hrDiff==1){
						return $hrDiff.' hour ago';
					}else{
						return $hrDiff.' hours ago';
					}
				}else{
					return date("h:i a", $time);
				}

			}else{
				return date("d M h:i a", $time);
			}
		}
	}

	function validateDate($date, $format = 'Y-m-d'){
	    $d = DateTime::createFromFormat($format, $date);
	    // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
	    return $d && $d->format($format) === $date;
	}

	function validateDateTimeInFormat($dateStr, $format){
	    date_default_timezone_set('UTC');
	    $date = DateTime::createFromFormat($format, $dateStr);
	    return $date && ($date->format($format) === $dateStr);
	}

	function convertDate($date){
		if($date!=""){
			/*Valid Date format: mm/dd/yyyy, dd-mm-yyyy, yyyy-mm-dd
			  User format: dd.mm.yyyy, dd-mm-yyyy, dd/mm/yyyy
			  Allowed format from the user: d-m-Y, d/m/Y, d.m.Y or Y-m-d*/
			if(strstr($date, "/")){
				/*Change dd/mm/yyyy to mm/dd/yyyy which is the right computer format.*/
				$dates = explode("/", $date);
				if(sizeof($dates)>2 && sizeof($dates)<4){
					$date = $dates[1]."/".$dates[0]."/".$dates[2];
				}
			}
			if(validateDateTimeInFormat($date, "d-m-Y") || validateDateTimeInFormat($date, "m/d/Y") || validateDateTimeInFormat($date, "d.m.Y") || validateDateTimeInFormat($date, "Y-m-d") || validateDateTimeInFormat($date, "j-n-Y") || validateDateTimeInFormat($date, "n/j/Y") || validateDateTimeInFormat($date, "j.n.Y") || validateDateTimeInFormat($date, "Y-n-j")){
				return date("Y-m-d", strtotime($date));
			}else{
				die(ErrorMsg("Please! enter the right date format eg. dd-mm-yyyy, dd/mm/yyyy, dd.mm.yyyy or yyyy-mm-dd"));
			}
		}
	}

	function isUrlValid($url){
		if (filter_var($url, FILTER_VALIDATE_URL)) {
		    return true;
		} else {
		    return false;
		}
	}

	/*Random only numbers*/
	function getRandomNumber($limit){
		$rands=rand(1, 9);
		if($limit == 0){
			return 0;
		}else if($limit == 1){
			return $limit;
		}else if($limit == 2){
			$rands.=rand(1, 9);
			return $rands;
		}else if($limit == 3){
			$rands.=rand(0, 9).rand(1, 9);
			return $rands;
		}else if($limit == 4){
			$rands.=rand(0, 9).rand(0, 9).rand(1, 9);
			return $rands;
		}else if($limit > 4){
			for ($i=0; $i < ($limit - 2); $i++) { 
				$rands.=rand(0, 9);
			}
			$rands.=rand(1, 9);
			return $rands;
		}
	}
	
	/*Random number + alphabet*/
	function getRandomMixedCode($limit){
		$rands=chr(rand(65,90));
		if($limit == 0){
			return 0;
		}else if($limit == 1){
			return $limit;
		}else if($limit == 2){
			$rands.=rand(1, 9);
			return $rands;
		}else if($limit == 3){
			$rands.=rand(0, 9).chr(rand(97,122));
			return $rands;
		}else if($limit == 4){
			$rands.=rand(0, 9).chr(rand(97,122)).rand(1, 9);
			return $rands;
		}else if($limit == 5){
			$rands.=rand(0, 9).chr(rand(65,90)).rand(1, 9).chr(rand(97,122));
			return $rands;
		}else if($limit > 5){
			for ($i=0; $i < ($limit - 3); $i++) { 
				$rands.=rand(0, 9);
			}
			$rands.=rand(1, 9).chr(rand(97,122));
			return $rands;
		}
	}

	/*Random Upper alphabet*/
	function getRandomAlphabetUpper($limit){
		$rands=chr(rand(97,122));
		if($limit == 0){
			return 0;
		}else if($limit == 1){
			return $limit;
		}else if($limit == 2){
			$rands.=chr(rand(97,122));
			return $rands;
		}else if($limit == 3){
			$rands.=chr(rand(97,122)).chr(rand(97,122));
			return $rands;
		}else if($limit == 4){
			$rands.=chr(rand(97,122)).chr(rand(97,122)).chr(rand(97,122));
			return $rands;
		}else if($limit > 4){
			for ($i=0; $i < ($limit - 2); $i++) { 
				$rands.=chr(rand(97,122));
			}
			$rands.=chr(rand(97,122));
			return $rands;
		}
	}

	/*Random lower alphabet*/
	function getRandomAlphabetLower($limit){
		$rands=chr(rand(65,90));
		if($limit == 0){
			return 0;
		}else if($limit == 1){
			return $limit;
		}else if($limit == 2){
			$rands.=chr(rand(65,90));
			return $rands;
		}else if($limit == 3){
			$rands.=chr(rand(65,90)).chr(rand(65,90));
			return $rands;
		}else if($limit == 4){
			$rands.=chr(rand(65,90)).chr(rand(65,90)).chr(rand(65,90));
			return $rands;
		}else if($limit > 4){
			for ($i=0; $i < ($limit - 2); $i++) { 
				$rands.=chr(rand(65,90));
			}
			$rands.=chr(rand(65,90));
			return $rands;
		}
	}

	function generateToken(){
        $randCode=getRandomMixedCode(7);
        $token=md5(md5(sha1(md5($randCode))));
        $ci =& get_instance();
        $ci->load->database();
        $ci->db->select('id');
		$ci->db->where('token',$token);
		$query = $ci->db->get('tbl_users');
        while($query->num_rows() > 0){
            $randCode=getRandomMixedCode(7);
            $token=md5(md5(sha1(md5($randCode))));
        }
        return $token;
    }

    function do_upload($path, $userfile, $fileName, $max_size='100', $max_width='1024', $max_height='768', $fileType='image') { 
    	$ci =& get_instance();
    	$ci->session->unset_userdata('uploaded_file');
    	$userfile=$ci->security->sanitize_filename($userfile, TRUE);
		$config['upload_path']   = $path;
		if($fileType=='image'){
			$config['allowed_types'] = 'gif|jpg|png|jpeg|PNG';
		}else{
			$config['allowed_types'] = '*';
		}
		
		$config['max_size']      = $max_size;//100; 
		$config['max_width']     = $max_width;//1024; 
		$config['max_height']    = $max_height; //768;
		$fileName=strtolower(str_replace(" ", '_', $fileName));
		$fileName=str_replace(".", '', $fileName);
		$fName=$fileName.'_'.date("dmY_h_i");
		while (file_exists($path.'/'.$fileName)) {
			$fName=$fileName.'_'.date("dmY_h_i_s");
		}
		$config['file_name'] = $fName; 
		$ci->load->library('upload', $config);		
		$ci->upload->initialize($config);
			
	    if(!$ci->upload->do_upload($userfile)) {
	        return $ci->upload->display_errors(); 
	    }else { 
	    	$fls = $ci->upload->data();
	    	// if(!empty($fName))
	    	$_SESSION['uploaded_file']=$fls['file_name'];
	        return 'ok';
	    } 
	}

	function downloadFile($file_path, $file_name){
		if(!file_exists($file_path.''.$file_name))
			die($file_path.''.$file_name." == File not exist");
		header('Content-Description: File Transfer');
	    header('Content-Type: application/force-download');
	    header("Content-Disposition: attachment; filename=\"" . basename($file_path.''.$file_name) . "\";");
	    header('Content-Transfer-Encoding: binary');
	    header('Expires: 0');
	    header('Cache-Control: must-revalidate');
	    header('Pragma: public');
	    header('Content-Length: ' . filesize($file_path.''.$file_name));
	    ob_clean();
	    flush();
	    readfile($file_path."".$file_name); //showing the path to the server where the file is to be download
	    exit;
	}

	function redirect_back($site_params=""){
		if(isset($_SESSION['redirect_back']) && $_SESSION['redirect_back']!=NULL){
			redirect($_SESSION['redirect_back']);
		}else{
			header('Location: '.base_url().''.$site_params);
		}
        exit;
    }

    function remove_emoji($string) {

	    // Match Emoticons
	    $regex_emoticons = '/[\x{1F600}-\x{1F64F}]/u';
	    $clear_string = preg_replace($regex_emoticons, '', $string);

	    // Match Miscellaneous Symbols and Pictographs
	    $regex_symbols = '/[\x{1F300}-\x{1F5FF}]/u';
	    $clear_string = preg_replace($regex_symbols, '', $clear_string);

	    // Match Transport And Map Symbols
	    $regex_transport = '/[\x{1F680}-\x{1F6FF}]/u';
	    $clear_string = preg_replace($regex_transport, '', $clear_string);

	    // Match Miscellaneous Symbols
	    $regex_misc = '/[\x{2600}-\x{26FF}]/u';
	    $clear_string = preg_replace($regex_misc, '', $clear_string);

	    // Match Dingbats
	    $regex_dingbats = '/[\x{2700}-\x{27BF}]/u';
	    $clear_string = preg_replace($regex_dingbats, '', $clear_string);

	    return $clear_string;
	}

	function decodeEmoticons($src) {
	    $replaced = preg_replace("/\\\\u([0-9A-F]{1,4})/i", "&#x$1;", $src);
	    $result = mb_convert_encoding($replaced, "UTF-16", "HTML-ENTITIES");
	    $result = mb_convert_encoding($result, 'utf-8', 'utf-16');
	    return $result;
	}

	function format_phone_number($phone){
		if(strpos($phone, ',') !== false){
            $phn=explode(",", $phone);
            $phone=$phn[0];
        }
		$phone_number=trim($phone);
		$phone_number=ltrim($phone_number, '0');
		$phone_number=str_replace(" ", "", $phone_number);
		$phone_number=str_replace("+", "", $phone_number);
		/*if(substr($phone_number, 0, 3)!='255'){
			$phone_number="255".$phone_number;
		}*/
		return $phone_number;
	}

	function getMobileMoneyName($phone_number){
		$name="";		
		$phone=str_replace("+", "", $phone_number);
		$phone=ltrim($phone, '255');
		$block=substr($phone, 0, 3);
		if($block=='074' || $block=='075' || $block=='076'){
			$name="mpesa";
		}elseif($block=='078' || $block=='068'){
			$name="airtelmoney";
		}elseif($block=='071' || $block=='065' || $block=='067'){
			$name="tigopesa";
		}elseif($block=='062'){
			$name="halopesa";
		}elseif($block=='077'){
			$name="ezypesa";
		}elseif($block=='073'){
			$name="ttclpesa";
		}else{
			$name="unknown";
		} 
		// die("Name: ".$name);
		return $name;
	}

?>