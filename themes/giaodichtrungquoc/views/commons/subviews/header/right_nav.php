<?php if($this->getUser()): ?>
	<div class="account-menu">
		<ul>
			<li>
				<a href="javascript:;">
					<?php echo $this->getUser()->name ?><i class="fa fa-angle-down"></i>
				</a>
				<ul class="account-dropdown">
					<li><a href="<?php echo $this->createUrl("logout") ?>"><?php l_("home","Đăng xuất") ?></a></li>
				</ul>
			</li>
		</ul>
	</div>
	<?php if(ArrayHelper::get($this->data,"collaboratorNotificationEnabled")): ?>
		<?php $this->renderFragment("application.views.layouts.subviews.header.notifications",array(
			"user_type" => Notification::USER_TYPE_COLLABORATOR,
		),"layouts-header-notifications",array(
			CacheHelper::getKeyForUser(CacheHelper::HTTP_CACHE_KEY)
					),array(
				"differentByUser" => true
		)) ?>
	<?php endif; ?>
<?php else: ?>
	<div class="account-menu">
		<ul>
			<li>
				<a href="<?php echo $this->createUrl("login") ?>">
					<?php l_("home","Đăng nhập") ?>
				</a>
			</li>
		</ul>
	</div>
<?php endif; ?>