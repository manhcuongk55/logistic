<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://ogp.me/ns#" xmlns:fb="https://www.facebook.com/2008/fbml">
<head>
	<?php $this->renderPartial("application.views.layouts.subviews.head"); ?>
</head>
<body>
	<?php $this->renderPartial("application.views.layouts.subviews.header",array(
		"preventIncludeBelowBar" => true
	)); ?>
	<div class="login-main-area">
		<div class="container">
			<?php echo $content ?>
		</div>
	</div>
</body>
</html>