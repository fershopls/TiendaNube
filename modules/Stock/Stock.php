<?php

namespace Modules\Stock;

use lib\Handler\StockHandler;
use lib\Module\Module;
use Modules\Stock\StockController;

class Stock extends Module {

    public function getHandlerClass()
    {
        return StockHandler::class;
    }

    public function getControllerClass()
    {
        return StockController::class;
    }

    public function getModuleDependencies()
    {
        return array(
            'app',
            'api'
        );
    }

    public function getModuleRoutes ()
    {
        return array(
            "set_qty" => "ifi",
            "add_qty" => "ent",
            "min_qty" => "sal",
        );
    }

    public function getModuleAttributes ()
    {
        return array(
            'qty' => '1|12',
            'sku' => '14|16',
            'store' => '31|5',
        );
    }

}