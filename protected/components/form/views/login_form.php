<?php $form->open(array(
	"id" => "login-form"
)) ?>
	<div class="form-group">
		<label><?php $form->l_("Email") ?></label>
		<?php $form->inputField("email",array(
			"class" => "form-control input-sm",
			"placeholder" => $form->l("Email")
		)) ?>
	</div>
	<div class="form-group">
		<label><?php $form->l_("Mật khẩu") ?></label>
		<?php $form->inputField("password",array(
			"class" => "form-control input-sm",
			"placeholder" => $form->l("Mật khẩu")
		)) ?>
	</div>
	<div class="form-group">
		<div class="checkbox">
			<label>
			<input type="checkbox" value="">
			<?php $form->l_("Ghi nhớ tài khoản") ?> </label>
		</div>
		<div>
			<a href="<?php echo $this->createUrl("/site/forgot_password") ?>" class="fz12"><i><?php $form->l_("Quên mật khẩu") ?></i></a>
		</div>
	</div>
	<div class="form-group">
		<?php $form->submitButton("Đăng nhập",array(
			"class" => "btn btn-sm btn-primary"
		)) ?>
	</div>
	<hr>
	<div class="form-group">
		<a href="<?php echo $this->createUrl("/site/login_with_facebook") ?>" class="btn btn-primary btn-facebook btn-block"><?php $form->l_("Đăng nhập với Facebook") ?></a>
	</div>
<?php $form->close(); ?>
<script>
	$(function(){
		$("#login-form").on("form-success",function(){
			location.href = "<?php echo LoginHelper::getLoginCallbackUrl() ?>";
		});
	});
</script>