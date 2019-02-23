<ul class="page-sidebar-menu" data-auto-scroll="true" data-slide-speed="200">
	<?php $menu->resetLoop(); while($menu->loop()): ?>
	<?php 
		$itemMenu = $menu->currentItemGetMenu(); 
		$url = $menu->currentItem("url","javascript:;");
		$icon = $menu->currentItem("icon");
		$currentItemActive = $menu->currentItemActive(true);
		$content = $menu->currentItem("content");
	?>
		<li<?php if($currentItemActive): ?> class="active"<?php endif; ?>>
			<a href="<?php echo $url ?>">
				<?php if($icon): ?>
					<i class="<?php echo $icon ?>"></i>
				<?php endif; ?>
				<span class="title">
					<?php echo $content ?>
				</span>
				<?php if($itemMenu): ?>
					<?php if($currentItemActive): ?>
						<span class="selected"></span>
					<?php endif; ?>
					<span class="arrow<?php if($currentItemActive): ?> open<?php endif; ?>"></span>
				<?php endif; ?>
			</a>
			<?php if($itemMenu): ?>
				<ul class="sub-menu">
					<?php $itemMenu->resetLoop(); while($itemMenu->loop()): ?>
					<li<?php if($itemMenu->currentItemActive(false)): ?> class="active"<?php endif; ?>>
						<a href="<?php echo $itemMenu->currentItem("url") ?>"><?php echo $itemMenu->currentItem("content") ?></a>
					</li>
					<?php endwhile; ?>
				</ul>
			<?php endif; ?>
		</li>
	<?php endwhile; ?>
</ul>