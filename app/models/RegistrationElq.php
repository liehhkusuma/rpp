<?php
class RegistrationElq extends BaseEloquent {

	/* Core Attributes */
	protected static $elq = __CLASS__;
	protected $table = "registran";
	protected $primaryKey = 'p_id';
	protected $statusKey = "";
	protected $fillable = "";
	protected $rules = "";
	// Public variable
	public $orderKey = "bm_order";

	public $timestamps = false;

	public function __construct(){
		parent::__construct();
	}

	/* 
	* Function: Field Rules Validation And Regular Expression Replace
	*/
	protected $fieldRules = [
		"p_no_regis" 		=> ["required", ""],
		"p_a1" 		=> ["", ""],
		"p_a2" 		=> ["", ""],
		"p_a3" 		=> ["", ""],
		"p_a4" 		=> ["", ""],
		"p_a5" 		=> ["", ""],
		"p_a6" 		=> ["", ""],
	];

	public function BoUsersElq(){
		return $this->hasOne("BoUsersElq","bu_id","p_no_regis");
	}
}
