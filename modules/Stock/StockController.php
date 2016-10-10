<?php

namespace Modules\Stock;

use lib\Module\Controller;
use Modules\Product\ProductModel;

class StockController extends Controller {

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
        // Todo: set right N# of store as const
        if ($row['store'] == 100)
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
        echo "\nAdd {$row['qty']} to {$row['sku']} store {$row['store']}";
    }

    public function doMinQty ($row)
    {
        echo "\nMin {$row['qty']} to {$row['sku']} store {$row['store']}";
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