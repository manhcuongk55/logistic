<?php 
	$this->mailTitle = "[Orderhip.com thông báo] Chúc mừng bạn đã khách hàng của Orderhip.com";
	$url = $this->createAbsoluteUrl("/site/confirm_email?token=" . $user->email_active_token);
?>
<div>
	Chúc mừng bạn đã là khách hàng của Orderhip.com.<br/>
	Vui lòng xác nhận lại đăng kí của bạn khi click vào link sau:<br/>
	<a href="<?php echo $url ?>"><?php echo $url ?></a>
</div>