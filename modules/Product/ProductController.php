<?php

namespace Modules\Product;

use lib\Module\Controller;

class ProductController extends Controller {
    
    public function doUploadProduct ($row)
    {
        // Todo: debug
        $response = $this->dependency('api')->request("POST /products", $this->getProduct($row));
        # echo json_encode($row, JSON_PRETTY_PRINT) . PHP_EOL;
        echo("Product {$row['sku']} created with id {$response->id}.\n");
        # $logger->debug("Product {$product['sku']} created with id {$response->id}.", []);
    }

    protected function custom ($attribute_key, $attribute_value) {
        return ['attribute_code' => $attribute_key, 'value' => $attribute_value];
    }

    protected function getProduct ($product = array()) {
        #$category = $this->CategoryModel->getSQLite3CategoryIdByRoute($product['category']);
        return [
            'product' => array(
                'type_id' => 'virtual',
                'attribute_set_id' => 4,
                'sku' => $product['sku'],
                'name' => $product['name'],
                'price' => $product['price'],
                'weight' => 0,
                'status' => 1,
                'visibility' => 4,
                'custom_attributes' => array(
                    $this->custom('description', $product['name2']),
                    $this->custom('category_ids', array(59)),
                ),
                'extension_attributes' => array(
                    'stock_item' => [
                        'qty' => $product['stock'],
                        'isInStock' => $product['stock']?true:false,
                    ]
                )
            ),
        ];
    }

}