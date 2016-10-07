<?php

/*============================
= Slim Boling Framework
==============================*/
new Boling\Slim();

/*============================
= PHP Setting
==============================*/
date_default_timezone_set(config('app.timezone'));
ini_set('upload_max_filesize', formatByte(config('uploader.max_file_size'), true));

/*============================
= Slim Configuration
==============================*/
app()->config(array(
	'view' 					=> new Boling\Views\Blade(),
	'log.enable' 			=> true,
	'debug'					=> false,
    'templates.path' 		=> root_path().config('app.view'),
    'cookies.encrypt' 		=> true,
    'cookies.secret_key' 	=> config('app.encryption_key'),
    'cookies.cipher' 		=> MCRYPT_RIJNDAEL_256,
    'cookies.cipher_mode' 	=> MCRYPT_MODE_CBC,
));

/*============================
= Error Log writer
==============================*/
app()->error(function(\Exception $e) {
	new LogWriter($e);
});

/*============================
= Session Encryption
==============================*/
$sessionPath = sys_get_temp_dir();
session_save_path(root_path()."/cache/session");
session_start();

/*============================
= Slim View Configuration
==============================*/
$view = app()->view();
$view->parserOptions = array(
    'debug' => true,
    'cache' => root_path().'/cache/view'
);

/*============================
= Params regex
==============================*/
\Slim\Route::setDefaultConditions(array(
    'id' => '[0-9]+',
    'paging' => '[0-9]+',
));

/*============================
= Load all config files 
==============================*/
class Config{
	// Path Config
	var $path = "../config";

	function __construct(){
		$this->path = __DIR__."/".$this->path;

	}

	static function get($name, $default = ""){
		$app = new Config;

		$name = explode(".", $name);
		$ret = require($app->path."/".$name[0].".php");
		unset($name[0]);

		foreach ($name as $v) {
			if(isset($ret[$v])){
				$ret = $ret[$v];
			}else{
				return $default;
			}
		}

		return $ret;
	}

}