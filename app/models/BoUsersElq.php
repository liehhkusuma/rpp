<?php
class BoUsersElq extends BaseEloquent {

	/* Core Attributes */
	protected static $elq = __CLASS__;
	protected $table = 'bo_users';
	protected $primaryKey = 'bu_id';
	protected $statusKey = "bu_status";
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
		"bu_real_name"			=> ["required|minlength:3", ""],
		"bu_no_regis"			=> ["required|minlength:3", ""],
		"bu_email"				=> ["required|email", ""],
		"bu_name"				=> ["required|minlength:3", "user"],
		"bu_passwd"				=> ["required|minlength:5", ""],
		"bu_salt"				=> ["required", ""],
		"bu_init"				=> ["required", ""],
		"bu_pic"				=> ["", ""],
		"bu_level"				=> ["required", ""],
		"bu_status"				=> ["required", ""],
		"bu_create_date"		=> ["", ""],
	];

	/**
	* Join Function
	*/
	public function BoUserLevelElq(){
		return $this->hasOne("BoUserLevelElq","bul_id","bu_level");
	}
}
