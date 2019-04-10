<?php
class CollaboratorUserLoginForm extends SForm {
	var $config = array(
		"title" => "Đăng nhập",
		"inputs" => array(
			"__item" => array(
			),
			"login_name" => array(
				"type" => "email",
				"label" => "Email",
				"rules" => array(
					array("required")
				)
			),
			"password" => array(
				"type" => "password",
				"label" => "Mật khẩu",
				"rules" => array(
					array("required")
				)
			)
		),
		"view" => "webroot.themes.giaodichtrungquoc.views.components.form.form_login",
		"method" => "post"
	);

	protected function onHandleInput(){
		if(Input::isPost())
		{
			$valid = $this->readInput();
			$this->setError(!$valid);
			if($valid){
				$identity = new CollaboratorUserIdentity($this->login_name,$this->password);
				$result = $identity->authenticate();
				$this->setError(!$result);
				if($result){
					$duration= 3600 * 24 * 2; // 2 days
					Yii::app()->collaboratorUser->login($identity,$duration);
					$key = CacheHelper::getKeyForUser(CacheHelper::HTTP_CACHE_KEY,Yii::app()->collaboratorUser->id);
					CacheHelper::setLastUpdatedTimeOfDependencyKey($key,time());
					Util::controller()->redirect("/collaborator");
				} else {
					$this->addError("global","Tên đăng nhập hoặc mật khẩu không đúng!");
				}
			}
			return true;
		}
		else 
		{
			return false;
		}
	}
}