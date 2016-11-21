<?php

namespace Modules\Product;

use lib\Module\Module;
use Modules\Product\ProductController;
use Modules\Category\CategoryModel;

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
            'model' => CategoryModel::class,
        );
    }

    public function getModuleRoutes ()
    {
        return array(
            "create_product" => "@",
            "upload_product" => "%",
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
            "unity_v"   => "129|3",
            "price"     => "133|12",
            "tax"       => "146|6",
            "create_on" => "153|11",
            "update_on" => "165|11",
            "stock"     => "177|12",
            // Category
            "id_x"  => "118|2",
            "id_y"  => "121|3",
            "id_z"  => "125|3",
        );
    }

}