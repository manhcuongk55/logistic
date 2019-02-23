<?php
return array(
    "set_completed" => function($deliveryOrder){
        $result = $deliveryOrder->setCompleted();
        return array($result,$result ? null : $deliveryOrder->getFirstError(),null);
    },
    "set_canceled" => function($deliveryOrder){
        $result = $deliveryOrder->setCanceled();
        return array($result,$result ? null : $deliveryOrder->getFirstError(),null);
    },
);