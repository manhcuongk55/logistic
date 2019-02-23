<?php $html->begin(); ?>
    <div class="blog-page-area">
        <?php $html->resetLoop(); while($html->loop()): ?>
            <?php $html->renderCurrentItem() ?>
        <?php endwhile; ?>
    </div>
    <?php $list->getPagination()->render(); ?>
<?php $html->end(); ?>