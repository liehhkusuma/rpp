<?php
class ModuleAccessCtrl extends AdminCtrl{

    protected $ctrl = __CLASS__;
	protected $page = "bo_user_level";
	protected $view = "module-access";
	protected $elq = "BoUserLevelElq";

    function listAction($paging = ""){
        $_SESSION['list_page'] = current_url();
        // Searching
        $elq = $this->elq;
        if($keyword = get('keyword')){
            $elq = $elq->like("bul_level_name", $keyword);
        }
        // Get Data
        $data = $elq->paginate();
        if(!$data->count() && $paging > 1) redirect_route(__CLASS__.':list', ['paging' => $paging - 1]);

        return view('backoffice.'.$this->view.'-list',array_merge($this->view_share(),[
        	'data' => $data,
            'nav_sidebar' => 'user',
            'sidebar' => 'access',
        ]));
    }

    function addAction(){
        return view('backoffice.'.$this->view.'-add', array_merge($this->view_share(),[
            'validation' => $this->elq->jqv(),
            'menu_role_select' => getSelect('bo_menu', 'bm_id', 'bm_name'),
            'module_role_select' => getSelect('bo_module', 'bmd_id', 'bmd_name'),
            'nav_sidebar' => 'user',
            'sidebar' => 'access',
        ]));
    }

    function editAction($id){
        $elq = $this->elq->find($id);

        return view('backoffice.'.$this->view.'-edit', array_merge($this->view_share(),[
            'id' => $id,
            'row' => $elq,
            'status' => $elq->status(),
            'validation' => $this->elq->jqv(),
            'menu_role_select' => getSelect('bo_menu', 'bm_id', 'bm_name'),
            'module_role_select' => getSelect('bo_module', 'bmd_id', 'bmd_name'),
            'nav_sidebar' => 'user',
            'sidebar' => 'access',
        ]));
    }

    function storeAction(){
        $_POST['bul_menu_role'] = post('bul_menu_role') ? implode(',', $_POST['bul_menu_role']) : "";
        $_POST['bul_module_role'] = post('bul_module_role') ? implode(',', $_POST['bul_module_role']) : "";

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
        $_POST['bul_menu_role'] = post('bul_menu_role') ? implode(',', $_POST['bul_menu_role']) : "";
        $_POST['bul_module_role'] = post('bul_module_role') ? implode(',', $_POST['bul_module_role']) : "";

        $elq = $this->elq->find($id);
    	$elq->fill($_POST);

        $field_rules = "bul_level_name";
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