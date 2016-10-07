<?php
class ProdProjectsCtrl extends AdminCtrl{

    protected $ctrl = __CLASS__;
    protected $page = "dt_projects";
    protected $view = "prod-projects";
    protected $elq = "DtProjectsElq";

    function listAction($paging = ""){
        $_SESSION['list_page'] = current_url();
        // Searching
        $elq = $this->elq
            ->join('dt_clients', 'dt_clients.dc_id', '=', 'dt_projects.dc_id')
            ->leftJoin('mr_product_type', 'mpt_id', '=', 'dpty_id')
            ->orderBy('dc_cli_num', 'asc')->orderBy('dpro_number', 'asc');
        if($keyword = get('keyword')){
            $elq = $elq->like("dpro_name,dpro_initial,mpt_name,dc_cli_name", $keyword);
        }
        // Get Data
        $data = $elq->paginate();
        if(!$data->count() && $paging > 1) redirect_route(__CLASS__.':list', ['paging' => $paging - 1]);

        // return response($data->data()->toArray());

        return view('backoffice.'.$this->view.'-list',array_merge($this->view_share(),[
            'data' => $data,
        ]));
    }

    function addAction(){
        return view('backoffice.'.$this->view.'-add', array_merge($this->view_share(),[
            'validation' => $this->elq->jqv(),
            'cp_data' => Select::ContactPerson(),
            'client_data' => Select::Client(),
            'product_type_data' => Select::ProductType(),
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
            'client_data' => Select::Client(),
            'product_type_data' => Select::ProductType(),
        ]));
    }

    function storeAction(){
        $elq = $this->elq;
        // Check Initial Exist
        if(DtProjectsElq::where(['dpro_initial' => post('dpro_initial')])->first()){
            return response(['pAlert' => [
                'title' => lang('gen.notif'),
                'msg' => lang_var('validation.unique', ['attribute' => DtProjectsElq::lang('dpro_initial')]),
            ]]);
        }

        $get_dcon_id = $_POST['dcon_id'];
        $_POST['dcon_id'] = implode(",", $_POST['dcon_id']);
        $create_date = date("Y-m-d", strtotime(str_replace("/", "-", $_POST['dpro_create_date'])));
        $elq->fill($_POST);

        if($elq->isValid()){
            $elq->status(post('status'));
            list($d,$m,$y) = explode("/",$_POST['dpro_create_date']);
            $elq->fill([
                'dpro_number' => Gen::genProjectNumber($_POST['dc_id'], $create_date),
                'dpro_create_date' => $create_date,
            ]);
            $elq->save();

            foreach ($get_dcon_id as $dcon_id) {
                $dcon = DtContactsElq::find($dcon_id);
                if(!$dcon->DtClientsElq) $dcon->fill(['dcli_id' => $elq['dc_id']])->save();
                if(!$dcon->DtProjectsElq) $dcon->fill(['dpro_id' => $elq['dpro_id']])->save();
            }

            // Create Dropbox Folder
            Gen::DBXCreate($elq);

            return response([
                'info' => UI::alert(lang_var('gen.add_success', ['type' => $this->pages['type']]), 'success'),
                'redirect' => session('list_page'),
            ]);
        }
        return response(['pAlert' => [
            'title' => lang('gen.validation_error'),
            'msg' => $elq->ulError(),
        ]]);
    }

    function updateAction($id){
        $elq = $this->elq->find($id);
        $elq1 = DtProjectsElq::with('DtClientsElq')->find($id)->toArray();
        // Check Initial Exist
        if(DtProjectsElq::where('dpro_id', '!=', $id)->where(['dpro_initial' => post('dpro_initial')])->first()){
            return response(['pAlert' => [
                'title' => lang('gen.notif'),
                'msg' => lang_var('validation.unique', ['attribute' => DtProjectsElq::lang('dpro_initial')]),
            ]]);
        }

        $get_dcon_id = $_POST['dcon_id'];
        $_POST['dcon_id'] = implode(",", $_POST['dcon_id']);
        $create_date = date("Y-m-d", strtotime(str_replace("/", "-", $_POST['dpro_create_date'])));
        $elq->fill($_POST);

        $field_rules = "dpro_name,dpro_initial,dc_id,dcon_id,dpty_id";
        if($elq->isValid('only', $field_rules)){
            $elq->status(post('status'));
            list($num, $year) = explode("-", $elq['dpro_number']);
            $elq->fill([
                'dpro_number' => $num."-".date("y", strtotime($create_date)),
                'dpro_create_date' => $create_date,
            ]);
            $elq->save();

            foreach ($get_dcon_id as $dcon_id) {
                $dcon = DtContactsElq::find($dcon_id);
                if(!$dcon->DtClientsElq) $dcon->fill(['dcli_id' => $elq['dc_id']])->save();
                if(!$dcon->DtProjectsElq) $dcon->fill(['dpro_id' => $elq['dpro_id']])->save();
            }

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

    function store_clientAction(){
        ModalStore::client();
    }

    function store_contactAction(){
        ModalStore::contact();
    }
    
    function deleteAction($id){
        $data = $this->elq->find($id);
        $data->delete();
    }
}