<?php

Yii::import('application.models._base.BaseDeliveryOrder');

class DeliveryOrder extends BaseDeliveryOrder
{
	const
			STATUS_SHIPPING = 5,
			STATUS_STOREHOUSE_CHINA = 6,
			STATUS_STOREHOUSE_VIETNAM = 7,
			STATUS_COMPLETED = 8,
			STATUS_CANCELED = 9,
			STATUS_EXPORTED = 10;

	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public function relations(){
		return array(
			"user" => array(
				self::BELONGS_TO, "User", "user_id"
			),
			"shop_delivery_order" => array(
				self::HAS_ONE, "ShopDeliveryOrder", "order_id",
				"condition" => "shop_delivery_order.active = 1 AND shop_delivery_order.type = " . ShopDeliveryOrder::TYPE_DELIVERY_ORDER
			)
		);
	}

	public $listDropdownConfig = array(
		"status" => array(
			self::STATUS_SHIPPING => "Đang về kho Trung Quốc",
			self::STATUS_STOREHOUSE_CHINA => "Về kho Trung Quốc",
			self::STATUS_STOREHOUSE_VIETNAM => "Về kho Việt Nam",
			self::STATUS_COMPLETED => "Hoàn thành",
			self::STATUS_CANCELED => "Đã hủy",
			self::STATUS_EXPORTED => "Đã xuất kho"
		)
	);

	protected $fileUploadEnabled = true;
	protected function getFileConfig(){
		return array(
			"image" => array(
				"folder" => function($model){
					return "upload/delivery_order/" . $model->id;
				},
				"fileName" => function($model){
					return "image_" . time();
				},
				"type" => "_image_",
				"extension" => array("png","jpg","jpeg"),
				"size" => array(-1,10),
				"targetExtension" => "png",
				"image" => array(
					// "minSize" => array(200,200),
					//"resize" => array(200,200),
				),
				"updateFileNameAfterSave" => true,
				//"defaultUrl" => "/img/default/page/image.png"
			),
		);
	}
	protected function fileGetFolderToBeDeleted(){
		return "upload/delivery_order/" . $this->id;
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
		$this->status = self::STATUS_COMPLETED;
		$result = $this->save(true,array(
			"status",
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
		$this->status = self::STATUS_EXPORTED;
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

	public function getTheImage(){
		if($this->image){
			return $this->image;
		}
		return $this->image_url;
	}

	function notifyChangeToUser(){
		Notification::push($this->user_id,Notification::TYPE_DELIVERY_ORDER_STATUS_CHANGED,array(
			"status" => $this->status,
			"delivery_order_id" => $this->id
		));
	}
}