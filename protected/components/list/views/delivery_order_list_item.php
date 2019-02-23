<?php 
    $shopDeliveryOrder = $item->model->shop_delivery_order;
?>
<?php $item->renderBegin("tr"); ?>
<td>
    #<?php echo $item->model->id ?>
</td>
<td>
    <?php echo $item->model->product_name ?>
</td>
<td>
    <?php echo @$shopDeliveryOrder->delivery_order_code ?>
</td>
<td>
    <?php echo @$shopDeliveryOrder->total_weight ?>
</td>
<td>
    <?php echo @$shopDeliveryOrder->total_volume ?>
</td>
<td>
    <span class="label label-info">
        <?php echo $item->model->listGetLabel("status") ?>
    </span>
</td>
<td>
    <?php if($image = $item->model->getTheImage()): ?>
        <img src="<?php echo $item->model->getTheImage() ?>" />
    <?php endif; ?>
</td>
<td>
    <?php echo @date("d/m/Y",$shopDeliveryOrder->created_time) ?>
</td>
<td>
    <?php echo @date("d/m/Y",$shopDeliveryOrder->china_delivery_time) ?>
</td>
<td>
    <?php echo @date("d/m/Y",$shopDeliveryOrder->vietnam_delivery_time) ?>
</td>
<?php $item->renderEnd(); ?>