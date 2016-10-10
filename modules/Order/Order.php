<?php

namespace Modules\Order;

use lib\Module\Module;
use lib\Handler\WriterHandler;
use Modules\Order\OrderController;
use lib\Util\PlainTextWriter;

class Order extends Module {

    public function getHandlerClass()
    {
        return WriterHandler::class;
    }

    public function getControllerClass()
    {
        return OrderController::class;
    }

    public function getModuleDependencies()
    {
        return array(
            'app',
            'api',
            'plain_writer' => PlainTextWriter::class,
        );
    }

    public function getModuleRoutes ()
    {
        return array(
            "fetch" => "run",
        );
    }

    public function getModuleAttributes ()
    {
        return array(
            'header' => [
                'folio' => '1|10',
                'date' => '12|8',
                'customer' => '22|10',
                'aditional' => '33|10',
                'store' => '44|5',
            ],
            'row' => [
                'qty' => '1|10',
                'sku' => '12|16',
                'price' => '29|14',
                'discount' => '44|6',
                'tax' => '51|6',
            ]
        );
    }

}