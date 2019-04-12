<?php 
    $deliveryOrderList = new DeliveryOrderList(DeliveryOrderList::TYPE_COMPLETED); 
    $deliveryOrderID = Input::get("delivery_order_id");
    $deliveryOrderCode = Input::get("delivery_order_code");
    $fromDateStr = Input::get("from_date_str");
    $toDateStr = Input::get("to_date_str");
    if($deliveryOrderID){
        $deliveryOrderList->setDynamicInput("delivery_order_id",$deliveryOrderID);
    }
    if($deliveryOrderCode){
        $deliveryOrderList->setDynamicInput("delivery_order_code",$deliveryOrderCode);
    }
    if($fromDateStr){
        $deliveryOrderList->setDynamicInput("from_date_str",$fromDateStr);
    }
    if($toDateStr){
        $deliveryOrderList->setDynamicInput("to_date_str",$toDateStr);
    }
?>
<h2 class="account-section-title"><b>Ký gửi:</b> <i>"Đã hoàn thành"</i></h2>
<div class="mg-b10">
    <form method="GET">
        <div>
            Mã ký gửi
            <input type="text" class="input-sm" name="delivery_order_id" value="<?php echo $deliveryOrderID ?>" />
            Mã vận đơn
            <input type="text" class="input-sm" name="delivery_order_code" value="<?php echo $deliveryOrderCode ?>" />
        </div>
        <div class="mg-t10">
            Từ ngày
            <input type="date" class="input-sm" name="from_date_str" value="<?php echo $fromDateStr ?>" />
            Đến ngày
            <input type="date" class="input-sm" name="to_date_str" value="<?php echo $toDateStr ?>" />
            <button type="submit" class="btn btn-sm btn-primary">Tìm kiếm</button>
        </div>
    </form>
</div>
<hr/>
<?php $deliveryOrderList->render(); ?>