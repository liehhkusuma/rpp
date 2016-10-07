<?php

$c['max_file_size'] = "209715200"; // 200M
$c['max_image_size'] = "20000000";
$c['max_doc_size'] = "50000000";
$c['max_video_size'] = $c['max_file_size'];

$c['image_type'] = "gif,jpeg,jpg,png,bmp";
$c['document_type'] = "txt,pdf,doc,xls,docx,xlsx";
$c['video_type'] = "mpeg,avi,flv,mp4,webm,swf";

/**
 CKEDITOR
*/
$c['editor'] = [
	'path' => config('path.upload.ckeditor'),
	'rule_type' => $c['image_type'],
	'nameformat' => "{stamp}_{filename[10]}",
];

/**
* File Uploader Module
* ====================================================================
* File Name format : {stamp} , {filename} , {filename[10]}
*/

/**
 Users Documents
*/
// $c['users_document']['path'] = $c['path_bo_dn']['documents'];
// $c['users_document']['nameformat'] = "{stamp}_{filename[10]}";


/**
* Crop Uploader Module
* =====================================================================
* Start - Image files configuration Image orientation options
* Available options: widen, heighten, special, square, crop;
* Image Name format : {stamp} , {filename} , {filename[10]}
*/
$c['image_quality'] = 90;

/** 
 Users Photo
*/
$c['bo_users'] = [
	'path' => config('path.bo.user'),
	'rule_type' => $c['image_type'],
	'nameformat' => "{stamp}_{filename[10]}",
	'img_ratio' => 1,
	'img_orientation' => "square",
	'imgsize' => [
		"" => [250,250],
		"sm" => [100,100],
	],
	'imgshow' => "",
];

/** 
 Slide Photo
*/
$c['bo_slide'] = [
	'path' => config('path.bo.slide'),
	'rule_type' => $c['image_type'],
	'nameformat' => "{stamp}_{filename[10]}",
	'img_ratio' => 1600/1080,
	'img_orientation' => "widen",
	'imgsize' => [
		"" => [1600,1080],
	],
	'imgshow' => "",
];
/** 
 Static Photo
*/
$c['bo_static'] = [
	'path' => config('path.bo.static'),
	'rule_type' => $c['image_type'],
	'nameformat' => "{stamp}_{filename[10]}",
	'img_ratio' => 840/464,
	'img_orientation' => "widen",
	'imgsize' => [
		"" => [840,464],
	],
	'imgshow' => "",
];
/** 
 Gallery Photo
*/
$c['bo_gallery'] = [
	'path' => config('path.bo.gallery'),
	'rule_type' => $c['image_type'],
	'nameformat' => "{stamp}_{filename[10]}",
	'img_ratio' => 540/365,
	'img_orientation' => "widen",
	'imgsize' => [
		"" => [540,365],
		"md" => [337,228],
	],
	'imgshow' => "",
];

return $c;