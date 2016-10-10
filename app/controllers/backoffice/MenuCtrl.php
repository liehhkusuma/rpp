<?php
class MenuCtrl extends AdminCtrl{

    protected $ctrl = __CLASS__;
	protected $page = "bo_menu";
	protected $view = "menu";
	protected $elq = "BoMenuElq";

    function listAction($paging = ""){
        $_SESSION['list_page'] = current_url();
        // Searching
        $elq = $this->elq->orderBy('bm_parent_id', 'asc')->orderBy('bm_order', 'asc');
        if($keyword = get('keyword')){
            $elq = $elq->like("bm_name,bm_link", $keyword);
        }
        // Get Data
        $data = $elq->paginate();
        if(!$data->count() && $paging > 1) redirect_route(__CLASS__.':list', ['paging' => $paging - 1]);

        // Create Parent Name
        foreach ($data->data() as $key => $row) {
            $parent = BoMenuElq::where('bm_id', "=", $row['bm_parent_id'])->first();
            if($parent)
                $field['parent_name'] = $parent['bm_name'];
            else
                $field['parent_name'] = "Root";
            $data->setData($key, $field);
        }

        return view('backoffice.'.$this->view.'-list',array_merge($this->view_share(),[
        	'data' => $data,
            'nav_sidebar' => 'user',
            'sidebar' => 'menu',
        ]));
    }

    function addAction(){
        $parent = $this->elq->where(['bm_parent_id' => 0])->get();

        return view('backoffice.'.$this->view.'-add', array_merge($this->view_share(),[
            'validation' => $this->elq->jqv(),
            'nav_sidebar' => 'user',
            'sidebar' => 'menu',
            'parent_select' => getSelect($parent, 'bm_id', 'bm_name', ['root' => "Root"])
        ]));
    }

    function editAction($id){
        $elq = $this->elq->find($id);
        $parent = $this->elq->where(['bm_parent_id' => 0])->get();

        return view('backoffice.'.$this->view.'-edit', array_merge($this->view_share(),[
            'id' => $id,
            'row' => $elq,
            'status' => $elq->status(),
            'validation' => $this->elq->jqv(),
            'nav_sidebar' => 'user',
            'sidebar' => 'menu',
            'parent_select' => getSelect($parent, 'bm_id', 'bm_name', ['root' => "Root"])
        ]));
    }

    function storeAction(){
        $this->elq->fill($_POST);
        if($this->elq->isValid()){
            $this->elq->status(post('status'));
            $this->elq->save();

            return response([
                'info' => UI::alert(lang_var('gen.add_success', ['type' => $this->pages['type']]), 'success'),
                'redirect' => route($this->ctrl.":list"),
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

        $field_rules = "bm_name,bm_link";
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