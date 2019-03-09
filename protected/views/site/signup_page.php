<?php 
 $signupForm = new SignupForm();
?>
<div class="col-sm-8 col-sm-offset-2 col-md-4 col-md-offset-2">
        <h2><?php l_("forms/signup_form","Đăng ký") ?></h2>
        <hr>
        <?php $signupForm->render(); ?>
    </div>
</div>
