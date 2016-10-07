<?php

class AjaxCtrl{

	/* indexAction
	* HTTP POST Method
	*/
	public function indexAction($method){
		return $this->$method();
	}

	/* Function post_upload
	Function: to upload image from local disk to uang teman, in 2 languages. 
	Result: value to show the popup of the image that has uploaded.
	*/
	protected function upload(){
		$pageType = $_GET['pageType'];

		// Generate
		$file = $pageType == 'editor' ? $_FILES['upload'] : $_FILES['file'];
		list($fileName, $fileType) = splitFileName($file['name']);
		$fileName = strtolower($fileName);
		$fileType = strtolower($fileType);
		$fileOri = $file['name'];
		$fileTemp = $file['tmp_name'];
		$fileMime = $file['type'];
		$fileSize = $file['size'];

		// Get config
		$uploadType = isset($_GET['uploadType']) ? $_GET['uploadType'] : '';
		$uploader = config('uploader.'.$pageType);
		$lang = get('lang', '');

		// Target Directory
		switch ($uploadType) {
			case 'cropping':
				$targetDir = trim(config('path.temp'), "/");
				break;
			default:
				$targetDir = trim(trim($uploader['path'], "/")."/".$lang, '/');
				break;
		}

		/* Validation */
		// FileType
		if(!in_array($fileType, explode(",", $uploader['rule_type']))){
			return response(['error' => [
	            'title' => lang('gen.validation_error'),
	            'msg' => lang_var('validation.mimes', ['type' => implode(", ",explode(",", $uploader['rule_type']))]),
	        ]]);
		}
		// Max file size
		$max_file_size = config('uploader.max_file_size');
		if(in_array($fileType, explode(",", config('uploader.image_type')))){
			$max_file_size = config('uploader.max_image_size');
		}elseif(in_array($fileType, explode(",", config('uploader.document_type')))){
			$max_file_size = config('uploader.max_doc_size');
		}elseif(in_array($fileType, explode(",", config('uploader.video_type')))){
			$max_file_size = config('uploader.max_video_size');
		}
		if($fileSize > $max_file_size){
			return response(['error' => [
	            'title' => lang('gen.validation_error'),
	            'msg' => lang_var('validation.maxfilesize', ['max' => formatByte($max_file_size)]),
	        ]]);
		}

		/* Change FileName */
		$var_replace = array(
			"{stamp}" => TIME,
			"{filename}" => $fileName,
			"{filename[10]}" => substr($fileName,0,10),
		);
		if($uploader['nameformat']){
			$fileChange = str_replace(array_keys($var_replace), array_values($var_replace), $uploader['nameformat']) . "." . $fileType;
			$fileChange = RegexRep::file($fileChange);
		}else{
			$fileChange = RegexRep::file($fileOri);
		}

		// Target File
		$targetFile = $targetDir."/".$fileChange;

		/* Create Directory */
		if (!file_exists($targetDir)) @mkdir($targetDir, 0777, TRUE);

		/* Upload to dir */
		move_uploaded_file($fileTemp, $targetFile);

		/* Response */
		if($pageType == 'editor'){
			echo "
			<html>
			    <body>
			        <script type=\"text/javascript\">
			            window.parent.CKEDITOR.tools.callFunction('{$_GET['CKEditorFuncNum']}','".base_url()."/".$targetFile."');
			        </script>
			    </body>
			</html>
			";die;
		}


		if(in_array($fileType, ['jpg', 'jpeg', 'gif', 'png'])){
			$img = new SimpleImage;
			$img->load($targetFile);

			$response = [
				'uploadtype' => $uploadType,
				'filename' => $fileChange,
				'filesize' => $fileSize,
				'width' => $img->getWidth(),
				'height' => $img->getHeight(),
				'filedir' => $targetDir,
				'targetfile' => base_url()."/".$targetFile,
				'filetype' => $fileType,
				'mime' => $fileMime,
				'preview' => UI::upload_preview(base_url()."/".$targetFile),
			];

			if($uploadType == "img2pdf"){
				$pdfType = "pdf";
				// Rotate Image
				if($response['width'] > $response['height']){
					$img->rotate(-90);
					$img->save($targetFile, $response['filetype'], config('path.image_quality'));
				}
				// Image
				$image = '<img src="'.$targetFile.'" style="width:100%;max-height:98%;" />';

			    $pdf = App::make('dompdf')->setPaper('a4');
			    $pdf->loadHTML($image)->save(changeType($targetFile, $pdfType));

			    $response['filename'] = changeType($response['filename'], $pdfType);
			    $response['targetfile'] = changeType($response['targetfile'], $pdfType);
			    $response['filetype'] = $pdfType;

				return response($response);
			}

			return response($response);
		}else{

			return response([
				'uploadtype' => $uploadType,
				'filename' => $fileChange,
				'filesize' => $fileSize,
				'filedir' => $targetDir,
				'targetfile' => base_url()."/".$targetFile,
				'filetype' => $fileType,
				'mime' => $fileMime,
				'preview' => $preview = UI::upload_preview(base_url()."/".$targetFile),
			]);
		}
	}

