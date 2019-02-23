<?php $form->open(array(
	"id" => "signup-form"
)); ?>
	<div class="form-group">
		<?php $form->inputField("name",array(
			"class" => "form-control input-sm",
			"placeholder" => $form->l("Họ và tên")
		)) ?>
	</div>
	<div class="form-group">
		<?php $form->inputField("email",array(
			"class" => "form-control input-sm",
			"placeholder" => $form->l("Email")
		)) ?>
	</div>
	<div class="form-group">
		<?php $form->inputField("phone",array(
			"class" => "form-control input-sm",
			"placeholder" => $form->l("Số điện thoại")
		)) ?>
	</div>
	<div class="form-group">
		<?php $form->inputField("address",array(
			"class" => "form-control input-sm",
			"placeholder" => $form->l("Địa chỉ")
		)) ?>
	</div>
	<div class="form-group">
		<?php $form->inputField("password",array(
			"class" => "form-control input-sm",
			"placeholder" => $form->l("Mật khẩu")
		)) ?>
	</div>
	<div class="form-group">
		<?php $form->inputField("retype_password",array(
			"class" => "form-control input-sm",
			"placeholder" => $form->l("Nhập lại mật khẩu")
		)) ?>
	</div>
	<hr>
	<div class="form-group">
		<?php $form->submitButton("Đăng ký",array(
			"class" => "btn btn-primary"
		)) ?>
	</div>
	<hr>
<?php $form->close(); ?>
<script>
	$(function(){
		$("#signup-form").on("form-success",function(){
			$__$.alert("<?php $form->l_("Đăng ký thành công. Vui lòng kiểm tra email và kích hoạt tài khoản!") ?>",function(){
				location.href = "<?php echo $this->createUrl('/home') ?>";
			});
		});
	});
</script>