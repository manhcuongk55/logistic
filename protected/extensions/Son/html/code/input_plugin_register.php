<?php
$sPlugin = Son::load("SPlugin");
$sPlugin->registerInputPlugin("timestamp_datepicker","html_attr",array(
	"input-datetime" => "",
	"hidden" => "",
),array(
	"format" => "DD/MM/YYYY"
),array(
	"bootstrap_datetimepicker"
));

$sPlugin->registerInputPlugin("timestamp_datetimepicker","html_attr",array(
	"input-datetime" => "",
	"hidden" => ""
),array(
	"format" => "DD/MM/YYYY HH:mm"
),array(
	"bootstrap_datetimepicker"
));

$sPlugin->registerInputPlugin("text_slug","html_attr",array(
	"input-slug" => ""
),null,array(
	"slug"
));

$sPlugin->registerInputPlugin("file_picker","function",function($name,$value,$config,$htmlAttributes){
	$htmlAttributes["type"] = "file";
	$htmlAttributes["input-file"] = "";
	$htmlAttributes["data-url"] = $value;
	if($config["multiple"]){
		$htmlAttributes["multiple"] = "multiple";
	}
	echo CHtml::fileField($name,"",$htmlAttributes);
},array(
	"multiple" => false
),array(
	"bootstrap-fileinput"
));

$sPlugin->registerInputPlugin("file_base64","load_file","ext.Son.html.views.input_plugin.file_base64");


$sPlugin->registerInputPlugin("html","html_attr",array(
	"input-html" => ""
),null,array(
	"summernote"
));

$sPlugin->registerInputPlugin("checkbox_button","html_attr",array(
	"input-checkbox-button" => "",
),array(
	"type" => "checkbox"
),array(
	"icheck"
));

$sPlugin->registerInputPlugin("radio_button","html_attr",array(
	"input-checkbox-button" => "",
),array(
	"type" => "radio"
),array(
	"icheck"
));

$sPlugin->registerInputPlugin("dropdown","html_attr",array(
	"input-dropdown" => ""
),array(
	"type" => "select"
));

$sPlugin->registerInputPlugin("dropdown_model","function",function($name,$value,$config,$htmlAttributes){
	$attr = $config["attr"];
	$modelClass = $config["modelClass"];
	$items = $modelClass::model()->getListDropdownConfig($attr);
	$data = array(
		0 => l("app",ArrayHelper::get($config,"defaultText",$modelClass::model()->getAttributeLabel($attr)))
	);
	foreach($items as $key => $item){
		$data[$key] = l("app",$item);
	}
	if($config["inputDropdown"])
		$htmlAttributes["input-dropdown"] = "";
	echo CHtml::dropdownList($name,$value,$data,$htmlAttributes);
},array(
	"inputDropdown" => true
));

$sPlugin->registerInputPlugin("dropdown_model_2","function",function($name,$value,$config,$htmlAttributes){
	$modelClass = $currentModelClass = $config["modelClass"];
	$defaultCriteria = ArrayHelper::get($config,"criteria");
	$attr = $currentModelAttr = $config["attr"];
	$listConfig = $modelClass::model()->getListDropdownConfig($attr);
	$uniqueKey = "dropdown_model_2_" . $modelClass. "_" . $attr;
	$data = ArrayHelper::get(Util::controller()->data,$uniqueKey,function() use($listConfig,$currentModelAttr,$currentModelClass,$defaultCriteria){
		$modelClass = $listConfig["model"];
		$valueAttr = $listConfig["valueAttr"];
		$displayAttr = $listConfig["displayAttr"];
		$criteria = $defaultCriteria;
		if(!$criteria){
			$criteria = ArrayHelper::get($listConfig,"criteria");
		}
		$items = $modelClass::model()->findAll($criteria);
		$returnData = array();
		foreach($items as $item){
			$returnData[$item->$valueAttr] = $item->$displayAttr;
		}
		if(!isset($returnData[0])){
			$returnData = array(l("app",ArrayHelper::get($config,"defaultText",$currentModelClass::model()->getAttributeLabel($currentModelAttr)))) + $returnData;
		}
		return $returnData;
	},true);
	if($config["inputDropdown"])
		$htmlAttributes["input-dropdown"] = "";
	echo CHtml::dropdownList($name,$value,$data,$htmlAttributes);
},array(
	"inputDropdown" => true
));

$sPlugin->registerInputPlugin("dropdown_model_ajax","function",function($name,$value,$config,$htmlAttributes){
	$data = array();
	$htmlAttributes["data-url"] = ArrayHelper::get($config,"url",function(){
		return Util::controller()->createUrl("/site/search");
	});
	$htmlAttributes["data-value"] = $value;
	if($defaultDisplayAttr=ArrayHelper::get($config,"defaultDisplayAttr")){
		$htmlAttributes["item-display-attr"] = $defaultDisplayAttr;
	}
	if($defaultDisplayValue=ArrayHelper::get($config,"defaultDisplayValue")){
		$htmlAttributes["data-display"] = $defaultDisplayValue;
	}
	$htmlAttributes["data-model"] = $config["modelClass"];
	$htmlAttributes["input-dropdown"] = "";
	$htmlAttributes["data-ajax"] = "1";
	$htmlAttributes["data-attr"] = $config["attr"];
	echo CHtml::dropdownList($name,$value,$data,$htmlAttributes);
});

$sPlugin->registerInputPlugin("color_picker","html_attr",array(
	"input-color-picker" => ""
),null,array(
	"colorpicker"
));

$sPlugin->registerInputPlugin("money_input","html_attr",array(
	"input-money" => ""
),null,array(
	"maskmoney"
));

$sPlugin->registerInputPlugin("permission_input","function",function($name,$value,$config,$htmlAttributes){
	$htmlAttributes["multiple"] = "multiple";
	$permissionList = Son::load("Permission")->getPermissionConfig($config["config"]);
	$data = array();
	foreach($permissionList as $permissionItem){
		$label = $permissionItem[0];
		$index = $permissionItem[1];
		$data[$index] = $label;
	}
	$htmlAttributes["input-permission"] = "";
	$htmlAttributes["data-permissions"] = json_encode($data);
	echo CHtml::hiddenField($name,$value,$htmlAttributes);
});