<?php $item->renderBegin("tr"); ?>
<td>
    #<?php echo $item->model->id ?>
</td>
<td>
    <?php echo date("d/m/Y",$item->model->created_time) ?>
</td>
<td>
    <span money-display data-money="<?php echo $item->model->total_price ?>"></span>
</td>
<td>
    <span class="label label-info">
        <?php echo $item->model->listGetLabel("status") ?>
    </span>
</td>
<td>
    <a href="<?php echo $this->createUrl("/user/order",array(
        "order_id" => $item->model->id
    )) ?>" class="btn btn-sm btn-info"><i class="fa fa-external-link"></i></a>
</td>
<?php $item->renderEnd(); ?>