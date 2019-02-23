<?php
class OrderHelper {

    public static function makeOrder(){
        $items = ProductCartHelper::getItems();
        $totalCount = 0;
        $originalAmount = 0;
        $orderProducts = array();
        $shopDeliveryOrderOrderProductLists = array();

        foreach($items as $id => $item){
            if(!$item->shop_id){
                Output::returnJsonError("SP không tự cập nhật được link shop, quý khách vui lòng thêm link shop vào sp, xin cảm ơn!");
                return;
            }
            $orderProduct = new OrderProduct();
            $orderProduct->url = $item->url;
            $orderProduct->name = $item->original_name;
            $orderProduct->vietnamese_name = $item->name;
            $orderProduct->image = $item->image;
            $orderProduct->count = $item->count;
            $orderProduct->website_type = $item->type;
            $orderProduct->description = $item->description;
            $orderProduct->web_price = $item->web_price;
            $orderProduct->shop_id = @substr($item->shop_id,0,50);
            $orderProduct->ordered_count = $orderProduct->count;

            $result = $orderProduct->validate(array(
                "url", "name", "vietnamese_name", "image", "count", "type", "description", "web_price", "shop_id"
            ));
            if(!$result){
                Output::returnJsonError($orderProduct->getFirstError());
                return;
            }
            $orderProducts[] = $orderProduct;
            $totalCount += $orderProduct->count;

            if($shopId = $orderProduct->shop_id){
                if(!isset($shopDeliveryOrderOrderProductLists[$shopId])){
                    $shopDeliveryOrderOrderProductLists[$shopId] = array();
                }
                $shopDeliveryOrderOrderProductLists[$shopId][] = $orderProduct;
            }
        }

        if(!$totalCount){
            Output::returnJsonError("No product selected");
            return;
        }

        
        foreach($shopDeliveryOrderOrderProductLists as $shopId => $shopDeliveryOrderOrderProductList){
            $order = new Order();
            $order->user_id = Yii::app()->user->id;
            $order->total_quantity = $totalCount;
            $order->status = Order::STATUS_WAIT_FOR_PRICE;
            $order->shop_id = $shopId;
            $order->shop_name = $shopId;
            $order->delivery_price_ndt = 0;
            $order->real_price = 0;

            $result = $order->save();
            

            if(!$result){
                Output::returnJsonError($order->getFirstError());
                return;
            }

            $order->notifyChangeToUser();

            foreach($shopDeliveryOrderOrderProductList as 
            $orderProduct){
                $orderProduct->order_id = $order->id;
            }
        }

        foreach($orderProducts as $orderProduct){
            $result = $orderProduct->save(false);
            if(!$result){
                Output::returnJsonError($orderProduct->getFirstError());
                return;
            }
        }

        ProductCartHelper::clearSavedItems();
        Output::returnJsonSuccess(array(
            "order_id" => $order->id
        ));
    }
}