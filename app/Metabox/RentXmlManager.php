<?php

namespace App\Metabox;

use App\Library\Services\RentOrderManager;

class RentXmlManager
{
    public function index($args)
    {
        $view_args = [];
        $order_id = get_the_ID();
        $rentOrderManager = new RentOrderManager();
        $view_args['content'] = $rentOrderManager->getXmlContent($order_id);

        return view('metabox.rent-xml-order', $view_args);
    }
}