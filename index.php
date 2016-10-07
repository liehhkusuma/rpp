<?php
/*
| List library composer :
| Email => "phpmailer/phpmailer": "dev-master"
| 
*/

/**
* Set application Evironment
* value : true or false
*/
define('PROD', false);

// Autoload Composer
require __DIR__.'/vendor/autoload.php';

// Boling System
require __DIR__.'/system/System.php';

// Routing
require __DIR__.'/app/route.php';

app()->run();