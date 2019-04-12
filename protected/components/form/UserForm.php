<?php
class UserForm extends SForm {
	protected function getConfig(){
		$config = array(
			"inputs" => array(
				"__item" => array(
					"model" => "user"
				),
				"name" => array(
					"type" => "text",
					"rules" => array(
						array("required")
					)
				),
				"phone" => array(
					"type" => "phone",
				),
				"image" => array(
					"type" => "file",
				),
				"facebook_id" => array(
					"type" => "text"
				),
				"skype" => array(
					"type" => "text"
				)
			),
			"models" => array(
				"user" => "User"
			),
			"method" => "post",
			"ajaxSubmit" => true,
			"uploadEnabled" => true,
			"view" => "application.components.form.views.user_form",
            "actionUrl" => Util::controller()->createUrl("/user/user_form")
		);
		return $config;
	}

	public function init(){
		$this->user = Yii::app()->user->getModel("User");
	}

	protected function onHandleInput(){
		if(Input::isPost() && isset($_POST["user"]))
		{
			$valid = $this->readInputToModel();
			$this->setError(!$valid);
			if($valid){
				$result = $this->user->save(true,array(
					"name", "phone", "image", "image_thumbnail", "facebook_id", "skype"
				));
				$this->setError(!$result);
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