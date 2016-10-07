<?php 

class Validate {

	/**
	 * Validate that a required attribute exists.
	 *
	 * @param  mixed   $value
	 * @return bool
	 */
	protected function validateRequired($value){
		if (is_null($value)){
			return false;
		}elseif (is_string($value) && trim($value) === ''){
			return false;
		}elseif ((is_array($value) || $value instanceof \Countable) && count($value) < 1){
			return false;
		}elseif ($value instanceof File){
			return (string) $value->getPath() != '';
		}
		return true;
	}

	/**
	 * Validate that a minimal attribute character length.
	 *
	 * @param  mixed   $value
	 * @param  array   $parameters
	 * @return bool
	 */
	protected function validateMinLength($value, $parameters){
		return $this->getStringSize($value) >= $parameters[0];
	}

	/**
	 * Validate that a maximal attribute character length.
	 *
	 * @param  mixed   $value
	 * @param  array   $parameters
	 * @return bool
	 */
	protected function validateMaxLength($value, $parameters){
		return $this->getStringSize($value) <= $parameters[0];
	}

	/**
	 * Validate that a range attribute character length.
	 *
	 * @param  mixed   $value
	 * @param  array   $parameters
	 * @return bool
	 */
	protected function validateRangeLength($value, $parameters){
		$size = $this->getStringSize($value);
		return ($size >= $parameters[0]) && ($size <= $parameters[1]);
	}

	/**
	 * Validate that attribute exact length.
	 *
	 * @param  mixed   $value
	 * @param  array   $parameters
	 * @return bool
	 */
	protected function validateExact($value, $parameters){
		$size = $this->getStringSize($value);
		return $size == $parameters;
	}

	/**
	 * Validate that a minimal attribute numeric value.
	 *
	 * @param  mixed   $value
	 * @param  array   $parameters
	 * @return bool
	 */
	protected function validateMin($value, $parameters){
		return $value >= $parameters[0] && $this->validateNumber($value);
	}

	/**
	 * Validate that a maximal attribute numeric value.
	 *
	 * @param  mixed   $value
	 * @param  array   $parameters
	 * @return bool
	 */
	protected function validateMax($value, $parameters){
		return $value <= $parameters[0] && $this->validateNumber($value);
	}

	/**
	 * Validate that a range attribute numeric value.
	 *
	 * @param  mixed   $value
	 * @param  array   $parameters
	 * @return bool
	 */
	protected function validateRange($value, $parameters){
		return  ($value >= $parameters[0]) && ($value <= $parameters[1]) && $this->validateNumber($value);
	}

	/**
	 * Validate that an attribute is a valid e-mail address.
	 *
	 * @param  mixed   $value
	 * @return bool
	 */
	protected function validateEmail($value){
		if(!$value) return true;
		return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
	}

	/**
	 * Validate that an attribute is a valid URL.
	 *
	 * @param  mixed   $value
	 * @return bool
	 */
	protected function validateUrl($value){
		return filter_var($value, FILTER_VALIDATE_URL) !== false;
	}

	/**
	 * Validate that an attribute is a valid date.
	 *
	 * @param  mixed   $value
	 * @return bool
	 */
	protected function validateDate($value){
		if ($value instanceof DateTime) return true;

		if (strtotime($value) === false) return false;

		$date = date_parse($value);

		return checkdate($date['month'], $date['day'], $date['year']);
	}

	/**
	 * Validate that an attribute is numeric.
	 *
	 * @param  mixed   $value
	 * @return bool
	 */
	protected function validateNumber($value){
		return is_numeric($value);
	}

	/**
	 * Validate that an attribute has a given number of digits.
	 *
	 * @param  mixed   $value
	 * @return bool
	 */
	protected function validateDigits($value){
		return filter_var($value, FILTER_VALIDATE_INT) !== false;
	}

	/**
	 * Validate that an attribute has a similiar to.
	 *
	 * @param  mixed   $value
	 * @param  array   $parameters
	 * @return bool
	 */
	protected function validateEqualTo($value, $parameters){
		return $value == $this->getValue($parameters[0]);
	}

