<?php

require_once realpath(__DIR__) . '/bootstrap.php';

// Core
use lib\App\App;
use Phine\Path\Path;
// Modules
use Modules\Product\Product;
use Modules\Customer\Customer;
use Modules\Category\Category;

$settings = include(Path::join([APP_PATH, 'support/config.php']));
$app = new App($settings);
// Register Modules
# $app->register(Product::class);
# $app->register(Customer::class);
$app->register(Category::class);

$app->run();

echo "Done. " . floor((int)(microtime(true) -  APP_TIME_START)/60) . " min " . floor((int)(microtime(true)-APP_TIME_START)%60) . " sec";