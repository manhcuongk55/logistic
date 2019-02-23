<?php $item->renderBegin("li",array(
	"class" => $item->model->is_read ? "" : "unread"
)); ?>
	<a href="<?php echo $item->model->getUrl() ?>">
        <div class="notify-content">
            <?php echo $item->model->getMessage() ?>
        </div>
        <div class="notify-time" style="color: #999">
            <i class="fa fa-clock-o mg-r5"></i>
            <?php echo date("h:iA - d/m/Y",$item->model->created_time) ?>
        </div>
    </a>
<?php $item->renderEnd(); ?>