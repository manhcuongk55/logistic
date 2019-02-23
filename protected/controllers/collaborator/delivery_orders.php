<?php
$arr = array(
	"onRun" => function($list){
	},
	"fields" => array(
		"__item" => array(
			"order" => true
		),
		"id" => array(
			"advancedSearchInputType" => true,
		),
		"created_time" => array(
			"displayType" => "time_format",
			"displayConfig" => array(
				"format" => "d-m-Y h:i:s"
			),
			"inputType" => "timestamp_datetimepicker",
			"advancedSearchInputType" => "timestamp_range_datetimepicker",
			"exportType" => "timestamp"
		),
		"updated_time" => array(
			"displayType" => "time_format",
			"displayConfig" => array(
				"format" => "d-m-Y h:i:s"
			),
			"inputType" => "timestamp_datetimepicker",
			"advancedSearchInputType" => "timestamp_range_datetimepicker",
			"exportType" => "timestamp"
		),
		"active" => array(
			"inputType" => "checkbox_button",
			"displayType" => "checkbox_label",
			"advancedSearchInputType" => true,
			"advancedSearchConfig" => array(
				"triggerType" => "changed",
			),
			"exportType" => "boolean"
		),
		"user_id" => array(
			"label" => "ND",
			"advancedSearchInputType" => "text",
		),
        "status" => array(
            "inputType" => "dropdown_model",
			"inputConfig" => array(
				"modelClass" => "DeliveryOrder",
				"attr" => "status",
				"inputDropdown" => false
			),
			"displayType" => "label_model",
			"displayConfig" => array(
				"modelClass" => "DeliveryOrder",
				"attr" => "status"
			),
			"advancedSearchInputType" => true
        ),
        "description" => array(),
        "product_name" => array(),
        "image" => array(
			"order" => false,
			"advancedSearchInputType" => false,
			"inputType" => "file_picker",
			"inputConfig" => array(
				"file_type" => "image"
			),
			"displayType" => "image",
			"exportType" => "url",
			"displayHtmlAttributes" => array(
				"style" => "width:50px; height: 50px;"
			),
		),
		"image_url" => array(),
		"user_name" => array(
			"label" => "ND",
			"foreignConfig" => array("user","name"),
			"advancedSearchInputType" => true,
		),
		"delivery_order_code" => array(
			"label" => "Mã vận đơn",
			"foreignConfig" => array("shop_delivery_order","delivery_order_code"),
			"advancedSearchInputType" => true,
		),
		"total_weight" => array(
			"label" => "Cân nặng",
			"foreignConfig" => array("shop_delivery_order","total_weight"),
			"advancedSearchInputType" => true,
		),
		"total_volume" => array(
			"label" => "Số khối",
			"foreignConfig" => array("shop_delivery_order","total_volume"),
			"advancedSearchInputType" => true,
		),
		"num_block" => array(
			"label" => "Số kiện",
			"foreignConfig" => array("shop_delivery_order","num_block"),
			"advancedSearchInputType" => true,
		),
		"block_id" => array(
			"label" => "Mã kiện hàng",
			"foreignConfig" => array("shop_delivery_order","block_id"),
			"advancedSearchInputType" => true,
		),
	),
	"actions" => array(
		"action" => array(
			"update" => array(
				"status"
			),
			"delete" => true,
			"insert" => false,
			"data" => array(
				"search" => array(
					"id", "user_name"
				),
				"advancedSearch" => true,
				"order" => true,
				"limit" => true,
				"offset" => true,
				"page" => true,
				"export" => array(
					"columns" => array(
						"id", "created_time", "updated_time", "active", "user_id", "status",  "user_name", "product_name", "description", "image", "image_url"
					),
					"types" => array(
						"excel", "csv"
					),
					"name" => "delivery_orders_report"
				),
			),			
		),
		"actionTogether" => array(
			"deleteTogether" => false	
		),
		"extendedAction" => include(dirname(__FILE__) . "/includes/delivery_order_extended_actions.php"),
		"extendedActionTogether" => array(
		),
	),
	"model" => array(
		"class" => "DeliveryOrder",
		"primaryField" => "id",
		"conditions" => array(
			// "t.user_id IN (SELECT id FROM {{user}} WHERE collaborator_group_id = " . Util::controller()->getUser()->collaborator_group_id . ")"
		),
		"with" => array(
			"user", "shop_delivery_order",
		),
		"addedCondition" => array(),
		"defaultQuery" => array(
			"orderBy" => "id",
			"orderType" => "desc",
			"limit" => 20,
			"offset" => 0,
			"search" => "",
			"advancedSearch" => array(
				"t.active" => 1
			),
			"page" => 1
		),
		"dynamicInputs" => array(
		),
		"preloadData" => false,
	),
	"view" => array(
		"itemSelectable" => array(
			"type" => "checkbox",
			"selectedClass" => "active"
		),
		"limitSelectList" => array(10,20,30,40),
		"trackUrl" => true,
	),
	"pagination" => array(
		"first" => true,
		"back" => true,
		"next" => true,
		"last" => true,
		"count" => 5
	),
	"admin" => array(
		"title" => "Danh sách ký gửi",
		"columns" => array(
			"id", "delivery_order_code", "block_id", "user_id", "user_name", "status", "__action__", "num_block", "total_weight", "total_volume"
		),
		"detail" => array(
			"id", "created_time", "updated_time", "active", "user_id", "status", "description", "user_name", "image", "image_url", "num_block", "total_weight", "total_volume",
		),
		"action" => true,
		"actionButtons" => include(dirname(__FILE__) . "/includes/delivery_order_action_buttons.php")
	),
	"mode" => "jquery",
);

$collaborator = Util::controller()->getUser();

if(!$collaborator->is_manager){
	$arr["actions"]["action"]["update"] = false;
	$arr["actions"]["action"]["delete"] = false;
}

$collaboratorConditionSet = false;
if($collaborator->type==Collaborator::TYPE_SALE){
	if(!$collaborator->is_manager){
		$collaboratorConditionSet = true;
		$arr["model"]["conditions"][] = "t.user_id IN (SELECT id FROM {{user}} WHERE collaborator_id = $collaborator->id)";
	}
}
if(!$collaboratorConditionSet){
	$arr["model"]["conditions"][] = "t.user_id IN (SELECT id FROM {{user}} WHERE collaborator_group_id = " . $collaborator->collaborator_group_id . ")";
}

if($collaborator->collaborator_group->is_admin_group && $collaborator->is_manager){
	$arr["model"]["conditions"] = array();
}

return $arr; 