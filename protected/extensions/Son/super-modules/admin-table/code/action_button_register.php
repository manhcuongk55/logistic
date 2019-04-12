<?php
$adminTableHelper = Son::load("AdminTableHelper");

$adminTableHelper->registerActionButton("delete","function",function($item,$config,$htmlAttributes){
	$htmlAttributes["item-action-delete"] = "";
	if($message = $config["message"]){
		$htmlAttributes["item-message"] = $message;
	}
	if($title=$config["title"]){
		$htmlAttributes["data-toggle"] = "tooltip";
		$htmlAttributes["title"] = $title;
	}
	echo CHtml::htmlButton($config["content"],$htmlAttributes);
},array(
	"content" => '<i class="fa fa-trash"></i>',
	"message" => "Bạn có chắc chắn muốn xóa mục này?",
	"title" => "Xóa"
),array(
	"class" => $adminTableHelper->defaultButtonClass,
));

$adminTableHelper->registerActionButton("update","function",function($item,$config,$htmlAttributes){
	$htmlAttributes["item-action-update"] = "";
	echo CHtml::htmlButton($config["content"],$htmlAttributes);
},array(
	"content" => '<i class="fa fa-edit"></i>',
	"title" => "Sửa"
),array(
	"class" => $adminTableHelper->defaultButtonClass,
));

$adminTableHelper->registerActionButton("extended_action","function",function($item,$config,$htmlAttributes){
	$htmlAttributes["item-extended-action"] = $config["action"];
	if($message = $config["message"]){
		$htmlAttributes["item-message"] = $message;
	}
	$actionFunction = $item->list->config["actions"]["extendedAction"][$config["action"]];
	if(!is_callable($actionFunction)){
		$form = $item->list->getModel()->getExtendedActionForm($config["action"],$actionFunction);
		$form->render();
		$htmlAttributes["item-form-modal"] = "#" . $form->viewParam("modalId");
	}
	echo CHtml::htmlButton($config["content"],$htmlAttributes);
},array(
	"message" => false,
	"title" => false
),array(
	"class" => "btn btn-sm btn-alert"
));

$adminTableHelper->registerActionButton("detail","file","ext.Son.super-modules.admin-table.views.action-button.detail",array(
	"content" => '<i class="fa fa-info"></i>',
	"header" => false,
	"headerCloseButton" => false,
	"closeButton" => "Đóng",
	"title" => "Chi tiết"
),array(
	"class" => $adminTableHelper->defaultButtonClass
));

$adminTableHelper->registerActionButton("link","function",function($item,$config,$htmlAttributes){
	if($config["href"]===false)
		return;
	if($config["newTab"]){
		$htmlAttributes["target"] = "_blank";
	}
	echo CHtml::link($config["content"],$config["href"],$htmlAttributes);
},array(
	"content" => '<i class="fa fa-external-link"></i>',
	"href" => "javascript:;",
	"title" => false,
	"newTab" => true
),array(
	"class" => "btn btn-sm btn-success"
));

$adminTableHelper->registerActionButton("content_display","file","ext.Son.super-modules.admin-table.views.action-button.content_display",array(
	"content" => '<i class="fa fa-search-plus"></i>',
	"header" => false,
	"headerCloseButton" => false,
	"closeButton" => false
),array(
	"class" =>  $adminTableHelper->defaultButtonClass
));

$adminTableHelper->registerActionButton("iframe_modal","file","ext.Son.super-modules.admin-table.views.action-button.iframe_modal",array(
	"content" => '<i class="fa fa-search-plus"></i>',
	"header" => false,
	"headerCloseButton" => false,
	"closeButton" => false,
	"iframeWidth" => "800px"
),array(
	"class" =>  $adminTableHelper->defaultButtonClass
));

