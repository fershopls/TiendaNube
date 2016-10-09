<?php

namespace Modules\Category;

use lib\Module\Controller;

class CategoryController extends Controller {

    const DEFAULT_PARENT_ID = 87;
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


    protected function getParentIdFrom ($row = array()) {
        $id = array($row['id_x'], $row['id_y'], $row['id_z']);
        $id = array_filter($id,function($e){ if ($e && !empty($e)) return $e; });
        array_pop($id);
        $parent_category_root_string = $this->model()->solveIds($row);
        $parent_id = $this->model()->getSQLite3CategoryIdByRoute($parent_category_root_string);
        return $parent_id?$parent_id:self::DEFAULT_PARENT_ID;
    }

    protected function category ($row = array()) {
        return [
            'category' => [
                'name' => ucwords(strtolower($row['name'])),
                'parentId' => $this->getParentIdFrom($row),
                'isActive' => true,
                'includeInMenu' => true,
            ]
        ];
    }
    
    public function doUploadCategory ($row)
    {
        // Todo: debug
        $api = $this->dependency('api');
        $model = $this->model();
        $id = $model->getSQLite3CategoryIdByRoute($model->solveIds($row));
        if ($id)
        {
            $response = $api->request("PUT /categories/{$id}", $this->category($row));
            echo("Category {$row['name']} updated with id {$response->id}. parent: ".$response->parent_id);
        } else {
            $response = $api->request("POST /categories", $this->category($row));
            $this->model()->setSQLite3Category($response->id, $this->model()->solveIds($row));
            echo("Category {$row['name']} created with id {$response->id}. parent: ".$response->parent_id);
        }
        echo "\n";
    }

}