<?php if($item->hasActionDetail()): ?>
    <?php $item->renderActionDetail(); ?>
<?php endif; ?>
<?php if($item->hasActionUpdate()): ?>
    <?php $item->renderActionUpdate(); ?>
<?php endif; ?>
<?php if($item->hasActionDelete()): ?>
    <?php $item->renderActionDelete(); ?>
<?php endif; ?>
<?php if($list->config["admin"]["actionButtons"]): ?>
    <?php foreach($list->config["admin"]["actionButtons"] as $actionButton): ?>
        <?php $item->renderAction($actionButton); ?>
    <?php endforeach; ?>
<?php endif; ?>