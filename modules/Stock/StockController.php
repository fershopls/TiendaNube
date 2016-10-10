<?php

namespace Modules\Stock;

use lib\Module\Controller;

class StockController extends Controller {

    public function doSetQty ($row)
    {
        echo "\nSet {$row['qty']} to {$row['sku']} store {$row['store']}";
    }

    public function doAddQty ($row)
    {
        echo "\nAdd {$row['qty']} to {$row['sku']} store {$row['store']}";
    }

    public function doMinQty ($row)
    {
        echo "\nMin {$row['qty']} to {$row['sku']} store {$row['store']}";
    }

}