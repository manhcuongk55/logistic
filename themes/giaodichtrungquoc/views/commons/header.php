<?php
	$frontPage = Util::param2("front_page");
?>
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
							<?php $this->renderFragment("webroot.themes.giaodichtrungquoc.views.commons.subviews.header.right_nav",null,"layouts-header-right-nav" . "-" . @$this->getUser()->id,array(
					CacheHelper::getKeyForUser(CacheHelper::HTTP_CACHE_KEY,@$this->getUser()->id)
							),array(
								"differentByUser" => true
							)) ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</header>
<div class="hidden-md hidden-lg text-center">
	<?php if($this->getUser()): ?>
		<div>
			<ul>
				<li>
					<a href="javascript:;">
						<?php echo $this->getUser()->name ?>
					</a>
					<li><a class="bold" href="<?php echo $this->createUrl("logout") ?>"><?php l_("home","Đăng xuất") ?></a></li>
				</li>
			</ul>
		</div>
	<?php else: ?>
		<div>
			<ul>
				<li>
					<a href="<?php echo $this->createUrl("login") ?>">
						<?php l_("home","Đăng nhập") ?>
					</a>
				</li>
			</ul>
		</div>
	<?php endif; ?>
</div>