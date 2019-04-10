<?php
class DeliveryOrderList extends SList {
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
				"product_name" => array(),
				"description" => array(),
				"image" => array(),
				"image_url" => array(),
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
				"class" => "DeliveryOrder",
				"primaryField" => "id",
				"conditions" => array(
					"t.active" => 1,
					"t.user_id" => Yii::app()->user->id
				),
				"with" => array(
					"shop_delivery_order"
				),
				"onCriteria" => function($criteria) use($list){
					switch($list->type){
						case self::TYPE_ACTIVE:
							$criteria->addInCondition("t.status",array(
								DeliveryOrder::STATUS_SHIPPING, 
								DeliveryOrder::STATUS_STOREHOUSE_CHINA, 
								DeliveryOrder::STATUS_STOREHOUSE_VIETNAM
							));
							break;
						case self::TYPE_COMPLETED:
							$criteria->compare("t.status",DeliveryOrder::STATUS_COMPLETED);
							break;
						case self::TYPE_CANCELED:
							$criteria->compare("t.status",DeliveryOrder::STATUS_CANCELED);
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

					},
					"from_date_str" => function($criteria,$value){
						$criteria->addCondition("t.created_time >= :from_date");
						$criteria->params[":from_date"] = strtotime($value);
					},
					"to_date_str" => function($criteria,$value){
						$criteria->addCondition("t.created_time <= :to_date");
						$criteria->params[":to_date"] = strtotime($value) + 86400 - 1;
					},
					"delivery_order_id" => function($criteria,$value){
						$criteria->addCondition("t.id = :delivery_order_id");
						$criteria->params[":delivery_order_id"] = $value;
					},
					"delivery_order_code" => function($criteria,$value){
						$criteria->addCondition("shop_delivery_order.delivery_order_code = :delivery_order_code");
						$criteria->params[":delivery_order_code"] = $value;
					}
				),
				"preloadData" => false
			),
			"view" => array(
				"itemSelectable" => false,
				"trackUrl" => true,
				"viewPath" => array(
					"list" => "application.components.list.views.delivery_order_list",
					"item" => "application.components.list.views.delivery_order_list_item"
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
			"baseUrl" => Util::controller()->createUrl("/user/delivery_order_list") . "?"
		);

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