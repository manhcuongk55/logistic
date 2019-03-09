<?php

Yii::import('application.models._base.BaseNotification');

class Notification extends BaseNotification
{
	const
			TYPE_FROM_SITE = 1,
			TYPE_ORDER_STATUS_CHANGED = 2,
			TYPE_DELIVERY_ORDER_STATUS_CHANGED = 3;

	const 	SCENARIO_DO_READ = 1;

	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public static function getParamsArray(){
		return array(
			self::TYPE_FROM_SITE => array(
				"message"
			),
			self::TYPE_ORDER_STATUS_CHANGED => array(
				"status", "order_id"
			),
			self::TYPE_DELIVERY_ORDER_STATUS_CHANGED => array(
				"status", "delivery_order_id"
			),
		);
	}

	public static function getParamsArrayOfType($type){
		$arr = self::getParamsArray();
		return $arr[$type];
	}

	public function relations(){
		return array(
			"user" => array(
				self::BELONGS_TO,"User","user_id"
			)
		);
	}

	public function doRead(){
		$this->scenario = self::SCENARIO_DO_READ;
		$this->is_read = 1;
		$this->save(true,array(
			"is_read"
		));
	}

	public function getParam($index=false,$default=null){
		$arr = explode(":", $this->params);
		if($index===false)
			return $arr;
		if(!is_integer($index)){
			$fieldArr = self::getParamsArrayOfType($this->type);
			$index = array_search($index,$fieldArr);
		}
		return ArrayHelper::get($arr,$index,$default);
	}

	public function getParamValues(){
		$arr = explode(":", $this->params);
		$fieldArr = self::getParamsArrayOfType($this->type);
		$returnArr = array();
		foreach($fieldArr as $i => $field){
			$returnArr[$field] = $arr[$i];
		}
		return $returnArr;
	}

	public function getMessage(){
		$messageKey = "type_" . $this->type;
		if($this->type==self::TYPE_ORDER_STATUS_CHANGED){
			$status = $this->getParam("status");
			$messageKey = "type_" . $this->type . "_status_" . $status;
		} else if($this->type==self::TYPE_DELIVERY_ORDER_STATUS_CHANGED){
			$status = $this->getParam("status");
			$messageKey = "type_" . $this->type . "_status_" . $status;
		}
		return l("home/notifications",$messageKey,$this->getParamValues());
	}

	public function getUrl(){
		switch($this->type){
			case self::TYPE_ORDER_STATUS_CHANGED:
				$orderId = $this->getParam("order_id");
				return Yii::app()->controller->createUrl("/user/order",array(
					"order_id" => $orderId
				));
			case self::TYPE_DELIVERY_ORDER_STATUS_CHANGED:
				$deliveryOrderId = $this->getParam("delivery_order_id");
				return Yii::app()->controller->createUrl("/user/delivery_order",array(
					"delivery_order_id" => $deliveryOrderId
				));
		}
		return "javascript:;";
	}

	public static function push($userId, $type, $dataParams=null){
		$notification = new Notification();
		$notification->user_id = $userId;
		$notification->type = $type;
		$params = array();
		if($dataParams && is_array($dataParams)){
			$fieldArr = self::getParamsArrayOfType($type);
			foreach($fieldArr as $fieldName){
				$params[] = $dataParams[$fieldName];
			}
		}
		$params = implode(":", $params);
		$notification->params = $params;
		$result = $notification->save();
		return $result ? $notification : false;
	}
}