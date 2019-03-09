<?php
	$modalId = "modal-content-" . $item->primaryValue() . "-" . Util::generateUniqueStringByRequest();
	$htmlAttributes["data-toggle"] = "modal";
	$htmlAttributes["data-target"] = "#$modalId";
	$attr = $config["attr"]; 
	$value = $item->$attr;
	$value = nl2br($value);
	echo CHtml::htmlButton($config["content"],$htmlAttributes);
?>
<!-- Modal -->
<div class="modal fade" id="<?php echo $modalId ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document" style="width: auto; min-width: 600px; margin-left: 30px; margin-right: 30px;">
		<div class="modal-content">
			<?php if($config["header"]): ?>
				<div class="modal-header">
					<?php if($config["headerCloseButton"]): ?>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<?php endif; ?>
					<h4 class="modal-title" id="myModalLabel"><?php echo $config["header"] ?></h4>
				</div>
			<?php endif; ?>
			<div class="modal-body">
				<div style="white-space: normal;">
					<?php echo $value ?>
				</div>
			</div>
			<?php if($config["closeButton"]): ?>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $config["closeButton"] ?></button>
			</div>
			<?php endif; ?>
		</div>
	</div>
</div>