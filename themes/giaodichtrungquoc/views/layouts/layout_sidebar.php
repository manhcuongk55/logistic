<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<?php $this->renderPartial("application.views.layouts.subviews.head"); ?>
</head>
<body>
	<?php 
	$facebookConfig = Util::param2("accounts","facebook");
	$frontPage = Util::param2("front_page");
?>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.5&appId=<?php echo $facebookConfig["app_id"] ?>";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<style>
	.header-contact a{
		color: inherit !important;
	}
	.header-main .logo img {
		max-height: 70px;
	}
	body {
		background-color: #eee;
	}
</style>
<?php $this->renderPartialTheme("commons.header"); ?>
<div class="container-fluid mg-t10 mg-b10">
	<div class="text-right mg-b5">
		Tỷ giá hôm nay: <span class="bold" money-display data-money="<?php echo Util::param2("setting","vnd_ndt_rate") ?>"></span> VNĐ / 1 NDT
	</div>
	<div class="row">
		<div class="col-sm-3">
			<div class="sidebar-content z-depth-1">
				<?php $this->getMenu()->render($this->getThemePath("components.menu.menu_sidebar")); ?>
			</div>
		</div>
		<div class="col-sm-9">
			<?php echo $content; ?>
		</div>
	</div>
</div>
<?php $this->renderPartialTheme("commons.footer") ?>
</body>
</html>