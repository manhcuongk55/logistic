<?php
class LoginHelper {
	public static function requireLogin($url=null){
		$loggedIn = !Yii::app()->user->isGuest;
		if($loggedIn)
			return true;
		if($url=="prev"){
			$url = ArrayHelper::get($_SERVER,"HTTP_REFERER");
		}else if(!$url){
			$url = Yii::app()->request->requestUri;
		}
		$newUrl = Yii::app()->controller->createUrl("/site/login?callback_url=" . $url);
		Yii::app()->controller->redirect($newUrl);
		return false;
	}

	public static function setLoginCallbackUrlIfExist(){
		if($callbackUrl = Input::get("callback_url")){
			if($callbackUrl=="prev" && isset($_SERVER['HTTP_REFERER'])){
				$callbackUrl = $_SERVER['HTTP_REFERER'];
			}
			Yii::app()->session["login_callback_url"] = $callbackUrl;
		}
		if($collaboratorGroupId = Input::get("collaborator_group_id")){
			Yii::app()->session["login_collaborator_group_id"] = $collaboratorGroupId;
		}
	}

	public static function getLoginCallbackUrl(){
		return isset(Yii::app()->session["login_callback_url"]) ? Yii::app()->session["login_callback_url"] : "/";
	}

	public static function getLoginCollaboratorGroupId(){
		return Yii::app()->session["login_collaborator_group_id"] ? Yii::app()->session["login_collaborator_group_id"] : null;
	}
}