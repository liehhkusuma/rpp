<?php

class ModalStore{

	static function client(){
        // Check Initial Exist
        if(DtClientsElq::where(['dc_initial' => post('dc_initial')])->first()){
            return response(['pAlert' => [
                'title' => lang('gen.notif'),
                'msg' => lang_var('validation.unique', ['attribute' => DtClientsElq::lang('dc_initial')]),
            ]]);
            die;
        }

        $dc = new DtClientsElq;

        $join_date = str_replace("/", "-", $_POST['dc_join_date']);
        $dc->fill($_POST);

        if($dc->isValid()){
            $dc->status(post('status'));

            $dc->fill([
                'dc_cli_num' => Gen::genClientNumber($join_date),
                'dc_join_date' => date("Y-m-d", strtotime($join_date)),
            ]);
            $dc->save();

            $dcon = DtContactsElq::find($_POST['dcon_id']);
            if(!$dcon['dcli_id']) $dcon->fill(['dcli_id' => $dc['dc_id']])->save();

            // Create Dropbox Folder
            Gen::DBXCreate($dc);
            
            return response('$(".modal").modal("hide");
            $("[name=\'dc_id\']").append("<option value=\''.$dc['dc_id'].'\' selected>'.$dc['dc_cli_name']." (".$dc['dc_cli_num'].")".'</option>").trigger("chosen:updated");');
            die;
        }
        
        return response(['pAlert' => [
            'title' => lang('gen.validation_error'),
            'msg' => $dc->ulError(),
        ]]);
        die;
	}

    static function project(){
        // Check Initial Exist
        if(DtProjectsElq::where(['dpro_initial' => post('dpro_initial')])->first()){
            return response(['pAlert' => [
                'title' => lang('gen.notif'),
                'msg' => lang_var('validation.unique', ['attribute' => DtProjectsElq::lang('dpro_initial')]),
            ]]);die;
        }

        $dpro = new DtProjectsElq;

        $create_date = date("Y-m-d", strtotime(str_replace("/", "-", $_POST['dpro_create_date'])));
        $dpro->fill($_POST);

        if($dpro->isValid()){
            $dpro->status(post('status'));
            list($d,$m,$y) = explode("/",$_POST['dpro_create_date']);
            $dpro->fill([
                'dpro_number' => Gen::genProjectNumber($_POST['dc_id'], $create_date),
                'dpro_create_date' => $create_date,
            ]);
            $dpro->save();

            $dcon = DtContactsElq::find($_POST['dcon_id']);
            if(!$dcon->DtClientsElq) $dcon->fill(['dcli_id' => $dpro['dc_id']])->save();
            if(!$dcon->DtProjectsElq) $dcon->fill(['dpro_id' => $dpro['dpro_id']])->save();

            // Create Dropbox Folder
            Gen::DBXCreate($dpro);

            return response('$(".modal").modal("hide");
            $("[name=\'dpro_id\']").append("<option value=\''.$dpro['dpro_id'].'\' selected>'.$dpro['dpro_name']." (".$dpro->DtClientsElq['dc_cli_name'].")".'</option>").trigger("chosen:updated");');
            die;
        }
        return response(['pAlert' => [
            'title' => lang('gen.validation_error'),
            'msg' => $dpro->ulError(),
        ]]);die;
    }

	static function contact(){
        $dcon = new DtContactsElq;
        $dcon->fill($_POST);
        if($dcon->isValid()){
            $dcon->save();
            return response('$(".modal").modal("hide");
            $("[name=\'dcon_id\'],[name=\'dcon_id[]\'").append("<option value=\''.$dcon['dcon_id'].'\' selected>'.$dcon['dcon_name'].'</option>").trigger("chosen:updated");');
            die;
        }
        return response(['pAlert' => [
            'title' => lang('gen.validation_error'),
            'msg' => $dcon->ulError(),
        ]]);
        die;
	}
}