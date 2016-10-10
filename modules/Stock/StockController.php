<?php

namespace Modules\Stock;

use lib\Module\Controller;
use Modules\Product\ProductModel;

class StockController extends Controller {

    const MAGENTO_STORE = 100;
    protected $model;

    public function model()
    {
        if (!isset($this->model))
        {
            $this->model = new ProductModel($this->dependency('api'));
        }
        return $this->model;
    }

    public function doSetQty ($row)
    {
        if ($this->validate($row))
            return false;

        $me = $this->getProduct($row['sku']);
        if (!$me) return false;

        echo "\nUpdating product({$row['sku']}) with id {$me->id} to " . $row['qty'];
        $product = [
            'product' => array(
                'extension_attributes' => array(
                    'stock_item' => [
                        'qty' => $row['qty'],
                        'isInStock' => $row['qty']!='0.00'?true:false,
                    ]
                )
            ),
        ];

        $res = $this->dependency('api')->request('PUT /products/'.urlencode($row['sku']), $product);

        if (isset($res->id))
            return true;
        else
            return false;
            // Todo: error
    }

    public function doAddQty ($row)
    {
        if ($this->validate($row))
            return false;
        echo "\nAdd {$row['qty']} to {$row['sku']} store {$row['store']}";
        return $this->addProductQty($row['sku'], $row['qty']);
    }

    public function doMinQty ($row)
    {
        if ($this->validate($row))
            return false;
        echo "\nMin {$row['qty']} to {$row['sku']} store N#{$row['store']}";
        return $this->addProductQty($row['sku'], $row['qty']*-1);
    }
    
    public function addProductQty ($sku, $qty)
    {
        $me = $this->getProduct($sku);
        if (!$me) return false;
        $_qty = $me->extension_attributes->stock_item->qty + $qty;
        $_qty = $_qty<0?0:$_qty;

        $product = [
            'product' => array(
                'extension_attributes' => array(
                    'stock_item' => [
                        'qty' => $_qty,
                        'isInStock' => $_qty?true:false,
                    ]
                )
            ),
        ];

        return $this->dependency('api')->request('PUT /products/'.urlencode($sku), $product);
    }

    public function validate ($row)
    {
        return $row['store'] == self::MAGENTO_STORE && preg_match("/^\d+(\.\d+$)?/i", $row['qty']);
    }

    public function getProduct ($sku)
    {
        $product = $this->model()->getProduct ($sku);
        if (!isset($product->id))
        {
            // Todo: error
            return false;
        }
        return $product;
    }

}