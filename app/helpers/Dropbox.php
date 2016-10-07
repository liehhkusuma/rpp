<?php

class Dropbox{

	protected static $app_key = 'JIFQhtTuSFAAAAAAAAAADGY80BzBwBsTdgjfKSpAC3sG2yE9Puuse5iCLcDucBHr';
	protected static $root = "/CLIENTS LIST";

	static function instance(){
		return new Dropbox\Client(self::$app_key, "PHP-Example/1.0");
	}

	static function createFolder($path){
		$dbx = self::instance();
		return $dbx->createFolder(self::$root.$path);
	}

	static function move($path, $topath){
		$dbx = self::instance();
		return $dbx->move(self::$root.$path, self::$root.$topath);
	}

	static function delete($path){
		$dbx = self::instance();
		return $dbx->delete(self::$root.$path);
	}

}