<?php

namespace Modules\Order;

use lib\Module\Controller;
use lib\Module\Module;

class OrderController extends Controller {

    public function doFetch(Module $module, $stringPath)
    {
        $res = $this->getInvoices();
        
        foreach ($res->items as $item)
        {
            print_r($item);
            
            $rules = $module->getModuleAttributes();
            $data = array(
                ['__type__' => 'header', 'id' => '400', 'name'=>'Waldorf list', 'email'=>'myemail@gmail.com'],
                ['__type__' => 'row', 'email'=>'ubermail@gmail.com'],
            );
            $this->dump($data, $rules);
        }

    }

    public function getInvoices ()
    {
        $api = $this->dependency('api');
        return $api->request('GET /orders', ['searchCriteria'=>0]);
    }

    public function dump ($data, $rules)
    {
        $writer = $this->dependency('plain_writer');
        return $writer->load($data)->toString($rules);
    }

}