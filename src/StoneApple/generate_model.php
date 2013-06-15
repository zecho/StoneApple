<?php #generate_model.php

$env = 'dev';

require __DIR__.'/bootstrap.php';

$scan = new Pomm\Tools\ScanSchemaTool(array(
    'schema' => 'public',
    'database' => $app['pomm']->getDatabase(),
    'prefix_dir' => __DIR__.'/Model',
));
$scan->execute();
