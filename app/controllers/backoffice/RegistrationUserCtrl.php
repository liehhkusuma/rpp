<?php
class RegistrationUserCtrl extends AdminCtrl{

    protected $ctrl = __CLASS__;
	protected $page = "bo_registration_user";
	protected $view = "registration-user";
	protected $elq = "RegistrationElq";

    function listAction($paging = ""){
        $_SESSION['list_page'] = current_url();
        // Searching
        $elq = $this->elq;
        if($keyword = get('keyword')){
            $elq = $elq->like("p_no_regis", $keyword);
        }
        // Get Data
        $data = $elq->paginate();
        if(!$data->count() && $paging > 1) redirect_route(__CLASS__.':list', ['paging' => $paging - 1]);

        // Create Parent Name
        foreach ($data->data() as $key => $row) {
            $field['registran_code'] = "No Registran";
            if($row->BoUsersElq){
                $field['registran_code'] = $row->BoUsersElq['bu_no_regis'].' - '.$row->BoUsersElq['bu_real_name'];
            }
            $data->setData($key, $field);
        }

        return view('backoffice.'.$this->view.'-list',array_merge($this->view_share(),[
        	'data' => $data,
            'nav_sidebar' => 'registration_user',
            'sidebar' => 'registration_user',
        ]));
    }

    function addAction(){
        return view('backoffice.'.$this->view.'-add', array_merge($this->view_share(),[
            'validation' => $this->elq->jqv(),
            'nav_sidebar' => 'registration',
            'sidebar' => 'registration',
            'module_access_select' => getSelect(BoUsersElq::where('bu_level', '!=', 0)->where('bu_level', '!=', 1)->active()->get(), 'bu_id', 'bu_real_name')
        ]));
    }

    function editAction($id){
        $elq = $this->elq->find($id);
        return view('backoffice.'.$this->view.'-edit', array_merge($this->view_share(),[
            'id' => $id,
            'row' => $elq,
            'validation' => $this->elq->jqv(),
            'nav_sidebar' => 'registration',
            'sidebar' => 'registration',
            'module_access_select' => getSelect(BoUsersElq::where('bu_level', '!=', 0)->where('bu_level', '!=', 1)->active()->get(), 'bu_id', 'bu_real_name')
        ]));
    }

    function storeAction(){
        $this->elq->fill($_POST);
        if($this->elq->isValid()){
            // $this->elq->status(post('status'));
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

        $field_rules = "p_no_regis";
        if($elq->isValid('only', $field_rules)){
        	// $elq->status(post('status'));
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