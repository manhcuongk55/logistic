<ul class="nav navbar-nav horizontal-menu hidden-sm hidden-xs ">
	<?php $menu->resetLoop(); while($menu->loop()): ?>
		<li <?php if($menu->currentItemActive(false)): ?>class="active"<?php endif; ?>>
			<a href="<?php echo $menu->currentItem("url") ?>">
				<?php echo $menu->currentItem("content") ?>
			</a>
		</li>
	<?php endwhile; ?>
</ul>