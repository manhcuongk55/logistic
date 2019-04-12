<?php
$sListHelper = Son::load("SListHelper");
$sListHelper->advancedSearchRegisterType("timestamp_range_datetimepicker","load_file","ext.Son.super-modules.list.views.advanced-search.timestamp_range_datetimepicker","range",array(
	"placeholder_1" => "From",
	"placeholder_2" => "To"
));

$sListHelper->advancedSearchRegisterType("number_range","load_file","ext.Son.super-modules.list.views.advanced-search.number_range","range",array(
	"placeholder_1" => "From",
	"placeholder_2" => "To"
));

$sListHelper->advancedSearchRegisterType("text_match_partial","function",function($name,$value,$config,$htmlAttributes){
	echo CHtml::textField($name,$value,$htmlAttributes);
},"match_partial",array(
	"triggerType" => "enter"
));

$sListHelper->advancedSearchRegisterType("greater","function",function($name,$value,$config,$htmlAttributes){
	echo CHtml::textField($name,$value,$htmlAttributes);
},"greater",array(
	"triggerType" => "enter"
));

$sListHelper->advancedSearchRegisterType("less","function",function($name,$value,$config,$htmlAttributes){
	echo CHtml::textField($name,$value,$htmlAttributes);
},"less",array(
	"triggerType" => "enter"
));