<?php
$arr = array(
	"onRun" => function($list){
	},
	"fields" => array(
		"__item" => array(
			"order" => true
		),
		"id" => array(
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
			"inputType" => "hidden",
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
        "delivery_order_code" => array(),
        "description" => array(),
        "total_price" => array(
            "displayType" => "money_display"
        ),
        "total_weight" => array(
            "displayType" => "money_display"
        ),
        "is_paid" => array(
            "inputType" => "checkbox_button",
			"displayType" => "checkbox_label",
			"advancedSearchInputType" => true,
			"advancedSearchConfig" => array(
				"triggerType" => "changed",
			),
			"exportType" => "boolean"
        ),
        "deposit_amount" => array(
            "displayType" => "money_display"
        ),
        "delivery_price" => array(
            "displayType" => "money_display"
        ),
        "total_real_price" => array(
            "displayType" => "money_display"
        ),
        "storehouse_name" => array(),
		"user_name" => array(
			"foreignConfig" => array("user","name")
		),
	),
	"actions" => array(
		"action" => array(
			"update" => false,
			"delete" => true,
			"insert" => false,
			"data" => array(
				"search" => array(
					"id", "user_name", "delivery_order_code"
				),
				"advancedSearch" => true,
				"order" => true,
				"limit" => true,
				"offset" => true,
				"page" => true,
				"export" => array(
					"columns" => array(
						"id", "created_time", "updated_time", "active", "user_id", "status", "delivery_order_code", "description", "total_price", "total_weight", "is_paid", "deposit_amount", "delivery_price", "total_real_price", "storehouse_name", "user_name"
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
		"extendedAction" => array(
		),
		"extendedActionTogether" => array(
		),
	),
	"model" => array(
		"class" => "DeliveryOrder",
		"primaryField" => "id",
		"conditions" => array(),
		"with" => array(
			"user"
		),
		"addedCondition" => array(),
		"defaultQuery" => array(
			"orderBy" => "id",
			"orderType" => "desc",
			"limit" => 20,
			"offset" => 0,
			"search" => "",
			"advancedSearch" => array(
				"active" => 1
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
		"title" => "Danh sách vận đơn",
		"columns" => array(
			"id", "user_id", "user_name", "delivery_order_code", "total_price", "status", "created_time"
		),
		"detail" => array(
			"id", "created_time", "updated_time", "active", "user_id", "status", "delivery_order_code", "description", "total_price", "total_weight", "is_paid", "deposit_amount", "delivery_price", "total_real_price", "storehouse_name", "user_name"
		),
		"action" => true,
		"actionButtons" => array(
		)
	),
	"mode" => "jquery",
);

return $arr; 