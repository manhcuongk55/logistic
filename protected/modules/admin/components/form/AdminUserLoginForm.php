<?php
class AdminUserLoginForm extends SForm {
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
				$identity = new AdminUserIdentity($this->login_name,$this->password);
				$result = $identity->authenticate();
				$this->setError(!$result);
				if($result){
					$duration= 3600 * 24 * 2; // 2 days
					Yii::app()->adminUser->login($identity,$duration);
					Util::controller()->redirect("/admin");
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