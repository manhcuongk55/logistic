<?php
return array(
    array("extended_action",function($item){
        if($item->status!=DeliveryOrder::STATUS_WAIT_FOR_PRICE || Util::controller()->getUser()->type!=Collaborator::TYPE_SHIP){
            return array(
                "disabled" => true
            );
        }
        return array(
            "action" => "set_deposit_price",
            "content" => "Yêu cầu đặt cọc",
            "title" => "Yêu cầu đặt cọc",
        );
    },array(
        "class" => "btn btn-sm btn-primary"
    )),
    array("extended_action",function($item){
        if($item->status!=DeliveryOrder::STATUS_WAIT_FOR_DEPOSIT || Util::controller()->getUser()->type!=Collaborator::TYPE_SALE){
            return array(
                "disabled" => true
            );
        }
        return array(
            "action" => "set_deposit_done",
            "content" => "XN đặt cọc",
            "title" => "XN đặt cọc",
            "message" => "XN đặt cọc cho đơn hàng #$item->id ? Hành động này sẽ không thể hoàn tác? Bạn có muốn tiếp tục?"
        );
    },array(
        "class" => "btn btn-sm btn-primary"
    )),
    array("extended_action",function($item){
        if($item->status!=DeliveryOrder::STATUS_DEPOSIT_DONE || Util::controller()->getUser()->type!=Collaborator::TYPE_SHIP){
            return array(
                "disabled" => true
            );
        }
        return array(
            "action" => "set_start_shipping",
            "content" => "XN chuyển hàng",
            "title" => "XN chuyển hàng",
            "message" => "XN chuyển hàng cho đơn hàng #$item->id ? Hành động này sẽ không thể hoàn tác? Bạn có muốn tiếp tục?"
        );
    },array(
        "class" => "btn btn-sm btn-primary"
    )),
    array("extended_action",function($item){
        if($item->status!=DeliveryOrder::STATUS_SHIPPING || Util::controller()->getUser()->type!=Collaborator::TYPE_SHIP){
            return array(
                "disabled" => true
            );
        }
        return array(
            "action" => "set_delivered_storehouse_china",
            "content" => "XN về kho TQ",
            "title" => "XN về kho TQ",
            "message" => "XN về kho TQ cho đơn hàng #$item->id ? Hành động này sẽ không thể hoàn tác? Bạn có muốn tiếp tục?"
        );
    },array(
        "class" => "btn btn-sm btn-primary"
    )),
    array("extended_action",function($item){
        if($item->status!=DeliveryOrder::STATUS_STOREHOUSE_CHINA || Util::controller()->getUser()->type!=Collaborator::TYPE_SHIP){
            return array(
                "disabled" => true
            );
        }
        return array(
            "action" => "set_delivered_storehouse_vietnam",
            "content" => "XN về kho VN",
            "title" => "XN về kho VN",
            "message" => "XN về kho VN cho đơn hàng #$item->id ? Hành động này sẽ không thể hoàn tác? Bạn có muốn tiếp tục?"
        );
    },array(
        "class" => "btn btn-sm btn-primary"
    )),
    array("extended_action",function($item){
        if($item->status!=DeliveryOrder::STATUS_STOREHOUSE_VIETNAM || Util::controller()->getUser()->type!=Collaborator::TYPE_STORE){
            return array(
                "disabled" => true
            );
        }
        return array(
            "action" => "set_completed",
            "content" => "XN hoàn thành",
            "title" => "Giao hàng và hoàn thành",
            "message" => "XN hoàn thành cho đơn hàng #$item->id ? Hành động này sẽ không thể hoàn tác? Bạn có muốn tiếp tục?"
        );
    },array(
        "class" => "btn btn-sm btn-primary"
    )),
    array("extended_action",function($item){
        if($item->status==DeliveryOrder::STATUS_COMPLETED || $item->status==DeliveryOrder::STATUS_CANCELED || !Util::controller()->getUser()->is_manager){
            return array(
                "disabled" => true
            );
        }
        return array(
            "action" => "set_canceled",
            "content" => "Hủy",
            "title" => "Hủy đơn hàng",
            "message" => "Hủy đơn hàng #$item->id ? Bạn sẽ không thể hoàn tác? Bạn có muốn tiếp tục?" 
        );
    },array(
        "class" => "btn btn-sm btn-danger"
    )),
);