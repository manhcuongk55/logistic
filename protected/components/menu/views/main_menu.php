<div class="mainmenu-area home-four-menu hidden-sm hidden-xs clearfix">
	<div id="sticker">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 col-md-12 hidden-sm">
					<div class="mainmenu">
						<nav>
							<ul id="nav">
								<?php $menu->resetLoop(); while($menu->loop()): ?>
									<li>
										<a href="<?php echo $menu->currentItem("url") ?>">
											<?php echo $menu->currentItem("name") ?>
										</a>
										<?php $this->renderPartial("application.components.menu.views.include.main_submenu",array(
											"menu" => $menu
										)) ?>
									</li>
								<?php endwhile; ?>
							</ul>
						</nav>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!--End of Mainmenu-->
<!-- Mobile Menu Area start -->
<div class="mobile-menu-area">
	<div class="container">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12">
				<div class="mobile-menu">
					<nav id="dropdown">
					<ul>
						<?php $menu->resetLoop(); while($menu->loop()): ?>
							<li>
								<a href="<?php echo $menu->currentItem("url") ?>">
									<?php echo $menu->currentItem("name") ?>
								</a>
								<?php $this->renderPartial("application.components.menu.views.include.main_submenu",array(
									"menu" => $menu
								)) ?>
							</li>
						<?php endwhile; ?>
						<?php if($this->getUser()): ?>
							<li>
								<a href="<?php echo $this->createUrl("/user") ?>">
									<?php echo Yii::app()->user->name ?><i class="fa fa-angle-down"></i>
								</a>
							</li>
							<li>
								<li><a href="<?php echo $this->createUrl("/site/logout") ?>"><?php l_("home","Đăng xuất") ?></a></li>
							</li>
						<?php else: ?>
							<li>
								<a href="<?php echo $this->createUrl("/site/login?callback_url=prev") ?>">
									<?php l_("home","Đăng nhập") ?>
								</a>
							</li>
							<li>
								<a href="<?php echo $this->createUrl("/site/login_with_facebook?callback_url=prev") ?>">
									Facebook
								</a>
							</li>
							<li>
								<a href="<?php echo $this->createUrl("/site/login?callback_url=prev") ?>">
									<?php l_("home","Đăng ký") ?>
								</a>
							</li>
						<?php endif; ?>
					</ul>
					</nav>
				</div>
			</div>
		</div>
	</div>
</div>