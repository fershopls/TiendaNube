<?php

require_once realpath(__DIR__) . '/bootstrap.php';


use lib\App\App;
use Phine\Path\Path;

// TODO: Register `apps` $name => $app and call it from $app::register($name);
$settings = include(Path::join([APP_PATH, 'support/config.php']));
$app = new App($settings);
$app->register('product');
#$app->register('category');
#$app->register('customer');
#$app->register('order');
$app->run();

echo "Done. " . floor((int)(microtime(true) -  APP_TIME_START)/60) . " min " . floor((int)(microtime(true)-APP_TIME_START)%60) . " sec";