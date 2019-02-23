<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
    <title><?php echo $this->pageTitle; ?></title>
</head>
<body class="page-header-fixed page-sidebar-closed-hide-logo">
	<style>
		.page-content {
			padding-top: 0px !important;
		}
	</style>
	<!-- BEGIN HEADER -->
	<div class="page-header navbar navbar-fixed-top">
		<!-- BEGIN TOP NAVIGATION BAR -->
		<div class="page-header-inner">
			<!-- BEGIN LOGO -->
			<div class="page-logo">
				<a href="<?php echo $this->data["homeUrl"] ?>" class="text-style">
					<!--<img src="assets/img/logo.png" alt="logo" class="img-responsive"/>-->
					<h1 class="text-primary"><?php echo $this->data["brandName"] ?></h1>
				</a>
				<!-- END LOGO -->
				<!-- BEGIN RESPONSIVE MENU TOGGLER -->
				<a href="javascript:;" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
					<img src="/themes/metronic/assets/extensions/metronic/img/menu-toggler.png" alt=""/>
				</a>
			</div>
			<!-- END RESPONSIVE MENU TOGGLER -->
			<!-- BEGIN TOP NAVIGATION MENU -->
			<div class="page-top">
				<div class="top-menu">
					<ul class="nav navbar-nav pull-right">
						<?php if(!Yii::app()->user->isGuest): ?>
						<li class="dropdown user">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
								<!--<img alt="" src="assets/img/avatar1_small.jpg"/>-->
								<span class="username">
									<?php echo Yii::app()->user->getState("name") ?>
								</span>
								<i class="fa fa-angle-down"></i>
							</a>
							<ul class="dropdown-menu">
								<!--<li>
									<a href="extra_profile.html">
										<i class="fa fa-user"></i> My Profile
									</a>
								</li>
								<li class="divider">
								</li>-->
								<li>
									<a href="<?php echo $this->createUrl("logout") ?>">
										<i class="fa fa-key"></i> Đăng xuất
									</a>
								</li>
							</ul>
						</li>
						<!-- END USER LOGIN DROPDOWN -->
						<?php else: ?>
						<li>
							<a href="<?php echo $this->createUrl("login") ?>">Đăng nhập</a>
						</li>
						<?php endif; ?>
					</ul>
				</div>
			</div>
			<!-- END TOP NAVIGATION MENU -->
		</div>
		<!-- END TOP NAVIGATION BAR -->
	</div>
	<!-- END HEADER -->
	<div class="clearfix">
	</div>
	<!-- BEGIN CONTAINER -->
	<div class="page-container">
		<!-- BEGIN SIDEBAR -->
		<div class="page-sidebar-wrapper">
			<div class="page-sidebar navbar-collapse collapse">
				<!-- BEGIN SIDEBAR MENU -->
				<?php $this->getMenu()->render($this->getThemePath("components.menu.menu_sidebar")); ?>
				<!-- END SIDEBAR MENU -->
			</div>
		</div>
		<!-- END SIDEBAR -->
		<!-- BEGIN CONTENT -->
		<div class="page-content-wrapper">
			<div class="page-content">
				<!-- BEGIN PAGE CONTENT-->
				<div class="row">
					<div class="col-md-12">
						<?php echo $content ?>
					</div>
				</div>
				<!-- END PAGE CONTENT-->
			</div>
		</div>
		<!-- END CONTENT -->
	</div>
	<!-- END CONTAINER -->
	<!-- BEGIN FOOTER -->
	<?php $this->renderPartialTheme("commons.footer") ?>
	<!-- END FOOTER -->
</body>
<script>
	$(function(){
		Metronic.init(); 
	});
</script>
</html>