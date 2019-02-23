<?php
class FrontPageBaseController extends Controller {
	public function init(){
		parent::init();
		Yii::app()->theme = "giaodichtrungquoc";
		$this->pageTitle = "Order Hip";

		$user = $this->getUser();
		if($user){
			Son::load("SAsset")->arrayToJs(array(
				"userID" => "user-$user->id",
				"userInfo" => array(
					"name" => $user->name,
				)
			),"chatUserInfo");
		}
	}
}