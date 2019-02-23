<?php

Yii::import('application.models._base.BaseBanner');

class Banner extends BaseBanner
{
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	protected $fileUploadEnabled = true;
	protected function getFileConfig(){
		return array(
			"image" => array(
				"folder" => function($model){
					return "upload/banner/" . $model->id;
				},
				"fileName" => function($model){
					return "image_" . time();
				},
				"type" => "_image_",
				"extension" => array("png","jpg","jpeg"),
				"size" => array(-1,10),
				"targetExtension" => "png",
				"image" => array(
					//"minSize" => array(150,170),
					//"resize" => array(200,200),
				),
				"updateFileNameAfterSave" => true,
				//"defaultUrl" => "/img/default/company/image.png"
			),
                        
		);
	}
	protected function fileGetFolderToBeDeleted(){
		return  "upload/banner/" . $this->id;
	}
}