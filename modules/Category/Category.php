<?php

namespace Modules\Category;

use lib\Module\Module;
use Modules\Category\CategoryController;
use Modules\Category\CategoryModel;

class Category extends Module {

    public function getControllerClass()
    {
        return CategoryController::class;
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
            "upload_category" => "@",
            "remove_category" => "-",
        );
    }

    public function getModuleAttributes ()
    {
        return array(
            "controller" => "1|1",
            "id_x" => "2|2",
            "id_y" => "5|3",
            "id_z" => "9|3",
            "name" => "13|30",
        );
    }

}