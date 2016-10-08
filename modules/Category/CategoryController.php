<?php

namespace Modules\Category;

use lib\Module\Controller;

class CategoryController extends Controller {
    
    public function doUploadCategory ($row)
    {
        echo "[C] {$row['name']} \n";
    }

}