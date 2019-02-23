<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<?php $this->renderPartial("application.views.layouts.subviews.head"); ?>
	<style>
		body {
			background-color: #eee;
		}
	</style>
</head>
<body class="login">
	<?php $this->renderPartialTheme("commons.header") ?>
	<div class="login-main-area">
		<div class="container">
			<div class="row">
				<div class="col-md-4 col-md-offset-4">
					<div class="z-depth-1 pd-a10">
						<?php echo $content ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>