<?php
class UserCtrl extends AdminCtrl{

    protected $ctrl = __CLASS__;
	protected $page = "bo_users";
	protected $view = "users";
	protected $elq = "BoUsersElq";

    function listAction($paging = ""){
        $_SESSION['list_page'] = current_url();
        // Searching
        /*->where('bu_level', '!=', 0)->where('bu_level', '!=', 1)*/
        $elq = $this->elq;
        if($keyword = get('keyword')){
            $elq = $elq->like("bu_real_name,bu_no_regis", $keyword);
        }
        // Get Data
        $data = $elq->paginate();
        if(!$data->count() && $paging > 1) redirect_route(__CLASS__.':list', ['paging' => $paging - 1]);

        return view('backoffice.'.$this->view.'-list',array_merge($this->view_share(),[
        	'data' => $data,
            'nav_sidebar' => 'user',
            'sidebar' => 'user',
        ]));
    }

    function addAction(){
        return view('backoffice.'.$this->view.'-add', array_merge($this->view_share(),[
            'lang' => $this->elq->lang(),
            'validation' => $this->elq->jqv(),
            'module_access_select' => getSelect('bo_user_level', 'bul_id', 'bul_level_name'),
            'nav_sidebar' => 'user',
            'sidebar' => 'user',
        ]));
    }

    function editAction($id){
        $elq = $this->elq->find($id);
        // return response($elq);
        return view('backoffice.'.$this->view.'-edit', array_merge($this->view_share(),[
            'id' => $id,
            'row' => $elq,
            'status' => $elq->status(),
            'validation' => $this->elq->jqv('ignore', 'bu_passwd'),
            'module_access_select' => getSelect('bo_user_level', 'bul_id', 'bul_level_name'),
            'nav_sidebar' => 'user',
            'sidebar' => 'user',
        ]));
    }

    function storeAction(){
        // Check Initial Exist
        if(BoUsersElq::where(['bu_init' => post('bu_init')])->first()){
            return response(['pAlert' => [
                'title' => lang('gen.notif'),
                'msg' => lang_var('validation.unique', ['attribute' => BoUsersElq::lang('bu_init')]),
            ]]);
        }
        if(BoUsersElq::where(['bu_name' => post('bu_name')])->first()){
            return response(['pAlert' => [
                'title' => lang('gen.notif'),
                'msg' => lang_var('validation.unique', ['attribute' => BoUsersElq::lang('bu_name')]),
            ]]);
        }

        $this->elq->fill($_POST);
        if($this->elq->isValid()){
            $this->elq->fill([
                    'bu_passwd' => sha1(post('bu_passwd')),
                    'bu_create_date' => date("Y-m-d H:i:s"),
                ]);
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
        // Check Initial Exist
        if(BoUsersElq::where('bu_id', '!=', $id)->where(['bu_init' => post('bu_init')])->first()){
            return response(['pAlert' => [
                'title' => lang('gen.notif'),
                'msg' => lang_var('validation.unique', ['attribute' => BoUsersElq::lang('bu_init')]),
            ]]);
        }
        if(BoUsersElq::where('bu_id', '!=', $id)->where(['bu_name' => post('bu_name')])->first()){
            return response(['pAlert' => [
                'title' => lang('gen.notif'),
                'msg' => lang_var('validation.unique', ['attribute' => BoUsersElq::lang('bu_name')]),
            ]]);
        }

    	$elq->fill($_POST);

        $field_rules = "bu_real_name,bu_no_regis";
        if($elq->isValid('only', $field_rules)){
        	$elq->status(post('status'));
            if(empty($_POST['bu_passwd'])) 
                unset($elq->bu_passwd);
            else
                $elq->fill(['bu_passwd' => sha1($_POST['bu_passwd'])]);

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