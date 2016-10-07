<?php
class ProdDocsCtrl extends AdminCtrl{

    protected $ctrl = __CLASS__;
    protected $page = "dt_document";
    protected $view = "prod-docs";
    protected $elq = "DtDocumentElq";
    
    function listAction($paging = ""){
        $_SESSION['list_page'] = current_url();
    	// Searching
        $elq = $this->elq->orderBy('ddoc_id', 'desc')
            ->join('dt_clients', 'dt_clients.dc_id', '=', 'dt_document.dc_id')
            ->join('mr_departement', 'mr_departement.mdep_id', '=', 'dt_document.mdep_id');
        if($keyword = get('keyword')){
            $elq = $elq->like("ddoc_number,ddoc_name,mdep_name,dc_cli_name", $keyword);
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
            'client_data' => Select::Client(),
            'cp_data' => Select::ContactPerson(),
            'project_data' => Select::Project(),
            'departemen_data' => Select::Departement(),
            'doctype_data' => Select::DocType(),
            'access_type_data' => Select::AccessType(),
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
            'product_type_data' => Select::ProductType(),
        ]));
    }

    function storeAction(){
        $elq = $this->elq;
        $elq->fill($_POST);

        if($elq->isValid()){
            $elq->status(post('status'));
            $elq->fill([
                'ddoc_number' => Gen::genDocsNumber($_POST['mdoc_id'], $_POST['mdep_id'], $_POST['mat_id'], $_POST['dpro_id']),
                'ddoc_create_date' => date('Y-m-d'),
            ]);
            $elq->save();

            $dcon = DtContactsElq::find($_POST['dcon_id']);
            if(!$dcon->DtClientsElq) $dcon->fill(['dcli_id' => $elq['dc_id']])->save();
            if(!$dcon->DtProjectsElq) $dcon->fill(['ddoc_id' => $elq['ddoc_id']])->save();

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
        $elq->fill($_POST);

        $field_rules = "ddoc_name,dcon_id";
        if($elq->isValid('only', $field_rules)){
            $elq->status(post('status'));
            $elq->fill(['ddoc_create_date' => date("Y-m-d", strtotime(str_replace("/", "-", $_POST['ddoc_create_date'])))]);
            $elq->save();

            $dcon = DtContactsElq::find($_POST['dcon_id']);
            if(!$dcon->DtClientsElq) $dcon->fill(['dcli_id' => $elq['dc_id']])->save();
            if(!$dcon->DtProjectsElq) $dcon->fill(['dpro_id' => $elq['dpro_id']])->save();

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

    function store_projectAction(){
        ModalStore::project();
    }

    function store_contactAction(){
        ModalStore::contact();
    }

    function get_doc_typeAction(){
        Select::setLabel(false);
        return response(Select::DocType($_POST['id']));
    }
    
    function deleteAction($id){
        $data = $this->elq->find($id);
        $data->delete();
    }
}