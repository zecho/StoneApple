<?php #generate_model.php

require __DIR__.'/autoload.php';

$env = 'dev';
$application = new \StoneApple\Application($env);

$scan = new Pomm\Tools\ScanSchemaTool(array(
    'schema' => 'public',
    'database' => $application['pomm']->getDatabase(),
    'prefix_dir' => __DIR__.'/Model',
));
$scan->execute();
