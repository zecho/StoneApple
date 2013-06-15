<?php

$loader = require_once __DIR__.'/../../vendor/autoload.php';

use Silex\Provider\HttpCacheServiceProvider;
use Silex\Provider\TwigServiceProvider;

// -----------------------------------------------
// Pomm model

$loader->add(null, __DIR__."/Model");


// -----------------------------------------------
// App & config

$app = new Silex\Application();
$env = isset($env) ? $env : 'prod';
if(in_array($env,array('dev','test','prod'))) {
    require __DIR__. "/../../resources/config/$env.php";
}


// -----------------------------------------------
// Services registering

$app->register(new HttpCacheServiceProvider()); // see ./resources/config
$app->register(new TwigServiceProvider(), array(
    'twig.path'       => __DIR__.'/view',
    'twig.class_path' => __DIR__.'/../vendor/twig/lib',
));
$app->register(
    new Pomm\Silex\PommServiceProvider(),
    array(
        'pomm.class_path' => __DIR__.'/vendor/pomm',
        'pomm.databases' => array(
            'default' => array(
                'dsn' => $app['pomm.dns'],
    )))
);