<?php

class CSRF{

	private static $instance;
	private $token;

	function __construct(){
		$this->token = sha1(time().rand());
		$_SESSION['CSRF_TOKEN'] = $this->token;
	}

	static function generateToken(){
		return self::$instance = new CSRF();
	}

	static function instance(){
		if(!self::$instance){
			return self::$instance = new CSRF();
		}

		return self::$instance;
	}

	static function getToken(){
		$self = self::instance();
		return $self->token;
	}

	static function protect(){
		if(!isset($_SESSION['CSRF_TOKEN'])) return app()->notFound();

        $cliparam = post("CSRF_TOKEN", get("CSRF_TOKEN", ""));
        if($cliparam == $_SESSION['CSRF_TOKEN']){
        	CSRF::generateToken();
            return true;
        }else{
            app()->notFound();
        }
	}

}