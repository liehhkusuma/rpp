<?php
class BoModuleElq extends BaseEloquent {

	/* Core Attributes */
	protected static $elq = __CLASS__;
	protected $table = 'bo_module';
	protected $primaryKey = 'bmd_id';
	protected $statusKey = "bmd_status";
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
		"bmd_order"				=> ["", ""],
		"bmd_name"				=> ["required|minlength:3", ""],
		"bmd_mod_name"			=> ["required|minlength:3", "slug"],
		"bmd_desc"				=> ["required", ""],
		"bmd_status"			=> ["required", ""],
	];
}
