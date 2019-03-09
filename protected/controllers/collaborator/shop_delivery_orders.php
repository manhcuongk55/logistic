<?php
$type = Input::get("type");
$this->data["type"] = $type;
$orderId = Input::get("order_id");
$this->data["order_id"] = $orderId;

$order = null;
if($orderId){
	if($type==ShopDeliveryOrder::TYPE_ORDER){
		$order = Order::getOrderOfCollaboratorGroup($this->data["order_id"],Util::controller()->getUser()->collaborator_group_id);
		if(!$order){
			Output::showPermissionDenied();
		}
	} else if($type==ShopDeliveryOrder::TYPE_DELIVERY_ORDER){
		$order = DeliveryOrder::getDeliveryOrderOfCollaboratorGroup($this->data["order_id"],Util::controller()->getUser()->collaborator_group_id);
		if(!$order){
			Output::showPermissionDenied();
		}
	}
}
$this->data["order"] = $order;

$arr = array(
	"onRun" => function($list){
		if($order = Util::controller()->data["order"]){
        	$list->setDynamicInput("order_id",$order->id);
		}
		if($type = Util::controller()->data["type"]){
        	$list->setDynamicInput("type",$type);
		}
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
        "status" => array(
            "inputType" => "dropdown_model",
			"inputConfig" => array(
				"modelClass" => "ShopDeliveryOrder",
				"attr" => "status_by_collaborator",
				"inputDropdown" => false,
				"triggerType" => "changed"
			),
			"displayType" => "label_model",
			"displayConfig" => array(
				"modelClass" => "ShopDeliveryOrder",
				"attr" => "status"
			),
			"advancedSearchInputType" => "dropdown_model",
			"advancedSearchConfig" => array(
				"modelClass" => "ShopDeliveryOrder",
				"attr" => "status",
				"inputDropdown" => false,
				"triggerType" => "changed"
			)
        ),
        "type" => array(
            "inputType" => "dropdown_model",
			"inputConfig" => array(
				"modelClass" => "ShopDeliveryOrder",
				"attr" => "type",
				"inputDropdown" => false,
				"triggerType" => "changed"
			),
			"displayType" => "label_model",
			"displayConfig" => array(
				"modelClass" => "ShopDeliveryOrder",
				"attr" => "type"
			),
			"advancedSearchInputType" => "dropdown_model",
			"advancedSearchConfig" => array(
				"modelClass" => "ShopDeliveryOrder",
				"attr" => "type",
				"inputDropdown" => false,
				"triggerType" => "changed"
			)
        ),
        "order_id" => array(
			"inputType" => "hidden",
			"default" => $this->data["order_id"],
			"displayInput" => false
		),
        "delivery_order_code" => array(
			"advancedSearchInputType" => "text_match_partial",
		),
        "total_weight" => array(
            "displayType" => "number"
        ),
        "total_volume" => array(
            "displayType" => "number"
        ),
		"china_delivery_time" => array(
			"displayType" => "time_format",
			"displayConfig" => array(
				"format" => "d-m-Y"
			),
			"inputType" => "timestamp_datetimepicker",
			"advancedSearchInputType" => "timestamp_range_datetimepicker",
			"exportType" => "timestamp"
		),
		"vietnam_delivery_time" => array(
			"displayType" => "time_format",
			"displayConfig" => array(
				"format" => "d-m-Y"
			),
			"inputType" => "timestamp_datetimepicker",
			"advancedSearchInputType" => "timestamp_range_datetimepicker",
			"exportType" => "timestamp"
		),
        "block_id" => array(
            "displayType" => "text",
			"advancedSearchInputType" => true,
			"label" => "Mã kiện hàng"
        ),
        "num_block" => array(
            "inputType" => "number",
			"advancedSearchInputType" => true
        ),
        "purchase_price" => array(
            "displayType" => "text",
        ),
        "image_url" => array(),
		"user_name" => array(
			"manualJoinForeignConfig" => array("u","user_name"),
			"displayType" => "text",
			"label" => "Khách hàng"
		)
	),
	"actions" => array(
		"action" => array(
			"update" => array(
				"status"
			),
			"delete" => true,
			/*"insert" => array(
                "order_id", "shop_name", "total_weight", "delivery_price_ndt", "real_price",
            ),*/
			"insert" => false,
			"data" => array(
				"search" => array(
					"id", "delivery_order_code"
				),
				"advancedSearch" => true,
				"order" => true,
				"limit" => true,
				"offset" => true,
				"page" => true,
				"export" => array(
					"columns" => array(
						"id", "created_time", "updated_time", "active", "order_id", "status", "delivery_order_code", "total_weight", "block_id", "num_block", "purchase_price"
					),
					"types" => array(
						"excel", "csv"
					),
					"name" => "shop_delivery_orders_report"
				),
			),			
		),
		"actionTogether" => array(
			"deleteTogether" => false	
		),
		"extendedAction" => include(dirname(__FILE__) . "/includes/shop_delivery_order_extended_actions.php"),
		"extendedActionTogether" => array(
			"set_delivered_storehouse_vietnam" => array(function($ids){
				$ordersByUserIDAndStatus = array();
				$criteria = new CDbCriteria();
				$criteria->addInCondition("id",$ids);
				$criteria->compare("status", ShopDeliveryOrder::STATUS_STOREHOUSE_CHINA);
				$shopDeliveryOrders = ShopDeliveryOrder::model()->findAll($criteria);
				// echo CJSON::encode($shopDeliveryOrders); die();
				foreach($shopDeliveryOrders as $shopDeliveryOrder){
					$shopDeliveryOrder->setDeliveredStorehouseVietnam();
				}
				return array(true);
			},"Xác nhận về kho VN","Bạn có chắc chắn muốn xác nhận về kho Việt Nam cho những đơn hàng này?")
		),
	),
	"model" => array(
		"class" => "ShopDeliveryOrder",
		"primaryField" => "id",
		"conditions" => array(),
		"onCriteria" => function($criteria){
			$criteria->join .= "
				LEFT JOIN (
					SELECT shop_delivery_order_id, SUM(price * `count`) AS total_price FROM {{order_product}} GROUP BY shop_delivery_order_id
				) s ON (s.shop_delivery_order_id = t.id)
				LEFT JOIN (
					SELECT u1.name as user_name, o1.id AS order_id FROM {{user}} u1,{{order}} o1 WHERE o1.user_id = u1.id 
				) u ON (u.order_id = t.order_id)
			";
		},
		"with" => array(
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
		"dynamicInputs" => array(
			"order_id" => function($criteria,$value){
				$criteria->compare("t.order_id",$value);
			},
			"type" => function($criteria,$value){
				$criteria->compare("t.type",$value);
			},
		),
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
		"title" => "Danh sách vận đơn",
		"columns" => array(
			"id", "delivery_order_code", "status", "type", "__action__", "block_id", "total_weight", "total_volume", "order_id","created_time", "china_delivery_time", "vietnam_delivery_time"
		),
		"detail" => array(
			"user_name", "id", "created_time", "updated_time", "active", "order_id", "status", "delivery_order_code", "total_weight", "total_volume", "china_delivery_time", "vietnam_delivery_time", "block_id", "num_block", "purchase_price", "type",
		),
		"action" => true,
		"actionButtons" => include(dirname(__FILE__) . "/includes/shop_delivery_order_action_buttons.php")
	),
	"mode" => "jquery",
);

if(!Util::controller()->getUser()->is_manager){
	$arr["actions"]["action"]["delete"] = false;
}

if($this->data["order"]){
	if($this->data["order"]->status==Order::STATUS_WAIT_FOR_PRICE){
		if(Util::controller()->getUser()->type==Collaborator::TYPE_SALE){
			$arr["actions"]["action"]["update"] = array(
				"total_weight", "total_volume", "status"
			);
		}
	}
}

$collaborator = Util::controller()->getUser();

if(!$order){
	$arr["actions"]["action"]["insert"] = false;
	//$arr["actions"]["action"]["update"] = false;
} else {
	if($order->status==Order::STATUS_ORDERED || $order->status==Order::STATUS_SHIPPING && $collaborator->type=Collaborator::TYPE_SHIP){
		$arr["actions"]["action"]["insert"] = array(
			"delivery_order_code","num_block", "order_id", "image_url",
		);
	}
}

if(!Util::controller()->getUser()->is_manager){
	$arr["actions"]["action"]["delete"] = false;
}

if($type==ShopDeliveryOrder::TYPE_ORDER){
	$collaboratorConditionSet = false;
	if($collaborator->type==Collaborator::TYPE_SALE){
		if(!$collaborator->is_manager){
			$collaboratorConditionSet = true;
			$arr["model"]["conditions"][] = "t.order_id IN (SELECT id FROM {{order}} WHERE user_id IN (SELECT id FROM {{user}} WHERE collaborator_id = $collaborator->id))";
		}

		$arr["actions"]["action"]["insert"] = array(
			"order_id"
		);
	}
	if(!$collaboratorConditionSet){
		$arr["model"]["conditions"][] = "t.order_id IN (SELECT id FROM {{order}} WHERE user_id IN (SELECT id FROM {{user}} WHERE collaborator_group_id = $collaborator->collaborator_group_id))";
	}
}

if($collaborator->collaborator_group->is_admin_group && $collaborator->is_manager){
	$arr["model"]["conditions"] = array();
}

if($collaborator->type!=Collaborator::TYPE_STORE){
	unset($arr["actions"]["extendedActionTogether"]["set_delivered_storehouse_vietnam"]);
}

return $arr; 