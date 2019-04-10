<?php
class ContactMessageForm extends SForm {
	protected function getConfig(){
		$config = array(
			"inputs" => array(
				"__item" => array(
					"model" => "contact_message"
				),
				"title" => array(
					"type" => "text"
				),
                "content" => array(
                    "type" => "textarea"
                )
			),
			"models" => array(
				"contact_message" => "ContactMessage"
			),
			"view" => "application.components.form.views.contact_message_form",
			"method" => "post",
			"ajaxSubmit" => true,
			"multipleLanguageEnabled" => "contact_message_form",
			"actionUrl" => Util::controller()->createUrl("/user/contact_message_form")
		);
		return $config;
	}

	protected function onHandleInput(){
		if(Input::isPost())
		{
			$valid = $this->readInput() && $this->readInputToModel();
			$this->setError(!$valid);
			if($valid){
				$this->contact_message->user_id = Yii::app()->user->id;
				$result = $this->contact_message->save();
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