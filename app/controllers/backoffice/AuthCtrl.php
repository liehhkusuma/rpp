<?php

class AuthCtrl extends BaseCtrl{

    public function indexAction(){
        if(static::user()) return redirect_route('DashboardCtrl:list');
        return view('backoffice.index',[]);
    }

    public function dologinAction(){
    	$username = $_POST['username'];
    	$password = $_POST['password'];

    	$bu = BoUsersElq::where('bu_name', '=', $username)->where('bu_passwd', '=', sha1($password))->first();
    	if($bu){
            CSRF::protect();
    		$_SESSION['auth_admin'] = $bu['bu_id'];

    		return response([
    			'info' => UI::alert(lang('gen.auth_success'), 'success'),
    			'redirect' => route('DashboardCtrl:list')
    		]);
    	}

    	return response([
            'info' => UI::alert(lang('gen.auth_fail'), 'danger'),
            'delay' => true
        ]);
    }

    public function dologoutAction(){
    	unset_session('auth_admin');
    	redirect_route(__CLASS__.':index');
    }

    static function user(){
    	return BoUsersElq::find(session('auth_admin'));
    }

    static function menuRole(){
        if(static::user()->bu_level == 0) return "super";
        return explode(",", static::user()->BoUserLevelElq['bul_menu_role']);
    }

    static function moduleRole(){
        if(static::user()->bu_level == 0) return "super";
        return explode(",", static::user()->BoUserLevelElq['bul_module_role']);
    }

    static function module($module = false){
        if(self::moduleRole() == "super") return true;

        $module = BoModuleElq::where(['bmd_mod_name' => $module])->first();
        if($module){
            return in_array($module['bmd_id'], self::moduleRole());
        }

        return false;
    }
}