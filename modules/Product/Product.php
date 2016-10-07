<?php

namespace Modules\Product;

use lib\Module\Module;
use Modules\Product\ProductController;

class Product extends Module {

    public function getControllerClass()
    {
        return ProductController::class;
    }

    public function getModuleDependencies()
    {
        return array(
            'app',
            'api',
        );
    }

    public function getModuleRoutes ()
    {
        return array(
            "upload_product" => "@",
            "remove_product" => "-",
        );
    }

    public function getModuleAttributes ()
    {
        return array(
            "controller" => "1|1",
            "sku"   => "2|16",
            "name"  => "36|40",
            "about" => "77|40",
            "category"  => "118|11",
            "price"     => "133|12",
            "stock"     => "189|12"
        );
    }

}