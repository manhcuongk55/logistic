<?php
return array(
    "set_price" => array(
        "title" => "Đặt giá",
        "inputs" => array(
            "total_delivery_price_ndt" => array(
                "type" => "money_input",
                "label" => "Cước vận chuyển TQ (VNĐ)",
                "rules" => array(
                    array(
                        "required"
                    )
                )
            ),
            "name" => array(
                "label" => "Tên SP"
            )
        ),
        "method" => "post",
        "ajax" => true,
        "onHandleInput" => function($form){
            if(Util::controller()->getUser()->type!=Collaborator::TYPE_SALE)
                return false;
            $result = $form->readInput();
            if(!$result){
                $form->setError(true);
            } else {
                $order = Order::model()->findByAttributes(array(
                    "id" => $form->id,
                ));
                if(!$order){
                    $form->addError("global","Invalid request");
                    $form->setError(true);
                } else {
                    $result = $order->setPrice($form->total_delivery_price_ndt,null,null,null,0,null,$form->name);
                    $form->setError(!$result);
                    if(!$result){
                        $form->addError("global",$order->getFirstError());
                    }
                }
            }
            return true;
        }
    ),
    "set_deposit_price" => array(
        "title" => "Yêu cầu đặt cọc",
        "inputs" => array(
            "deposit_amount" => array(
                "type" => "money_input",
                "label" => "Tiền đặt cọc (VNĐ)",
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
            if(Util::controller()->getUser()->type!=Collaborator::TYPE_ACCOUNTANT)
                return false;
            $result = $form->readInput();
            if(!$result){
                $form->setError(true);
            } else {
                $order = Order::model()->findByAttributes(array(
                    "id" => $form->id,
                ));
                if(!$order){
                    $form->addError("global","Invalid request");
                    $form->setError(true);
                } else {
                    $result = $order->setDepositAmount($form->deposit_amount);
                    $form->setError(!$result);
                    if(!$result){
                        $form->addError("global",$order->getFirstError());
                    }
                }
            }
            return true;
        }
    ),
    "set_deposit_done" => function($order){
        if(Util::controller()->getUser()->type!=Collaborator::TYPE_ACCOUNTANT)
            return false;
        $result = $order->setDepositDone();
        return array($result,$result ? null : $order->getFirstError(),null);
    },
    "set_start_ordered" => array(
        "title" => "Hoàn thành",
        "inputs" => array(
            "order_code" => array(
                "label" => "Mã order *",
                "rules" => array(
                )
            ),
            "exchange_rate" => array(
                "type" => "money_input",
                "label" => "Tỉ giá bán (VNĐ/NDT) *",
                "rules" => array(
                )
            ),
            "real_exchange_rate" => array(
                "type" => "money_input",
                "label" => "Tỉ giá mua (VNĐ/NDT) *",
                "rules" => array(
                )
            ),
        ),
        "method" => "post",
        "ajax" => true,
        "onHandleInput" => function($form){
            if(Util::controller()->getUser()->type!=Collaborator::TYPE_SHIP)
                return false;
            $result = $form->readInput();
            if(!$result){
                $form->setError(true);
            } else {
                $order = Order::model()->findByAttributes(array(
                    "id" => $form->id,
                ));
                if(!$order){
                    $form->addError("global","Invalid request");
                    $form->setError(true);
                } else {
                    $result = $order->setStartOrdered($form->order_code,$form->exchange_rate,$form->real_exchange_rate);
                    $form->setError(!$result);
                    if(!$result){
                        $form->addError("global",$order->getFirstError());
                    }
                }
            }
            return true;
        }
    ),
    "set_completed" => array(
        "title" => "Hoàn thành",
        "inputs" => array(
            "exchange_rate" => array(
                "type" => "money_input",
                "label" => "Đặt lại tỉ giá bán",
                "rules" => array(
                )
            ),
            "total_weight" => array(
                "type" => "money_input",
                "label" => "Đặt lại tổng cân nặng",
                "rules" => array(
                )
            ),
            "total_weight_price" => array(
                "type" => "money_input",
                "label" => "Đặt lại cước cân nặng",
                "rules" => array(
                )
            ),
            "service_price" => array(
                "type" => "money_input",
                "label" => "Đặt lại phí dịch vụ",
                "rules" => array(
                )
            ),
            "shipping_home_price" => array(
                "type" => "money_input",
                "label" => "Phí vận chuyển nội địa (VNĐ) *",
                "rules" => array(
                )
            ),
            "extra_price" => array(
                "type" => "number",
                "label" => "Chi phí phát sinh (VNĐ) *",
                "rules" => array(
                )
            ),
            "extra_description" => array(
                "type" => "textarea",
                "label" => "Ghi chú lỗi phát sinh",
                "rules" => array(
                )
            ),
        ),
        "method" => "post",
        "ajax" => true,
        "onHandleInput" => function($form){
            if(Util::controller()->getUser()->type!=Collaborator::TYPE_ACCOUNTANT)
                return false;
            $result = $form->readInput();
            if(!$result){
                $form->setError(true);
            } else {
                $order = Order::model()->findByAttributes(array(
                    "id" => $form->id,
                ));
                if(!$order){
                    $form->addError("global","Invalid request");
                    $form->setError(true);
                } else {
                    $result = $order->setCompleted($form->shipping_home_price,$form->extra_price,$form->exchange_rate,$form->total_weight,$form->total_weight_price,$form->service_price,$form->extra_description);
                    $form->setError(!$result);
                    if(!$result){
                        $form->addError("global",$order->getFirstError());
                    }
                }
            }
            return true;
        }
    ),
    "set_paid" => function($order){
        if(Util::controller()->getUser()->type!=Collaborator::TYPE_ACCOUNTANT)
            return false;
        $result = $order->setPaid();
        return array($result,$result ? null : $order->getFirstError(),null);
    },
    "set_canceled" => function($order){
        $result = $order->setCanceled();
        return array($result,$result ? null : $order->getFirstError(),null);
    },
);