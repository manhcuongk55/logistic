<?php 
 $signupForm = new SignupForm();
?>
<div class="col-sm-6 col-sm-offset-4 col-md-5 box">
        <h2 class="title1"><?php l_("forms/signup_form","Đăng ký") ?></h2>
        <hr>
        <?php $signupForm->render(); ?>
    </div>
</div>

<style>
.box {
    padding-top: 25px;
    padding-left: 15px;
    padding-right: 15px;
    padding-bottom: 25px;

    /* drop shadow */
    -webkit-box-shadow: 3px 3px 10px 0px rgba(0,0,0,0.63);
    -moz-box-shadow: 3px 3px 10px 0px rgba(0,0,0,0.63);
    box-shadow: 3px 3px 10px 0px rgba(0,0,0,0.63);

    /* rounded edge */
    border-radius: 25px;
    border: 3px solid #73AD21;
    background-color: #ffffff; /* Fake Green */
    color: #ffffff;
}
.title1 {
    font-family: Helvetica, sans-serif;
    text-align: center;
    font-weight: bold;
    font-size: 200%;
}
</style>