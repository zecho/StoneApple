<?php

ini_set('display_errors', 1);
error_reporting(-1);

require __DIR__.'/../vendor/autoload.php';

$env = "dev";
$application = new \StoneApple\Application($env);
$application->run();
