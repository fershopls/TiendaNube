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
            "controller"=> "1|1",
            "sku"       => "2|16",
            "sku2"      => "19|16",
            "name"      => "36|40",
            "name2"     => "77|40",
            "category"  => "118|10",
            "unity_v"   => "129|3",
            "price"     => "133|12",
            "tax"       => "146|6",
            "create_on" => "153|11",
            "update_on" => "165|11",
            "stock"     => "177|12",
        );
    }

}