<div class="row">
	<div class="col-md-4 col-md-offset-4 text-center">
		<h3>Nhập mật khẩu mới</h3>
		<?php $form->open(array(
			"class" => "ez-form ez-form-normal",
			"id" => "password-form"
		)) ?>
			<?php $form->inputField("token") ?>
			<div class="form-group">
				<?php $form->inputField("new_password",array(
					"class" => "form-control input-sm",
					"placeholder" => "Mật khẩu mới"
				)) ?>
			</div>
			<div class="form-group">
				<?php $form->inputField("retype_new_password",array(
					"class" => "form-control input-sm",
					"placeholder" => "Nhập lại mật khẩu mới"
				)) ?>
			</div>
			<div>
				<?php $form->submitButton("Thay đổi",array(
					"class" => "btn btn-sm btn-primary btn-block"
				)) ?>
			</div>
		<?php $form->close(); ?>
	</div>
</div>
<script>
	$(function(){
		$("#password-form").on("form-success",function(){
			$__$.alert("Đổi mật khẩu thành công!",function(){
				location.href = "<?php echo $this->createUrl('/home/login') ?>";
			});
		});
	});
</script>