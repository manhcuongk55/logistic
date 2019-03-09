<?php 
	$htmlAttributes_1 = $htmlAttributes;
	$htmlAttributes_2 = $htmlAttributes;
	$htmlAttributes_1["placeholder"] = $config["placeholder_1"];
	$htmlAttributes_2["placeholder"] = $config["placeholder_2"];
?>
<?php Son::load("SAsset")->addExtension("bootstrap-datetimepicker") ?>
<div class="div-table">
	<div style="width:50%; padding-right: 5px;">
		<?php Son::load("SPlugin")->renderInput($name."[0]",ArrayHelper::get($value,0,""),"timestamp_datetimepicker",null,$htmlAttributes_1) ?>
	</div>
	<div style="width:50%; padding-left: 5px;">
		<?php Son::load("SPlugin")->renderInput($name."[1]",ArrayHelper::get($value,1,""),"timestamp_datetimepicker",null,$htmlAttributes_2) ?>
	</div>
</div>