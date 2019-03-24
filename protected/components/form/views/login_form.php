<?php $form->open(array(
	"id" => "login-form"
)) ?>
	<div class="form-group">
		<label><?php $form->l_("Email") ?></label>
		<?php $form->inputField("email",array(
			"class" => "form-control textbox",
			"placeholder" => $form->l("Email")
		)) ?>
	</div>
	<div class="form-group">
		<label><?php $form->l_("Mật khẩu") ?></label>
		<?php $form->inputField("password",array(
			"class" => "form-control textbox",
			"placeholder" => $form->l("Mật khẩu")
		)) ?>
	</div>
	<div class="form-group">
		<div class="checkbox">
			<label class="checkbox-text">
			<input type="checkbox" value="">
			<?php $form->l_("Ghi nhớ tài khoản") ?> </label>
		</div>
		<div>
			<a href="<?php echo $this->createUrl("/site/forgot_password") ?>" class="fz12"><i><?php $form->l_("Quên mật khẩu") ?></i></a>
		</div>
	</div>
	<div class="form-group">
		<?php $form->submitButton("Đăng nhập",array(
			"type" => "button",
			"class" => "custom-button1"
		)) ?>
	</div>
	<hr>
	<div class="form-group" type="button">
		<a href="<?php echo $this->createUrl("/site/login_with_facebook") ?>" class="btn fb-button"><?php $form->l_("Đăng nhập với Facebook") ?></a>
	</div>
<?php $form->close(); ?>
<script>
	$(function(){
		$("#login-form").on("form-success",function(){
			location.href = "<?php echo LoginHelper::getLoginCallbackUrl() ?>";
		});
	});
</script>

<style>
.textbox {
	border-radius: 15px;
    border: 2px solid #00BFFF;
    padding: 20px; 
}
.custom-button1 {
	background-color: #4CAF50;
	border: none;
	color: white;
	height: 50px;
	width: 100%;
	text-align: center;
	text-decoration: none;
	display: block;
	font-size: 16px;
	margin: 4px 2px;
	border-radius: 12px;
}
.fb-button {
	background-color: #3b5998;
	border: none;
	color: white;
	height: 50px;
	width: 100%;
	text-align: center;
	text-decoration: none;
	display: inline-block;
	font-size: 16px;
	margin: 4px 2px;
	border-radius: 12px;
}
.checkbox-text {
	background-color: #ffffff;
	border: none;
	color: #00BFFF;
}
</style>