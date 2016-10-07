<?php

namespace Modules\Product;

use lib\Module\Controller;

class ProductController extends Controller {
    
    public function doUploadProduct ($row)
    {
        echo "[Uploading] {$row['name']} \n";
    }

}