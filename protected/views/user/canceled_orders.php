<?php 
    $orderList = new OrderList(OrderList::TYPE_CANCELED); 
    $orderID = Input::get("order_id");
    $fromDateStr = Input::get("from_date_str");
    $toDateStr = Input::get("to_date_str");
    if($orderID){
        $orderList->setDynamicInput("order_id",$orderID);
    }
    if($fromDateStr){
        $orderList->setDynamicInput("from_date_str",$fromDateStr);
    }
    if($toDateStr){
        $orderList->setDynamicInput("to_date_str",$toDateStr);
    }
?>
<h2 class="account-section-title"><b>Đơn hàng:</b> <i>"<?php l_("user/order","Đã hủy") ?>"</i></h2>
<div class="mg-b10">
    <form method="GET">
        Mã đơn hàng
        <input type="text" class="input-sm" name="order_id" value="<?php echo $orderID ?>" />
        Từ ngày
        <input type="date" class="input-sm" name="from_date_str" value="<?php echo $fromDateStr ?>" />
        Đến ngày
        <input type="date" class="input-sm" name="to_date_str" value="<?php echo $toDateStr ?>" />
        <button type="submit" class="btn btn-sm btn-primary">Tìm kiếm</button>
    </form>
</div>
<hr/>
<?php $orderList->render(); ?>