<?php
return array(
    array("link",function($item){
        $url = Util::controller()->createUrl("/collaborator/order_products?order_id=" . $item->id);
        if(!$url){
            return array(
                "disabled" => true
            );
        }
        return array(
            "content" => '<i class="fa fa-umbrella"></i>',
            "newTab" => true,
            "href" => $url,
            "title" => "Danh sách sản phẩm"
        );
    }),
    array("link",function($item){
        $url = Util::controller()->createUrl("/collaborator/shop_delivery_orders?order_id=" . $item->id . "&type=" . ShopDeliveryOrder::TYPE_ORDER);
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
    array("extended_action",function($item){
        if($item->status!=Order::STATUS_WAIT_FOR_PRICE || Util::controller()->getUser()->type!=Collaborator::TYPE_SALE){
            return array(
                "disabled" => true
            );
        }
        return array(
            "action" => "set_price",
            "content" => "Đặt giá",
            "title" => "Đặt giá",
        );
    },array(
        "class" => "btn btn-sm btn-primary"
    )),
    array("extended_action",function($item){
        if($item->status!=Order::STATUS_WAIT_FOR_DEPOSIT_AMOUNT || Util::controller()->getUser()->type!=Collaborator::TYPE_ACCOUNTANT){
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
        if($item->status!=Order::STATUS_WAIT_FOR_DEPOSIT || Util::controller()->getUser()->type!=Collaborator::TYPE_ACCOUNTANT){
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
        if($item->status!=Order::STATUS_DEPOSIT_DONE || Util::controller()->getUser()->type!=Collaborator::TYPE_SHIP){
            return array(
                "disabled" => true
            );
        }
        return array(
            "action" => "set_start_ordered",
            "content" => "XN đặt hàng",
            "title" => "XN đặt hàng",
        );
    },array(
        "class" => "btn btn-sm btn-primary"
    )),
    array("extended_action",function($item){
        if($item->status!=Order::STATUS_STOREHOUSE_VIETNAM || Util::controller()->getUser()->type!=Collaborator::TYPE_ACCOUNTANT){
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
        if($item->status!=Order::STATUS_COMPLETED || Util::controller()->getUser()->type!=Collaborator::TYPE_ACCOUNTANT){
            return array(
                "disabled" => true
            );
        }
        return array(
            "action" => "set_paid",
            "content" => "XN thanh lý",
            "title" => "XN thanh lý",
            "message" => "XN thanh lý cho đơn hàng #$item->id ? Hành động này sẽ không thể hoàn tác? Bạn có muốn tiếp tục?"
        );
    },array(
        "class" => "btn btn-sm btn-primary"
    )),
    array("link",function($item){
        if(($item->status!=Order::STATUS_COMPLETED && $item->status!=Order::STATUS_PAID) || Util::controller()->getUser()->type!=Collaborator::TYPE_ACCOUNTANT){
            return array(
                "disabled" => true
            );
        }
        $url = Util::controller()->createUrl("/collaborator/order_pdf?order_id=" . $item->id);
        if(!$url){
            return array(
                "disabled" => true
            );
        }
        return array(
            "content" => 'PDF <i class="fa fa-download"></i>',
            "newTab" => true,
            "href" => $url,
            "title" => "Download chi tiết đơn hàng PDF"
        );
    }),
    array("extended_action",function($item){
        if($item->status==Order::STATUS_COMPLETED || $item->status==Order::STATUS_PAID || $item->status==Order::STATUS_CANCELED || !Util::controller()->getUser()->is_manager){
            return array(
                "disabled" => true
            );
        }
        if(Util::controller()->getUser()->type!=Collaborator::TYPE_ACCOUNTANT && $item->status >= Order::STATUS_DEPOSIT_DONE){
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