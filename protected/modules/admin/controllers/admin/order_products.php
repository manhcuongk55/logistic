<?php
$this->data["order_id"] = Input::get("order_id",array(
	"rules" => array(
		array("required")
	)
),"html");
$this->data["order"] = Order::model()->findByPk($this->data["order_id"]);
if(!$this->data["order"]){
	Output::showPermissionDenied();
}
$arr = array(
	"onRun" => function($list){
		$list->setDynamicInput("order_id",Util::controller()->data["order_id"]);
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
		"order_id" => array(
			"inputType" => "hidden",
			"default" => $this->data["order_id"],
			"displayInput" => false
		),
		"url" => array(),
		"website_type" => array(
			"inputType" => "dropdown_model",
			"inputConfig" => array(
				"modelClass" => "OrderProduct",
				"attr" => "website_type",
				"inputDropdown" => false
			),
			"displayType" => "label_model",
			"displayConfig" => array(
				"modelClass" => "OrderProduct",
				"attr" => "website_type"
			),
			"advancedSearchInputType" => true
		),
		"name" => array(),
		"count" => array(),
		"vietnamese_name" => array(),
		"image" => array(
			"displayType" => "image",
		),
		"ordered_count" => array(
			"inputType" => "number"
		),
		"price" => array(
			//"inputType" => "money_input",
			"displayType" => "money_display"
		),
		"web_price" => array(
			"inputType" => "money_input",
			"displayType" => "money_display"
		),
		"color" => array()
	),
	"actions" => array(
		"action" => array(
			"update" => false,
			"delete" => false,
			"insert" => false,
			"data" => array(
				"search" => array(
					"id", "name", "vietnamese_name"
				),
				"advancedSearch" => true,
				"order" => true,
				"limit" => true,
				"offset" => true,
				"page" => true,
				"export" => false,
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
		"class" => "OrderProduct",
		"primaryField" => "id",
		"conditions" => array(),
		"with" => array(
			"product"
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
			"order_id" => function($criteria,$value){
				$criteria->compare("t.order_id",$value);
			}
		),
		"preloadData" => false,
		"forms" => array()
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
		"title" => "Danh sách sản phẩm đơn hàng",
		"columns" => array(
			"order_id", "web_price", "price", "count", "image"
		),
		"detail" => array(
			"id", "created_time", "updated_time", "active", "order_id", "name", "url", "website_type", "price", "count", "ordered_count", "vietnamese_name", "image", "web_price", "color"
		),
		"action" => true,
		"actionButtons" => array(
		)
	),
	"mode" => "jquery",
);

return $arr;