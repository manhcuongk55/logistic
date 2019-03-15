<?php
class LoginForm extends SForm {
	protected function getConfig(){
		$config = array(
			"inputs" => array(
				"__item" => array(
				),
				"email" => array(
					"type" => "text",
					"label" => l("forms/login_form","Email"),
					"rules" => array(
						array("required")
					)
				),
				"password" => array(
					"type" => "password",
					"label" => l("forms/login_form","Mật khẩu"),
					"rules" => array(
						array("required")
					)
				),
				"remember" => array(
					"type" => "checkbox"
				)
			),
			"method" => "post",
			"ajaxSubmit" => true,
			"view" => "application.components.form.views.login_form",
			"multipleLanguageEnabled" => "login_form",
			"actionUrl" => Util::controller()->createUrl("/site/login")
		);

		return $config;
	}

	protected function onHandleInput(){
		if(Input::isPost())
		{
			$valid = $this->readInput();
			$this->setError(!$valid);
			if($valid){
				$identity = new UserIdentity($this->email,$this->password);
				$result = $identity->authenticate();
				if($result===false){
					$this->addError("global",$this->l("Tên đăng nhập hoặc mật khẩu không đúng!"));
					$this->setError(true);
				} else if($result==="has_not_active_email"){
					$this->addError("global",$this->l("Tài khoản của bạn chưa được kích hoạt email. Vui lòng kiểm tra lại email!"));
					$this->setError(true);
				} else {
					$this->setError(false);
					$this->remember = 1;
					if($this->remember){
						$duration= 3600 * 24 * 30; // 30 days
					}
					Yii::app()->user->login($identity,$duration);
				}
			}
			$this->returnJson();

			return true;
		}
		else 
		{
			return false;
		}
	}
}