<?php

namespace Modules\Order;

use lib\Module\Controller;
use lib\Module\Module;
use Modules\Stock\StockController;
use Phine\Path\Path;

class OrderController extends Controller {

    public function doFetch(Module $module, $stringPath)
    {
        $invoices = $this->getInvoices();
        $rules = $module->getModuleAttributes();

        foreach ($invoices->items as $item)
        {
            $data = array([
                '__type__' => 'header',
                'folio' => $item->increment_id,
                'date' => $this->getDateField($item->created_at),
                'customer' => $item->billing_address->email,
                'aditional' => '99999',
                'store' => StockController::MAGENTO_STORE,
                'ctrl' => '@'
            ]);

            foreach ($item->items as $product)
            {
                $arrayProduct = array(
                    '__type__' => 'row',
                    'qty' => $this->getIntField($product->qty_ordered),
                    'sku' => $product->sku,
                    'price' => $this->getIntField($product->price),
                    'discount' => '40%',
                    'tax' => '16%',
                    'ctrl' => '@'
                );
                $data[] = $arrayProduct;
            }

            $order = $this->createOrder($data, $rules);
            $this->dumpOrder($stringPath, $item->increment_id, $order);
        }

    }

    public function getInvoices ()
    {
        $api = $this->dependency('api');
        return $api->request('GET /orders', ['searchCriteria'=>0]);
    }

    public function createOrder ($data, $rules)
    {
        $writer = $this->dependency('plain_writer');
        return $writer->load($data)->toString($rules);
    }

    public function dumpOrder ($stringPath, $intId, $stringContent)
    {
        $filename = date('Ymd\_').$intId.'.txt';
        file_put_contents(Path::join([$stringPath, $filename]), $stringContent);
    }

    public function getDateField ($date)
    {
        $time = preg_replace('/^(\d{4})\-(\d{2})\-(\d{2})\s(\d{2})\:(\d{2})\:(\d{2})$/i', '$3.$2.$1', $date);
        $time = strtotime($time);

        return date('d/m/y', $time);
    }

    public function getIntField ($int)
    {
        $int = round($int, 2);
        if (! preg_match('/^\d+\.\d+$/i', $int))
            $int = $int . '.00';
        return $int;
    }

}