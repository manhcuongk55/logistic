<?php

Yii::import('application.models._base.BaseUser');

class User extends BaseUser
{
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	protected $passwordField = "password";
	protected $preventDeleteAttr = "active";
	protected $deleteTogetherRelations = array();
	public function relations(){
		return array(
			"collaborator_group" => array(
				self::BELONGS_TO, "CollaboratorGroup", "collaborator_group_id"
			),
			"collaborator" => array(
				self::BELONGS_TO, "Collaborator", "collaborator_id"
			),
			"unread_notification_count" => array(
				self::STAT, "Notification", "user_id",
				"condition" => "user_type= " . Notification::USER_TYPE_CUSTOMER . " AND is_read = 0"
			),
			"customer_type" => array(
				self::BELONGS_TO, "CustomerType", "customer_type_id"
			),
		);
	}

	public $listDropdownConfig = array(
		"collaborator_group_id" => array(
			"model" => "CollaboratorGroup",
			"valueAttr" => "id",
			"displayAttr" => "name",
			"criteria" => array(
				"order" => "name ASC",
			)
		),
		"collaborator_id" => array(
			"model" => "Collaborator",
			"valueAttr" => "id",
			"displayAttr" => "name",
			"criteria" => array(
				"order" => "name ASC",
			)
		),
		"customer_type_id" => array(
			"model" => "CustomerType",
			"valueAttr" => "id",
			"displayAttr" => "name",
			"criteria" => array(
				"order" => "name ASC",
			)
		)
	);

	protected $fileUploadEnabled = true;
	protected function getFileConfig(){
		return array(
			"image" => array(
				"folder" => function($model){
					return "upload/user/" . $model->id;
				},
				"fileName" => function($model){
					return "image_" . time();
				},
				"type" => "_image_",
				"extension" => array("png","jpg","jpeg"),
				"size" => array(-1,2),
				"targetExtension" => "png",
				"image" => array(
					//"minSize" => array(200,200),
					//"resize" => array(200,200),
				),
				"thumbnails" => array(
					"image_thumbnail"
				),
				"updateFileNameAfterSave" => true,
				"defaultUrl" => "/img/default/user/image.jpg"
			),
			"image_thumbnail" => array(
				"folder" => function($model){
					return "upload/user/" . $model->id;
				},
				"fileName" => function($model){
					return "image_thumbnail" . "_" . time();
				},
				"targetExtension" => "png",
				"image" => array(
					"resize" => array(75,75),
				),
				"updateFileNameAfterSave" => true,
				"defaultUrl" => "/img/default/user/image_thumbnail.jpg"
			)
		);
	}
	protected function fileGetFolderToBeDeleted(){
		return "upload/user/" . $this->id;
	}

	protected function getExtendedRules(){
		return array(
			array("email", "unique"),
		);
	}

	protected function beforeSave(){
		if(!parent::beforeSave()){
			return false;
		}
		if(!$this->collaborator_group_id){
			$this->collaborator_group_id = Util::param2("common","defaultCollaboratorGroup");
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
			$collaborator = Util::controller()->getUser();
			$this->collaborator_id = $collaborator->id;
			$this->collaborator_group_id = $collaborator->collaborator_group_id;
			$this->active = 1;
			$this->is_email_active = 1;
			$this->blockSaveHandle();
			$this->setIsNewRecord(false);
			$this->save(false,array(
				"collaborator_id", "collaborator_group_id", "active", "is_email_active"
			));
			$this->releaseSaveHandle();
		}
		parent::afterSave();
	}

	public function rules(){
		return self::newRules();
	}

	public function sendConfirmMail($template="confirm_email"){
		Util::sendMail($this->email,"Confirm email",$template,array(
			"user" => $this
		));
	}

	public function generateEmailActiveToken($sendMail="confirm_email",$save=false){
		$this->email_active_token = md5(Util::generateUUID() . Util::generateRandomString() . $this->id);
		if($sendMail){
			$this->sendConfirmMail($sendMail);
		}
		if($save){
			$this->save(true,array(
				"email_active_token"
			));
		}
	}

	public function updateInfoFromGoogle($userInfo,$accessTokenString){
		$this->name = $userInfo["name"];
		$this->email = $userInfo["email"];
		$this->google_id = $userInfo["id"];
		$this->google_token = $accessTokenString;
		$this->active = 1;
		$this->is_email_active = 1;
		/*if(isset($userInfo["gender"])){
			//var_dump($userInfo); die();
			if($userInfo["gender"]=="male")
				$this->sex = self::SEX_MALE;
			elseif($userInfo["gender"]=="female")
				$this->sex = self::SEX_FEMALE;
		}*/
		$this->updateImageFromUrl($userInfo["picture"],false);
		$result = $this->save();
		if($result){
		}
		return $result;
	}

	public function notifyOrderStatuses($status,$orders){
		if(!$this->phone){
			return false;
		}
		$messageKey = "status_$status";
		// $orderIDs = array();
		// foreach($orders as $order){
		// 	$orderIDs[] = "#$order->id";
		// }
		// $orderIDsStr = implode($orderIDs,",");
		// $message = l("home/sms_messages",$messageKey,array(
		// 	"user_name" => $this->name,
		// 	"order_ids" => $orderIDsStr,
		// ));
		foreach($orders as $order){
			$message = Util::controller()->renderPartial("application.views.messages.$messageKey",array(
				"user" => $this,
				"order" => $order,
			),true);
			Yii::import("ext.sms.SMSSender");
			$smsSender = new SMSSender();
			$result = $smsSender->sendSMS(array($this->phone),$message);
		}
		return true;
	}
}