<?php
return array(
    array("extended_action",function($item){
        if($item->status>=ShopDeliveryOrder::STATUS_STOREHOUSE_CHINA || Util::controller()->getUser()->type!=Collaborator::TYPE_SHIP){
            return array(
                "disabled" => true
            );
        }
        return array(
            "action" => "set_delivered_storehouse_china",
            "content" => "XN về kho TQ",
            "title" => "XN về kho TQ",
            // "message" => "XN về kho TQ cho vận đơn #$item->delivery_order_code ? Hành động này sẽ không thể hoàn tác? Bạn có muốn tiếp tục?"
        );
    },array(
        "class" => "btn btn-sm btn-primary"
    )),
    array("extended_action",function($item){
        if(!in_array($item->status,array(ShopDeliveryOrder::STATUS_STOREHOUSE_CHINA)) || Util::controller()->getUser()->type!=Collaborator::TYPE_STORE){
            return array(
                "disabled" => true
            );
        }
        return array(
            "action" => "set_delivered_storehouse_vietnam",
            "content" => "XN về kho VN",
            "title" => "XN về kho VN",
            "message" => "XN về kho VN cho vận đơn #$item->delivery_order_code ? Hành động này sẽ không thể hoàn tác? Bạn có muốn tiếp tục?"
        );
    },array(
        "class" => "btn btn-sm btn-primary"
    )),
    array("extended_action",function($item){
        if($item->status==Order::STATUS_CANCELED || Util::controller()->getUser()->type!=Collaborator::TYPE_SHIP){
            return array(
                "disabled" => true
            );
        }
        if(!$item->order || (Util::controller()->getUser()->type!=Collaborator::TYPE_ACCOUNTANT && $item->order->status >= Order::STATUS_DEPOSIT_DONE)){
             return array(
                "disabled" => true
            );
        }
        return array(
            "action" => "set_canceled",
            "content" => "Hủy",
            "title" => "Hủy đơn hàng",
            "message" => "Hủy vận đơn #$item->delivery_order_code ? Bạn sẽ không thể hoàn tác? Bạn có muốn tiếp tục?" 
        );
    },array(
        "class" => "btn btn-sm btn-danger"
    )),
);