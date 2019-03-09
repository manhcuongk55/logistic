<?php 
	//$this->mailTitle = l("mail/forgot_password","Forgot password");
	$this->mailTitle = "[Orderhip.com] Xác nhận đổi mật khẩu";
	$forgotPasswordUrl = $this->createAbsoluteUrl("/site/forgot_password?token=" . $user->email_active_token);
	$notForgotPasswordUrl = $this->createAbsoluteUrl("/site/not_forgot_password?token=" . $user->email_active_token);
?>
Bạn quên mật khẩu tại website Orderhip.com? Xin vui lòng click vào đường dẫn dưới đây để khôi phục lại mật khẩu<br/>
<a href="<?php echo $forgotPasswordUrl ?>"><?php echo $forgotPasswordUrl ?></a><br/>
Nếu đó không phải là bạn, vui lòng click vào đường dẫn này<br/>
<a href="<?php echo $notForgotPasswordUrl ?>"><?php echo $notForgotPasswordUrl ?></a><br/>