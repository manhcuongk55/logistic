<?php

Yii::import('application.models._base.BaseOrder');

class Order extends BaseOrder
{
	const
			STATUS_WAIT_FOR_SUBMIT = 1,
			STATUS_WAIT_FOR_PRICE = 2,
			STATUS_WAIT_FOR_DEPOSIT_AMOUNT = 3,
			STATUS_WAIT_FOR_DEPOSIT = 4,
			STATUS_DEPOSIT_DONE = 5,
			STATUS_ORDERED = 6,
			STATUS_SHIPPING = 7,
			STATUS_STOREHOUSE_VIETNAM = 8,
			STATUS_STOREHOUSE_CHINA = 9,
			STATUS_COMPLETED = 10,
			STATUS_CANCELED = 11;

	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public function relations(){
		return array(
			"user" => array(
				self::BELONGS_TO, "User", "user_id"
			),
			"order_products" => array(
				self::HAS_MANY, "OrderProduct", "order_id"
			),
			"shop_delivery_orders" => array(
				self::HAS_MANY, "ShopDeliveryOrder", "order_id"
			)
		);
	}

	public $listDropdownConfig = array(
		"status" => array(
			self::STATUS_WAIT_FOR_SUBMIT => "Khởi tạo",
			self::STATUS_WAIT_FOR_PRICE => "Chờ báo giá",
			self::STATUS_WAIT_FOR_DEPOSIT_AMOUNT => "Chờ báo đặt cọc",
			self::STATUS_WAIT_FOR_DEPOSIT => "Chờ đặt cọc",
			self::STATUS_DEPOSIT_DONE => "Đã đặt cọc",
			self::STATUS_ORDERED => "Đã đặt hàng",
			self::STATUS_SHIPPING => "Đang chuyển hàng",
			self::STATUS_STOREHOUSE_CHINA => "Về kho Trung Quốc",
			self::STATUS_STOREHOUSE_VIETNAM => "Về kho Việt Nam",
			self::STATUS_COMPLETED => "Hoàn thành",
			self::STATUS_CANCELED => "Đã hủy",
		)
	);

	public function calculateServicePricePercentage($totalPrice,$willAddError=false){
		if($totalPrice < 5000000){
			return 7;
		} else if($totalPrice < 20000000){
			return 5;
		} else if($totalPrice < 100000000){
			return 4;
		} else {
			if($willAddError){
				$this->addError("global","Đối với đơn hàng lớn hơn 100.000.000đ bạn cần nhập phí dịch vụ!");
			}
			return null;
		}
	}

	public function calculateWeightPrice($totalWeight,$willAddError=false){
		if($totalWeight < 20){
			return 25000;
		} else if($totalWeight < 100){
			return 20000;
		} else if($totalWeight < 200){
			return 15000;
		} else {
			if($willAddError){
				$this->addError("global","Đối với đơn hàng có tổng số cân nặng lớn hơn 200kg bạn cần nhập cước cân nặng (vnđ / kg) !");
			}
			return null;
		}
	}

	public function reCalculatePrice(){
		OrderProduct::model()->noCache();
		Order::model()->noCache();
		ShopDeliveryOrder::model()->noCache();

		$totalWeight = 0;
		$totalRealPriceNdt = 0;
		foreach($this->shop_delivery_orders as $shopDeliveryOrder){
			$totalWeight += $shopDeliveryOrder->total_weight;
			$totalRealPriceNdt += $shopDeliveryOrder->real_price;
		}
		$this->total_weight = $totalWeight;
		if(!$this->weight_price){
			$this->weight_price = $this->calculateWeightPrice($this->total_weight,true);
			if(!$this->weight_price){
				return false;
			}
		}

		$totalRealPriceNdt = 0;
		foreach($this->order_products as $orderProduct){
			$totalRealPriceNdt += $orderProduct->real_price * $orderProduct->count;
		}

		$this->total_real_price_ndt = $totalRealPriceNdt;
		$this->total_real_price = $this->total_real_price_ndt * $this->exchange_rate;

		$this->final_price = $this->total_price + $this->total_delivery_price + $this->service_price + $this->shipping_home_price + $this->total_weight_price + $this->extra_price;

		$this->remaining_amount = $this->final_price - $this->deposit_amount;

		$this->save();

	}

	public function modifyPrice($weightPrice,$exchangeRate,$servicePricePercentage){
		return $this->setPrice($weightPrice,$exchangeRate,$servicePricePercentage,null);
	}

