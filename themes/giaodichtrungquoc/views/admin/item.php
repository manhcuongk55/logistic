<?php 
	$actionRendered = false;
?>
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
		<td>
			<?php if($attr=="__action__"): $actionRendered = true; ?>
				<?php $this->renderPartial($this->getThemePath("admin.subviews.item_action"),array(
					"item" => $item,
					"list" => $list
				)) ?>
			<?php else: ?>
				<?php $item->renderAttr($attr) ?>
			<?php endif; ?>
		</td>
	<?php endforeach; ?>
	<?php if(!$actionRendered && $list->config["admin"]["action"]): ?>
		<td>
			<?php $this->renderPartial($this->getThemePath("admin.subviews.item_action"),array(
				"item" => $item,
				"list" => $list
			)) ?>
		</td>
	<?php endif; ?>
<?php $item->renderEnd(); ?>