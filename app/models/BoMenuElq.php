<?php
class BoMenuElq extends BaseEloquent {

	/* Core Attributes */
	protected static $elq = __CLASS__;
	protected $table = "bo_menu";
	protected $primaryKey = 'bm_id';
	protected $statusKey = "bm_status";
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
		"bm_order" 		=> ["", ""],
		"bm_parent_id" 	=> ["", ""],
		"bm_name" 		=> ["required", ""],
		"bm_desc" 		=> ["required", ""],
		"bm_link" 		=> ["required", ""],
		"bm_icon" 		=> ["", ""],
		"bm_type" 		=> ["", ""],
		"bm_status" 	=> ["required", ""],
	];
}
