<?php

class Lang{

	protected static $_instance;

	// Path Config
	var $dir = "../lang";
	var $lang = "en";

	function __construct(){
		$this->lang = config('app.lang');
		$this->path = __DIR__."/".$this->dir;
	}

	public static function instance() {
        static $initialized = FALSE;

        if ( ! $initialized) {
            self::$_instance = new Lang;
            $initialized = TRUE;
        }

        return self::$_instance;
    }
	
	static function current(){
		$app = self::instance();
		return $app->lang;
	}
	
	static function set($lang){
		$app = self::instance();
		$app->lang = $lang;
	}

	static function get($name, $default = ""){
		$app = self::instance();

		$name = explode(".", $name);
		$ret = require($app->path."/".$app->lang."/".$name[0].".php");
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