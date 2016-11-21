<?php

namespace Modules\Category;

use lib\Module\Controller;

class CategoryController extends Controller {

    const DEFAULT_PARENT_ID = 117;
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
        $ids = array($row['id_x'], $row['id_y'], $row['id_z']);
        $ids = array_filter($ids,function($e){ if ($e && !empty($e)) return $e; });
        array_pop($ids);
        $parent_category_root_string = $this->model()->solveIds($ids);
        $parent_id = $this->model()->getSQLite3CategoryIdByRoute($parent_category_root_string);
        return $parent_id?$parent_id:self::DEFAULT_PARENT_ID;
    }

    protected function category ($row = array()) {
        return [
            'category' => [
                'name' => ucwords(strtolower(utf8_encode($row['name']))),
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
        $id = $model->solveIds([$row['id_x'], $row['id_y'], $row['id_z']]);
        if ($model->getSQLite3CategoryIdByRoute($id))
        {
            $response = $api->request("PUT /categories/{$id}", $this->category($row));
            echo("\nCategory {$row['name']} updated with id {$response->id}. parent: ".$response->parent_id);
        } else {
            $response = $api->request("POST /categories", $this->category($row));
            $this->model()->setSQLite3Category($response->id, $id);
             echo("\nCategory {$row['name']} created with id {$response->id}. parent: ".$response->parent_id);
        }
    }

}