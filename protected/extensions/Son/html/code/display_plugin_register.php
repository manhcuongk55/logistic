<?php
$sPlugin = Son::load("SPlugin");
$sPlugin->registerDisplayPlugin("link","function",function($value,$config,$htmlAttributes){
	if(ArrayHelper::get($config,"newTab")){
		$htmlAttributes["target"] = "_blank";
	}
	if(!isset($config["content"]))
	{
		if(!$value){
			return;
		}
		echo CHtml::link($value,$value,$htmlAttributes);
		return;
	}
	echo CHtml::link($config["content"],$config["href"],$htmlAttributes);
});

$sPlugin->registerDisplayPlugin("image","function",function($value,$config,$htmlAttributes){
	echo CHtml::image(ArrayHelper::get($config,"url",$value),ArrayHelper::get($config,"alt"),$htmlAttributes);
});

$sPlugin->registerDisplayPlugin("image_thumbnail","file","ext.Son.html.views.display_plugin.image_thumbnail",array(
	//"fancybox"
));

$sPlugin->registerDisplayPlugin("timestamp","function",function($value,$config,$htmlAttributes){
	$htmlAttributes["timestamp"] = $value;
	echo CHtml::tag("span",$htmlAttributes);
});

$sPlugin->registerDisplayPlugin("time_format","function",function($value,$config,$htmlAttributes){
	$displayValue = date($config["format"],$value);
	echo CHtml::tag("span",$htmlAttributes,$displayValue);
},array(
	"format" => "d-m-Y"
));

$sPlugin->registerDisplayPlugin("label_model","function",function($value,$config,$htmlAttributes){
	$attr = $config["attr"];
	$modelClass = $config["modelClass"];
	$label = $modelClass::model()->listGetLabel($attr,$value);
	echo CHtml::tag("span",$htmlAttributes,$label);
},null,null,array(
	"class" => "label label-success"
));

$sPlugin->registerDisplayPlugin("label_model_2","function",function($value,$config,$htmlAttributes){
	$attr = $config["attr"];
	$modelClass = $config["modelClass"];
	$listConfig = $modelClass::model()->getListDropdownConfig($attr);
	$uniqueKey = "dropdown_model_2_" . $modelClass. "_" . $attr;
	$data = ArrayHelper::get(Util::controller()->data,$uniqueKey,function() use($listConfig){
		$modelClass = $listConfig["model"];
		$valueAttr = $listConfig["valueAttr"];
		$displayAttr = $listConfig["displayAttr"];
		$criteria = ArrayHelper::get($listConfig,"criteria");
		$items = $modelClass::model()->findAll($criteria);
		$returnData = array();
		foreach($items as $item){
			$returnData[$item->$valueAttr] = $item->$displayAttr;
		}
		return $returnData;
	},true);
	$label = ArrayHelper::get($data,$value);
	echo CHtml::tag("span",$htmlAttributes,$label);
},null,null,array(
	"class" => "label label-success"
));

$sPlugin->registerDisplayPlugin("checkbox_label","function",function($value,$config,$htmlAttributes){
	$label = $value ? '<i class="fa fa-check"></i>' : '<i class="fa fa-times"></i>';
	echo CHtml::tag("span",$htmlAttributes,$label);
},null,null,array(
	"class" => "label label-primary"
));

$sPlugin->registerDisplayPlugin("text_short_description","function",function($value,$config,$htmlAttributes){
	$shortValue = $value;
	//$shortValue = TextHelper::getShortDescription($value,ArrayHelper::get($config,"length",100));
	?>
		<div style="text-overflow:ellipsis; overflow-x:hidden; width: 150px;"><?php echo $shortValue ?></div>
	<?php
},null,null,array(
	"class" => "label label-success"
));

$sPlugin->registerDisplayPlugin("color","function",function($value,$config,$htmlAttributes){
	$cssForColor = "background-color: $value";
	if(isset($htmlAttributes["style"]))
		$htmlAttributes["style"] .= "; $cssForColor";
	else
		$htmlAttributes = $cssForColor;
	echo CHtml::tag("div",$htmlAttributes);
},null,null,array(
	"style" => "width: 80%; height: 20px; margin: auto;"
));

$sPlugin->registerDisplayPlugin("money_display","function",function($value,$config,$htmlAttributes){
	$htmlAttributes["data-money"] = $value;
	$htmlAttributes["money-display"] = "";
	echo CHtml::tag("span",$htmlAttributes,$value);
});

$sPlugin->registerDisplayPlugin("permission_display","file","ext.Son.html.views.display_plugin.permission_display",array(
	"class" => "label label-success"
));