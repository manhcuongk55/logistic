<?php $item->renderBegin("tr",array(
	"class" => "list-item"
)) ?>
	<?php if($html->willUseCheckbox()): ?>
		<td>
			<?php $item->renderCheckbox(array(
				"input-checkbox-button" => ""
			)); ?>
		</td>
	<?php endif; ?>
	<?php foreach($list->config["admin"]["columns"] as $attr): ?>
		<td><?php $item->renderAttr($attr) ?></td>
	<?php endforeach; ?>
	<?php if($list->config["admin"]["action"]): ?>
		<td>
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
		</td>
	<?php endif; ?>
<?php $item->renderEnd(); ?>