<?php
$this->data["order_id"] = Input::get("order_id",array(
	"rules" => array(
		array("required")
	)
),"html");

$this->data["order"] = Order::getOrderOfCollaboratorGroup($this->data["order_id"],Util::controller()->getUser()->collaborator_group_id);
if(!$this->data["order"]){
	Output::showPermissionDenied();
}


$this->data["shop_delivery_order_id"] = Input::get("shop_delivery_order_id");
if($shopDeliveryOrderId = $this->data["shop_delivery_order_id"]){
	$this->data["shop_delivery_order"] = ShopDeliveryOrder::model()->findByPk($shopDeliveryOrderId);
} else {
	$this->data["shop_delivery_order"] = null;
}

$arr = array(
	"onRun" => function($list){
		$list->setDynamicInput("order_id",Util::controller()->data["order_id"]);
		if($shopDeliveryOrderId = Util::controller()->data["shop_delivery_order_id"]){
			$list->setDynamicInput("shop_delivery_order_id",$shopDeliveryOrderId);
		}
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
			// "inputType" => "number",
			"default" => $this->data["order_id"],
			// "displayInput" => true
		),
		"shop_delivery_order_id" => array(),
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
		),
		"web_price" => array(
			"inputType" => "money_input",
			"displayType" => "money_display"
		),
		"real_price" => array(
			//"inputType" => "money_input",
		),
		"shop_id" => array(
			//"inputType" => "money_input",
		),
		"real_price" => array(
			//"inputType" => "money_input",
		),
		"color" => array(),
		"description" => array(),
		"collaborator_note" => array(
			"inputType" => "textarea"
		),
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
			},
			"shop_delivery_order_id" => function($criteria,$value){
				$criteria->compare("t.shop_delivery_order_id",$value);
			},
		),
		"preloadData" => false,
		"forms" => array(),
		"insertScenario" => "insert_from_collaborator",
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
			"order_id", "price", "real_price", "count", "image"
		),
		"detail" => array(
			"id", "created_time", "updated_time", "active", "order_id", "name", "url", "website_type", "price", "real_price" , "count", "ordered_count", "vietnamese_name", "image", "web_price", "color", "description", "collaborator_note"
		),
		"action" => true,
		"actionButtons" => array(
			array("link",function($item){
				$url = $item->url;
				if(!$url){
					return array(
						"disabled" => true
					);
				}
				return array(
					"content" => '<i class="fa fa-external-link"></i>',
					"newTab" => true,
					"href" => $url,
					"title" => "Đường dẫn sản phẩm"
				);
			}),
		)
	),
	"mode" => "jquery",
);

$collaborator = Util::controller()->getUser();

if($this->data["order"]->status==Order::STATUS_WAIT_FOR_PRICE){
	$user = Util::controller()->getUser();
	if($user->type==Collaborator::TYPE_SALE){
		$arr["actions"]["action"]["update"] = array(
			"price", "real_price", "order_id"
		);
	} else if($user->type==Collaborator::TYPE_SHIP){
		$arr["actions"]["action"]["update"] = array(
			"order_id"
		);
	}
} else if(($this->data["order"]->status==Order::STATUS_DEPOSIT_DONE) && $this->data["shop_delivery_order"] && ($this->data["shop_delivery_order"]->status==ShopDeliveryOrder::STATUS_INIT)
){
	if(Util::controller()->getUser()->type==Collaborator::TYPE_SHIP){
		$arr["actions"]["action"]["update"] = array(
			"ordered_count", "real_price"
		);
	}
} else if($this->data["shop_delivery_order"] && ($this->data["shop_delivery_order"]->status==ShopDeliveryOrder::STATUS_STOREHOUSE_VIETNAM)){
	if(Util::controller()->getUser()->type==Collaborator::TYPE_STORE){
		$arr["actions"]["action"]["update"] = array(
			"ordered_count"
		);
	}
}

if(!is_array($arr["actions"]["action"]["update"])){
	$arr["actions"]["action"]["update"] = array();
}
$arr["actions"]["action"]["update"][] = "collaborator_note";


if($collaborator->type==Collaborator::TYPE_SALE){
	$arr["actions"]["action"]["insert"] = array(
		"order_id", "shop_id", "url", "name", "vietnamese_name", "image", "count", "description", "web_price"
	);
}

return $arr;