<?php
class ProdClientsCtrl extends AdminCtrl{

    protected $ctrl = __CLASS__;
    protected $page = "dt_clients";
    protected $view = "prod-clients";
    protected $elq = "DtClientsElq";

    function listAction($paging = ""){
        $_SESSION['list_page'] = current_url();
        // Searching
        $elq = $this->elq->orderBy("dc_cli_num", "asc");
        if($keyword = get('keyword')){
            $elq = $elq->like("dc_cli_name,dc_initial,dc_comp_name,dc_cli_url", $keyword);
        }
        // Get Data
        $data = $elq->paginate();
        if(!$data->count() && $paging > 1) redirect_route(__CLASS__.':list', ['paging' => $paging - 1]);

        return view('backoffice.'.$this->view.'-list',array_merge($this->view_share(),[
            'data' => $data,
        ]));
    }

    function addAction(){
        return view('backoffice.'.$this->view.'-add', array_merge($this->view_share(),[
            'validation' => $this->elq->jqv(),
            'cp_data' => Select::ContactPerson(),
        ]));
    }

    function editAction($id){
        $elq = $this->elq->find($id);

        return view('backoffice.'.$this->view.'-edit', array_merge($this->view_share(),[
            'id' => $id,
            'row' => $elq,
            'status' => $elq->status(),
            'validation' => $this->elq->jqv(),
            'cp_data' => Select::ContactPerson(),
        ]));
    }

    function storeAction(){
        $elq = $this->elq;
        // Check Initial Exist
        if(DtClientsElq::where(['dc_initial' => post('dc_initial')])->first()){
            return response(['pAlert' => [
                'title' => lang('gen.notif'),
                'msg' => lang_var('validation.unique', ['attribute' => DtClientsElq::lang('dc_initial')]),
            ]]);
        }

        $join_date = str_replace("/", "-", $_POST['dc_join_date']);
        $elq->fill($_POST);

        if($elq->isValid()){
            $elq->status(post('status'));
            $elq->fill([
                'dc_cli_num' => Gen::genClientNumber($join_date),
                'dc_join_date' => date("Y-m-d", strtotime($join_date)),
            ]);
            $elq->save();

            $dcon = DtContactsElq::find($_POST['dcon_id']);
            if(!$dcon->DtClientsElq) $dcon->fill(['dcli_id' => $elq['dc_id']])->save();
            
            // Create Dropbox Folder
            Gen::DBXCreate($elq);

            return response([
                'info' => UI::alert(lang_var('gen.add_success', ['type' => $this->pages['type']]), 'success'),
                'redirect' => session('list_page'),
            ]);
        }
        return response(['pAlert' => [
            'title' => lang('gen.validation_error'),
            'msg' => $this->elq->ulError(),
        ]]);
    }

    function updateAction($id){
        $elq = $this->elq->find($id);
        $elq1 = $elq->toArray();
        // Check Initial Exist
        if(DtClientsElq::where('dc_id', '!=', $id)->where(['dc_initial' => post('dc_initial')])->first()){
            return response(['pAlert' => [
                'title' => lang('gen.notif'),
                'msg' => lang_var('validation.unique', ['attribute' => DtClientsElq::lang('dc_initial')]),
            ]]);
        }

        $join_date = str_replace("/", "-", $_POST['dc_join_date']);
        list($num, $year) = explode("-",$elq1['dc_cli_num']);
        $dc_cli_num = $num."-".date("y", strtotime($join_date));
        $elq->fill($_POST);

        $field_rules = "dc_cli_name,dc_initial,dc_comp_name,dc_cli_phone,dcon_id,dc_join_date";
        if($elq->isValid('only', $field_rules)){
            $elq->status(post('status'));
            $elq->fill([
                'dc_cli_num' => $dc_cli_num,
                'dc_join_date' => date("Y-m-d", strtotime($join_date)),
            ]);
            $elq->save();

            $dcon = DtContactsElq::find($_POST['dcon_id']);
            if(!$dcon->DtClientsElq) $dcon->fill(['dcli_id' => $elq['dc_id']])->save();

            // Move Dropbox Folder
            Gen::DBXMove($elq1, $elq);

            return response([
                'info' => UI::alert(lang_var('gen.update_success', ['type' => $this->pages['type']]), 'success'),
                'redirect' => session('list_page'),
            ]);
        }
        return response(['pAlert' => [
            'title' => lang('gen.validation_error'),
            'msg' => $elq->ulError(),
        ]]);
    }

    function store_contactAction(){
        $dcon = new DtContactsElq;
        $dcon->fill($_POST);
        if($dcon->isValid()){
            CSRF::protect();
            
            $dcon->save();
            return response('$(".modal").modal("hide");
            $("[name=\'dcon_id\']").append("<option value=\''.$dcon['dcon_id'].'\' selected>'.$dcon['dcon_name'].'</option>").trigger("chosen:updated");');
        }
        return response(['pAlert' => [
            'title' => lang('gen.validation_error'),
            'msg' => $dcon->ulError(),
        ]]);
    }
    
    function deleteAction($id){
        $data = $this->elq->find($id);
        $data->delete();
    }
}