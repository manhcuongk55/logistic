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
			STATUS_CANCELED = 6,
			STATUS_EXPORTED = 7;

	const
			TYPE_ORDER = 1,
			TYPE_DELIVERY_ORDER = 2;

	public $shop_total_price_ndt = 0;
	public $user_name;

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
			"delivery_order" => array(
				self::BELONGS_TO, "DeliveryOrder", "order_id"
			),
		);
	}

	public function getListDropdownConfigBase(){
		$arr = array();
		$arr["status"] = array(
			self::STATUS_INIT => "Đang chuyển hàng",
			self::STATUS_ORDERED => "Đang chuyển hàng",
			self::STATUS_SHIPPING => "Đang chuyển hàng",
			self::STATUS_STOREHOUSE_CHINA => "Về kho Trung Quốc",
			self::STATUS_STOREHOUSE_VIETNAM => "Về kho Việt Nam",
			self::STATUS_CANCELED => "Đã hủy",
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
						);
						break;
					case Collaborator::TYPE_SHIP:
						$arr["status_by_collaborator"] = array(
							self::STATUS_ORDERED => "Đang chuyển hàng",
							self::STATUS_SHIPPING => "Đang chuyển hàng",
							self::STATUS_INIT => "Đang chuyển hàng",
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
						);
						break;
				}
			}
		}
		$arr["type"] = array(
			self::TYPE_ORDER => "Order",
			self::TYPE_DELIVERY_ORDER => "Ký gửi",
		);
		return $arr;
	}

	private $status_backup = null;
	private $total_weight_backup = null;
	private $total_volume_backup = null;
	protected function afterFind(){
		parent::afterFind();
		$this->status_backup = $this->status;
		$this->total_weight_backup = $this->total_weight;
		$this->total_volume_backup = $this->total_volume;
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
			// if($this->status > $this->status_backup || $this->order->status >= Order::STATUS_DEPOSIT_DONE){
			// 	if(Util::controller()->getUser()->type==Collaborator::TYPE_SALE){
			// 		$this->addError("status","Bạn không có quyền thay đổi trạng thái đơn hàng!");
			// 		return false;
			// 	}
			// }
			if(!$this->user_id){
				if($order = $this->getOrder()){
					$this->user_id = $order->user_id;
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
			if($this->order->status >= Order::STATUS_DEPOSIT_DONE){
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
			if(($this->total_weight_backup != $this->total_weight || $this->total_volume_backup != $this->total_volume)){
				if($this->type==self::TYPE_ORDER && $this->order){
					$this->order->reCalculatePrice();
				}
			}
			return;
		}
		if($this->scenario=="insert_from_collaborator"){
			$this->active = 1;

			$this->blockSaveHandle();
			$this->setIsNewRecord(false);
			$this->save(false,array(
				"active",
			));
			$this->releaseSaveHandle();

			if($this->type==self::TYPE_ORDER && $this->order){
				$this->order->setStartShipping();
			}
			
		}
		parent::afterSave();
	}

	public function setDeliveredStorehouseChina($totalWeight){
		if($this->status>=self::STATUS_STOREHOUSE_CHINA)
			return false;
		$this->status = self::STATUS_STOREHOUSE_CHINA;
		$this->china_delivery_time = time();
		if($totalWeight){
			$this->total_weight = $totalWeight;
		}
		$result = $this->save(true,array(
			"status", "china_delivery_time", "total_weight"
		));
		if($result){
			if($this->checkOrderStatusUpdatable()){
				$this->getOrder()->setDeliveredStorehouseChina(true);
			}
			if($this->type==self::TYPE_ORDER){
				$this->order->reCalculateWeightPrice();
			}
		}
		return $result;
	}

	public function setDeliveredStorehouseVietnam(){
		//if($this->status!=self::STATUS_STOREHOUSE_CHINA)
			//return false;
		if(!in_array($this->status,array(self::STATUS_INIT,self::STATUS_STOREHOUSE_CHINA))){
			return false;
		}
		$this->status = self::STATUS_STOREHOUSE_VIETNAM;
		$this->vietnam_delivery_time = time();
		$result = $this->save(true,array(
			"status", "vietnam_delivery_time"
		));
		if($result){
			if($this->checkOrderStatusUpdatable()){
				$this->getOrder()->setDeliveredStorehouseVietnam(true);
			}
			if($this->type==self::TYPE_ORDER){
				$this->order->reCalculateWeightPrice();
			}
		}
		return $result;
	}

	public function setCanceled(){
		$this->status = self::STATUS_CANCELED;
		$result = $this->save(true,array(
			"status"
		));
		$this->getOrder()->setCanceled();
		//$this->order->setNextStatusIfPossible();
		return $result;
	}

	public function setExported(){
		if ($this->status != self::STATUS_STOREHOUSE_VIETNAM){
			return false;
		}
		if($this->type==self::TYPE_ORDER){
			if(!$this->order){
				$this->addError("global","Order not found");
				return false;
			}
			if($this->order->status!=Order::STATUS_PAID){
				$this->addError("global","Order status is invalid");
				return false;
			}
			$this->status = self::STATUS_EXPORTED;
			$result = $this->save(true,array(
				"status",
			));
			if($result){
				if($this->checkOrderStatusUpdatable()){
					$this->order->setExported();
				}
			}
			return $result;
		} else {
			$this->status = self::STATUS_EXPORTED;
			$result = $this->save(true,array(
				"status",
			));
			$result = $this->delivery_order->setExported();
			return $result;
		}
		return false;
	}

	private function getOrder(){
		if($this->type==self::TYPE_ORDER){
			return $this->order;
		}
		return $this->delivery_order;
	}

	private function checkOrderStatusUpdatable(){
		if($this->type==self::TYPE_DELIVERY_ORDER){
			return true;
		}

		foreach($this->getOrder()->shop_delivery_orders as $shopDeliveryOrder){
			if($shopDeliveryOrder->status < $this->status)
				return false;
		}
		return true;
	}
}