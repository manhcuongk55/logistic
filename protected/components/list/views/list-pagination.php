<div class="row" list-pagination>
	<div class="col-md-12">
		<div class="pagination-content">
			<div class="pagination" data-num-page="<?php echo $pagination->lastPage() ?>">
				<ul class="list">
					<?php if(($pagination->lastPage() > 1) && $pagination->hasCalculated()): ?>
						<?php if($pagination->canFirst()): ?>
							<li><a list-page="<?php echo $pagination->firstPage() ?>" href="<?php echo $pagination->firstUrl() ?>"><i class="fa fa-caret-left"></i></a></li>
						<?php endif; ?>
						<?php if($pagination->canBack()): ?>
							<li><a list-page="<?php echo $pagination->backPage() ?>" href="<?php echo $pagination->backUrl() ?>"><i class="fa fa-angle-left"></i></a></li>
						<?php endif; ?>
						<?php $pagination->resetLoop(); while($pagination->loop()): ?>
							<li<?php if($pagination->currentItemActive()): ?> class="active"<?php endif; ?>><a list-page="<?php echo $pagination->currentPage() ?>" href="<?php echo $pagination->pageUrl() ?>"><?php echo $pagination->currentPage() ?></a></li>
						<?php endwhile; ?>
						<?php if($pagination->canNext()): ?>
							<li><a list-page="<?php echo $pagination->nextPage() ?>" href="<?php echo $pagination->nextUrl() ?>"><i class="fa fa-angle-right"></i></a></li>
						<?php endif; ?>
						<?php if($pagination->canLast()): ?>
							<li><a list-page="<?php echo $pagination->lastPage() ?>" href="<?php echo $pagination->lastUrl() ?>"><i class="fa fa-caret-right"></i></a></li>
						<?php endif; ?>
					<?php endif; ?>
				</ul>
			</div>
		</div>
	</div>
</div>