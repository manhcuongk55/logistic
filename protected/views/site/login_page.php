<?php
    $loginForm = new LoginForm();
    $signupForm = new SignupForm();
?>
<div class="row">
    <div class="col-sm-8 col-sm-offset-2 col-md-4 col-md-offset-1">
        <h2><?php l_("forms/login_form","Đăng nhập") ?></h2>
        <hr>
        <?php $loginForm->render(); ?>
    </div>
    <div class="col-sm-8 col-sm-offset-2 col-md-4 col-md-offset-2">
        <h2><?php l_("forms/signup_form","Đăng ký") ?></h2>
        <hr>
        <?php $signupForm->render(); ?>
    </div>
</div>
