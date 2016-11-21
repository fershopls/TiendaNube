<?php

namespace Modules\Product;

use lib\Module\Controller;
use Modules\Category\CategoryModel;

class ProductController extends Controller {

    protected $classModel;
    /**
     * @return CategoryModel
     */
    public function model ()
    {
        if (!$this->classModel)
        {
            $app = $this->dependency('app');
            $db = $app->getPath($app->set()->get('database'));
            $this->classModel = $this->dependency('model')->open($db);
        }
        return $this->classModel;
    }

    public function doCreateProduct ($row)
    {
        // Todo: debug
        $response = $this->dependency('api')->request("POST /products", $this->getProduct($row));
        # echo json_encode($row, JSON_PRETTY_PRINT) . PHP_EOL;
        echo("\nProduct {$row['sku']} created with id {$response->id}.");
        # $logger->debug("Product {$row['sku']} created with id {$response->id}.", []);
    }

    public function doUploadProduct ($row)
    {
        // Todo: debug
        $product = $this->getProduct($row);
        unset($product['product']['extension_attributes']['stock_item']); // Remove sku data
        $response = $this->dependency('api')->request("POST /products", $product);
        # echo json_encode($row, JSON_PRETTY_PRINT) . PHP_EOL;
        echo("\nProduct {$row['sku']} updated with id {$response->id}.");
        # $logger->debug("Product {$row['sku']} created with id {$response->id}.", []);
    }

    protected function custom ($attribute_key, $attribute_value) {
        return ['attribute_code' => $attribute_key, 'value' => $attribute_value];
    }

    protected function getProduct ($row)
    {
        $model = $this->model();
        $id = $model->getSQLite3CategoryIdByRoute($model->solveIds([$row['id_x'], $row['id_y'], $row['id_z']]));
        return [
            'product' => array(
                'type_id' => 'virtual',
                'attribute_set_id' => 4,
                'sku' => $row['sku'],
                'name' => utf8_encode($row['name']),
                'price' => $row['price'],
                'weight' => 0,
                'status' => 1,
                'visibility' => 4,
                'custom_attributes' => array(
                    $this->custom('description', utf8_encode($row['name2'])),
                    $this->custom('category_ids', array($id)),
                ),
                'extension_attributes' => array(
                    'stock_item' => [
                        'qty' => $row['stock'],
                        'isInStock' => $row['stock']?true:false,
                    ]
                )
            ),
        ];
    }

}