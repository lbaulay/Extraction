<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once __DIR__.'/../vendor/autoload.php';


$app = new Silex\Application();
$app->get('/', function () {
    return 'Hello world';
});
$app->get('/hello/{name}', function ($name) use ($app) {
    return 'Hello ' . $app->escape($name);
});

$app->run();
