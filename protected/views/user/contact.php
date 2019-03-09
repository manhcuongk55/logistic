<?php
    $userForm = new UserForm();
?>
<h2 class="account-section-title"><b>Liên hệ CTV:</b></h2>
<div class="row">
    <div class="col-md-6">
        <h3>CTV sẽ liên lạc với bạn với thông tin sau:</h3>
        <hr/>
        <?php $userForm->render(); ?>
    </div>
    <div class="col-md-6">
        <h3>Hoặc bạn có thể liên hệ trực tiếp với CTV</h3>
        <hr/>
        <?php $this->renderPartial('subviews/sale_contact_info') ?>
    </div>
</div>
<?php if(Input::get("require_phone_number")): ?>
<script>
    $(function(){
        alert("Bạn cần cập nhập số điện thoại!");
        $("[name='user[phone]']").focus();
    })
</script>
<?php endif; ?>