<?php
/* Class BoUsers
* Function: Model ORM Eloquent BaseEloquent
* Parent of all model
*/
use Illuminate\Database\Eloquent\Model as Eloquent;

class BaseEloquent extends Eloquent {

	protected $mandatory = [];
	protected $errors = [];
	protected $lang = [];

	// Conctruct
	public function __construct(){
		// Fillable Generate
		if(!empty($this->fieldRules)){
			foreach ($this->fieldRules as $key => $val) {
				if(!strpos($key, "!")){
					$this->fillable[] = $key;
				}else{
					unset($this->fieldRules[$key]);
					$this->fieldRules[trim($key,'!')] = $val;
				}
			}
			
			// Rules Generate
			foreach ($this->fieldRules as $key => $val) {
				if($val[0] != "") $this->rules[$key] = $val[0];
				// Set validation langueage
				$this->lang[$key] = lang('field.'.$this->table.".".$key, $key);
			}
		}
	}

	/* 
	* Function: Create a mandatory fields for laravel validation
	*/
	public function mandatory($field = []){
		$this->mandatory = $field;
	}

	/* 
	* Function: Run a laravel validation by taking fields rules on model who call this function
	* only validate field from field post method
	* and automatically get lang from fields on lang/[in|en]/field.php for error message
	*/
	public function isValid($type = "", $field = ""){
		$rules = array();
		$rules_field = $this->rules;

		// Ignore Field
		if($type == "ignore"){
			foreach (str_getcsv($field) as $field) {
				unset($rules_field[$field]);
			}
		}

		// Only Field
		if($type == "only"){
			$rules_field = [];
			foreach (str_getcsv($field) as $field) {
				if(isset($this->rules[$field]))
					$rules_field[$field] = $this->rules[$field];
			}
		}

		// Replace Regular Expression
		foreach ($this->fieldRules as $key => $val){
			// Create Validation rules
			if(isset($rules_field[$key])){
				$no_validate = ['ktp','kk','minimum','maximum'];
				$rule = explode("|", $rules_field[$key]);
				foreach($rule as $row){
					$row1 = explode(":", $row);
					if(!in_array($row1[0], $no_validate)){
						$role[$key][] = $row;
					}
				}
				$rules_field[$key] = implode("|", $role[$key]);
			}

			// Trim All \n in atttribute
			if(isset($this->attributes[$key])){
			$this->attributes[$key] = trim($this->attributes[$key],"
        ​​");
				$this->attributes[$key] = trim($this->attributes[$key]);
			}

			// Regex Replace
			if($val[1] != ""){
				$regexRep = explode("|", $val[1]);
				foreach($regexRep as $fn){
					if(method_exists("RegexRep", $fn) && isset($this->attributes[$key])){
						$this->attributes[$key] = RegexRep::$fn($this->attributes[$key]);
					};
				}
			}
		}

		// Filter Rules from post method
		foreach($this->attributes as $key => $val){
			if(isset($rules_field[$key])) $rules[$key] = $rules_field[$key];
		}
		// Mandatory Rules
		foreach($this->mandatory as $field){
			if(isset($rules_field[$field])) $rules[$field] = $rules_field[$field];
		}

		// Ignore Field
		if(isset($active_rules['ignore'])){
			foreach($active_rules['ignore'] as $row){
				unset($rules[$row]);
			}
		}

		// Make Validation Rules
		$validation = Validator::make($this->attributes, $rules, array(), $this->lang);

		// Is Fails
		if($validation->fails()){
			$this->errors = $validation->errors();
			return false;
		}

		// Error Empty Attributes
		if(empty($this->attributes)) return false;

		return true;
	}

	public function errors(){
		if(! $this->errors) $this->isValid();
		return $this->errors;
	}

	/* 
	* Function: create error validation with ul li html tag 
	*/
	public function ulError(){
		if(! $this->errors) $this->isValid();
		if(!$this->errors) return;

		$res = '<ul class="leftmargin-sm">';
		$res .= "<li>".implode("</li>\n<li>", $this->errors)."</li>";
		$res .= "</ul>";
		return $res;
	}

	/* 
	* Function: Convert laravel validation to jquery validation by select field, default : all field
	*/
	protected $jqv_format = array(
			"minlength"		=> [':0','min'],
			"maxlength"		=> [':0','max'],
			"rangelength"	=> ['[:0,:1]','min|max'],
			"min"			=> [':0','min'],
			"max"			=> [':0','max'],
			"range"			=> ['[:0,:1]','min|max'],
			"equalTo"		=> ['#:0','other'],
		);

	static function jqv($type = "", $field = []){
		$self = new static::$elq;
		$rules_field = $self->rules;

		// Ignore Field
		if($type == "ignore"){
			foreach (str_getcsv($field) as $field) {
				unset($rules_field[$field]);
			}
		}

		// Only Field
		if($type == "only"){
			$rules_field = [];
			foreach (str_getcsv($field) as $field) {
				$rules_field[$field] = $this->rules[$field];
			}
		}

		if(!empty($rules_field)){
			foreach($rules_field as $key => $val){
				$rule = explode("|", $val);

				$messages[$key] = [];
				$lang = $self->lang[$key];
				foreach($rule as $rule){
					$param[0] = true;
					if(strpos($rule, ":")){
						list($rule, $param) = explode(":", $rule);
						$param = str_getcsv($param);
					}

					// Create Rules
					$rules[$key][$rule] = isset($self->jqv_format[$rule]) ? str_var($self->jqv_format[$rule][0], $param) : $param[0];

					// Create Meassages
					if($rule == "equalTo") $param[0] = $self->lang[$param[0]];
					$lang_rep['attribute'] = $lang;
					if(isset($self->jqv_format[$rule])){
						$lang_format = str_getcsv($self->jqv_format[$rule][1]);
						foreach ($lang_format as $key1 => $val1) {
							$lang_rep[$val1] = $param[$key1];
						}
					}
					$messages[$key][$rule] = lang_var("validation.$rule", $lang_rep);
				}
			}

			if(!empty($active)){
				foreach($active as $key){
					$rules_active[$key] = $rules[$key];
					$messages_active[$key] = $messages[$key];
				}
				return ['rules' => $rules_active, 'messages' => $messages_active];
			}

			return json_encode(['rules' => $rules, 'messages' => $messages]);
		}
	}

	/* 
	* Function: Simple to call this model eloquent lang
	*/
	static function lang($name = ""){
		$self = new static::$elq;
		if(!$name) return $self->lang;
		return $self->lang[$name];
	}

	/* 
	* Function: Simple update order
	*/
	function get_table(){
		return $this->table;
	}

	/* 
	* Function: Simple update order
	*/
	function order($order = ""){
		$orderKey = $this->orderKey;
		// Getter
		if(!$order) return $this->$orderKey;
		// Setter
		$this->$orderKey = $order;
		return $this;
	}

	/* 
	* Function: Scope active fuction for get model with active status
	*/
	function status($status = ""){
		$statusKey = $this->statusKey;
		// Getter
		if(!$status) return $this->$statusKey;
		// Setter
		$this->$statusKey = $status;
		return $this;
	}

	/* 
	* Function: Simple like fuction for practice query
	*/
	function scopeLike($q, $field, $keyword){
		$field = str_getcsv($field);
		foreach ($field as $field) {
			$q = $q->orWhere($field, "like", "%$keyword%");
		}

		return $q;
	}

	/* 
	* Function: Scope active fuction for get model with active status
	*/
	function scopeActive($q){
		return $q->where($this->statusKey, "y");
	}
}