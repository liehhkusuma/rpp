<?php

class Gen{

	static function genClientNumber($join_date = false){
		$dc = DtClientsElq::orderBy('dc_id','desc')->first();
		$years = $join_date ? date("y", strtotime($join_date)) : date("y");

		if($dc){
			list($num, $year) = explode("-",$dc['dc_cli_num']);
			$cli_num = intval($num) / 1000 + 1;
			$cli_fullnum = ($cli_num * 1000) . "-" . $years;
		}else{
			$cli_fullnum = "11000-" . $years;
		}
		return $cli_fullnum;
	}

	static function genProjectNumber($dc_id, $create_date){
		$dc = DtClientsElq::where(['dc_id' => $dc_id])->first();
		$dpro = DtProjectsElq::orderBy('dpro_id','desc')->where(['dc_id' => $dc_id])->first();
		$years = date("y", strtotime($create_date));

		if($dpro){
			list($drop_num, $year) = explode("-",$dpro['dpro_number']);
			$pro_fullnum = (intval($drop_num) + 1). "-" . $years;
		}else{
			list($cli_num, $year) = explode("-",$dc['dc_cli_num']);
			// Bug if project more than 999
			$pro_fullnum = (intval($cli_num) + 1 )."-". $years;
		}
		return $pro_fullnum;
	}

	static function genDocsNumber($mdoc_id, $mdep_id, $mat_id, $dpro_id){
		// INV22.122.15.0025-11001-13
		$docs_num = "";
		$mdoc = MrDocTypeElq::find($mdoc_id);
		$mdep = MrDepartementElq::find($mdep_id);
		$dpro = DtProjectsElq::find($dpro_id);
		$mdoc_check = DtDocumentElq::orderBy('ddoc_id','desc')->where(['mdoc_id' => $mdoc_id])->first();
		$docs_check = DtDocumentElq::orderBy('ddoc_id','desc')->whereRaw("YEAR(ddoc_create_date) = ?", [date('Y')])->first();

		// Create DocType Number
		if($mdoc_check){
			$x = explode(".", $mdoc_check['ddoc_number']);
			$mdoc_num = $mdoc['mdoc_initial'] . (intval(RegexRep::numeric($x[0])) + 1);
		}else{
			$mdoc_num = $mdoc['mdoc_initial']."1";
		}

		// Add Departemen Code And Years
		$docs_num .= $mdoc_num .'.'. $mdep['mdep_num'] .$mat_id.'.'. date('y');

		// Add Number of document in Year
		if($docs_check){
			$y = explode(".", $docs_check['ddoc_number']);
			$num = (intval($y[3]) + 1);
			for($i=strlen($num);$i<4;$i++){
				$num = "0".$num;
			}
			$docs_num .= '.'.$num;
		}else{
			$docs_num .= '.0001';
		}

		// Add Project Number
		$docs_num .= '-'.$dpro['dpro_number'];

		return $docs_num;
	}

	static function DBXCreate($elq){
		try {
			if(get_class($elq) == 'DtProjectsElq'){
				if($elq->DtClientsElq){
					$client = $elq->DtClientsElq;
					$client_folder = $client['dc_cli_num']." ".strtoupper($client['dc_cli_name']);
					$cli_num = substr($client['dc_cli_num'],0,2);
					$project_folder = $client_folder."/".substr_replace($elq['dpro_number'], $cli_num, 0, 2)." ".strtoupper($elq['dpro_name']);
					// dd($project_folder);
					Dropbox::createFolder("/".$project_folder."/DOC");
					Dropbox::createFolder("/".$project_folder."/INV");
					Dropbox::createFolder("/".$project_folder."/QUO");
				}
			}else{
				$client_folder = $elq['dc_cli_num']." ".strtoupper($elq['dc_cli_name']);
				Dropbox::createFolder("/".$client_folder);
			}
			return true;
		} catch (Exception $e) {
			return false;
		}
	}

	static function DBXMove($elq, $elq2){
		try {
			if(get_class($elq2) == 'DtProjectsElq'){
				$client = $elq['dt_clients_elq'];
				$client_folder = $client['dc_cli_num']." ".strtoupper($client['dc_cli_name']);
				$project_folder = $client_folder."/".$elq['dpro_number']." ".strtoupper($elq['dpro_name']);

				$client2 = $elq2->DtClientsElq;
				$client_folder2 = $client2['dc_cli_num']." ".strtoupper($client2['dc_cli_name']);
				$project_folder2 = $client_folder2."/".$elq2['dpro_number']." ".strtoupper($elq2['dpro_name']);
				Dropbox::move("/".strtoupper($project_folder), "/".strtoupper($project_folder2));
			}else{
				$client_folder = $elq['dc_cli_num']." ".strtoupper($elq['dc_cli_name']);
				$client_folder2 = $elq2['dc_cli_num']." ".strtoupper($elq2['dc_cli_name']);
				Dropbox::move("/".strtoupper($client_folder), "/".strtoupper($client_folder2));
			}
			return true;
		} catch (Exception $e) {
			return false;
		}
	}
}