	/* Function post_crop
	Function: to crop the image that has chosen to be uploaded. 
	Result: set the crop selection to the image.
	*/
	protected function crop(){
		/* Generate */
		$pageType = $_POST['pageType'];
		$lang = post('lang');
		$crop_w = $_POST['w'];
		$crop_h = $_POST['h'];
		$crop_x = $_POST['x'];
		$crop_y = $_POST['y'];
		$fileOri = $_POST['filename'];
		$real_width = $_POST['real_width'];
		$real_height = $_POST['real_height'];

		$tempPath = trim(config('path.temp'), "/");
		$tempImg = $tempPath."/".$fileOri;
		$image_quality = config('uploader.image_quality');

		/* Image Crop Config */
		$uploader = config('uploader.'.$pageType);
		$pathImg = trim($uploader['path'], '/');
		if($lang) $pathImg = $pathImg."/".$lang;
		$orientation = $uploader['img_orientation'];
		list($fileName, $fileType) = splitFileName($fileOri);
		$fileName = strtolower($fileName);
		$fileType = strtolower($fileType);
		$imgconf = $uploader['imgsize'];
		$imgshow = $uploader['imgshow'];
		$imgratio = $uploader['img_ratio'];
		
		/* Make Folder Temporary */
		if (!file_exists($tempPath)) @mkdir($tempPath, 0777, TRUE);
		
		/* Make Image Folder  */
		if (!file_exists($pathImg)) @mkdir($pathImg, 0777, TRUE);

		/* FileType */
		if($fileType == "png"){
			$fileType = IMAGETYPE_PNG;
		} else if($fileType == "jpg" || $fileType == "jpeg") {
			$fileType = IMAGETYPE_JPEG;
		} else if($fileType == "gif") {
			$fileType = IMAGETYPE_GIF;
		}

		/* Image Crop Process */
		foreach($imgconf as $k => $v){
			$img = new SimpleImage;
			$img->load($tempImg);
			$img->crop($crop_x, $crop_y, $crop_w, $crop_h);
			$filenames[$k] = !empty($k) ? imagesize($fileOri, "_".$k) : $fileOri;
			$square = $v[0] > $v[1] ? $v[0] : $v[1];

			switch ($orientation) {
				case 'widen':
						$img->resizeToWidth($v[0]);
					break;
				case 'heighten':
						$img->resizeToHeight($v[1]);
					break;
				case 'special':
					if($img->getWidth() > $img->getHeight()) {
						$img->resizeToWidth($v[0]);
					}else{
						$img->resizeToHeight($v[1]);
					}
					break;
				case 'square':
						$square = $v[0] > $v[1] ? $v[0] : $v[1];
						$img->resize($square, $square);
					break;
				case 'crop':
					if($v[0]/$v[1] >= $imgratio) {
						$img->resizeToWidth($v[0]);
					}else{
						$width = $crop_h * $v[0] / $v[1];
						// $height = $crop_h * $v[0] / $v[1];
						$left = ($crop_w / 2) - ($width / 2);
						$img->crop($left, 0, $width, $crop_h);
						$img->resizeToHeight($v[1]);
					}
				default:break;
					break; $img->getWidth();
			}
			$img->save($pathImg . '/' . $filenames[$k], $fileType, $image_quality);
		}

		/* Unlink Image_temp */
		$files = glob($tempPath.'/*');
		foreach($files as $file){
		  if(is_file($file))
		    unlink($file);
		}

		$targetImgShow = base_url()."/".$pathImg.'/'.$filenames[$imgshow];

		$response = array(
			'filename' => $fileOri,
			'image_quality' => $image_quality,
			'targetfile' => $targetImgShow,
			'preview' => UI::upload_preview($targetImgShow),
		);

		return response($response);
	}

	/*
	* Ajax sorting table
	*/
	protected function sorter(){
		$new_sort = explode(",", post('new_sorts'));
		$max_data = post('max');
		$count_data = count($new_sort);
		foreach ($new_sort as $key => $id) {
			$key++;
			$elq = $_POST['table']::find($id);
			$elq->order($key)->save();
		}
	}
	protected function manual_sorter(){
		$id = $_POST['id'];
		$sort =$_POST['sort'];
		$elq = $_POST['table']::find($id);
		$table = $elq->get_table();
		$order_name = $elq->orderKey;

		DB::statement("UPDATE ".$table." SET ".$order_name." = ".$order_name." - 1"." WHERE ".$order_name." >= ".$elq->order());
		DB::statement("UPDATE ".$table." SET ".$order_name." = ".$order_name." + 1"." WHERE ".$order_name." >= ".$sort);

		$elq->order($sort)->save();
	}
}