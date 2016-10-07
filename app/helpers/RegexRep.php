<?php
/* Class RegexRep
* Function: filled by the functions to replace string into rigt regular expression
*/

class RegexRep{

	// Usernmae Regular Expression
	public static function user($str){
		$str = preg_replace("/[^\w.-]+/", "", $str); // Username Regex
		$str = strtolower($str); // To Lower
		$str = trim($str); // trim
		return $str;
	}

	// Name Regular Expression
	public static function name($str){
   		$str = preg_replace('/[^A-Za-z,. \']+/', '', $str); // Username Regex
		$str = static::nms($str); // No Multiple Space
		$str = preg_replace("/[.]+/", '.', $str); // No Multiple Dotted
		$str = preg_replace("/[,]+/", ',', $str); // No Multiple Comma
		$str = trim($str); // trim
		return $str;
	}

	// Email Regular Expression
	public static function email($str){
		$str = preg_replace("/[^\w.-@]+/", "", $str); // Email Regex
		$str = static::nms($str); // No Multiple Space
		$str = strtolower($str); // To Lower
		$str = trim($str); // trim
		return $str;
	}

	// Numeric Regular Expression
	public static function numeric($str){
		$str = preg_replace("/[^0-9]+/", "", $str); // Numeric Regex
		$str = trim($str); // trim
		return intval($str);
	}

	// Filename Regular Expression
	public static function file($str){
		$str = preg_replace("/[^\w.-]+/", " ", $str); // File Regex
		$str = preg_replace("/[_]+/", " ", $str);
		$str = preg_replace("/[\s]+/", "-", $str);
		$str = strtolower($str); // To Lower
		$str = trim($str); // trim
		return $str;
	}

	// Create Slug
	public static function slug($str){
		$str = preg_replace("/[^\w]+/", " ", $str); // Slug Regex
		$str = preg_replace("/[_]+/", " ", $str);
		$str = preg_replace("/[\s]+/", "-", $str);
		$str = strtolower($str); // To Lower
		$str = trim($str); // trim
		return $str;
	}

	// No Multiple Space
	public static function nms($str){
		$str = preg_replace("/[\s]+/", " ", $str);
		$str = trim($str); // trim
		return $str;
	}
}