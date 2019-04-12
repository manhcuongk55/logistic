<?php
// defined list of warehouses
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
				//add birthday property to signup form
				//XuanCuong 12/3
				"birthday" => array(
					"type" => "text",
					"label" => l("forms/signup_form","Ngày sinh"),
					"rules" => array(
						array("required")
					)
				)
				,
				//add selected warehouse
				//xuancuong 20/3
				"warehouse"=>array(
					"type" => "text",
					"label" => l("forms/signup_form","Nhà kho"),
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
				//check password match with the retype
				if($this->password != $this->retype_password){
					$this->setError(true);
					$this->addError("password",$this->l("Mật khẩu không trùng!"));
				 } else 
				//check phone number is a correct vietnamese phone number
				//XuanCuong 12/3 
				if(!preg_match("/(09|03)+([0-9]{8})\b/",$this->phone)){
					$this->setError(true);
					$this->addError("phone",$this->l("Số điện thoại không hợp lệ"));
				} 
				else{
					$user = new User();
					$user->name = $this->name;
					$user->password = $this->password;
					$user->email = $this->email;
					$user->phone = $this->phone;
					$user->address = $this->address;
					//add birthday property to user object
					//xuancuong 16/3
					$user->birthday = $this->birthday;
					//add selected warehouse
					//xuancuong 20/3
					$user->warehouse = $this->warehouse;
					$result = $user->save();
					$this->setError(!$result);
					if(!$result){
						$this->addError("global",$user->getFirstError());
					} else {
						$user->generateEmailActiveToken("confirm_email",true);
						//comment to prevent auto login without check email confirmation
						// $identity = new UserIdentity($this->email,$this->password);
						// $identity->setUser($user);
						// Yii::app()->user->login($identity);
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