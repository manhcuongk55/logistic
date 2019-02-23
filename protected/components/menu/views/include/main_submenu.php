<?php $subMenu = $menu->currentItemGetMenu(); ?>
<?php if($subMenu): ?>
	<ul class="sub-menu">
		<?php $subMenu->resetLoop(); while($subMenu->loop()): ?>
			<li>
				<a href="<?php echo $subMenu->currentItem("url") ?>">
					<?php echo $subMenu->currentItem("name") ?>
				</a>
			</li>
		<?php endwhile; ?>
	</ul>
<?php endif; ?>