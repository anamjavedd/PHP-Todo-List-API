<?php
$app = [];


//storing the data of require 'config.php' in config key
App::bind('config', require 'config.php');

$config = App::get('config');

App::bind('database', new QueryBuilder(

    Connection::make(App::get('config')['database'])  // Connection::make($app['config']['database'])

)) ;
