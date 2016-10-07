<?php 

/* Year Copy New */
function yearcopy($y_copy = ""){
	if(empty($y_copy)) $y_copy = trans('frontend.site.copy_year');
	$this_year = date('Y');
	if ($y_copy == $this_year) {  $y_copy = "$y_copy"; } else {  $y_copy = "$y_copy - $this_year";  }
	return $y_copy;
}

/* If Ajax Request */
function ajaxRequest(){
	$ajax_request = isset($_SERVER['HTTP_X_REQUESTED_WITH']) ? $_SERVER['HTTP_X_REQUESTED_WITH'] : "";
	if(!empty($ajax_request) && strtolower($ajax_request) == 'xmlhttprequest'){
		return true;
	}
	return false;
}

function character_limiter($str, $n = 500, $end_char = '&#8230;'){
	if (strlen($str) < $n)
	{
		return $str;
	}

	$str = preg_replace("/\s+/", ' ', str_replace(array("\r\n", "\r", "\n"), ' ', $str));

	if (strlen($str) <= $n)
	{
		return $str;
	}

	$out = "";
	foreach (explode(' ', trim($str)) as $val)
	{
		$out .= $val.' ';

		if (strlen($out) >= $n)
		{
			$out = trim($out);
			return (strlen($out) == strlen($str)) ? $out : $out.$end_char;
		}
	}
}

function gen_unique_code($sub1, $sub2){
	$randCode = md5(uniqid(rand(), true));
	$code = substr($randCode, $sub1, $sub2);
	
	return $code;
}

function get_time( $ptime ){
    $etime = $ptime;

    if( $etime < 1 )
    {
        return 'less than 1 second ago';
    }

    $lang = Lang::get('backend.time');

    $a = array( 12 * 30 * 24 * 60 * 60  =>  $lang['year'],
                30 * 24 * 60 * 60       =>  $lang['month'],
                24 * 60 * 60            =>  $lang['day'],
                60 * 60             =>  $lang['hour'],
                60                  =>  $lang['minute'],
                1                   =>  $lang['second']
    );

    foreach( $a as $secs => $str )
    {
        $d = $etime / $secs;

        if( $d >= 1 )
        {
            $r = round( $d );
            return $r . ' ' . $str . ( $r > 1 ? 's' : '' );
        }
    }
}

function dates($date_format = 'l, d F Y | H:i', $timestamp = '', $suffix = 'WIB', $locale = "") {
    switch ($date_format) {
        case 'date': return dates("d F Y", $timestamp, '');break;
        case 'datetime': return dates("d M Y, H:i", $timestamp, $suffix);break;
        case 'time': return dates("H:i", $timestamp, $suffix);break;
    }

   if (trim ($timestamp) == '')
   {
           $timestamp = time ();
   }
   elseif (!ctype_digit ($timestamp))
   {
       $timestamp = strtotime($timestamp);
   }
   # remove S (st,nd,rd,th) there are no such things in indonesia :stuck_out_tongue:
   $date_format = preg_replace ("/S/", "", $date_format);
   $pattern = array (
       '/Mon[^day]/','/Tue[^sday]/','/Wed[^nesday]/','/Thu[^rsday]/',
       '/Fri[^day]/','/Sat[^urday]/','/Sun[^day]/','/Monday/','/Tuesday/',
       '/Wednesday/','/Thursday/','/Friday/','/Saturday/','/Sunday/',
       '/Jan[^uary]/','/Feb[^ruary]/','/Mar[^ch]/','/Apr[^il]/','/May/',
       '/Jun[^e]/','/Jul[^y]/','/Aug[^ust]/','/Sep[^tember]/','/Oct[^ober]/',
       '/Nov[^ember]/','/Dec[^ember]/','/January/','/February/','/March/',
       '/April/','/June/','/July/','/August/','/September/','/October/',
       '/November/','/December/',
   );
   $replace = array ( 'Sen','Sel','Rab','Kam','Jum','Sab','Min',
       'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu',
       'Jan ','Feb ','Mar ','Apr ','Mei ','Jun ','Jul ','Ags ','Sep ','Okt ','Nov ','Des ',
       'Januari','Februari','Maret','April','Juni','Juli','Agustus','Sepember',
       'Oktober','November','Desember',
   );
   $date = date ($date_format, $timestamp);
   if(!empty($locale)){
       $date = $locale == "in" ? preg_replace($pattern, $replace, $date) : $date;
    }else{
        if(Lang::current() == "in") $date = preg_replace($pattern, $replace, $date);
    }
   $date = "{$date} {$suffix}";
   $date = trim($date);
   return $date;
}

function currency($num, $format = "Rp. "){
	$currency = $format.number_format($num,0,',-','.');
	return $currency;
}

/* Backup Image if no exist file */
function image($assets, $image){
	$image_link = assets($assets)."/".$image;
	if(file_exists(assets($assets, true)."/".$image)){
		return $image_link;
	}else{
		$default = scandir(assets($assets, true)."/default/");
		$default_image = assets($assets)."/default/".$default[rand(2,count($default) - 1)];
		return $default_image;
	}
}

/* Image Function Type */
function changeType($file,$type){
	if(!empty($file)){
		$filetype = strtolower(substr($file, strrpos($file, '.') + 1));
		$basename = substr($file, 0, strrpos($file, '.') );
		$filename = $basename.".".$type;
		return $filename;
	}
}

