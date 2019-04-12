<?php 
    if(!$item->model->total_real_price_ndt){
        $item->model->total_real_price_ndt = $item->model->total_price_ndt;
    }
    if(!$item->model->real_exchange_rate){
        $item->model->real_exchange_rate = $item->model->exchange_rate;
    }
    $profit = $item->model->real_exchange_rate * ($item->model->total_price_ndt - $item->model->total_real_price_ndt);
    Util::controller()->data["totalProfit"] += $profit;

    $price = $item->model->total_real_price_ndt;
    Util::controller()->data["totalPrice"] += $price;
?>
<?php $item->renderBegin("tr"); ?>
<td>
    #<?php echo $item->model->id ?>
</td>
<td>
    <?php echo date("d/m/Y",$item->model->created_time) ?>
</td>
<td>
    <span money-display data-money="<?php echo $item->model->total_price_ndt + $item->model->total_delivery_price_ndt ?>"></span>
</td>
<td>
    <span money-display data-money="<?php echo $price ?>"></span>
</td>
<td>
    <span money-display data-money="<?php echo $item->model->real_exchange_rate ?>"></span>
</td>
<td>
    <span money-display data-money="<?php echo $item->model->exchange_rate ?>"></span>
</td>
<td>
    <span money-display data-money="<?php echo $item->model->real_exchange_rate * $item->model->total_price_ndt  ?>"></span>
</td>
<td>
    <span money-display data-money="<?php echo $profit  ?>"></span>
</td>
<?php $item->renderEnd(); ?>