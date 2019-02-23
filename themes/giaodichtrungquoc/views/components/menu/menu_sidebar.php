<style>
	.sidebar-content .account-nav .menu-list > li > a:before {
		display: none;
	}
	.sidebar-content .account-nav .menu-list > li > a {
		color: #636363;
	}
</style>
<div class="account-nav">
	<?php if(isset($this->menuName)): ?>
		<div class="menu-title">
			<?php echo $this->menuName ?>
		</div>
	<?php endif; ?>
	<ul class="menu-list">
		<?php $menu->resetLoop(); while($menu->loop()): ?>
			<li>
				<a href="<?php echo $menu->currentItem("url","javascript:;"); ?>" <?php if($menu->currentItemActive(true)): ?>class="active"<?php endif; ?>>
					<i class="<?php echo $menu->currentItem("icon"); ?>"></i>
					<?php echo $menu->currentItem("content") ?>
				</a>
				<?php $subMenu = $menu->currentItemGetMenu(); ?>
				<?php if($subMenu): ?>
					<ul class="inner-menu-list">
						<?php $subMenu->resetLoop(); while($subMenu->loop()): ?>
							<li <?php if($subMenu->currentItemActive()): ?>class="bold"<?php endif; ?>>
								<a href="<?php echo $subMenu->currentItem("url") ?>"><?php echo $subMenu->currentItem("content") ?></a>
							</li>
						<?php endwhile; ?>
					</ul>
				<?php endif; ?>
			</li>
		<?php endwhile; ?>
	</ul>
</div>