<?php if($title=$form->config["title"]): ?>
	<h3 class="text-center"><?php echo $title ?></h3>
<?php endif; ?>
<?php $form->open(array(
	"class" => "form-horizontal"
)); ?>
	<?php if($error = $form->otherError()): ?>
		<div class="error">
			<?php echo $error; ?>
		</div>
	<?php endif; ?>
	<?php $form->renderPreView(); ?>
	<?php foreach($form->config["inputs"] as $inputName => $inputConfig):if(!$inputConfig["displayInput"])continue; ?>
		<?php if(ArrayHelper::get($inputConfig,"type")=="hidden"): ?>
			<?php $form->inputField($inputName) ?>
		<?php else: ?>
			<div class="form-group">
				<label class="control-label <?php echo $form->viewParam("labelCol","col-lg-3 col-md-4 col-sm-12 col-xs-12") ?>"><?php echo $form->inputLabel($inputName) ?></label>
				<div class="<?php echo $form->viewParam("inputCol","col-lg-9 col-md-8 col-sm-12 col-xs-12") ?>">
					<?php $form->inputField($inputName,array(
						"class" => "form-control"
					)) ?>
					<?php if($error=$form->inputError($inputName)): ?>
						<span class="help-block <?php echo $form->viewParam("errorCol","col-lg-12") ?>">
							<?php echo $error; ?>
						</span>
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>
	<?php endforeach; ?>
	<?php $form->renderPostView(); ?>
	<div class="<?php echo $form->viewParam("submitAlign","text-right") ?>">
		<?php $form->submitButton($form->viewParam("submitText","Submit"),$form->viewParam("submitHtmlAttributes",array(
			"class" => "btn btn-primary"
		))); ?>
	</div>
<?php $form->close(); ?>