	public function setPrice($weightPrice,$exchangeRate,$servicePricePercentage,$shippingHomePrice){
		if($this->status!=self::STATUS_WAIT_FOR_PRICE && $this->status!=self::STATUS_WAIT_FOR_DEPOSIT_AMOUNT)
			return false;

		OrderProduct::model()->noCache();
		Order::model()->noCache();
		ShopDeliveryOrder::model()->noCache();

		$totalPriceNdt = 0;
		foreach($this->order_products as $orderProduct){
			if(!$orderProduct->price){
				$this->addError("global","Bạn cần cập nhật đầy đủ thông tin về giá sản phẩm trước khi thực hiện hành động này");
				return false;
			}
			$totalPriceNdt += $orderProduct->price * $orderProduct->count;
		}

		$totalDeliveryPriceNdt = 0;
		$totalWeight = 0;
		$totalRealPriceNdt = 0;
		foreach($this->shop_delivery_orders as $shopDeliveryOrder){
			/*if(!$shopDeliveryOrder->delivery_price_ndt){
				$this->addError("global","Bạn cần cập nhật đầy đủ thông tin về vận đơn trước khi thực hiện hành động này");
				return false;
			}*/
			$totalDeliveryPriceNdt += $shopDeliveryOrder->delivery_price_ndt;
			$totalWeight += $shopDeliveryOrder->total_weight;
			$totalRealPriceNdt += $shopDeliveryOrder->real_price;
		}

		if(!$exchangeRate){
			$exchangeRate = Util::param2("setting","vnd_ndt_rate");
		}

		$this->status = self::STATUS_WAIT_FOR_DEPOSIT_AMOUNT;

		$this->total_weight = $totalWeight;
		$this->exchange_rate = $exchangeRate;
		$this->shipping_home_price = $shippingHomePrice;

		$this->total_price_ndt = $totalPriceNdt;
		$this->total_real_price_ndt = $totalRealPriceNdt;
		$this->total_delivery_price_ndt = $totalDeliveryPriceNdt;
		
		$this->total_price = $this->total_price_ndt * $this->exchange_rate;
		$this->total_real_price = $this->total_real_price_ndt * $this->exchange_rate;
		$this->total_delivery_price = $this->total_delivery_price_ndt * $this->exchange_rate;
		
		$this->service_price_percentage = $servicePricePercentage;
		if(!$this->service_price_percentage){
			$this->service_price_percentage = $this->calculateServicePricePercentage($this->total_price,true);
			if(!$this->service_price_percentage){
				return false;
			}
		}

		$this->weight_price = $weightPrice;
		if(!$this->weight_price){
			$this->weight_price = $this->calculateWeightPrice($this->total_weight,true);
			if(!$this->weight_price){
				return false;
			}
		}

		$this->service_price = $this->service_price_percentage * $this->total_price / 100;
		$this->total_weight_price = $this->total_weight * $this->weight_price;

		$this->final_price = $this->total_price + $this->total_delivery_price + $this->service_price + $this->shipping_home_price + $this->total_weight_price;
		
		$result = $this->save();
		if($result){
			$this->notifyChangeToUser();
		}
		return $result;
	}

	public function setDepositAmount($depositAmount){
		if($this->status!=self::STATUS_WAIT_FOR_DEPOSIT_AMOUNT)
			return false;
		
		$this->status = self::STATUS_WAIT_FOR_DEPOSIT;
		$this->deposit_amount = $depositAmount;
		$this->remaining_amount = $this->final_price - $this->deposit_amount;

		$result = $this->save(array(
			"status", "deposit_amount", "remaining_amount"
		));
		if($result){
			$this->notifyChangeToUser();
		}
		return $result;
	}

	public function setDepositDone(){
		if($this->status!=self::STATUS_WAIT_FOR_DEPOSIT)
			return false;
		$this->status = self::STATUS_DEPOSIT_DONE;
		$result = $this->save(true,array(
			"status"
		));
		if($result){
			$this->notifyChangeToUser();
		}
		return $result;
	}

	public function setStartOrdered(){
		if($this->status!=self::STATUS_DEPOSIT_DONE)
			return false;
		$this->status = self::STATUS_ORDERED;
		$result = $this->save(true,array(
			"status"
		));
		if($result){
			$this->notifyChangeToUser();
		}
		return $result;
	}

	public function setStartShipping(){
		if($this->status!=self::STATUS_ORDERED)
			return false;
		$this->status = self::STATUS_SHIPPING;
		$result = $this->save(true,array(
			"status"
		));
		if($result){
			$this->notifyChangeToUser();
		}
		return $result;
	}

	public function setDeliveredStorehouseChina(){
		if($this->status!=self::STATUS_SHIPPING)
			return false;
		$this->status = self::STATUS_STOREHOUSE_CHINA;
		$result = $this->save(true,array(
			"status"
		));
		if($result){
			$this->notifyChangeToUser();
		}
		return $result;
	}

	public function setDeliveredStorehouseVietnam(){
		if($this->status!=self::STATUS_STOREHOUSE_CHINA)
			return false;
		$this->status = self::STATUS_STOREHOUSE_VIETNAM;
		$result = $this->save(true,array(
			"status"
		));
		if($result){
			$this->notifyChangeToUser();
		}
		return $result;
	}

	public function setCompleted($shippingHomePrice,$extraPrice){
		if($this->status!=self::STATUS_STOREHOUSE_VIETNAM)
			return false;
		$this->is_paid = 1;
		$this->status = self::STATUS_COMPLETED;
		$this->shipping_home_price = $shippingHomePrice ? $shippingHomePrice : 0;
		$this->extra_price = $extraPrice ? $extraPrice : 0;
		$result = $this->save(true,array(
			"status", "is_paid", "shipping_home_price", "extra_price"
		));
		$this->reCalculatePrice();
		if($result){
			$this->notifyChangeToUser();
		}
		return $result;
	}

	public function setCanceled(){
		$this->status = self::STATUS_CANCELED;
		$result = $this->save(true,array(
			"status"
		));
		if($result){
			$this->notifyChangeToUser();
		}
		return $result;
	}

	public static function getOrderOfCollaboratorGroup($id,$collaboratorGroupId){
		$collaboratorGroup = CollaboratorGroup::model()->findByPk($collaboratorGroupId);
		$criteria = array();
		if(!$collaboratorGroup->is_admin_group){
			$arr["condition"] = "t.user_id IN (SELECT id FROM {{user}} WHERE collaborator_group_id = $collaboratorGroupId)";
		}		
		return Order::model()->findByAttributes(array(
			"id" => $id,
		),$criteria);
	}

	function notifyChangeToUser(){
		Notification::push($this->user_id,Notification::TYPE_ORDER_STATUS_CHANGED,array(
			"status" => $this->status,
			"order_id" => $this->id
		));
	}
}