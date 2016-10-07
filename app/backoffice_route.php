<?php

/* Authentication */
app()->get("/", "AuthCtrl:index");
app()->post("/dologin", "AuthCtrl:dologin");
app()->get("/dologout", "AuthCtrl:dologout");

/* Dashboard */
app()->get("/dashboard", "DashboardCtrl:list");

/*
| User Management
*/
// Users List
app()->get("/users(/:paging)", "UserCtrl:list");
app()->get("/users/detail/:id", "UserCtrl:detail");
app()->get("/users/add", "UserCtrl:add");
app()->get("/users/edit/:id", "UserCtrl:edit");
app()->post("/users/store", "UserCtrl:store");
app()->post("/users/update/:id", "UserCtrl:update");
app()->post("/users/delete/:id", "UserCtrl:delete");

// Registran List
app()->get("/registran(/:paging)", "RegistranCtrl:list");
app()->get("/registran/detail/:id", "RegistranCtrl:detail");
app()->get("/registran/add", "RegistranCtrl:add");
app()->get("/registran/edit/:id", "RegistranCtrl:edit");
app()->post("/registran/store", "RegistranCtrl:store");
app()->post("/registran/update/:id", "RegistranCtrl:update");
app()->post("/registran/delete/:id", "RegistranCtrl:delete");