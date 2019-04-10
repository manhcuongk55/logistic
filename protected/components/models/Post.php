<?php

Yii::import('application.models._base.BasePost');

class Post extends BasePost
{
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	public function relations(){
		return array(
			"admin" => array(
				self::BELONGS_TO, "Admin", "admin_id"
			),
		);
	}

	protected $fileUploadEnabled = true;
	protected function getFileConfig(){
		return array(
			"image" => array(
				"folder" => function($model){
					return "upload/post/" . $model->id;
				},
				"fileName" => function($model){
					return "image_" . time();
				},
				"type" => "_image_",
				"extension" => array("png","jpg","jpeg"),
				"size" => array(-1,10),
				"targetExtension" => "png",
				"image" => array(
					//"minSize" => array(200,200),
					//"resize" => array(200,200),
				),
				"updateFileNameAfterSave" => true,
			),
		);
	}
	protected function fileGetFolderToBeDeleted(){
		return "upload/post/" . $this->id;
	}
}