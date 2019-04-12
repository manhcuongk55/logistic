<?php
return array(
    array("link",function($item){
        $url = Util::controller()->createUrl("/collaborator/shop_delivery_orders?order_id=" . $item->id . "&type=" . ShopDeliveryOrder::TYPE_DELIVERY_ORDER);
        if(!$url){
            return array(
                "disabled" => true
            );
        }
        return array(
            "content" => '<i class="fa fa-file-text-o"></i>',
            "newTab" => true,
            "href" => $url,
            "title" => "Danh sách vận đơn"
        );
    }),
    array("link",function($item){
        return array(
            "content" => '<i class="fa fa-external-link"></i>',
            "href" => Util::controller()->createUrl($item->model->getTheImage()),
            "title" => "Image",
            "newTab" => true
        );
    }),
    array("extended_action",function($item){
        if($item->status!=DeliveryOrder::STATUS_STOREHOUSE_VIETNAM || Util::controller()->getUser()->type!=Collaborator::TYPE_ACCOUNTANT){
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