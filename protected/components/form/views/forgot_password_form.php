<?php 
	$this->pageTitle = "Quên mật khẩu";
?>
<div class="row">
	<div class="col-md-4 col-md-offset-4 text-center">
		<h3>Quên mật khẩu</h3>
		<?php $form->open(array(
			"id" => "password-form"
		)) ?>
			<div class="fz12 fsi mg-b10 text-center">Nhập email tài khoản của bạn</div>
			<div class="form-group">
				<?php $form->inputField("email",array(
					"class" => "form-control input-sm",
					"placeholder" => "Email"
				)) ?>
			</div>
			<div>
				<?php $form->submitButton("Gửi",array(
					"class" => "btn btn-sm btn-primary btn-block"
				)) ?>
			</div>
			<div class="fz12 fsi mg-t10 text-center">Đường dẫn thay đổi mật khẩu sẽ được gửi về email của bạn</div>
		<?php $form->close(); ?>
	</div>
</div>
<script>
	$(function(){
		$("#password-form").on("form-success",function(){
			$__$.alert("Yêu cầu đổi mật khẩu thành công! Vui lòng kiểm tra email để nhận thông tin mật khẩu mới!",function(){
				location.href = "/";
			});
		});
	});
</script>