/* Image Function Type */
function imagesize($imgfile,$type){
	if(!empty($imgfile)){
		$filetype = strtolower(substr($imgfile, strrpos($imgfile, '.') + 1));
		$basename = substr($imgfile, 0, strrpos($imgfile, '.') );
		$filename = $basename.$type.".".$filetype;
		return $filename;
	}
}

/* Random User */
function randNum($length) {
    $result = '';

    for($i = 0; $i < $length; $i++) {
        $result .= mt_rand(0, 9);
    }

    return $result;
}

// Get Age
function datediff($d1, $d2){   
	$d1 = (is_string($d1) ? strtotime($d1) : $d1); $d2 = (is_string($d2) ? strtotime($d2) : $d2); $diff_secs = abs($d1 - $d2);   
	$base_year = min(date("Y", $d1), date("Y", $d2)); $diff = mktime(0, 0, $diff_secs, 1, 1, $base_year);   
	return array(   
		"years1" => date("Y", $diff) - $base_year, "months_total" => (date("Y", $diff) - $base_year) * 12 + date("n", $diff) - 1,   
		"months1" => date("n", $diff) - 1, "days_total" => floor($diff_secs / (3600 * 24)), "days1" => date("j", $diff) - 1,   
		"hours_total" => floor($diff_secs / 3600), "hours" => date("G", $diff), "minutes_total" => floor($diff_secs / 60),   
		"minutes" => (int) date("i", $diff), "seconds_total" => $diff_secs, "seconds" => (int) date("s", $diff) 
	);   
}

// Orderby Array Multidemension
function array_orderby($data, $field, $method = "asc"){
	// Obtain a list of columns
	foreach ($data as $key => $row) {
	    $field_order[$key]  = $row[$field];
	}
	$method = $method == "asc" ? SORT_ASC : SORT_DESC;
	// Sort the data with volume descending, edition ascending
	returnarray_multisort($field_order, SORT_ASC, $data);
}

// Curl
function curl($args){
	$args = func_get_args();

	if(is_array($args[0])){
		$arr = $args[0];
	}else{
		$arr['url'] = isset($args[0]) ? $args[0] : "";
		$arr['data'] = isset($args[1]) ? $args[1] : "";
		$arr['type'] = isset($args[2]) ? $args[2] : "";
	}

    $ch = curl_init();
    $referral='http://www.google.com';
    $url = $arr['url'];
    $type = $arr['type'];
    $data = $arr['data'];

    if($data){
	    switch ($type) {
	    	case 'xml':
	    		curl_setopt($ch, CURLOPT_POST, 1);
	    		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
	    		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
	    		
	    	case 'json':
	    		curl_setopt($ch, CURLOPT_POST, 1);
	    		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
	    		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
	    		break;

	    	case 'post':
	    		curl_setopt($ch, CURLOPT_POST, 1);
	    		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
	    		break;
	    	
	    	default: // get
	    		$arr['url'] .= "?".http_build_query($data);
	    		break;
	    }
	}

	// URL
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_NOBODY, 0);

	// Disable SSL
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);

	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_REFERER, $referral);
	curl_setopt($ch, CURLOPT_AUTOREFERER, 1);

    $data = curl_exec($ch);
    curl_close($ch);

    return $data;
}

function strposa($haystack, $needle, $offset=0) {
    if(!is_array($needle)) $needle = array($needle);
    foreach($needle as $query) {
        if(strpos($haystack, $query, $offset) !== false) return true; // stop on first true result
    }
    return false;
}

function getSelect($table, $key, $val, array $array = array()){
	if(is_object($table))
		$get = $table;
	else
		$get = DB::table($table)->get();

	$sel = $array;
	foreach ($get as $row) {
		$sel[$row[$key]] = $row[$val];
	}
	return $sel;
}

function select($name,$data=array(),$select='',$style='', $to_val = false){
	$h = "<select name='$name' $style>";
	foreach($data as $k => $v){
		if($to_val) $k = $v;
		$k = $k == null ? "" : $k;
		
		if(is_array($select))
			$s = (in_array($k,$select)) ? "selected='selected'" : "";
		else
			$s = ($k == $select) ? "selected='selected'" : "";
		$h .= '<option value="'.$k.'" '.$s.'>'.$v.'</option>';
	}
	$h .= "</select>";
	return $h;
}

function splitFileName($file){
	$name = substr($file, 0, strripos($file, "."));
	$type = substr($file, strripos($file, ".") + 1);
	return array($name, $type);
}

function str2color($str) {
  $code = dechex(crc32($str));
  $code = substr($code, 0, 6);
  return $code;
}

function hex2rgb($hex) {
   $hex = str_replace("#", "", $hex);

   if(strlen($hex) == 3) {
      $r = hexdec(substr($hex,0,1).substr($hex,0,1));
      $g = hexdec(substr($hex,1,1).substr($hex,1,1));
      $b = hexdec(substr($hex,2,1).substr($hex,2,1));
   } else {
      $r = hexdec(substr($hex,0,2));
      $g = hexdec(substr($hex,2,2));
      $b = hexdec(substr($hex,4,2));
   }
   $rgb = array($r, $g, $b);
   //return implode(",", $rgb); // returns the rgb values separated by commas
   return $rgb; // returns an array with the rgb values
}