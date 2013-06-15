<?php 

$loader = require_once __DIR__.'/../../vendor/autoload.php';

// the StoneApple sources
$loader->add(null, __DIR__.'/../');
// load our Model seperately bc of different namespace
$loader->add(null, __DIR__.'/Model');