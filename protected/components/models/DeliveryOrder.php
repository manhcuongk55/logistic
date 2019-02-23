<?php

Yii::import('application.models._base.BaseDeliveryOrder');

class DeliveryOrder extends BaseDeliveryOrder
{
	const
			STATUS_WAIT_FOR_SUBMIT = 1,
			STATUS_WAIT_FOR_PRICE = 2,
			STATUS_WAIT_FOR_DEPOSIT = 3,
			STATUS_DEPOSIT_DONE = 4,
			STATUS_SHIPPING = 5,
			STATUS_STOREHOUSE_CHINA = 6,
			STATUS_STOREHOUSE_VIETNAM = 7,
			STATUS_COMPLETED = 8,
			STATUS_CANCELED = 9;

	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public function relations(){
		return array(
			"user" => array(
				self::BELONGS_TO, "User", "user_id"
			),
		);
	}

	public $listDropdownConfig = array(
		"status" => array(
			self::STATUS_WAIT_FOR_SUBMIT => "Khởi tạo",
			self::STATUS_WAIT_FOR_PRICE => "Chờ báo giá",
			self::STATUS_WAIT_FOR_DEPOSIT => "Chờ đặt cọc",
			self::STATUS_DEPOSIT_DONE => "Đã đặt cọc",
			self::STATUS_SHIPPING => "Đang chuyển hàng",
			self::STATUS_STOREHOUSE_CHINA => "Về kho Trung Quốc",
			self::STATUS_STOREHOUSE_VIETNAM => "Về kho Việt Nam",
			self::STATUS_COMPLETED => "Hoàn thành",
			self::STATUS_CANCELED => "Đã hủy",
		)
	);
	
	public function setDepositePrice($depositPrice,$deliveryPrice,$totalPrice,$totalRealPrice,$totalWeight){
		if($this->status!=self::STATUS_WAIT_FOR_PRICE)
			return false;
		$this->status = self::STATUS_WAIT_FOR_DEPOSIT;
		$this->deposit_amount = $depositPrice;
		$this->delivery_price = $deliveryPrice;
		$this->total_price = $totalPrice;
		$this->total_real_price = $totalRealPrice;
		$this->total_weight = $totalWeight;
		$result = $this->save(true,array(
			"status", "deposit_amount", "total_price", "total_weight", "total_real_price", "delivery_price"
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

	public function setStartShipping(){
		if($this->status!=self::STATUS_DEPOSIT_DONE)
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

	public function setCompleted(){
		if($this->status!=self::STATUS_STOREHOUSE_VIETNAM)
			return false;
		$this->is_paid = 1;
		$this->status = self::STATUS_COMPLETED;
		$result = $this->save(true,array(
			"status", "is_paid"
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

	public static function getDeliveryOrderOfCollaboratorGroup($id,$collaboratorGroupId){
		return DeliveryOrder::model()->findByAttributes(array(
			"id" => $id,
		),array(
			"condition" => "t.user_id IN (SELECT id FROM {{user}} WHERE collaborator_group_id = $collaboratorGroupId)"
		));
	}

	function notifyChangeToUser(){
		Notification::push($this->user_id,Notification::TYPE_DELIVERY_ORDER_STATUS_CHANGED,array(
			"status" => $this->status,
			"delivery_order_id" => $this->id
		));
	}
}