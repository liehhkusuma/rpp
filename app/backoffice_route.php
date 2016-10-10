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

// Registration List
app()->get("/registration(/:paging)", "RegistrationCtrl:list");
app()->get("/registration/detail/:id", "RegistrationCtrl:detail");
app()->get("/registration/add", "RegistrationCtrl:add");
app()->get("/registration/edit/:id", "RegistrationCtrl:edit");
app()->post("/registration/store", "RegistrationCtrl:store");
app()->post("/registration/update/:id", "RegistrationCtrl:update");
app()->post("/registration/delete/:id", "RegistrationCtrl:delete");

// Registration List
app()->get("/registration-user(/:paging)", "RegistrationUserCtrl:list");
app()->get("/registration-user/detail/:id", "RegistrationUserCtrl:detail");
app()->get("/registration-user/add", "RegistrationUserCtrl:add");
app()->get("/registration-user/edit/:id", "RegistrationUserCtrl:edit");
app()->post("/registration-user/store", "RegistrationUserCtrl:store");
app()->post("/registration-user/update/:id", "RegistrationUserCtrl:update");
app()->post("/registration-user/delete/:id", "RegistrationUserCtrl:delete");
