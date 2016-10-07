<?php

class WA{
	private static $url = "http://lingkar9.com/Chat-API/";
	private static $token = "d5ac296df8018667349117c2ce8fa2d8b668e37a";

	static function send($to, $msg){
		curl(self::$url."send.php", [
	        'token' => self::$token,
	        'to' => $to,
	        'msg' => $msg,
		], 'post');
	}
}