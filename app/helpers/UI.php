<?php
/* class UI
* Function: filled by the functions to get a UI html code
*/

class UI{

	static function CSRF(){
		return '<input type="hidden" name="CSRF_TOKEN" value="'.CSRF::getToken().'"/>';
	}
	
	/* Function 
	* ajax_table table response for table section blade
	* Result: return json html table 
	*/
	static function ajax_table($table){
		if(!app()->request->isAjax()){
		    echo $table;
		}else{ 
			echo json_encode([
			  'table' => $table,
			  'url' => current_url(),
			]);
			die;
		}
	}

	/* Function logo
	* Function: get logo image by insert type of size : sm or md
	* Path Image: from path.path_fo.web -> config/path.php
	* Result: return html code for logo image
	*/
	static function logo($type = "sm"){
		if($type == "sm") $logo = "uangteman_logo.png";
		$img = Config::get("path.path_fo.web").$logo;
		return '<img src="'.$img.'" alt="Uang Teman">';
	}

	/* Function alert
	* Function: get alert by insert text and style : success, info, warning, danger
	* Result: return html code for alert
	*/
	static function alert($text, $style){
		return '<div class="alert alert-'.$style.'">'.$text.'</div>';
	}

	/* Function label
	* Function: get label by insert text and style : success, info, warning, danger
	* Result: return html code for label
	*/
	static function label($text, $style){
		return '<span class="label label-sm label-'.$style.' no_radius">'.$text.'</span>';
	}

	/* Function upload_preview
	* Function: get image with link popup ligthbox by insert image path file and title
	* Result: return html code for image with link popup ligthbox
	*/
	static function upload_preview($targetFile){
		list($basefile, $fileType) = splitFileName($targetFile);
		if(in_array($fileType, ['jpg', 'jpeg', 'gif', 'png', 'bmp'])){
			$title = "Image Preview";
			return '<a href="'.$targetFile.'" class="fancybox" title="'.$title.'"><img alt="'.$title.'" height="30" src="'.assets('bo.icon').'/image2.png" /></a>';
		}elseif($fileType == "mp4"){
			$title = "Image Preview";
			return '<a href="'.route('videoiframe').'?video='.$targetFile.'" class="fancybox fancybox.iframe" title="'.$title.'"><img alt="'.$title.'" height="30" src="'.assets('bo.icon').'/image2.png" /></a>';
				break;
		}else{
			$title = "File Preview";
			return '<a href="'.$targetFile.'" target="_blank" title="'.$title.'"><img alt="'.$title.'" height="30" src="'.assets('bo.icon').'/image2.png" /></a>';
		}
	}

	/* Function view_pdf
	* Function: get pdf icon with link target blank to the file path by insert file path pdf and title
	* Result: return html code for pdf icon link target blank to the file path
	*/
	static function view_pdf($pdf, $title = "Preview PDF"){
		return '<a target="_blank" href="'.$pdf.'" class="ligthbox" title="'.$title.'"><img alt="'.$title.'" height="30" src="'.assets('bo.icon').'/image2.png"/></a>';
	}

	/* Function view_detail
	* Function: get eye icon with link to detail by insert link detail and title
	* Result: return html code for eye icon with link to detail
	*/
	static function view_detail($link, $title = "View Detail"){
		return '<a href="'.$link.'" class="btn btn-xs btn-light-grey btn-squared tooltips ligthbox" data-placement="top" data-original-title="'.$title.'"><i class="fa fa-eye"></i></a>';
	}

	/* Function btn3d
	* Function: get 3d button by insert title, link, and style : 'first' for dark blue color or 'second' for orange color
	* Result: return html code for eye icon with link to detail
	*/
	static function btn3d($title,$link,$style = "second"){
		return '<a href="'.$link.'" class="button button-3d button-small nomargin btn-'.$style.' btn-loading"><strong>'.$title.'</strong></a>';
	}

	/* Function btn3d
	* Function: get caret icon font-awesome by insert type : up, down, left, right
	* Result: return html code caret icon
	*/
	static function caret($type){
		return '<i class="fa fa-caret-'.$type.'"></i>';
	}
}