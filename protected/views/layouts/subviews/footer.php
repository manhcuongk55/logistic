<?php
	$frontPage = Util::param2("front_page");
	// var_dump($frontPage); die();
	$subscribeForm = new SubscribeForm();
	$transferAccounts = $frontPage["transfer_accounts"];
	// $transferAccounts = array();
?>
<!--Footer Widget Area Start-->
<div class="footer-widget-area">
	<div class="container">
		<div class="footer-widget-padding">
			<div class="row">
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
					<div class="single-widget">
						<div class="footer-widget-title">
							<h3>Tài khoản ngân hàng</h3>
						</div>
						<?php foreach($transferAccounts as $i => $account): ?>
							<div class="footer-widget-list ">
								<ul class="address">
									<li>Chủ tài khoản: <?php echo $account["owner"] ?></li>
									<li>Ngân hàng: <?php echo $account["bank"] ?></li>
									<li>Số tài khoản: <?php echo $account["id_number"] ?></li>
									<li>Chi nhánh: <?php echo $account["branch"] ?></li>
								</ul>
							</div>
							<hr/>
						<?php endforeach; ?>
					</div>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
					<div class="single-widget">
						<div class="footer-widget-title">
							<h3><?php l_("home","Liên hệ") ?></h3>
						</div>
						<div class="footer-widget-list ">
							<ul class="address">
								<li><span class="fa fa-map-marker"></span> <?php echo $frontPage["address"] ?></li>
								<li><span class="fa fa-phone"></span> <?php echo $frontPage["phone"] ?></li>
								<li class="support-link"><span class="fa fa-envelope-o"></span> <a href="mailto:<?php echo $frontPage["email"] ?>"><?php echo $frontPage["email"] ?></a></li>
							</ul>
						</div>
						<div class="footer-widget-list ">
							<ul class="social-link text-right">
								<li><a href="<?php echo $frontPage["facebook"] ?>"><i class="fa fa-facebook"></i></a></li>
								<li><a href="<?php echo $frontPage["twitter"] ?>"><i class="fa fa-twitter"></i></a></li>
								<li><a href="<?php echo $frontPage["pinterest"] ?>"><i class="fa fa-pinterest"></i></a></li>
								<li><a href="<?php echo $frontPage["googleplus"] ?>"><i class="fa fa-google-plus"></i></a></li>
							</ul>
						</div>
					</div>
				</div>
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
					<div class="single-widget">
						<div class="footer-widget-title">
							<h3>Fanpage</h3>
						</div>
						<div class="footer-widget-list ">
							<div class="fb-page" data-href="<?php echo $frontPage["facebook_page"] ?>" data-tabs="timeline" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true" data-height="350"><blockquote cite="<?php echo $frontPage["facebook_page"] ?>" class="fb-xfbml-parse-ignore"><a href="<?php echo $frontPage["facebook_page"] ?>">Orderhip - Nhập Hàng Trung Quốc</a></blockquote></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!--End of Footer Widget Area-->
<!--Footer Area Start-->
<footer class="footer">
	<div class="container">
		<div class="footer-padding">
			<div class="row">
				<div class="col-lg-7 col-md-7 col-sm-8">
					<p class="">
						<a href="<?php echo $this->createUrl("/collaborator") ?>">CTV Đăng nhập</a>
					</p>
				</div>
				<div class="col-lg-5 col-md-5 col-sm-4 text-right">
					<nav>
					<!--<ul id="footer-menu">
						<li><a href="#">Về HươngLee</a></li>
						<li><a href="#">Liên hệ</a></li>
						<li><a href="#">Chăm sóc khách hàng</a></li>
						<li><a href="#">Điều khoản dịch vụ</a></li>
					</ul>-->
					<!-- <p class="author">
						Copyright ©2016 <a href="#">orderhip.com</a> All Rights Reserved.
					</p> -->
					</nav>
				</div>
			</div>
		</div>
	</div>
</footer>
<!--End of Footer Area-->
<?php if(Util::param2("setting","popup_banner_enabled")): ?>
	<?php $this->renderPartial("application.views.layouts.subviews.footer.popup_banner"); ?>
<?php endif; ?>

<?php //$this->renderPartial("application.views.layouts.subviews.plugins.live_chat"); ?>
<?php //$this->renderPartial("application.views.layouts.subviews.plugins.social_sharing"); ?>
<?php //$this->renderPartial("application.views.layouts.subviews.plugins.ga"); ?>


<!-- Facebook Pixel Code -->
<script>
  !function(f,b,e,v,n,t,s)
  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
  n.queue=[];t=b.createElement(e);t.async=!0;
  t.src=v;s=b.getElementsByTagName(e)[0];
  s.parentNode.insertBefore(t,s)}(window, document,'script',
  'https://connect.facebook.net/en_US/fbevents.js');
  fbq('init', '401269217012169');
  fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
  src="https://www.facebook.com/tr?id=401269217012169&ev=PageView&noscript=1"
/></noscript>
<!-- End Facebook Pixel Code -->

<style>
	.float-container{
		position:fixed;
		bottom:40px;
		left:40px;
		z-index:999;
	}

	.float {
		display:block;
		margin-top: 35px;
		position:relative;
		text-align: center;
	}

	.float > div {
		display:block;
		text-align:center;
		width:60px;
		height:60px;
		border-radius:50px;
		position: relative;
		left: 50%;
		margin-left: -30px;
		background-color:#0C9;
		color:#FFF;
		/* box-shadow: 2px 2px 3px #999; */
	}

	.float > div > i {
		margin-top: 22px;
	}

	.float > span {
		font-weight: bold;
		text-decoration: none;
		color:#0C9;
	}
</style>
<?php $links = $frontPage["float_links"] ?>
<div class="float-container">
	<a href="<?php echo $this->createUrl("/home/check_price") ?>" title="Check giá sản phẩm về Việt Nam" class="float">
		<div>
			<i class="fa fa-umbrella"></i>
		</div>
		<span>Check giá sản phẩm</span>
	</a>
	<a href="<?php echo $links["extension_link"] ?>" title="Công cụ đặt hàng" class="float">
		<div>
			<i class="fa fa-wrench"></i>
		</div>
		<span>Công cụ đặt hàng</span>
	</a>
</div>