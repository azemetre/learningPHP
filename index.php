<?php
require 'vendor/autoload.php';
date_default_timezone_set('America/New_York');

// $log = new Monolog\Logger('name');
// $log->pushHandler(new StreamHandler('app.txt', Logger::WARNING));
// $log->addWarning('Oh Noes.');

$app = new \Slim\Slim();

$app->get('/', function(){
    echo 'Hello, this is the home page.';
});

$app->get('/contact', function(){
    echo 'Feel free to contact us.';
});

$app->run();
?>