<?php

namespace Modules\Category;

use lib\Module\Module;
use Modules\Category\CategoryController;

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
            "code" => "2|10",
            "name" => "13|30",
        );
    }

}