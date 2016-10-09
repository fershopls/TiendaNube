<?php

namespace Modules\Customer;

use lib\Module\Controller;

class CustomerController extends Controller {
    
    public function doUploadCustomer ($row)
    {
        echo "\n[C] {$row['name']}";
    }

}