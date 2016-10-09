<?php
/**
 * Created by PhpStorm.
 * User: FershoPls
 * Date: 10/9/2016
 * Time: 6:47 PM
 */

namespace lib\Handler;

use lib\Util\PlainText;

class StockHandler extends Handler {

    public function getDependencies()
    {
        return array (
            'app',
            'plain_text' => PlainText::class,
        );
    }

    public function handle()
    {
        
    }

}