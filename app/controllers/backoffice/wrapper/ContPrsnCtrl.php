<?php
class ContPrsnCtrl extends AdminCtrl{

    protected $ctrl = __CLASS__;
    protected $page = "dt_contacts";
    protected $view = "prod-contact";
    protected $elq = "DtContactsElq";

    function listAction($paging = ""){
        $_SESSION['list_page'] = current_url();
        // Searching
        $elq = $this->elq;
        if($keyword = get('keyword')){
            $elq = $elq->like("dcon_name,dcon_phone_1,dcon_phone_2,dcon_email,dcon_position", $keyword);
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
            'data' => Select::ContactPerson(),
        ]));
    }

    function editAction($id){
        $elq = $this->elq->find($id);

        return view('backoffice.'.$this->view.'-edit', array_merge($this->view_share(),[
            'id' => $id,
            'row' => $elq,
            'status' => $elq->status(),
            'validation' => $this->elq->jqv(),
            'data' => Select::ContactPerson(),
        ]));
    }

    function storeAction(){
        $this->elq->fill($_POST);
        if($this->elq->isValid()){
            $this->elq->status(post('status'));
            $this->elq->save();

            return response([
                'info' => UI::alert(lang_var('gen.add_success', ['type' => $this->pages['type']]), 'success'),
                'redirect' => session('list_page'),
                'delay' => 2000,
            ]);
        }
        return response(['pAlert' => [
            'title' => lang('gen.validation_error'),
            'msg' => $this->elq->ulError(),
        ]]);
    }

    function updateAction($id){
        $elq = $this->elq->find($id);
        $elq->fill($_POST);

        $field_rules = "dcon_name";
        if($elq->isValid('only', $field_rules)){
            $elq->status(post('status'));
            $elq->save();

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
            $dcon->save();
            return response('$(".modal").modal("hide");
            $("[name=\'dcon_id\']").append("<option value=\''.$dcon['dcon_id'].'\' selected>'.$dcon['dcon_name'].'</option>").trigger("chosen:updated");');
        }
        return response(['pAlert' => [
            'title' => lang('gen.validation_error'),
            'msg' => $elq->ulError(),
        ]]);
    }
    
    function deleteAction($id){
        $data = $this->elq->find($id);
        $data->delete();
    }
}