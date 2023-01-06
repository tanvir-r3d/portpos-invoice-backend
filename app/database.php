<?php

use Illuminate\Database\Capsule\Manager as DB;

$database = new DB;

$database->addConnection([
    'driver' => $_ENV['DB_CONNECTION'],
    'host' => $_ENV['DB_HOST'],
    'database' => $_ENV['DB_DATABASE'],
    'username' => $_ENV['DB_USERNAME'],
    'password' => $_ENV['DB_PASSWORD'],
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => '',
]);

// Make this Capsule instance available globally via static methods... (optional)
$database->setAsGlobal();

// Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
$database->bootEloquent();