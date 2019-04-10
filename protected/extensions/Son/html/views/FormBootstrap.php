<?php $form->open(array(
	"class" => "form-horizontal"
)); ?>
	<?php if($error = $form->otherError()): ?>
		<div class="error">
			<?php echo $error; ?>
		</div>
	<?php endif; ?>
	<?php foreach($form->config["inputs"] as $inputName => $inputConfig): ?>
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
	<?php endforeach; ?>
	<div>
		<?php $form->submitButton($form->viewParam("submitText","Submit")); ?>
	</div>
<?php $form->close(); ?>