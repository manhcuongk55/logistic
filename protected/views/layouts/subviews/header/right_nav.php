<?php if($this->getUser()): ?>
	<div class="account-menu">
		<ul>
			<li>
				<a href="<?php echo $this->createUrl("/user") ?>">
					<?php echo Yii::app()->user->name ?><i class="fa fa-angle-down"></i>
				</a>
				<ul class="account-dropdown">
					<li><a href="<?php echo $this->createUrl("/user") ?>"><?php l_("home","Trang cá nhân") ?></a></li>
					<li><a href="<?php echo $this->createUrl("/user/create_order") ?>"><?php l_("home","Giỏ hàng") ?></a></li>
					<li><a href="<?php echo $this->createUrl("/site/logout") ?>"><?php l_("home","Đăng xuất") ?></a></li>
				</ul>
			</li>
		</ul>
	</div>
	<?php $this->renderFragment("application.views.layouts.subviews.header.notifications",array(
		"user_type" => Notification::USER_TYPE_CUSTOMER,
	),"layouts-header-notifications",array(
		CacheHelper::getKeyForUser(CacheHelper::HTTP_CACHE_KEY)
				),array(
			"differentByUser" => true
	)) ?>
	<?php $this->renderFragment("application.views.layouts.subviews.header.cart",null,"layouts-header-cart",array(
		CacheHelper::getKeyForUser(CacheHelper::HTTP_CACHE_KEY)
				),array(
			"differentByUser" => true
	)) ?>
<?php else: ?>
	<div class="account-menu">
		<ul>
			<li>
				<a href="<?php echo $this->createUrl("/site/login_with_facebook?callback_url=prev") ?>">
					<i class="fa fa-facebook"></i> Facebook
				</a>
			</li>
		</ul>
	</div>
	<div class="account-menu">
		<ul>
			<li>
				<a href="<?php echo $this->createUrl("/site/login?callback_url=prev") ?>">
					<?php l_("home","Đăng ký") ?>
				</a>
			</li>
		</ul>
	</div>
	<div class="account-menu">
		<ul>
			<li>
				<a href="<?php echo $this->createUrl("/site/login?callback_url=prev") ?>">
					<?php l_("home","Đăng nhập") ?>
				</a>
			</li>
		</ul>
	</div>
<?php endif; ?>