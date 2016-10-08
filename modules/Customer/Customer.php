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
            "upload_customer" => "$",
            "remove_customer" => "-",
        );
    }

    public function getModuleAttributes ()
    {
        return array(
            'line1' => [ // Line 1
                "controller" => "1|1",
                "id" => "2|10",
                "type" => "13|20",
                "name" => "34|80",
                "nick" => "115|10",
                "email" => "126|40",
                "RFC" => "167|15",
            ],
            'line2' => [ // Line 2
                "controller" => "1|1",
                "id" => "2|10",
                "address" => "13|30",
                "next" => "44|10",
                "nint" => "55|10",
                "colonia" => "66|40",
                "city" => "107|40",
                "state" => "148|15",
                "postal" => "164|7",
                "country" => "172|10",
            ]
        );
    }

}