<?php
	if(ArrayHelper::get($config,"prevent"))
		return;
	$modalId = "modal-iframe-" . $item->primaryValue() . "-" . Util::generateUniqueStringByRequest();
	$htmlAttributes["data-toggle"] = "modal";
	$htmlAttributes["data-target"] = "#" . $modalId;
	echo CHtml::htmlButton($config["content"],$htmlAttributes);
?>
<!-- Modal -->
<div class="modal fade" id="<?php echo $modalId ?>" modal-iframe tabindex="-1" role="dialog" style="text-align: center;">
	<div class="modal-dialog" role="document" style="display: inline-block; width: auto; margin-left: auto; margin-right: auto;">
		<div class="modal-content">
			<?php if($config["header"]): ?>
				<div class="modal-header">
					<?php if($config["headerCloseButton"]): ?>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<?php endif; ?>
					<h4 class="modal-title" id="myModalLabel"><?php echo $config["header"] ?></h4>
				</div>
			<?php endif; ?>
			<div class="modal-body" style="padding: 0px">
				<iframe data-src="<?php echo $config["url"] ?>" style="width: <?php echo $config["iframeWidth"] ?>; border: none;" />
			</div>
			<?php if($config["closeButton"]): ?>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $config["closeButton"] ?></button>
			</div>
			<?php endif; ?>
		</div>
	</div>
</div>