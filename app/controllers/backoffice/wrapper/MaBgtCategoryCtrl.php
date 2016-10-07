<?php
class MaBgtCategoryCtrl extends AdminCtrl{

    protected $ctrl = __CLASS__;
    protected $page = "mr_budget_category";
	protected $view = "ma-budget-category";
	protected $elq = "MrBgtCategoryElq";
	
    function listAction($paging = ""){
        $_SESSION['list_page'] = current_url();
        $data = $this->elq->paginate();
        if(!$data->count() && $paging > 1) redirect_route(__CLASS__.':list', ['paging' => $paging - 1]);

        return view('backoffice.'.$this->view.'-list',array_merge($this->view_share(),[
            'data' => $data,
        ]));
    }

    function addAction(){
        return view('backoffice.'.$this->view.'-add', array_merge($this->view_share(),[
            'validation' => $this->elq->jqv(),
        ]));
    }

    function editAction($id){
        $elq = $this->elq->find($id);

        return view('backoffice.'.$this->view.'-edit', array_merge($this->view_share(),[
            'id' => $id,
            'row' => $elq,
            'status' => $elq->status(),
            'validation' => $this->elq->jqv(),
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
        $this->elq = $this->elq->find($id);
        $this->elq->fill($_POST);
        if($this->elq->isValid()){
            $this->elq->status(post('status'));
            $this->elq->save();

            return response([
                'info' => UI::alert(lang_var('gen.update_success', ['type' => $this->pages['type']]), 'success'),
                'redirect' => session('list_page'),
                'delay' => 2000,
            ]);
        }
        return response(['pAlert' => [
            'title' => lang('gen.validation_error'),
            'msg' => $this->elq->ulError(),
        ]]);
    }

    function deleteAction($id){
        $data = $this->elq->find($id);
        $data->delete();
    }
}