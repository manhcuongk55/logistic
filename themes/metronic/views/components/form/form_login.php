<?php $form->open(); ?>
	<?php if($title=$form->config["title"]): ?>
		<h3 class="form-title"><?php echo $title ?></h3>
	<?php endif; ?>
	<?php if($error = $form->otherError()): ?>
		<div class="alert alert-danger">
			<span>
				<?php echo $error; ?>
			</span>
		</div>
	<?php endif; ?>
	<div class="form-group">
		<!--ie8, ie9 does not support html5 placeholder, so we just show field title for that-->
		<label class="control-label visible-ie8 visible-ie9"><?php echo $form->inputLabel("login_name") ?></label>
		<div class="input-icon">
			<i class="fa fa-user"></i>
			<?php $form->inputField("login_name",array(
				"class" => "form-control placeholder-no-fix",
				"autocomplete" => "off",
				"placeholder" => $form->inputLabel("login_name")
			)) ?>
		</div>
		<?php if($error=$form->inputError("login_name")): ?>
			<span class="help-block"><?php echo $error ?></span>
		<?php endif; ?>
	</div>
	<div class="form-group">
		<label class="control-label visible-ie8 visible-ie9"><?php echo $form->inputLabel("password") ?></label>
		<div class="input-icon">
			<i class="fa fa-lock"></i>
			<?php $form->inputField("password",array(
				"class" => "form-control placeholder-no-fix",
				"autocomplete" => "off",
				"placeholder" => $form->inputLabel("password")
			)) ?>
		</div>
		<?php if($error=$form->inputError("password")): ?>
			<span class="help-block"><?php echo $error ?></span>
		<?php endif; ?>
	</div>
	<div class="form-actions">
		<?php $form->submitButton($form->viewParam("submitText",'Login <i class="m-icon-swapright m-icon-white"></i>'),$form->viewParam("submitHtmlAttributes",array(
			"class" => "btn green pull-right"
		))); ?>
	</div>
<?php $form->close(); ?>