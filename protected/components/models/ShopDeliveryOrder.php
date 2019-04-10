<?php

Yii::import('application.models._base.BaseShopDeliveryOrder');

class ShopDeliveryOrder extends BaseShopDeliveryOrder
{
	const
			STATUS_INIT = 1,
			STATUS_ORDERED = 2,
			STATUS_SHIPPING = 3,
			STATUS_STOREHOUSE_CHINA = 4,
			STATUS_STOREHOUSE_VIETNAM = 5,
			STATUS_CANCELED = 6;

	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public function relations(){
		return array(
			"order_products" => array(
				self::HAS_MANY, "OrderProduct", "shop_delivery_order_id"
			),
			"order" => array(
				self::BELONGS_TO, "Order", "order_id"
			),
		);
	}

	public $listDropdownConfig = array(
		"status" => array(
			self::STATUS_INIT => "Khởi tạo",
			self::STATUS_ORDERED => "Đã đặt hàng",
			self::STATUS_SHIPPING => "Đang chuyển hàng",
			self::STATUS_STOREHOUSE_CHINA => "Về kho Trung Quốc",
			self::STATUS_STOREHOUSE_VIETNAM => "Về kho Việt Nam",
			self::STATUS_CANCELED => "Đã hủy",
		)
	);

	public function setOrdered(){
		if($this->status!=self::STATUS_INIT)
			return false;
		foreach($this->order_products as $orderProduct){
			if(!$orderProduct->real_price || !$orderProduct->ordered_count){
				$this->addError("global","Bạn cần nhập giá thực tế và số lượng đặt được của từng sản phẩm trước khi xác nhận đặt hàng thành công!");
				return false;
			}
		}
		$this->status = self::STATUS_ORDERED;
		$result = $this->save(true,array(
			"status"
		));
		if($result){
			if($this->checkOrderStatusUpdatable()){
				$this->order->setStartOrdered();
			}
		}
		return $result;
	}
	
	public function setStartShipping($deliveryOrderCode,$orderCode){
		if($this->status!=self::STATUS_ORDERED)
			return false;
		$this->status = self::STATUS_SHIPPING;
		$this->delivery_order_code = $deliveryOrderCode;
		$this->order_code = $orderCode;
		$result = $this->save(true,array(
			"status", "delivery_order_code", "order_code"
		));
		if($result){
			if($this->checkOrderStatusUpdatable()){
				$this->order->setStartShipping();
			}
		}
		return $result;
	}

	public function setDeliveredStorehouseChina(){
		if($this->status!=self::STATUS_SHIPPING)
			return false;
		$this->status = self::STATUS_STOREHOUSE_CHINA;
		$this->china_delivery_time = time();
		$result = $this->save(true,array(
			"status", "china_delivery_time"
		));
		if($result){
			if($this->checkOrderStatusUpdatable()){
				$this->order->setDeliveredStorehouseChina();
			}
		}
		return $result;
	}

	public function setDeliveredStorehouseVietnam($totalWeight){
		if($this->status!=self::STATUS_STOREHOUSE_CHINA)
			return false;
		$this->status = self::STATUS_STOREHOUSE_VIETNAM;
		$this->vietnam_delivery_time = time();
		if($totalWeight){
			$this->total_weight = $totalWeight;
		}
		$result = $this->save(true,array(
			"status", "vietnam_delivery_time", "total_weight"
		));
		if($result){
			if($this->checkOrderStatusUpdatable()){
				$this->order->setDeliveredStorehouseVietnam();
			}
		}
		return $result;
	}

	public function setCanceled(){
		$this->status = self::STATUS_CANCELED;
		$result = $this->save(true,array(
			"status"
		));
		return $result;
	}

	private function checkOrderStatusUpdatable(){
		foreach($this->order->shop_delivery_orders as $shopDeliveryOrder){
			if($shopDeliveryOrder->status < $this->status)
				return false;
		}
		return true;
	}
}