<?php

use Illuminate\Database\Capsule\Manager as Capsule;

/**
 * Configure the database and boot Eloquent
 */

class DB extends Capsule{}

$DB = new DB;

$DB->addConnection(array(
    'driver'    => config('db.driver'),
    'host'      => config('db.host'),
    'database'  => config('db.database'),
    'username'  => config('db.username'),
    'password'  => config('db.password'),
    'charset'   => config('db.charset'),
    'collation' => config('db.collation'),
    'prefix'    => config('db.prefix')
));

$DB->setAsGlobal();

$DB->bootEloquent();

