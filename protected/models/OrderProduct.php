<?php

Yii::import('application.models._base.BaseOrderProduct');

class OrderProduct extends BaseOrderProduct
{
	const
			WEBSITE_TYPE_OTHER = 4,
			WEBSITE_TYPE_TAOBAO = 1,
			WEBSITE_TYPE_TMALL = 2,
			WEBSITE_TYPE_1688 = 3;
	
	protected $url_backup;

	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public function relations(){
		return array(
			"order" => array(
				self::BELONGS_TO, "Order", "order_id"
			),
			"shop_delivery_order" => array(
				self::BELONGS_TO, "ShopDeliveryOrder", "shop_delivery_order_id"
			)
		);
	}
	
	public $listDropdownConfig = array(
		"website_type" => array(
			self::WEBSITE_TYPE_OTHER => "Khác",
			self::WEBSITE_TYPE_TAOBAO => "Taobao",
			self::WEBSITE_TYPE_TMALL => "TMall",
			self::WEBSITE_TYPE_1688 => "1688",
		),
	);

	protected function afterFind(){
		$this->url_backup = $this->url;
		parent::afterFind();
	}

	protected function beforeValidate(){
		if(!parent::beforeValidate())
			return false;
		if(!$this->real_price){
			$this->real_price = $this->price;
		}

		$result = true;
		if($this->scenario=="insert_from_user"){
			$result = $this->fetchUrl($this->url);
		} else if($this->scenario=="update_from_user" && $this->url_backup!=$this->url){
			$result = $this->fetchUrl($this->url);
		}
		return $result;
	}

	protected function afterSave(){
		if($this->saveHandleBlocked)
			return;
		if($this->scenario=="insert_from_collaborator"){
			if(!$this->getIsNewRecord()){
				return;
			}
			$this->active = 1;
			$this->blockSaveHandle();
			$this->setIsNewRecord(false);
			$this->save(false,array(
				"active",
			));
			$this->releaseSaveHandle();
		}

		if($this->scenario=="insert_from_user" || $this->scenario=="update_from_user" || $this->scenario=="insert_from_collaborator"){
			
		}

		parent::afterSave();
	}

	protected function fetchUrl($url){
		$item = ProductFetcher::fetch($url,array());
		if(!$item){
			$this->addError("global","Đường dẫn không hợp lệ!");
			return false;
		}
		$this->website_type = $item["type"];
		$this->image = $item["image"];
		$this->name = $item["original_name"];
		if(!$this->vietnamese_name){
			$this->vietnamese_name = $item["name"];
		}
		$this->web_price = $item["web_price"] ? $item["web_price"] : 0;
		$this->price = $this->web_price;
		$this->shop_id = $item["shop_id"];
		$this->blockSaveHandle();
		$currentScenario = $this->scenario;
		$this->scenario = "abc";
		$result = $this->save();
		$this->scenario = $currentScenario;
		$this->releaseSaveHandle();
		return $result;
	}
}