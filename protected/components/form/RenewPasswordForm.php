<?php
class RenewPasswordForm extends SForm {
	protected function getConfig(){
		$config = array(
			"inputs" => array(
				"__item" => array(
				),
				"token" => array(
					"type" => "hidden",
					"label" => "Token",
					"rules" => array(
						array("required")
					)
				),
				"new_password" => array(
					"type" => "password",
					"label" => "Password",
					"rules" => array(
						array("required")
					)
				),
				"retype_new_password" => array(
					"type" => "password",
					"label" => "Password",
					"rules" => array(
						array("required")
					)
				),
			),
			"view" => "application.components.form.views.renew_password_form",
			"method" => "post",
			"ajaxSubmit" => true,
			"multipleLanguageEnabled" => "renew_password_form",
			"actionUrl" => Util::controller()->createUrl("/site/renew_password_form")
		);
		return $config;
	}

	public function init(){
		$this->user = Yii::app()->user->getModel("User");
	}

	public function __construct($token=null){
		parent::__construct();
		$this->token = $token;
	}

	protected function onHandleInput(){
		if(Input::isPost())
		{
			$valid = $this->readInput();
			$this->setError(!$valid);
			if($valid){
				if($this->new_password != $this->retype_new_password){
					$this->setError(true);
					$this->addError("global",$this->l("Password does not match!"));
				} else {
					$user = User::model()->findByAttributes(array(
						"email_active_token" => $this->token,
						"active" => 1,
						"is_email_active" => 1
					));
					if(!$user){
						$this->setError(true);
						$this->addError("global",$this->l("Invalid token!"));
					} else {
						$user->password = $this->new_password;
						$user->email_active_token = null;
						$result = $user->save(true,array(
							"email_active_token", "password"
						));
						$this->setError(!$result);
						if(!$result){
							$this->addError("global",$user->getFirstError());
						}
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