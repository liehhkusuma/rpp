<?php

/* Javascript Lang */
app()->get('/javascript_lang', function(){
	$res = "";
	foreach (config('jsvar') as $var => $val) {
		$val = is_array($val) ? json_encode($val) : "'$val'";
		$res .= "$$var = $val;";
	}
	$resp = app()->response();
	$resp['Content-Type'] = 'application/javascript';
	return $resp->body($res);
})->name('config:javascript');

app()->group("/backoffice", function(){
	require_once("backoffice_route.php");
});

/* Ajax */
app()->post("/ajax/:method", "AjaxCtrl:index");