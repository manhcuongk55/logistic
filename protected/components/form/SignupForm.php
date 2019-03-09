<?php
class SignupForm extends SForm {
	protected function getConfig(){
		$config = array(
			"inputs" => array(
				"__item" => array(
				),
				"name" => array(
					"type" => "text",
					"label" => l("forms/signup_form","Họ tên"),
					"rules" => array(
						array("required")
					)
				),
				"phone" => array(
					"type" => "text",
					"label" => l("forms/signup_form","Số điện thoại"),
					"rules" => array(
						array("required")
					)
				),
				"address" => array(
					"type" => "text",
					"label" => l("forms/signup_form","Địa chỉ"),
					"rules" => array(
						array("required")
					)
				),
				"email" => array(
					"type" => "text",
					"label" => l("forms/signup_form","Email"),
					"rules" => array(
						array("required"),
						array("email", "allowEmpty" => false),
						array("unique", "allowEmpty" => false, "className" => "User", "attributeName" => "email",
							"criteria" => array(
								"condition" => "is_email_active = 1"
							)
						)
					)
				),
				"password" => array(
					"type" => "password",
					"label" => l("forms/signup_form","Mật khẩu"),
					"rules" => array(
						array("required")
					)
				),
				"retype_password" => array(
					"type" => "password",
					"label" => l("forms/signup_form","Mật khẩu"),
					"rules" => array(
						array("required")
					)
				)
			),
			"method" => "post",
			"ajaxSubmit" => true,
			"view" => "application.components.form.views.signup_form",
			"multipleLanguageEnabled" => "signup_form",
			"actionUrl" => Util::controller()->createUrl("/site/signup")
		);

		return $config;
	}

	protected function onHandleInput(){
		if(Input::isPost())
		{
			$valid = $this->readInput();
			$this->setError(!$valid);
			if($valid){
				if($this->password != $this->retype_password){
					$this->setError(true);
					$this->addError("password",$this->l("Mật khẩu không trùng!"));
				} else {
					$user = new User();
					$user->name = $this->name;
					$user->password = $this->password;
					$user->email = $this->email;
					$user->phone = $this->phone;
					$user->address = $this->address;
					$result = $user->save();
					$this->setError(!$result);
					if(!$result){
						$this->addError("global",$user->getFirstError());
					} else {
						$user->generateEmailActiveToken("confirm_email",true);
						$identity = new UserIdentity($this->email,$this->password);
						$identity->setUser($user);
						Yii::app()->user->login($identity);
					}
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