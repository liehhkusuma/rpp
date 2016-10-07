<?php
class BoRegistranElq extends BaseEloquent {

	/* Core Attributes */
	protected static $elq = __CLASS__;
	protected $table = 'registran';
	protected $primaryKey = 'p_id';
	protected $statusKey = "";
	protected $fillable = "";
	protected $rules = "";

	/* timestamps */
	public $timestamps = false;
	const CREATED_AT = 'create_at';
    const UPDATED_AT = 'update_at';

	public function __construct(){
		parent::__construct();
	}

	/* 
	* Function: Field Rules Validation And Regular Expression Replace
	*/
	protected $fieldRules = [		
		"p_no_regis"			=> ["required", ""],
		"p_a1"					=> ["required|minlength:3", ""],
		"p_a2"					=> ["required|minlength:3", ""],
		"p_a3"					=> ["required|minlength:3", ""],
		"p_a4"					=> ["required|minlength:3", ""],
		"p_a5"					=> ["required|minlength:3", ""],
		"p_a6"					=> ["required|minlength:3", ""],
	];

	/**
	* Join Function
	*/
	public function BoUsersElq(){
		return $this->hasOne("BoUsersElq","bu_id","p_no_regis");
	}
}
