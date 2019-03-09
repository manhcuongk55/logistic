<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
    <title><?php echo $this->pageTitle; ?></title>
</head>
<body>
	<header id="header" class="header">
		<nav id="topbar" role="navigation" class="navbar navbar-default navbar-static-top">
			<div class="navbar-header">
				<button type="button" data-toggle="collapse" data-target=".sidebar-collapse" class="navbar-toggle">
				<span class="sr-only">Toggle navigation</span><span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span></button>
				<a id="logo" href="/admin" class="navbar-brand">
					<span class="fa fa-rocket"></span>
					<span class="logo-text" style="font-size:22px;">Buyplus Admin</span>
				</a>
			</div>
			<div class="topbar-main">
				<?php $this->getMenu()->render(); ?>
			</div>
		</nav> 
	</header>
	<div id="wrapper-full" class="the-body">
		<div id="page-wrapper-full">
			<?php echo $content; ?>
		</div>
	</div>
	<div id="footer" class="container">
		<div class="row">
			<div class="col-lg-6">
				<div class="copyright text-left">Copyright &copy; <?php echo date("Y") ?> Buyplus - All right reserved</div>
			</div>
			<div class="col-lg-6">
			</div>
		</div>
	</div>
</body>
</html>