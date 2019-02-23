<?php
class OrderList extends SList {
	const
			TYPE_ACTIVE = 1,
			TYPE_COMPLETED = 2,
			TYPE_CANCELED = 3,
			TYPE_ALL = 4;

	public $type, $typeConfig;

	protected function getConfig(){
		$list = $this;
		$arr = array(
			"fields" => array(
				"__item" => array(),
				"id" => array(),
				"active" => array(),
				"created_time" => array(),
				"updated_time" => array(),
				"user_id" => array(),
				"status" => array(),
				"total_price" => array(),
				"total_weight" => array(),
				"is_paid" => array(),
				"description" => array(),
				"deposit_amount" => array(),
				"total_delivery_price" => array(),
				"total_real_price" => array(),
				"total_quantity" => array(),
				"total_real_price_ndt" => array(),
				"total_price_ndt" => array(),
				"total_delivery_price_ndt" => array(),
				"weight_price" => array(),
				"service_price" => array(),
				"final_price" => array(),
				"remaining_amount" => array(),
				"exchange_rate" => array(),
				"shipping_home_price" => array(),
				"total_weight_price" => array(),
				"service_price_percentage" => array(),
				"real_exchange_rate" => array()
			),
			"actions" => array(
				"action" => array(
					"data" => array(
						"search" => false,
						"order" => false,
						"limit" => true,
						"offset" => true,
						"page" => true,
					),
				)
			),
			"model" => array(
				"class" => "Order",
				"primaryField" => "id",
				"conditions" => array(
					"t.active" => 1,
				),
				"with" => array(
					
				),
				"onCriteria" => function($criteria) use($list){
					switch($list->type){
						case self::TYPE_ACTIVE:
							$criteria->addInCondition("t.status",array(
								Order::STATUS_WAIT_FOR_SUBMIT, Order::STATUS_WAIT_FOR_PRICE, Order::STATUS_WAIT_FOR_DEPOSIT_AMOUNT, Order::STATUS_WAIT_FOR_DEPOSIT, 
								Order::STATUS_DEPOSIT_DONE, Order::STATUS_ORDERED, Order::STATUS_SHIPPING,Order::STATUS_STOREHOUSE_CHINA, 
								Order::STATUS_STOREHOUSE_VIETNAM
							));
							break;
						case self::TYPE_COMPLETED:
							$criteria->compare("t.status",Order::STATUS_COMPLETED);
							break;
						case self::TYPE_CANCELED:
							$criteria->compare("t.status",Order::STATUS_CANCELED);
							break;
						case self::TYPE_ALL:
							break;
					}
				},
				"addedCondition" => array(),
				"defaultQuery" => array(
					"orderBy" => "id",
					"orderType" => "desc",
					"limit" => 12,
					"offset" => 0,
					"search" => "",
					"advancedSearch" => array(
						"active" => 1
					),
					"page" => 1
				),
				"dynamicInputs" => array(
					"list_type" => function($criteria,$value){
						if($value && $value!="1"){
							$criteria->addCondition("status >= :listTypeStatus");
							$criteria->params[":listTypeStatus"] = Order::STATUS_DEPOSIT_DONE;
						}
					},
					"from_date" => function($criteria,$value){
						$criteria->addCondition("t.created_time >= :from_date");
						$criteria->params[":from_date"] = $value;
					},
					"to_date" => function($criteria,$value){
						$criteria->addCondition("t.created_time <= :to_date");
						$criteria->params[":to_date"] = $value;
					},
					"from_date_str" => function($criteria,$value){
						$criteria->addCondition("t.created_time >= :from_date");
						$criteria->params[":from_date"] = strtotime($value);
					},
					"to_date_str" => function($criteria,$value){
						$criteria->addCondition("t.created_time <= :to_date");
						$criteria->params[":to_date"] = strtotime($value) + 86400 - 1;
					},
					"order_id" => function($criteria,$value){
						$criteria->addCondition("t.id = :order_id");
						$criteria->params[":order_id"] = $value;
					}
				),
				"preloadData" => false
			),
			"view" => array(
				"itemSelectable" => false,
				"trackUrl" => true,
				"viewPath" => array(
					"list" => "application.components.list.views.order_list",
					"item" => "application.components.list.views.order_list_item"
				),
				//"infiniteScroll" => true
			),
			"pagination" => array(
				"first" => true,
				"back" => true,
				"next" => true,
				"last" => true,
				"count" => 5,
				"view" => "application.components.list.views.list-pagination"
			),
			"mode" => "jquery",
			"autoRenderPage" => false,
			"baseUrl" => Util::controller()->createUrl("/user/order_list") . "?"
		);

		if($this->type=="report1"){
			$arr["view"]["viewPath"] = array(
				"list" => "application.components.list.views.report1_order_list",
				"item" => "application.components.list.views.report1_order_list_item"
			);
			$arr["model"]["defaultQuery"]["limit"] = -1;
			$arr["mode"] = "php";
			//$arr["baseUrl"] = Util::controller()->createUrl("/home/order_list") . "?";
		} else if($this->type=="report2"){
			$arr["view"]["viewPath"] = array(
				"list" => "application.components.list.views.report2_order_list",
				"item" => "application.components.list.views.report2_order_list_item"
			);
			$arr["model"]["defaultQuery"]["limit"] = -1;
			$arr["mode"] = "php";
			//$arr["baseUrl"] = Util::controller()->createUrl("/home/order_list") . "?";
		} else if($this->type=="report3"){
			$arr["view"]["viewPath"] = array(
				"list" => "application.components.list.views.report3_order_list",
				"item" => "application.components.list.views.report3_order_list_item"
			);
			$arr["baseUrl"] = Util::controller()->createUrl("/home/order_list") . "?";
		} else {
			$arr["model"]["conditions"]["t.user_id"] = Yii::app()->user->id;
		}

		return $arr;
	}

	public function __construct($type=null,$typeConfig=null){
		if($type==null){
			$type = Input::get("list_type");
		}

		$this->type = $type;
		$this->typeConfig = $typeConfig;

		parent::__construct();

		$this->setDynamicInput("list_type",$this->type);


		if($this->typeConfig){
			/*if($this->type==self::TYPE_USER_PAGE){
				$this->setDynamicInput("user_id",$this->typeConfig->id);
			}*/
		}
	}
}