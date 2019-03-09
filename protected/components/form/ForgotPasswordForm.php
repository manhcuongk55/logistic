<?php
class ForgotPasswordForm extends SForm {
	protected function getConfig(){
		$config = array(
			"inputs" => array(
				"__item" => array(
				),
				"email" => array(
					"type" => "email",
					"label" => "Email",
					"rules" => array(
						array("required"),
						array("email")
					)
				),
			),
			"view" => "application.components.form.views.forgot_password_form",
			"method" => "post",
			"ajaxSubmit" => true,
			"multipleLanguageEnabled" => "forgot_password_form",
			"actionUrl" => Util::controller()->createUrl("/site/forgot_password"),
			"csrfProtect" => false
		);
		return $config;
	}

	public function init(){
		$this->user = Yii::app()->user->getModel("User");
	}

	protected function onHandleInput(){
		if(Input::isPost())
		{
			$valid = $this->readInput();
			$this->setError(!$valid);
			if($valid){
				$user  = User::model()->findByAttributes(array(
					"email" => $this->email,
					"active" => 1,
					"is_email_active" => 1
				));
				if(!$user){
					$this->setError(true);
					$this->addError("global",$this->l("This email doesn't exist or activated"));
				} else {
					$user->generateEmailActiveToken("forgot_password",true);
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