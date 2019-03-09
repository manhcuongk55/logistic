<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://ogp.me/ns#" xmlns:fb="https://www.facebook.com/2008/fbml">
<head>
	<?php $this->renderPartial("application.views.layouts.subviews.head"); ?>
</head>
<body>
	<?php $this->renderPartial("application.views.layouts.subviews.header"); ?>
	<div class="container-1">
		<?php echo $content; ?>
	</div>
	<?php $this->renderPartial("application.views.layouts.subviews.footer"); ?>
</body>
</html>