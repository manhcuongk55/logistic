<?php
class ChangePasswordForm extends SForm {
	var $user;

	protected function getConfig(){
		$config = array(
			"inputs" => array(
				"__item" => array(
				),
				"current_password" => array(
					"type" => "password",
					"label" => l("forms/change_password_form","Mật khẩu hiện tại"),
					"rules" => array(
					)
				),
				"new_password" => array(
					"type" => "password",
					"label" => l("forms/change_password_form","Mật khẩu mới"),
					"rules" => array(
						array("required")
					)
				),
				"retype_new_password" => array(
					"type" => "password",
					"label" => l("forms/change_password_form","Nhập lại mật khẩu mới"),
					"rules" => array(
						array("required")
					)
				),
			),
			"view" => "application.components.form.views.change_password_form",
			"method" => "post",
			"ajaxSubmit" => true,
			"multipleLanguageEnabled" => "change_password_form",
			"actionUrl" => Util::controller()->createUrl("/user/change_password_form")
		);
		return $config;
	}

	public function init(){
		$this->user = Yii::app()->controller->getUser();
	}

	protected function onHandleInput(){
		if(Input::isPost())
		{
			$valid = $this->readInput();
			$this->setError(!$valid);
			if($valid){
				if($this->new_password != $this->retype_new_password){
					$this->setError(true);
					$this->addError("global",$this->l("Mật khẩu không trùng!"));
				} else {
					$valid = true;
					$noNeedCurrentPassword = !$this->user->passwordBackup && $this->user->facebook_id;
					if(!$noNeedCurrentPassword){
						if(!$this->user->verifyPassword($this->current_password)){
							$valid = false;
							$this->setError(true);
							$this->addError("global",$this->l("Mật khẩu cũ không đúng"));
						}
					}
					if($valid){
						$this->user->password = $this->new_password;
						$result = $this->user->save(true,array(
							"password"
						));
						$this->setError(!$result);
						if(!$result){
							$this->addError("global",$this->user->getFirstError());
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