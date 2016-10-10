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

/* Users */
app()->get("/users-list", function(){
	return view("backoffice.users-list");
});

app()->get("/users-add", function(){
	return view("backoffice.users-add");
});

app()->get("/users-edit", function(){
	return view("backoffice.users-edit");
});

/* Menu */
app()->get("/menu-list", function(){
	return view("backoffice.menu-list");
});

app()->get("/menu-add", function(){
	return view("backoffice.menu-add");
});

app()->get("/menu-edit", function(){
	return view("backoffice.menu-edit");
});

/* Module */
app()->get("/module-list", function(){
	return view("backoffice.module-list");
});

app()->get("/module-add", function(){
	return view("backoffice.module-add");
});

app()->get("/module-edit", function(){
	return view("backoffice.module-edit");
});

/* Module Access */
app()->get("/module-access-list", function(){
	return view("backoffice.module-access-list");
});

app()->get("/module-access-add", function(){
	return view("backoffice.module-access-add");
});

app()->get("/module-access-edit", function(){
	return view("backoffice.module-access-edit");
});

/* Slide */
app()->get("/slide-list", function(){
	return view("backoffice.slide-list");
});

app()->get("/slide-add", function(){
	return view("backoffice.slide-add");
});

app()->get("/slide-edit", function(){
	return view("backoffice.slide-edit");
});

/* Static */
app()->get("/static-list", function(){
	return view("backoffice.static-list");
});

app()->get("/static-edit", function(){
	return view("backoffice.static-edit");
});

/* Gallery */
app()->get("/gallery-list", function(){
	return view("backoffice.gallery-list");
});

app()->get("/gallery-add", function(){
	return view("backoffice.gallery-add");
});

app()->get("/gallery-edit", function(){
	return view("backoffice.gallery-edit");
});


/* Video Iframe */
app()->get("/videoiframe", function(){
	return view('layout.video', ['video' => get('video')]);
})->name('videoiframe');

/* Ajax */
app()->post("/ajax/:method", "AjaxCtrl:index");