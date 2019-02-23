<?php
$arr = array(
	"onRun" => function($list){
		//return;
		
	},
	"fields" => array(
		"__item" => array(
			"order" => true
		),
		"id" => array(
			"advancedSearchInputType" => true
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
		"name" => array(
			"advancedSearchInputType" => "text_match_partial",
		),
		"service_price_percentage" => array(
		),
		"weight_price" => array(
			"inputType" => "number"
		),
		"volume_price" => array(
			"inputType" => "number"
		),
		"exchange_rate" => array(
			"inputType" => "number"
		)
	),
	"actions" => array(
		"action" => array(
			"update" => array(
				"active", "name", "service_price_percentage", "weight_price", "volume_price", "exchange_rate"
			),
			"delete" => true,
			"insert" => array(
				"name", "service_price_percentage", "weight_price", "volume_price", "exchange_rate"
			),
			"data" => array(
				"search" => array(
					"id", "name"
				),
				"advancedSearch" => true,
				"order" => true,
				"limit" => true,
				"offset" => true,
				"page" => true,
				"export" => array(
					"columns" => array(
						"name", "service_price_percentage", "weight_price", "volume_price", "exchange_rate"
					),
					"types" => array(
						"excel", "csv"
					),
					"name" => "customer_type_report"
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
		"class" => "CustomerType",
		"primaryField" => "id",
		"conditions" => array(
		),
		"with" => array(),
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
		"forms" => array(
		)
	),
	"view" => array(
		"itemSelectable" => array(
			"type" => "checkbox",
			"selectedClass" => "active"
		),
		"onRender" => function($list){
		},
		"limitSelectList" => array(10,20,30,40),
		"trackUrl" => true
	),
	"pagination" => array(
		"first" => true,
		"back" => true,
		"next" => true,
		"last" => true,
		"count" => 5
	),
	"admin" => array(
		"title" => "Quản lý loại khách hàng",
		"columns" => array(
			"id", "name", "service_price_percentage", "weight_price", "volume_price", "exchange_rate"
		),
		"detail" => array(
			"id", "created_time", "updated_time", "active", "name", "service_price_percentage", "weight_price", "volume_price", "exchange_rate"
		),
		"action" => true,
		"actionButtons" => array(
		)
	),
	"mode" => "jquery",
);

return $arr;