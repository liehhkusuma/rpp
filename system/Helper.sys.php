<?php

function slim(){
	return \Boling\Slim::getInstance();
}

function app(){
	return slim();
}

function pd($data){
	if(is_array($data)){
		header('Content-Type: application/json');
		echo json_encode($data);die;
	}
	echo"<pre>";print_r($data);echo"</pre>";die;
}

function config($name, $default = ""){
	return Config::get($name, $default);
}

function lang($name, $default = ""){
	return Lang::get($name, $default);
}

function response($data, $header = "json"){
	$res = app()->response();
	if(is_array($data)){
		$data = json_encode($data);
	}else{
		$header = "text";
	}
	switch ($header) {
		case 'json':
    		$res['Content-Type'] = 'application/json';
			break;
		case 'javascript':
    		$res['Content-Type'] = 'application/javascript';
			break;
		default:
			# code...
			break;
	}
   	$res->body($data);
}

function post($key, $default = ""){
	return isset($_POST[$key]) ? $_POST[$key] : $default;
}

function get($key, $default = ""){
	return isset($_GET[$key]) ? $_GET[$key] : $default;
}

function set_session($name, $data){
	$_SESSION[$name] = $data;
}

function unset_session($name){
	if(is_array($name)){
		foreach ($name as $row) {
			if(isset($_SESSION[$row]))
				unset($_SESSION[$row]);
		}
	}

	if(isset($_SESSION[$name]))
		unset($_SESSION[$name]);
}

function session($name, $default = ""){
	return isset($_SESSION[$name]) ? $_SESSION[$name] : $default;
}

function flash_session($name, $default = ""){
	if(isset($_SESSION[$name])){
		$res = $_SESSION[$name];
		unset($_SESSION[$name]);
	}else{
		$res = $default;	
	}
	return $res;
}

function redirect($url){
	return app()->redirect($url);
}

function redirect_route($routeName, array $param = array()){
	return redirect(route($routeName, $param));
}

function base_url(){
	return REQUEST_SCHEME."://".$_SERVER['HTTP_HOST'].str_replace("\\","",preg_replace('@/+$@','',dirname($_SERVER['SCRIPT_NAME'])));
}

function request_url(){
	return isset($_SERVER["REDIRECT_URL"])? REQUEST_SCHEME."://".$_SERVER['HTTP_HOST'].$_SERVER["REDIRECT_URL"]:base_url();
}

function current_url(){
	return request_url() . ($_SERVER['QUERY_STRING'] ? "?".$_SERVER['QUERY_STRING'] : "");
}

function uri_segment($segment = ""){
	$request  = str_replace(base_url(),"",request_url());
	$uri = explode("/", $request);
	$uri[0] = base_url();

	if($segment !== "") return isset($uri[$segment]) ? $uri[$segment] : "";

	return $uri;
}

function root_path(){
	return dirname(__DIR__."../");
}

function app_path(){
	return dirname(__DIR__)."/app";
}

function assets($name = "assets", $path_url = 0){
	if($path_url) return root_path()."/".trim(config('path.'.$name), "/");
	return base_url()."/".trim(config('path.'.$name), "/");
}

function url($url=""){
	return base_url().$url;
}

function route($name, array $param = array()){
	$link = REQUEST_SCHEME."://".$_SERVER['HTTP_HOST'].app()->urlFor($name, $param);
	return $link;
}

function view($view, $data = [], $return = false){
	ob_start();
	app()->render($view, $data);
	$result = ob_get_contents();
	ob_end_clean();

	if($return) return $result;

	echo $result;
}

function str_var($str, $var = array()){
	$var_rep = [];

	foreach($var as $key => $val){
		$var_rep[':'.$key] = $val;
	}

	$res = str_replace(array_keys($var_rep), array_values($var_rep), $str);
	return $res;
}

function lang_var($name, $var = array()){
	return str_var(lang($name), $var);
}

function formatByte($bytes, $type = false){
    if ($bytes >= 1073741824)
    {
        $bytes = number_format($bytes / 1073741824) . ($type ? 'G' : ' GB');
    }
    elseif ($bytes >= 1048576)
    {
        $bytes = number_format($bytes / 1048576) . ($type ? 'M' : ' MB');
    }
    elseif ($bytes >= 1024)
    {
        $bytes = number_format($bytes / 1024) . ($type ? 'K' : ' KB');
    }
    elseif ($bytes > 1)
    {
        $bytes = $bytes . ($type ? '' : ' bytes');
    }
    elseif ($bytes == 1)
    {
        $bytes = $bytes . ($type ? '' : ' byte');
    }
    else
    {
        $bytes = ($type ? 0 : '0 bytes');
    }

    return $bytes;
}