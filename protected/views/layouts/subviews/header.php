<?php 
	$facebookConfig = Util::param2("accounts","facebook");
	$frontPage = Util::param2("front_page");
	$userType = Yii::app()->user->getClass();
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
		max-height: 100px;
	}

	a.single-service {
		display: block;
		cursor: pointer;
	}
</style>
<header>
	<div class="header-top-home-four">
		<div class="container">
			<div class="header-container">
				<div class="row">
					<div class="col-md-6 col-sm-7">
						<div class="header-contact">
							<span class="email"><a href="mailto:<?php echo $frontPage["email"] ?>"><?php echo $frontPage["email"] ?></a></span> / <span class="phone"><a href="tel:<?php echo $frontPage["phone"] ?>"><?php echo $frontPage["phone"] ?></a></span>
						</div>
					</div>
					<div class="col-md-6 col-sm-5">
						<div class="currency-language">
							<?php $this->renderFragment("application.views.layouts.subviews.header.right_nav",null,"layouts-header-right-nav-" . $userType . "-" . @$this->getUser()->id,array(
					CacheHelper::getKeyForUser(CacheHelper::HTTP_CACHE_KEY)
							),array(
								"differentByUser" => true
							)) ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php if(!isset($preventIncludeBelowBar)): ?>
		<div class="header-main">
			<div class="container">
				<div class="header-content">
					<div class="row">
						<div class="col-lg-4 col-md-3">
							<div class="logo">
								<a href="<?php echo $this->createUrl("/home") ?>"><img src="/img/logo.jpg" alt="MOZAR"></a>
							</div>
						</div>
						<div class="col-lg-8 col-md-9 hidden-sm hidden-xs">
							<div class="service-home-four">
								<div class="row">
									<div class="col-md-4 col-sm-4">
										<a href="<?php echo $this->createUrl("/user/create_order") ?>" class="single-service">
											<span class="fa fa-truck"></span>
											<div class="service-text-container">
												<h3>Tạo đơn hàng</h3>
												<p>
													Đơn giản, tiện ích
												</p>
											</div>
										</a>
									</div>
									<div class="col-md-4 col-sm-4 hidden-sm">
										<a href="<?php echo $this->createUrl("/home/contact") ?>" class="single-service">
											<span class="fa fa-dropbox"></span>
											<div class="service-text-container">
												<h3>Thanh toán</h3>
												<p>
													Tiền mặt, chuyển khoản
												</p>
											</div>
										</a>
									</div>
									<div class="col-md-4 col-sm-4 hidden-sm">
										<a href="<?php echo $this->createUrl("/user/all_orders") ?>" class="single-service">
											<span class="fa fa-calendar-o"></span>
											<div class="service-text-container">
												<h3>Nhận hàng</h3>
												<p>
													Kho hàng tại HN
												</p>
											</div>
										</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="hidden-md hidden-lg">
			<a href="<?php echo $this->createUrl("/user/create_order") ?>" class="btn btn-block btn-primary">Tạo đơn hàng</a>
		</div>
		<?php if(CacheHelper::beginFragment("header-category-menu",array(
			"header-category-menu", CacheHelper::getKeyForUser(CacheHelper::HTTP_CACHE_KEY)
		),array(
			"differentByUser" => true
		))): ?>
			<?php Son::load("MainMenu")->render(); ?>
			<?php CacheHelper::endFragment(); ?>
		<?php endif; ?>
	<?php endif; ?>
</header>