<div class="list-group">
	<?php $menu->resetLoop(); while($menu->loop()): ?>
		<a href="<?php echo $menu->currentItem("url") ?>" class="list-group-item <?php if($menu->currentItemActive(false)): ?> active<?php endif; ?>">
			<?php echo $menu->currentItem("content") ?>
		</a>
	<?php endwhile; ?>
</div>