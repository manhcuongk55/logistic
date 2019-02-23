<?php
	$htmlAttributes["data-toggle"] = "modal";
	$htmlAttributes["data-target"] = "#modal-detail-" . $item->primaryValue();
	echo CHtml::htmlButton($config["content"],$htmlAttributes);
?>
<?php 
	$attrs = $item->list->config["admin"]["detail"];
?>
<!-- Modal -->
<div class="modal fade" id="modal-detail-<?php echo $item->primaryValue(); ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
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
				<div class="form-horizontal form-bordered">
					<?php foreach($attrs as $attr): ?>
						<div class="form-group">
							<label for="<?php echo $attr ?>" class="control-label col-lg-4"><?php $item->list->getHtml()->renderItemLabel($attr) ?></label>
							<div class="col-lg-8">
								<?php $item->renderDisplay($attr) ?>
							</div>
						</div>
					<?php endforeach; ?>
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