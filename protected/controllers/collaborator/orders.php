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
			"label" => "Mã đơn"
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
				"attr" => "status_by_collaborator",
				"inputDropdown" => false,
				"triggerType" => "changed"
			),
			"displayType" => "label_model",
			"displayConfig" => array(
				"modelClass" => "Order",
				"attr" => "status"
			),
			"advancedSearchInputType" => "dropdown_model",
			"advancedSearchConfig" => array(
				"modelClass" => "Order",
				"attr" => "status",
				"inputDropdown" => false,
				"triggerType" => "changed"
			)
        ),
        "sms_status" => array(
			"displayType" => "label_model",
			"displayConfig" => array(
				"modelClass" => "Order",
				"attr" => "status"
			),
			"advancedSearchInputType" => "dropdown_model",
			"advancedSearchConfig" => array(
				"modelClass" => "Order",
				"attr" => "status",
				"inputDropdown" => false,
				"triggerType" => "changed"
			)
        ),
        "description" => array(),
        "testla" => array(),
        "name" => array(),
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
        "total_volume" => array(
            "displayType" => "number"
        ),
		"weight_price" => array(
            "displayType" => "money_display"
		),
		"volume_price" => array(
            "displayType" => "money_display"
		),
		"service_price_percentage" => array(
            "displayType" => "number"
		),
		"exchange_rate" => array(
            "displayType" => "money_display"
		),
		"real_exchange_rate" => array(
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
        "extra_price" => array(
            "displayType" => "money_display"
		),
		"shop_id" => array(	
		),
		"order_code" => array(
			"advancedSearchInputType" => "text_match_partial",
		),
		"delivery_price_ndt" => array(
			"inputType" => "money_input",
            "displayType" => "money_display",
        ),
        "real_price" => array(
            "inputType" => "money_input",
            "displayType" => "money_display",
		),
		"shop_name" => array(
            "displayType" => "text",
			"advancedSearchInputType" => true
        ),
		"user_name" => array(
			"label" => "Tên người dùng",
			"foreignConfig" => array("user","name"),
			"advancedSearchInputType" => true
		),
		"user_phone" => array(
			"label" => "SĐT người dùng",
			"foreignConfig" => array("user","phone"),
			"advancedSearchInputType" => true
		),
		"collaborator_id" => array(
			"label" => "CTV",
			"manualJoinForeignConfig" => array("c","collaborator_id"),
			"advancedSearchInputType" => true,
		),
		"collaborator_name" => array(
			"label" => "CTV",
			"manualJoinForeignConfig" => array("c","collaborator_name"),
			"advancedSearchInputType" => "text_match_partial",
		)
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
						"id", "created_time", "updated_time", "active", "user_id", "status", "name", "deposit_amount", "total_delivery_price", "total_real_price", "total_quantity", "total_real_price_ndt", "total_price_ndt", "total_delivery_price_ndt", "weight_price", "volume_price", "final_price", "remaining_amount", "exchange_rate", "shipping_home_price", "total_weight_price","service_price_percentage", "user_name", "user_phone", "collaborator_name"
					),
					"types" => array(
						"excel", "csv"
					),
					"name" => "orders_report"
				),
			),			
		),
		"actionTogether" => array(
			"deleteTogether" => false	
		),
		"extendedAction" => include(dirname(__FILE__) . "/includes/order_extended_actions.php"),
		"extendedActionTogether" => array(
			"send_sms" => array(function($ids){
				$ordersByUserIDAndStatus = array();
				$criteria = new CDbCriteria();
				$criteria->addInCondition("id",$ids);
				$criteria->addInCondition("status",array(
					Order::STATUS_WAIT_FOR_DEPOSIT, Order::STATUS_STOREHOUSE_VIETNAM,
				));
				$allOrders = Order::model()->findAll($criteria);
				foreach($allOrders as $order){
					if($order->sms_status == $order->status){
						continue;
					}
					$userID = $order->user_id;
					$status = $order->status;
					if(!isset($ordersByUserIDAndStatus[$userID])){
						$ordersByUserIDAndStatus[$userID] = array();
					}
					if(!isset($ordersByUserIDAndStatus[$userID][$status])){
						$ordersByUserIDAndStatus[$userID][$status] = array();
					}
					$ordersByUserIDAndStatus[$userID][$status][] = $order;
				}
				foreach($ordersByUserIDAndStatus as $userID => $ordersByStatuses){
					$user = User::model()->findByPk($userID);
					foreach($ordersByStatuses as $status => $orders){
						$result = $user->notifyOrderStatuses($status,$orders);
						if($result){
							foreach($orders as $order){
								$order->updateSMSStatus();
							}
						}
					}
				}
				return array(true);
			},"Gửi tin nhắn SMS","Bạn có chắc chắn muốn gửi SMS cho những đơn hàng này?")
		),
	),
	"model" => array(
		"class" => "Order",
		"primaryField" => "id",
		"onCriteria" => function($criteria){
			$criteria->join .= "
				LEFT JOIN (
					SELECT cc.id AS collaborator_id, cc.name AS collaborator_name, cu.id AS user_id FROM {{user}} cu, {{collaborator}} cc WHERE cc.id = cu.collaborator_id AND cc.type = :cc_type GROUP BY cu.id
				) c ON c.user_id = t.user_id
			";
			$criteria->params[":cc_type"] = Collaborator::TYPE_SALE;
		},
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
		"insertScenario" => "insert_from_collaborator",
		"updateScenario" => "update_from_collaborator",
		"deleteScenario" => "delete_from_collaborator"
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
			"id", "status", "__action__", "total_price", "deposit_amount", "sms_status", "user_name", "collaborator_name", "created_time", "name"
		),
		"detail" => array(
			"id", "collaborator_name", "total_price_ndt", "total_delivery_price_ndt", "exchange_rate", "created_time", "user_name", "status", "sms_status", "total_price", "service_price", "total_weight", "total_weight_price", "total_volume", "total_delivery_price", "shipping_home_price", "extra_price", "final_price", "deposit_amount", "remaining_amount", "updated_time", "active", "user_id", "description", "name", "total_quantity", "weight_price", "service_price_percentage", "shop_name", "delivery_price_ndt", "real_price", "shop_id", "order_code", "testla"
		),
		"action" => true,
		"actionButtons" => include(dirname(__FILE__) . "/includes/order_action_buttons.php")
	),
	"mode" => "jquery",
);

$collaborator = Util::controller()->getUser();

if(!$collaborator->is_manager){
	$arr["actions"]["action"]["update"] = false;
	$arr["actions"]["action"]["delete"] = false;
}

if($collaborator->type==Collaborator::TYPE_ACCOUNTANT){
	$arr["actions"]["action"]["update"] = array(
		"real_exchange_rate", "weight_price", "status"
	);
}

$collaboratorConditionSet = false;
if($collaborator->type==Collaborator::TYPE_SALE){
	if(!$collaborator->is_manager){
		$collaboratorConditionSet = true;
		$arr["model"]["conditions"][] = "t.user_id IN (SELECT id FROM {{user}} WHERE collaborator_id = $collaborator->id)";
	}

	$arr["actions"]["action"]["insert"] = array(
		"user_id"
	);
}
if(!$collaboratorConditionSet){
	$arr["model"]["conditions"][] = "t.user_id IN (SELECT id FROM {{user}} WHERE collaborator_group_id = " . $collaborator->collaborator_group_id . ")";
}

if($collaborator->collaborator_group->is_admin_group && $collaborator->is_manager){
	$arr["model"]["conditions"] = array();
}

return $arr; 