<?php // $name, $value, $config, $htmlAttributes
	$htmlAttributes["file-preview-input-data"] = "";
	$htmlAttributes["value"] = "";
?>
<div file-preview style="max-width: 200px;">
	<input type="file" class="form-control" file-preview-input value="<?php echo $value ?>" />
	<img style="width: 100%; margin-top: 10px;" file-preview-image />
	<?php echo CHtml::hiddenField($name . "[base64]",$value,$htmlAttributes) ?>
</div>