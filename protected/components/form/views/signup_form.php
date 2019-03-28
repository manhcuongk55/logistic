<?php $form->open(array(
	"id" => "signup-form"
)); ?>
	<div class="form-group">
		<?php $form->inputField("name",array(
			"class" => "form-control textbox",		//Thienld 24/3 Signup page styling
			"placeholder" => $form->l("Họ và tên")
		)) ?>
	</div>
	<div class="form-group">
		<?php $form->inputField("email",array(
			"class" => "form-control textbox",
			"placeholder" => $form->l("Email")
		)) ?>
	</div>
	<div class="form-group">
		<?php $form->inputField("phone",array(
			"class" => "form-control textbox",
			"placeholder" => $form->l("Số điện thoại")
		)) ?>
	</div>
	<!-- add birthday box in signup page -->
	<div class="form-group">
		<?php $form->inputField("birthday",array(
			"class" => "form-control textbox",
			"placeholder" => $form->l("Ngày sinh")
		)) ?>
	</div>
	<!-- add dropdown list to select warehouse -->
	<!-- xuancuong 20/3 -->
	<div class="form-group">
		<?php echo CHtml::dropDownList("warehouse","warehouse",
				array("HN"=>"Ha Noi", "HCM"=>"TP Ho Chi Minh"),
				array(
					"class" => "textbox"
				));
		?>
	</div>
	<div class="form-group">
		<?php $form->inputField("address",array(
			"class" => "form-control textbox",
			"placeholder" => $form->l("Địa chỉ")
		)) ?>
	</div>
	<div class="form-group">
		<?php $form->inputField("password",array(
			"class" => "form-control textbox",
			"placeholder" => $form->l("Mật khẩu")
		)) ?>
	</div>
	<div class="form-group">
		<?php $form->inputField("retype_password",array(
			"class" => "form-control textbox",
			"placeholder" => $form->l("Nhập lại mật khẩu")
		)) ?>
	</div>
	<hr>
	<div class="form-group">
		<?php $form->submitButton("Đăng ký",array(
			"type" => "button",
			"class" => "custom-button1"
		)) ?>
	</div>
	<hr>
<?php $form->close(); ?>
<script>
	$(function(){
		$("#signup-form").on("form-success",function(){
			location.href = "<?php echo $this->createUrl('/site/redirect_page_after_signup') ?>";
		});
	});
</script>

<!-- Thienld 24/3 Signup page styling -->
<style>
.textbox {
	border-radius: 15px;
    border: 2px solid #00BFFF;
    padding: 20px; 
	width: 100%
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
</style>