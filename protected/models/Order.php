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
			STATUS_STOREHOUSE_CHINA = 8,
			STATUS_STOREHOUSE_VIETNAM = 9,
			STATUS_COMPLETED = 10,
			STATUS_CANCELED = 11,
			STATUS_PAID = 12,
			STATUS_EXPORTED = 13;

	public $collaborator_id, $collaborator_name;

	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public function relations(){
		return array(
			"user" => array(
				self::BELONGS_TO, "User", "user_id"
			),
			"shop_delivery_order" => array(
				self::HAS_ONE, "ShopDeliveryOrder", "order_id"
			),
			"order_products" => array(
				self::HAS_MANY, "OrderProduct", "order_id",
				"condition" => "active = 1"
			),
			"shop_delivery_orders" => array(
				self::HAS_MANY, "ShopDeliveryOrder", "order_id",
				"condition" => "active = 1 AND type = " . ShopDeliveryOrder::TYPE_ORDER . " AND status != " . ShopDeliveryOrder::STATUS_CANCELED
			)
		);
	}

	public function getListDropdownConfigBase(){
		$arr = array();
		$arr["status"] = array(
			self::STATUS_WAIT_FOR_SUBMIT => "Khởi tạo",
			self::STATUS_WAIT_FOR_PRICE => "Chờ báo giá",
			self::STATUS_WAIT_FOR_DEPOSIT_AMOUNT => "Chờ báo đặt cọc",
			self::STATUS_WAIT_FOR_DEPOSIT => "Chờ đặt cọc",
			self::STATUS_DEPOSIT_DONE => "Đã đặt cọc",
			self::STATUS_ORDERED => "Đã đặt hàng",
			self::STATUS_SHIPPING => "Đang về kho Trung Quốc",
			self::STATUS_STOREHOUSE_CHINA => "Về kho Trung Quốc",
			self::STATUS_STOREHOUSE_VIETNAM => "Về kho Việt Nam",
			self::STATUS_COMPLETED => "Hoàn thành",
			self::STATUS_CANCELED => "Đã hủy",
			self::STATUS_PAID => "Đã thanh lý",
			self::STATUS_EXPORTED => "Đã xuất kho",
		);
		$arr["status_by_collaborator"] = array();
		$user = Util::controller()->getUser();
		if($user!=null && $user instanceof Collaborator){
			if($user->email==Util::param2("common","masterUser")){
				$arr["status_by_collaborator"] = $arr["status"];
			} else {
				switch($user->type){
					case Collaborator::TYPE_SALE:
						$arr["status_by_collaborator"] = array(
							self::STATUS_WAIT_FOR_PRICE => "Chờ báo giá",
							self::STATUS_WAIT_FOR_DEPOSIT_AMOUNT => "Chờ báo đặt cọc",
							self::STATUS_WAIT_FOR_DEPOSIT => "Chờ đặt cọc",
						);
						break;
					case Collaborator::TYPE_SHIP:
						$arr["status_by_collaborator"] = array(
							self::STATUS_ORDERED => "Đã đặt hàng",
							self::STATUS_SHIPPING => "Đang về kho Trung Quốc",
							self::STATUS_STOREHOUSE_CHINA => "Về kho Trung Quốc",
						);
						break;
					case Collaborator::TYPE_STORE:
						$arr["status_by_collaborator"] = array(
							self::STATUS_STOREHOUSE_CHINA => "Về kho Trung Quốc",
							self::STATUS_STOREHOUSE_VIETNAM => "Về kho Việt Nam",
							self::STATUS_EXPORTED => "Đã xuất kho",
						);
						break;
					case Collaborator::TYPE_ACCOUNTANT:
						$arr["status_by_collaborator"] = array(
							self::STATUS_WAIT_FOR_DEPOSIT_AMOUNT => "Chờ báo đặt cọc",
							self::STATUS_WAIT_FOR_DEPOSIT => "Chờ đặt cọc",
							self::STATUS_DEPOSIT_DONE => "Đã đặt cọc",
							self::STATUS_STOREHOUSE_VIETNAM => "Về kho Việt Nam",
							self::STATUS_COMPLETED => "Hoàn thành",
							self::STATUS_PAID => "Đã thanh lý",
						);
						break;
				}
			}
		}
		return $arr;
	}

	private $status_backup = null;
	protected function afterFind(){
		parent::afterFind();
		$this->status_backup = $this->status;
	}

	protected function beforeSave(){
		if(!parent::beforeSave()){
			return false;
		}

		$user = Util::controller()->getUser();
		if($user->email==Util::param2("common","masterUser")){
			return true;
		}

		if($this->scenario=="update_from_collaborator"){
			if($this->status > $this->status_backup || $this->status_backup >= self::STATUS_DEPOSIT_DONE){
				if(Util::controller()->getUser()->type==Collaborator::TYPE_SALE){
					$this->addError("status","Bạn không có quyền thay đổi trạng thái đơn hàng!");
					return false;
				}
			}
		}
		return true;
	}

	protected function beforeDelete(){
		if(!parent::beforeDelete()){
			return false;
		}

		$user = Util::controller()->getUser();
		if($user->email==Util::param2("common","masterUser")){
			return true;
		}

		if($this->scenario=="delete_from_collaborator"){
			if($this->status_backup >= self::STATUS_DEPOSIT_DONE){
				if(Util::controller()->getUser()->type!=Collaborator::TYPE_ACCOUNTANT){
					$this->addError("status","Bạn không có quyền xóa đơn hàng!");
					return false;
				}
			}
		}
		return true;
	}

	protected function afterSave(){
		if($this->saveHandleBlocked)
			return;
		if(!$this->getIsNewRecord()){
			return;
		}
		if($this->scenario=="insert_from_collaborator"){
			$this->status = self::STATUS_WAIT_FOR_PRICE;
			$this->active = 1;

			$this->blockSaveHandle();
			$this->setIsNewRecord(false);
			$this->save(false,array(
				"status", "active",
			));
			$this->releaseSaveHandle();	
		}
		parent::afterSave();
	}

	public function calculateServicePricePercentage($totalPrice,$willAddError=false){
		if ($this->user->customer_type && $this->user->customer_type->service_price_percentage >= 0){
			return $this->user->customer_type->service_price_percentage;
		} 

		if($totalPrice < 5000000){
			return 5;
		} else if($totalPrice < 15000000){
			return 4;
		} else if($totalPrice < 30000000){
			return 3;
		} else {
			if($willAddError){
				$this->addError("global","Đối với đơn hàng lớn hơn 100.000.000đ bạn cần nhập phí dịch vụ!");
			}
			return null;
		}
	}

	public function calculateWeightPrice($totalWeight,$willAddError=false){
		if ($this->user->customer_type && $this->user->customer_type->weight_price >= 0){
			return $this->user->customer_type->weight_price;
		}

		if($totalWeight < 20){
			return 25000;
		} else if($totalWeight < 50){
			return 20000;
		} else if($totalWeight < 100){
			return 15000;
		} else {
			if(!$this->weight_price){
				if($willAddError){
					$this->addError("global","Đối với đơn hàng có tổng số cân nặng lớn hơn 100kg bạn cần nhập cước cân nặng (vnđ / kg) !");
				}
				return null;
			}
			return $this->weight_price;
		}
	}

	public function calculateVolumePrice($totalVolume,$willAddError=false){
		if ($this->user->customer_type && $this->user->customer_type->volume_price >= 0){
			return $this->user->customer_type->volume_price;
		}

		if($totalVolume < 1){
			return 0;
		} else if($totalVolume < 10){
			return 2500;
		} else if($totalVolume < 30){
			return 2200;
		} else {
			if(!$this->volume_price){
				if($willAddError){
					$this->addError("global","Đối với đơn hàng có tổng số khối lớn hơn 30m3 bạn cần nhập cước (vnđ / m3) !");
				}
				return null;
			}
			return $this->volume_price;
		}
	}

	public function reCalculateWeightPrice(){
		$totalWeight = 0;
		foreach($this->shop_delivery_orders as $shopDeliveryOrder){
			$totalWeight += $shopDeliveryOrder->total_weight;
		}
		$this->total_weight = $totalWeight;
		if(!$this->is_weight_price_fixed){
			$this->weight_price = $this->calculateWeightPrice($this->total_weight,true);
			if(!$this->weight_price){
				return false;
			}
		}

		$totalVolume = 0;
		foreach($this->shop_delivery_orders as $shopDeliveryOrder){
			$totalVolume += $shopDeliveryOrder->total_volume;
		}
		$this->total_volume = $totalVolume;
		if(!$this->is_weight_price_fixed){
			$this->volume_price = $this->calculateVolumePrice($this->total_volume,true);
			if(!$this->volume_price){
				return false;
			}
		}

		$this->total_weight_price = $this->weight_price * $this->total_weight + $this->volume_price * $this->total_volume;
		
		$this->save();
	}

	public function reCalculatePrice(){
		OrderProduct::model()->noCache();
		Order::model()->noCache();
		ShopDeliveryOrder::model()->noCache();

		$totalWeight = 0;
		$totalRealPriceNdt = 0;
		foreach($this->shop_delivery_orders as $shopDeliveryOrder){
			$totalWeight += $shopDeliveryOrder->total_weight;
			// $totalRealPriceNdt += $shopDeliveryOrder->real_price;
		}
		if($this->is_total_weight_price_fixed){
			$this->total_weight = $totalWeight;
			if(!$this->is_weight_price_fixed){
				$this->weight_price = $this->calculateWeightPrice($this->total_weight,true);
				if(!$this->weight_price){
					return false;
				}
			}
			$this->total_weight_price = $this->weight_price * $this->total_weight;
		}

		$totalRealPriceNdt = 0;
		foreach($this->order_products as $orderProduct){
			$totalRealPriceNdt += $orderProduct->real_price * $orderProduct->count;
		}

		$this->total_real_price_ndt = $totalRealPriceNdt;
		$this->total_real_price = $this->total_real_price_ndt * $this->exchange_rate;

		$this->final_price = $this->total_price + $this->total_delivery_price + $this->service_price; // + $this->shipping_home_price + $this->total_weight_price + $this->extra_price;

		$this->remaining_amount = $this->final_price + $this->shipping_home_price + $this->total_weight_price + $this->extra_price - $this->deposit_amount;

		$result = $this->save();

		return $result;
	}

	public function modifyPrice($weightPrice,$volumePrice,$exchangeRate,$servicePricePercentage){
		if($weightPrice || $volumePrice){
			$this->is_weight_price_fixed = 1;
		}
		return $this->setPrice($this->total_delivery_price_ndt,$weightPrice,$exchangeRate,$servicePricePercentage,null,$volumePrice);
	}

	public function setPrice($totalDeliveryPriceNdt,$weightPrice,$exchangeRate,$servicePricePercentage,$shippingHomePrice,$volumePrice=0,$name=""){
		if($this->status!=self::STATUS_WAIT_FOR_PRICE && $this->status!=self::STATUS_WAIT_FOR_DEPOSIT_AMOUNT)
			return false;

		OrderProduct::model()->noCache();
		Order::model()->noCache();
		ShopDeliveryOrder::model()->noCache();

		$totalPriceNdt = 0;
		$totalRealPriceNdt = 0;
		$totalCount = 0;
		foreach($this->order_products as $orderProduct){
			if($orderProduct->price===null){
				$this->addError("global","Bạn cần cập nhật đầy đủ thông tin về giá sản phẩm trước khi thực hiện hành động này. Mã sản phẩm #$orderProduct->id");
				return false;
			}
			$totalPriceNdt += $orderProduct->price * $orderProduct->count;
			$totalRealPriceNdt += $orderProduct->real_price * $orderProduct->count;
			$totalCount += $orderProduct->count;
		}

		$totalWeight = 0;
		foreach($this->shop_delivery_orders as $shopDeliveryOrder){
			/*if(!$shopDeliveryOrder->delivery_price_ndt){
				$this->addError("global","Bạn cần cập nhật đầy đủ thông tin về vận đơn trước khi thực hiện hành động này");
				return false;
			}*/
			// $totalDeliveryPriceNdt += $shopDeliveryOrder->delivery_price_ndt;
			$totalWeight += $shopDeliveryOrder->total_weight;
		}

		if(!$exchangeRate){
			if ($this->user->customer_type && $this->user->customer_type->exchange_rate >= 0){
				$exchangeRate = $this->user->customer_type->exchange_rate;
			} else {
				$exchangeRate = Util::param2("setting","vnd_ndt_rate");
			}
		}

		$this->status = self::STATUS_WAIT_FOR_DEPOSIT_AMOUNT;

		$this->total_weight = $totalWeight;
		$this->exchange_rate = $exchangeRate;
		$this->shipping_home_price = $shippingHomePrice;

		$this->total_quantity = $totalCount;
		$this->total_price_ndt = $totalPriceNdt;
		$this->total_real_price_ndt = $totalRealPriceNdt;
		$this->total_delivery_price_ndt = $totalDeliveryPriceNdt;
		
		$this->total_price = $this->total_price_ndt * $this->exchange_rate;
		$this->total_real_price = $this->total_real_price_ndt * $this->exchange_rate;
		$this->total_delivery_price = $this->total_delivery_price_ndt * $this->exchange_rate;
		
		$this->service_price_percentage = $servicePricePercentage;
		if(!$this->service_price_percentage){
			$this->service_price_percentage = $this->calculateServicePricePercentage($this->total_price,true);
			if($this->service_price_percentage===false){
				return false;
			}
		}

		$this->weight_price = $weightPrice;
		$this->volume_price = $volumePrice;
		if(!$this->is_weight_price_fixed){
			$this->weight_price = $this->calculateWeightPrice($this->total_weight,true);
			if($this->weight_price===false){
				return false;
			}
			$this->volume_price = $this->calculateVolumePrice($this->total_volume,true);
			if($this->volume_price===false){
				return false;
			}
		}

		$this->service_price = $this->service_price_percentage * $this->total_price / 100;
		$minServicePrice = Util::param2("setting","min_service_price");
		if($this->service_price < $minServicePrice){
			$this->service_price = $minServicePrice;
		}

		$this->total_weight_price = $this->total_weight * $this->weight_price + $this->total_volume * $this->volume_price;
		$minWeightPrice = Util::param2("setting","min_weight_price");
		if($this->total_weight_price < $minWeightPrice){
			$this->total_weight_price = $minWeightPrice;
		}

		$this->final_price = $this->total_price + $this->total_delivery_price + $this->service_price; // + $this->shipping_home_price + $this->total_weight_price;

		$this->remaining_amount = $this->final_price + $this->shipping_home_price + $this->total_weight_price + $this->extra_price - $this->deposit_amount;

		if($name){
			$this->name = $name;
		}
		
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
		$this->remaining_amount = $this->final_price + $this->shipping_home_price + $this->total_weight_price - $this->deposit_amount;

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

	public function setStartOrdered($orderCode,$exchangeRate,$realExchangeRate){
		if($this->status!=self::STATUS_DEPOSIT_DONE)
			return false;
		if(!$this->exchange_rate || $exchangeRate){
			if(!$exchangeRate){
				$exchangeRate = Util::param2("setting","vnd_ndt_rate");
			}
			$this->exchange_rate = $exchangeRate;
		}

		$this->status = self::STATUS_ORDERED;
		$this->order_code = $orderCode;
		$this->real_exchange_rate = $realExchangeRate;
		$result = $this->save(true,array(
			"status", "order_code", "exchange_rate", "real_exchange_rate",
		));
		if($result){
			$this->notifyChangeToUser();
		}
		return $result;
	}

	public function setStartShipping($anyway=false){
		if(!$anyway && $this->status!=self::STATUS_ORDERED)
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

	public function setDeliveredStorehouseChina($anyway=false){
		if(!$anyway && $this->status!=self::STATUS_SHIPPING)
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

	public function setDeliveredStorehouseVietnam($anyway=false){
		//if($this->status!=self::STATUS_STOREHOUSE_CHINA)
			//return false;
		if(!$anyway && !in_array($this->status,array(self::STATUS_SHIPPING,self::STATUS_STOREHOUSE_CHINA))){
			return false;
		}
		$this->status = self::STATUS_STOREHOUSE_VIETNAM;
		$result = $this->save(true,array(
			"status"
		));
		if($result){
			$this->notifyChangeToUser();
		}
		return $result;
	}

	public function setCompleted($shippingHomePrice,$extraPrice,$exchangeRate,$totalWeight,$totalWeightPrice,$servicePrice,$extraDescription){
		if($this->status!=self::STATUS_STOREHOUSE_VIETNAM)
			return false;
		$this->is_paid = 1;
		$this->status = self::STATUS_COMPLETED;
		$this->shipping_home_price = $shippingHomePrice ? $shippingHomePrice : 0;
		$this->extra_price = $extraPrice ? $extraPrice : 0;
		if($exchangeRate){
			$this->exchange_rate = $exchangeRate;
		}
		if($totalWeight){
			$this->total_weight = $totalWeight;
		}
		if($totalWeightPrice){
			$this->is_total_weight_price_fixed = 1;
			$this->total_weight_price = $totalWeightPrice;
		}
		if($servicePrice){
			$this->is_service_price_fixed = 1;
			$this->service_price = $servicePrice;
		}
		if($extraDescription){
			$this->extra_description = $extraDescription;
		}
		$result = $this->reCalculatePrice();
		if($result){
			/*$result = $this->save(true,array(
				"status", "is_paid", "shipping_home_price", "extra_price"
			));*/
		}
		if($result){
			$this->notifyChangeToUser();
		}
		return $result;
	}

	public function setPaid(){
		if($this->status!=self::STATUS_COMPLETED)
			return false;
		$this->status = self::STATUS_PAID;
		$result = $this->save(true,array(
			"status"
		));
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

	public function setExported(){
		if($this->status!=self::STATUS_PAID)
			return false;
		$this->status = self::STATUS_EXPORTED;
		$result = $this->save(true,array(
			"status"
		));
		if($result){
			$this->notifyChangeToUser();
		}
		return $result;
	}

	public function setNextStatusIfPossible(){
		$minStatus = 10000;
		foreach($this->shop_delivery_orders as $shopDeliveryOrder){
			$minStatus = min($minStatus,$shopDeliveryOrder->status);
		}
		if($minStatus==10000)
			return;
		$map = array(
			ShopDeliveryOrder::STATUS_ORDERED => self::STATUS_ORDERED,
			ShopDeliveryOrder::STATUS_SHIPPING => self::STATUS_SHIPPING,
			ShopDeliveryOrder::STATUS_STOREHOUSE_CHINA => self::STATUS_STOREHOUSE_CHINA,
			ShopDeliveryOrder::STATUS_STOREHOUSE_VIETNAM => self::STATUS_STOREHOUSE_VIETNAM
		);
		if(!isset($map[$minStatus]))
			return;
		$shouldBeStatus = $map[$minStatus];
		if($this->status>=$shouldBeStatus)
			return;
		switch($shouldBeStatus){
			case self::STATUS_ORDERED:
				$this->setStartOrdered();
				break;
			case self::STATUS_SHIPPING:
				$this->setStartShipping();
				break;
			case self::STATUS_STOREHOUSE_CHINA:
				$this->setDeliveredStorehouseChina();
				break;
			case self::STATUS_STOREHOUSE_VIETNAM:
				$this->setDeliveredStorehouseVietnam();
				break;
		}
		return;
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

	function notifyChangeToCollaborator($collaboratorType){
		$userType = Notification::USER_TYPE_COLLABORATOR;
		$type = Notification::TYPE_ORDER_STATUS_CHANGED;
		$params = array(
			"status" => $this->status,
			"order_id" => $this->id
		);
		$criteria = new CDbCriteria();
		$criteria->addCondition("collaborator_group_id IN (SELECT id FROM {{collaborator_group}} WHERE is_admin_group = 1)");
		if($collaboratorType==Collaborator::TYPE_SALE){
			$criteria->addCondition("is_manager = 1 OR (id = :collaborator_id)","OR");
			if($this->user->collaborator_id){
				$criteria->params[":collaborator_id"] = $this->user->collaborator_id;
			} else {
				$criteria->params[":collaborator_id"] = 0;
			}
		} else {
			$criteria->addCondition("collaborator_group_id = :collaborator_group_id","OR");
			$criteria->params[":collaborator_group_id"] = $this->user->collaborator_group_id;
		}
		$collaborators = Collaborator::model()->findAll($criteria);
		foreach($collaborators as $collaborator){
			Notification::push($userType,$collaborator->id,$type,$params);
		}
	}

	public function notifyChangeToUser(){
		$userType = Notification::USER_TYPE_CUSTOMER;
		$type = Notification::TYPE_ORDER_STATUS_CHANGED;
		$params = array(
			"status" => $this->status,
			"order_id" => $this->id
		);
		Notification::push($userType,$this->user_id,$type,$params);
		switch ($this->status) {
			case self::STATUS_WAIT_FOR_SUBMIT:
				break;
			case self::STATUS_WAIT_FOR_PRICE:
				$this->notifyChangeToCollaborator(Collaborator::TYPE_SALE);
				break;
			case self::STATUS_WAIT_FOR_DEPOSIT_AMOUNT:
				break;
			case self::STATUS_WAIT_FOR_DEPOSIT:
				break;
			case self::STATUS_DEPOSIT_DONE:
				$this->notifyChangeToCollaborator(Collaborator::TYPE_SALE);
				$this->notifyChangeToCollaborator(Collaborator::TYPE_SHIP);
				break;
			case self::STATUS_ORDERED:
				break;
			case self::STATUS_SHIPPING:
				break;
			case self::STATUS_STOREHOUSE_CHINA:
				$this->notifyChangeToCollaborator(Collaborator::TYPE_SALE);
				$this->notifyChangeToCollaborator(Collaborator::TYPE_STORE);
				break;
			case self::STATUS_STOREHOUSE_VIETNAM:
				$this->notifyChangeToCollaborator(Collaborator::TYPE_SALE);
				$this->notifyChangeToCollaborator(Collaborator::TYPE_ACCOUNTANT);
				break;
			case self::STATUS_COMPLETED:
				$this->notifyChangeToCollaborator(Collaborator::TYPE_SALE);
				break;
			case self::STATUS_CANCELED:
				$this->notifyChangeToCollaborator(Collaborator::TYPE_SALE);
				break;
			case self::STATUS_PAID:
				$this->notifyChangeToCollaborator(Collaborator::TYPE_SALE);
				break;
		}
	}

	public function updateSMSStatus(){
		$this->sms_status = $this->status;
		return $this->save(true,array(
			"sms_status"
		));
	}
}