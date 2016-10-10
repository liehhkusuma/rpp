<?php

class AdminCtrl extends BaseCtrl{

	function __construct(){
		if(!AuthCtrl::user()) redirect_route('AuthCtrl:index');

		$this->pages = !empty($this->page) ? config('config.page.'.$this->page) : "";
		$this->lang = !empty($this->page) ? lang('field.'.$this->page) : "";
		$this->elq = !empty($this->elq) ? new $this->elq : "";
	}

	function view_share(){
		return [
			'page' => $this->pages,
			'pagename' => $this->page,
			'ctrl' => $this->ctrl,
			// 'sidebar' => $this->sidebar(),
			'lang' => $this->lang,
			'count_data' => $this->elq->count(),
		];
	}

    function sidebar(){
		$get = BoMenuElq::orderBy('bm_parent_id', 'asc')->orderBy('bm_order', 'asc');
    	if(AuthCtrl::menuRole() == 'super')
			$get = $get->active()->get();
    	else
			$get = $get->whereIn('bm_id', AuthCtrl::menuRole())->active()->get();
		$menu_uri = uri_segment(1);
		$menu = [];

		foreach($get as $row){
			if($row['bm_parent_id'] == 0){
				$menu_link = $row['bm_link'] != "#" ? route($row['bm_link']) : "javascript:;";
				$menu_active = '';
				$url_ctrl = explode(':', $row['bm_link']);

				if($this->ctrl == $url_ctrl[0]){
					$menu_active = 'active';
				}

				$menu['parent'][$row['bm_id']] = array_merge($row->toArray(),[
					'link' => $menu_link,
					'active' => $menu_active,
				]);
			}else{
				if($row['bm_link'] != "#")
				$menu_link = $row['bm_link'] != "#" ? route($row['bm_link']) : "javascript:;";
				$menu_active = "";
				$url_ctrl = explode(':', $row['bm_link']);

				if($this->ctrl == $url_ctrl[0]){
					$menu['parent'][$row['bm_parent_id']]['active'] = 'active';
					$menu_active = 'class="active"';
				}
				$menu['sub_menu'][$row['bm_parent_id']][$row['bm_id']] = array_merge($row->toArray(),[
					'link' => $menu_link,
					'active' => $menu_active,
				]);
			}
		}
		return $menu;
	}

}