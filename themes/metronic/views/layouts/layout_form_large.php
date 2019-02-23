<?php /* @var $this Controller */
	Son::load("SAsset")->addCssFile("/themes/metronic/assets/extensions/metronic/css/pages/login.css");
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
    <title><?php echo $this->pageTitle; ?></title>
    <style>
    	.login .content {
    		width: auto;
    	}
    </style>
</head>
<body class="login" style="padding: 50px !important;">
	<div class="content">
		<!-- BEGIN LOGIN FORM -->
		<?php echo $content ?>
		<!-- END LOGIN FORM -->
	</div>
</body>
<script>
	$(function(){
		Metronic.init(); 
	});
</script>
</html>