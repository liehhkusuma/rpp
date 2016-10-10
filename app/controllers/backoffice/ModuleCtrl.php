<?php
class ModuleCtrl extends AdminCtrl{

    protected $ctrl = __CLASS__;
	protected $page = "bo_module";
	protected $view = "module";
	protected $elq = "BoModuleElq";

    function listAction($paging = ""){
        $_SESSION['list_page'] = current_url();
        // Searching
        $elq = $this->elq;
        if($keyword = get('keyword')){
            $elq = $elq->like("bmd_name,bmd_mod_name,bmd_desc", $keyword);
        }
        // Get Data
        $data = $elq->paginate();
        if(!$data->count() && $paging > 1) redirect_route(__CLASS__.':list', ['paging' => $paging - 1]);

        return view('backoffice.'.$this->view.'-list',array_merge($this->view_share(),[
        	'data' => $data,
            'nav_sidebar' => 'user',
            'sidebar' => 'module',
        ]));
    }

    function addAction(){
        return view('backoffice.'.$this->view.'-add', array_merge($this->view_share(),[
            'validation' => $this->elq->jqv(),
            'nav_sidebar' => 'user',
            'sidebar' => 'module',
        ]));
    }

    function editAction($id){
        $elq = $this->elq->find($id);

        return view('backoffice.'.$this->view.'-edit', array_merge($this->view_share(),[
            'id' => $id,
            'row' => $elq,
            'status' => $elq->status(),
            'validation' => $this->elq->jqv(),
            'nav_sidebar' => 'user',
            'sidebar' => 'module',
        ]));
    }

    function storeAction(){
        $elq = $this->elq;
        $elq->fill($_POST);
        if($elq->isValid()){
            $elq->status(post('status'));
            $elq->save();

            return response([
                'info' => UI::alert(lang_var('gen.add_success', ['type' => $this->pages['type']]), 'success'),
                'redirect' => session('list_page'),
                'delay' => 2000,
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

        $field_rules = "bmd_name,bmd_mod_name,bmd_desc";
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

    function deleteAction($id){
        $data = $this->elq->find($id);
        $data->delete();
    }
}