<?php

Yii::import('application.models._base.BasePage');

class Page extends BasePage
{			
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	protected $fileUploadEnabled = true;
	protected function getFileConfig(){
		return array(
			"image" => array(
				"folder" => function($model){
					return "upload/page/" . $model->id;
				},
				"fileName" => function($model){
					return "image_" . time();
				},
				"type" => "_image_",
				"extension" => array("png","jpg","jpeg"),
				"size" => array(-1,10),
				"targetExtension" => "png",
				"image" => array(
					"minSize" => array(200,200),
					//"resize" => array(200,200),
				),
				"thumbnails" => array(
					"image_thumbnail"
				),
				"updateFileNameAfterSave" => true,
				//"defaultUrl" => "/img/default/page/image.png"
			),
			"image_thumbnail" => array(
				"folder" => function($model){
					return "upload/page/" . $model->id;
				},
				"fileName" => function($model){
					return "image_thumbnail" . "_" . time();
				},
				"targetExtension" => "png",
				"image" => array(
					"resize" => array(75,75),
				),
				"updateFileNameAfterSave" => true,
				//"defaultUrl" => "/img/default/page/image_thumbnail.png"
			)
		);
	}
	protected function fileGetFolderToBeDeleted(){
		return "upload/page/" . $this->id;
	}
}