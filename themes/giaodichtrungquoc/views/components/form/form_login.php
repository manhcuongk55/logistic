<?php $form->open(); ?>
	<?php if($title=$form->config["title"]): ?>
		<h2 class="text-center"><?php echo $title ?></h2>
		<hr/>
	<?php endif; ?>
	<?php if($error = $form->otherError()): ?>
		<div class="alert alert-danger">
			<span>
				<?php echo $error; ?>
			</span>
		</div>
	<?php endif; ?>
	<div class="form-group">
		<?php $form->inputField("login_name",array(
			"class" => "form-control",
			"autocomplete" => "off",
			"placeholder" => $form->inputLabel("login_name")
		)) ?>
		<?php if($error=$form->inputError("login_name")): ?>
			<div>
				<span class="help-block"><?php echo $error ?></span>
			</div>
		<?php endif; ?>
	</div>
	<div class="form-group">
		<?php $form->inputField("password",array(
			"class" => "form-control",
			"autocomplete" => "off",
			"placeholder" => $form->inputLabel("password")
		)) ?>
		<?php if($error=$form->inputError("password")): ?>
			<div>
				<span class="help-block"><?php echo $error ?></span>
			</div>
		<?php endif; ?>
	</div>
	<div>
		<?php $form->submitButton($form->viewParam("submitText",'Login <i class="m-icon-swapright m-icon-white"></i>'),array(
			"class" => "btn btn-primary btn-block"
		)); ?>
	</div>
<?php $form->close(); ?>