<?php

namespace Modules\Customer;

use lib\Module\Module;
use Modules\Customer\CustomerController;

class Customer extends Module {

    public function getControllerClass()
    {
        return CustomerController::class;
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
            "upload_customer" => "@",
            "remove_customer" => "-",
        );
    }

    public function getModuleAttributes ()
    {
        return array(
            [ // Line 1
                "controller" => "1|1",
                "id" => "2|8",
                "name" => "12|79",
                "address" => "92|49",
                "province" => "142|39",
                "region" => "182|14",
                "city" => "197|4",
            ],
            [ // Line 2
                "telephone" => "2|52",
                "email" => "62|39",
                "postcode" => "55|6"
            ]
        );
    }

}