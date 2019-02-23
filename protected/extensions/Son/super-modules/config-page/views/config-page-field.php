<?php if($item["dataType"]=="field"): ?>
	<?php 
		$item["htmlAttributes"]["class"] = "form-control"; 
		$item["htmlAttributes"]["data-name"] = $fieldName;
		$item["htmlAttributes"]["data-level"] = $level;
		$item["htmlAttributes"]["is-valid"] = $isValid;
	?>
	<div class="form-group">
		<?php if($item["label"]): ?>
			<label><?php echo $item["label"] ?></label>
		<?php endif; ?>
		<?php Son::load("SPlugin")->renderInput($fieldName,$value,$item["type"],$item["config"],$item["htmlAttributes"]) ?>
	</div>
<?php elseif($item["dataType"]=="object"): ?>
	<div class="object-field-container" data-array="" data-name="<?php echo $fieldName ?>" data-level="<?php echo $level ?>" is-valid="<?php echo $isValid ?>">
		<h5>
			<button type="button" class="btn btn-sm btn-info" object-field-collapse><i class="fa fa-minus"></i></button> 
			<?php echo $item["label"] ?>
		</h5>
		<div class="field-items object-field-items" array-items>
			<?php foreach($item["items"] as $subItemName => $subItem): $subItemValue = ArrayHelper::get($value,$subItemName);?>
				<div array-item="<?php echo $subItemName ?>">
					<?php $this->renderPartial($fieldViewPath,array(
						"fieldName" => $subItemName,
						"item" => $subItem,
						"value" => $subItemValue,
						"configPage" => $configPage,
						"fieldViewPath" => $fieldViewPath,
						"level" => $level + 1,
						"isValid" => "1"
					)) ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
<?php elseif($item["dataType"]=="array"): ?>
	<div class="array-field-container" data-array data-name="<?php echo $fieldName ?>" data-level="<?php echo $level ?>" is-valid="<?php echo $isValid; ?>">
		<h5>
			<button type="button" class="btn btn-sm btn-info" array-field-collapse><i class="fa fa-minus"></i></button>
			<?php echo $item["label"] ?> 
			<button type="button" array-add class="btn btn-sm btn-success">Add <i class="fa fa-plus"></i></button>
		</h5>
		<div class="field-items array-field-items">
			<div class="hidden" array-item-template>
				<div class="div-table v-top-parent" array-field-item array-item>
					<div>
						<?php $this->renderPartial($fieldViewPath,array(
							"fieldName" => "",
							"item" => $item["itemTemplate"],
							"value" => ArrayHelper::get($item,"itemDefaultValue"),
							"configPage" => $configPage,
							"fieldViewPath" => $fieldViewPath,
							"level" => $level + 1,
							"isValid" => "0"
						)) ?>
					</div>
					<div class="array-remove-container fit-content">
						<button type="button" array-remove class="btn btn-sm btn-danger"><i class="fa fa-close"></i></button>
					</div>
				</div>
			</div>
			<div array-items>
				<?php if($value): ?>
					<?php foreach($value as $subValue): ?>
						<div class="div-table v-top-parent" array-field-item array-item>
							<div>
								<?php $this->renderPartial($fieldViewPath,array(
									"fieldName" => "",
									"item" => $item["itemTemplate"],
									"value" => $subValue,
									"configPage" => $configPage,
									"fieldViewPath" => $fieldViewPath,
									"level" => $level + 1,
									"isValid" => "1"
								)) ?>
							</div>
							<div class="array-remove-container fit-content">
								<button type="button" array-remove class="btn btn-sm btn-danger"><i class="fa fa-close"></i></button>
							</div>
						</div>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
<?php endif; ?>