<?php
$arr = array(
	"onRun" => function($list){
	},
	"fields" => array(
		"__item" => array(
			"order" => true
		),
		"id" => array(
			"advancedSearchInputType" => true
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
			"label" => "ID ND",
			"advancedSearchInputType" => true
		),
        "status" => array(
            "inputType" => "dropdown_model",
			"inputConfig" => array(
				"modelClass" => "Order",
				"attr" => "status",
				"inputDropdown" => false,
				"triggerType" => "changed"
			),
			"displayType" => "label_model",
			"displayConfig" => array(
				"modelClass" => "Order",
				"attr" => "status"
			),
			"advancedSearchInputType" => true
        ),
        "description" => array(),
        "is_paid" => array(
            "inputType" => "checkbox_button",
			"displayType" => "checkbox_label",
			"advancedSearchInputType" => true,
			"advancedSearchConfig" => array(
				"triggerType" => "changed",
			),
			"exportType" => "boolean"
        ),
        "total_quantity" => array(
            "displayType" => "number"
        ),
        "total_weight" => array(
            "displayType" => "number"
        ),
		"weight_price" => array(
            "displayType" => "money_display"
		),
		"service_price_percentage" => array(
            "displayType" => "number"
		),
		"exchange_rate" => array(
            "displayType" => "money_display"
		),
        "total_price_ndt" => array(
            "displayType" => "money_display"
        ),
        "total_real_price_ndt" => array(
            "displayType" => "money_display"
        ),
        "total_price" => array(
            "displayType" => "money_display"
        ),
        "total_real_price" => array(
            "displayType" => "money_display"
        ),
        "total_delivery_price_ndt" => array(
            "displayType" => "money_display"
        ),
        "total_delivery_price" => array(
            "displayType" => "money_display"
        ),
        "service_price" => array(
            "displayType" => "money_display"
        ),
        "total_weight_price" => array(
            "displayType" => "money_display"
        ),
        "shipping_home_price" => array(
            "displayType" => "money_display"
        ),
        "final_price" => array(
            "displayType" => "money_display"
        ),
        "deposit_amount" => array(
            "displayType" => "money_display"
        ),
        "remaining_amount" => array(
            "displayType" => "money_display"
        ),
		"user_name" => array(
			"label" => "ND",
			"foreignConfig" => array("user","name"),
			"advancedSearchInputType" => true
		),
	),
	"actions" => array(
		"action" => array(
			"update" => false,
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
						"id", "created_time", "updated_time", "active", "user_id", "status", "description", "total_price", "total_weight", "is_paid", "deposit_amount", "delivery_price", "total_real_price", "user_name"
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
			"set_price" => array(
				"title" => "Đặt lại giá",
				"inputs" => array(
					"exchange_rate" => array(
						"type" => "money_input",
						"label" => "Tỷ giá (VNĐ/NDT)"
					),
					"service_price_percentage" => array(
						"type" => "number",
						"label" => "Phí dịch vụ (%)",
					),
					"weight_price" => array(
						"type" => "money_input",
						"label" => "Phí cân nặng (VNĐ/kg)"
					),
					"volume_price" => array(
						"type" => "money_input",
						"label" => "Phí khối (VNĐ/m3)"
					),
				),
				"method" => "post",
				"ajax" => true,
				"onHandleInput" => function($form){
					$result = $form->readInput();
					if(!$result){
						$form->setError(true);
					} else {
						$order = Order::model()->findByAttributes(array(
							"id" => $form->id,
						));
						if(!$order){
							$form->addError("global","Invalid request");
							$form->setError(true);
						} else {
							$result = $order->modifyPrice($form->weight_price,$form->volume_price,$form->exchange_rate,$form->service_price_percentage);
							$form->setError(!$result);
							if(!$result){
								$form->addError("global",$order->getFirstError());
							}
						}
					}
					return true;
				}
			),
		),
		"extendedActionTogether" => array(
		),
	),
	"model" => array(
		"class" => "Order",
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
		"title" => "Danh sách đơn hàng",
		"columns" => array(
			"id", "user_id", "user_name", "total_price", "status", "created_time"
		),
		"detail" => array(
			"id", "created_time", "updated_time", "active", "user_id", "status", "description", "deposit_amount", "total_delivery_price", "total_real_price", "total_quantity", "total_real_price_ndt", "total_price_ndt", "total_delivery_price_ndt", "weight_price", "service_price", "final_price", "remaining_amount", "exchange_rate", "shipping_home_price", "total_weight_price", "service_price_percentage", "user_name"
		),
		"action" => true,
		"actionButtons" => array(
			array("extended_action",function($item){
				if($item->status!=Order::STATUS_WAIT_FOR_PRICE && $item->status!=Order::STATUS_WAIT_FOR_DEPOSIT_AMOUNT){
					return array(
						"disabled" => true
					);
				}
				return array(
					"action" => "set_price",
					"content" => "Đặt giá",
					"title" => "Đặt giá",
				);
			},array(
				"class" => "btn btn-sm btn-primary"
			))
		)
	),
	"mode" => "jquery",
);

return $arr; 