	/**
	 * Validate that an attribute is an array.
	 *
	 * @param  mixed   $value
	 * @return bool
	 */
	protected function validateArray($value){
		return is_array($value);
	}

	/**
	 * Validate that an attribute is a boolean.
	 *
	 * @param  mixed   $value
	 * @return bool
	 */
	protected function validateBoolean($value){
		$acceptable = array(true, false, 0, 1, '0', '1');

		return in_array($value, $acceptable, true);
	}

	/**
	 * Validate that an attribute is an integer.
	 *
	 * @param  mixed   $value
	 * @return bool
	 */
	protected function validateInteger($value){
		return filter_var($value, FILTER_VALIDATE_INT) !== false;
	}

	/**
	 * Validate that an attribute is a string.
	 *
	 * @param  mixed   $value
	 * @return bool
	 */
	protected function validateString($value){
		return is_string($value);
	}

	/**
	 * Validate an attribute is contained within a list of values.
	 *
	 * @param  mixed   $value
	 * @param  array   $parameters
	 * @return bool
	 */
	protected function validateIn($value, $parameters){
		return in_array((string) $value, $parameters);
	}

	/**
	 * Validate an attribute is not contained within a list of values.
	 *
	 * @param  mixed   $value
	 * @param  array   $parameters
	 * @return bool
	 */
	protected function validateNotIn($value, $parameters){
		return ! in_array((string) $value, $parameters);
	}

	/**
	 * Validate that an attribute is a valid IP.
	 *
	 * @param  mixed   $value
	 * @return bool
	 */
	protected function validateIp($value){
		return filter_var($value, FILTER_VALIDATE_IP) !== false;
	}

	/**
	 * Validate that an attribute is an active URL.
	 *
	 * @param  mixed   $value
	 * @return bool
	 */
	protected function validateActiveUrl($value){
		$url = str_replace(array('http://', 'https://', 'ftp://'), '', strtolower($value));

		return checkdnsrr($url);
	}

	/**
	 * Validate that an attribute contains only alphabetic characters.
	 *
	 * @param  mixed   $value
	 * @return bool
	 */
	protected function validateAlpha($value){
		return preg_match('/^[\pL\pM]+$/u', $value);
	}

	/**
	 * Validate that an attribute contains only alpha-numeric characters.
	 *
	 * @param  mixed   $value
	 * @return bool
	 */
	protected function validateAlphaNum($value){
		return preg_match('/^[\pL\pM\pN]+$/u', $value);
	}

	/**
	 * Validate that an attribute contains only alpha-numeric characters, dashes, and underscores.
	 *
	 * @param  mixed   $value
	 * @return bool
	 */
	protected function validateAlphaDash($value){
		return preg_match('/^[\pL\pM\pN_-]+$/u', $value);
	}

	/**
	 * Validate that an attribute passes a regular expression check.
	 *
	 * @param  mixed   $value
	 * @param  array   $parameters
	 * @return bool
	 */
	protected function validateRegex($value, $parameters){
		return preg_match($parameters[0], $value);
	}

	/**
	 * Validate that an attribute matches a date format.
	 *
	 * @param  mixed   $value
	 * @param  array   $parameters
	 * @return bool
	 */
	protected function validateDateFormat($value, $parameters){
		$parsed = date_parse_from_format($parameters[0], $value);

		return $parsed['error_count'] === 0 && $parsed['warning_count'] === 0;
	}

	/**
	 * Validate that an attribute is a valid timezone.
	 *
	 * @param  mixed   $value
	 * @return bool
	 */
	protected function validateTimezone($value){
		try{
			new DateTimeZone($value);
		}catch (\Exception $e){
			return false;
		}
		return true;
	}

	/**
	 * Get the size of a string.
	 *
	 * @param  string  $value
	 * @return int
	 */
	protected function getStringSize($value){
		if (function_exists('mb_strlen')) return mb_strlen($value);

		return strlen($value);
	}
}
