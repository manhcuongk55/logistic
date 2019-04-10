<?php
return array(
     "set_ordered" => function($shopDeliveryOrder){
        if(Util::controller()->getUser()->type!=Collaborator::TYPE_SHIP){
            return false;
        }
        $result = $shopDeliveryOrder->setOrdered();
        return array($result,$result ? null : $shopDeliveryOrder->getFirstError(),null);
    },
    "set_start_shipping" => array(
        "title" => "Xác nhận chuyển hàng",
        "inputs" => array(
            "delivery_order_code" => array(
                "label" => "Mã vận đơn",
                "rules" => array(
                    array(
                        "required"
                    )
                )
            ),
            "order_code" => array(
                "label" => "Mã đặt hàng",
                "rules" => array(
                    array(
                        "required"
                    )
                )
            ),
        ),
        "method" => "post",
        "ajax" => true,
        "onHandleInput" => function($form){
            $result = $form->readInput();
            if(!$result){
                $form->setError(true);
            } else {
                if(Util::controller()->getUser()->type!=Collaborator::TYPE_SHIP){
                    return false;
                }
                $shopDeliveryOrder = ShopDeliveryOrder::model()->findByPk($form->id);
                if(!$shopDeliveryOrder){
                    $form->addError("global","Invalid request");
                    $form->setError(true);
                } else {
                    if($shopDeliveryOrder->order->status < Order::STATUS_DEPOSIT_DONE){
                        return false;
                    }
                    $result = $shopDeliveryOrder->setStartShipping($form->delivery_order_code,$form->order_code);
                    $form->setError(!$result);
                    if(!$result){
                        $form->addError("global",$shopDeliveryOrder->getFirstError());
                    }
                }
            }
            return true;
        }
    ),
    "set_delivered_storehouse_china" => array(
        "title" => "Xác nhận về kho Trung Quốc",
        "inputs" => array(
            "total_weight" => array(
                "type" => "number",
                "label" => "Cân nặng",
            ),
        ),
        "method" => "post",
        "ajax" => true,
        "onHandleInput" => function($form){
            $result = $form->readInput();
            if(!$result){
                $form->setError(true);
            } else {
                if(Util::controller()->getUser()->type!=Collaborator::TYPE_SHIP){
                    return false;
                }
                $shopDeliveryOrder = ShopDeliveryOrder::model()->findByPk($form->id);
                if(!$shopDeliveryOrder){
                    $form->addError("global","Invalid request");
                    $form->setError(true);
                } else {
                    $result = $shopDeliveryOrder->setDeliveredStorehouseChina($form->total_weight);
                    $form->setError(!$result);
                    if(!$result){
                        $form->addError("global",$shopDeliveryOrder->getFirstError());
                    }
                }
            }
            return true;
        }
    ),
    "set_delivered_storehouse_vietnam" => function($shopDeliveryOrder){
        if(Util::controller()->getUser()->type!=Collaborator::TYPE_STORE){
            return false;
        }
        $result = $shopDeliveryOrder->setDeliveredStorehouseVietnam();
        return array($result,$result ? null : $shopDeliveryOrder->getFirstError(),null);
    },
    "set_canceled" => function($shopDeliveryOrder){
         if(Util::controller()->getUser()->type!=Collaborator::TYPE_SHIP){
            return false;
        }
        $result = $shopDeliveryOrder->setCanceled();
        return array($result,$result ? null : $shopDeliveryOrder->getFirstError(),null);
    },
);