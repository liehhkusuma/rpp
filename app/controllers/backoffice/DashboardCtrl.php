<?php
class DashboardCtrl extends AdminCtrl{

	protected $ctrl = __CLASS__;

    function listAction(){
        return view('backoffice.dashboard', [
        	// 'sidebar' => $this->sideBar(),
        	'title' => "Galih Kusuma",
        	'sidebar' => 'dashboard',
        	'nav_sidebar' => 'dashboard',
        ]);
    }
}