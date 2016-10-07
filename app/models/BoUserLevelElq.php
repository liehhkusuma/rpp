<?php
class BoUserLevelElq extends BaseEloquent {

	/* Core Attributes */
	protected static $elq = __CLASS__;
	protected $table = "bo_user_level";
	protected $primaryKey = 'bul_id';
	protected $statusKey = "bul_status";
	protected $fillable = "";
	protected $rules = "";

	public $timestamps = false;

	public function __construct(){
		parent::__construct();
	}

	/* 
	* Function: Field Rules Validation And Regular Expression Replace
	*/
	protected $fieldRules = [
		"bul_order" 		=> ["required", ""],
		"bul_level_name" 	=> ["required", ""],
		"bul_menu_role" 	=> ["", ""],
		"bul_module_role" 	=> ["", ""],
		"bul_status" 		=> ["required", ""],
	];
}
