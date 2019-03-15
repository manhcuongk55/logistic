<?php $form->open(array(
  "id" => "newsletter"
)) ?>
	<div class="newsletter-content">
		<?php $form->inputField("email",array(
			"placeholder" => $form->l("Điền email của bạn")
		)) ?>
		<?php $form->submitButton("<span>Đăng ký</span>",array(
			"class" => "button"
		)) ?>
	</div>
<?php $form->close(); ?>
<script>
  $("#newsletter").on("form-success",function(){
    $__$.alert('<?php $form->l_("Đăng ký nhận email thành công!") ?>',function(){
      location.reload();
    })
  })
</script>