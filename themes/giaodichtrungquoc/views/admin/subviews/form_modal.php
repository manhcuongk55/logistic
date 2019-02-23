<?php 
	$modalId = $form->viewParam("modalId","modal-" . Util::generateUniqueStringByRequest());
	$formId = $form->viewParam("formId","form-" . Util::generateUniqueStringByRequest());
	$title = $form->config["title"];
	if($form->viewParam("closeModalOnSuccess",true)){
		?>
			<script>
				$(function(){
					$("#<?php echo $formId ?>").on("form-success",function(){
						$("#<?php echo $modalId ?>").modal("hide");
					});
				});
			</script>
		<?php
	}
?>
<div id="<?php echo $modalId ?>" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
	<div class="modal-dialog <?php echo $form->viewParam("modalDialogClass","modal-lg") ?>">
		<div class="modal-content">
			<?php if($title): ?>
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
					<h4 class="modal-title"><?php echo $title ?></h4>
				</div>
			<?php endif; ?>
			<div class="modal-body">
				<?php $form->open(array(
					"class" => "form-horizontal",
					"id" => $formId
				)); ?>
					<?php if($error = $form->otherError()): ?>
						<div class="error">
							<?php echo $error; ?>
						</div>
					<?php endif; ?>
					<?php foreach($form->config["inputs"] as $inputName => $inputConfig):if(!$inputConfig["displayInput"])continue; ?>
						<?php if(ArrayHelper::get($inputConfig,"type")=="hidden"): ?>
							<?php $form->inputField($inputName) ?>
						<?php else: ?>
							<div class="form-group">
								<label class="control-label <?php echo $form->viewParam("labelCol","col-lg-3") ?>"><?php echo $form->inputLabel($inputName) ?></label>
								<div class="<?php echo $form->viewParam("inputCol","col-lg-9") ?>">
									<?php $form->inputField($inputName,array(
										"class" => "form-control"
									)) ?>
								</div>
							</div>
							<?php if($error=$form->inputError($inputName)): ?>
								<div class="error <?php echo $form->viewParam("errorCol","col-lg-12") ?>">
									<?php echo $error; ?>
								</div>
							<?php endif; ?>
						<?php endif; ?>
					<?php endforeach; ?>
					<!--<div>
						<?php $form->submitButton($form->viewParam("submitText","Submit"),$form->viewParam("submitHtmlAttributes",array(
							"class" => "btn btn-primary"
						))); ?>
					</div>-->
				<?php $form->close(); ?>
			</div>
			<div class="modal-footer">
				<button type="button" data-dismiss="modal" class="btn btn-default"><?php echo $form->viewParam("cancelText","Cancel") ?></button>
				<button type="button" class="btn btn-primary" onclick="$__$.__ajax('#<?php echo $formId ?>')"><?php echo $form->viewParam("submitText","Submit") ?></button>
			</div>
		</div>
	</div>
</div>
