<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<?php $this->renderPartial("application.views.layouts.subviews.head"); ?>
    <style>
    	.page-container {
    		padding: 20px 20px 0 20px !important;
    	}
    </style>
</head>
<body class="page-full-width">
	<div class="row">
		<div class="col-md-12">
			<?php echo $content ?>
		</div>
	</div>
</body>
</html>