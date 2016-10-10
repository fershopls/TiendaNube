<?php

namespace Modules\Product;

use lib\App\Api;

class ProductModel {

    protected $api;

    public function __construct(Api $api)
    {
        $this->api = $api;
    }

    public function getProduct ($sku)
    {
        return $this->api->request('GET /products/'.urlencode($sku));
    }

}