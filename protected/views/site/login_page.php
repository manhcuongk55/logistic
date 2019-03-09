<?php
    $loginForm = new LoginForm();
?>
<div class="row">
    <div class="col-sm-8 col-sm-offset-2 col-md-4 col-md-offset-1">
        <h2><?php l_("forms/login_form","Đăng nhập") ?></h2>
        <hr>
        <?php $loginForm->render(); ?>
    </div>