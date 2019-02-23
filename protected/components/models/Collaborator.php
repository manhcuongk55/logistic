<?php

Yii::import('application.models._base.BaseCollaborator');

class Collaborator extends BaseCollaborator
{
	const
			TYPE_SALE = 1,
			TYPE_SHIP = 2,
			TYPE_STORE = 3,
			TYPE_ACCOUNTANT = 4;

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
			)
		);
	}

	public $listDropdownConfig = array(
		"type" => array(
			self::TYPE_SALE => "Kinh doanh",
			self::TYPE_SHIP => "Vận chuyển",
			self::TYPE_STORE => "Nhà kho",
			self::TYPE_ACCOUNTANT => "Kế toán",
		),
		"collaborator_group_id" => array(
			"model" => "CollaboratorGroup",
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
					return "upload/collaborator/" . $model->id;
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
				"defaultUrl" => "/img/default/collaborator/image.png"
			),
			"image_thumbnail" => array(
				"folder" => function($model){
					return "upload/collaborator/" . $model->id;
				},
				"fileName" => function($model){
					return "image_thumbnail" . "_" . time();
				},
				"targetExtension" => "png",
				"image" => array(
					"resize" => array(75,75),
				),
				"updateFileNameAfterSave" => true,
				"defaultUrl" => "/img/default/collaborator/image_thumbnail.png"
			)
		);
	}
	protected function fileGetFolderToBeDeleted(){
		return "upload/collaborator/" . $this->id;
	}

	protected function getExtendedRules(){
		return array(
			array("email", "unique"),
		);
	}

	public function rules(){
		return self::newRules();
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
}