<?php 

class Validator extends Validate{

	protected $rules;
	protected $data;
	protected $messages;
	protected $customMessages = array();
	protected $customAttributes = array();
	protected $validationLang = array(
			"minlength"		=> ['min'],
			"maxlength"		=> ['max'],
			"rangelength"	=> ['min','max'],
			"min"			=> ['min'],
			"max"			=> ['max'],
			"range"			=> ['min','max'],
			"equalTo"		=> ['other'],
		);
	
	public function __construct(array $data, array $rules, array $messages = array(), array $customAttributes = array()){
		$this->customMessages = $messages;
		$this->data = $data;
		$this->rules = $this->explodeRules($rules);
		$this->customAttributes = $customAttributes;
	}

	public static function make(array $data, array $rules, array $messages = array(), array $customAttributes = array()) {
        return new Validator($data, $rules, $messages, $customAttributes);
    }

	/**
	 * Explode the rules into an array of rules.
	 *
	 * @param  string|array  $rules
	 * @return array
	 */
	protected function explodeRules($rules){
		foreach ($rules as $key => &$rule){
			$rule = (is_string($rule)) ? explode('|', $rule) : $rule;
		}

		return $rules;
	}

	/**
	 * Determine if the data passes the validation rules.
	 *
	 * @return bool
	 */
	public function passes(){

		foreach ($this->rules as $attribute => $rules){
			foreach ($rules as $rule){
				$this->validate($attribute, $rule);
			}
		}

		return count($this->messages) === 0;
	}

	/**
	 * Determine if the data fails the validation rules.
	 *
	 * @return bool
	 */
	public function fails(){
		return ! $this->passes();
	}

	/**
	 * Validate a given attribute against a rule.
	 *
	 * @param  string  $attribute
	 * @param  string  $rule
	 * @return void
	 */
	protected function validate($attribute, $rule){
		list($rule, $parameters) = $this->parseRule($rule);

		if ($rule == '') return;

		// We will get the value for the given attribute from the array of data and then
		// verify that the attribute is indeed validatable. Unless the rule implies
		// that the attribute is required, rules are not run for missing values.
		$value = $this->getValue($attribute);

		$method = "validate{$rule}";

		if (! $this->$method($value, $parameters)){
			$this->addFailure($attribute, $rule, $parameters);
		}
	}

	/**
	 * Returns array rule with parameter.
	 *
	 * @param  string  $attribute
	 * @return array
	 */
	protected function parseRule($rule){
		$param = [];
		if(strpos($rule, ":")){
			list($rule, $param) = explode(":", $rule);
			$param = explode(",", $param);
		}
		return [$rule,$param];
	}

	/**
	 * Set failure message.
	 *
	 * @param  string  $attribute
	 * @return mixed
	 */
	protected function addFailure($attribute, $rule, $parameters){
		if(isset($this->messages[$attribute])) return;

		$attr = isset($this->customAttributes[$attribute]) ? $this->customAttributes[$attribute] : $attribute;
		if(isset($this->customMessages[$attribute][$rule])){
			$messages = $this->customMessages[$attribute][$rule];
		}elseif(isset($this->customMessages[$attribute]) && !is_array($this->customMessages[$attribute])){
			$messages = $this->customMessages[$attribute];
		}else{
			$var['attribute'] = $attr;
			if(isset($this->validationLang[$rule])){
				foreach ($this->validationLang[$rule] as $key => $rep) {
					$var[$rep] = $parameters[$key];
					if($rule == "equalTo"){
						$var[$rep] = isset($this->customAttributes[$attribute]) ? $this->customAttributes[$attribute] : $parameters[$key];
					}
				}
			}
			$messages = str_var(lang('validation.'.$rule), $var);
		}

		$this->messages[$attribute] = $messages;
	}

	/**
	 * Get validation message.
	 *
	 * @return mixed
	 */
	public function errors(){
		if(!$this->messages) $this->passes();
		return $this->messages;
	}

	/**
	 * Get the value of a given attribute.
	 *
	 * @param  string  $attribute
	 * @return mixed
	 */
	protected function getValue($attribute){
		if ( ! is_null($value = array_get($this->data, $attribute))){
			return $value;
		}
	}

}
