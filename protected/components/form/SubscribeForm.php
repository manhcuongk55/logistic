<?php
class SubscribeForm extends SForm {
	protected function getConfig(){
		$config = array(
			"inputs" => array(
				"__item" => array(
					"model" => "subscriber"
				),
				"email" => array(
					"type" => "email",
					"label" => "Email",
					"rules" => array(
						array("required")
					)
				),
			),
			"models" => array(
				"subscriber" => "Subscriber"
			),
			"view" => "application.components.form.views.subscribe_form",
			"method" => "post",
			"ajaxSubmit" => true,
			"actionUrl" => Util::controller()->createUrl("/home/subscribe_form"),
			"multipleLanguageEnabled" => "subscribe_form",
			"csrfProtect" => false
		);
		return $config;
	}

	protected function onHandleInput(){
		if(Input::isPost())
		{
			$valid = $this->readInputToModel();
			$this->setError(!$valid);
			if($valid){
				$result = $this->subscriber->save(true,array(
					"email", "user_type"
				));
				Util::sendMail(Util::param2("setting","admin_email_subscribe"),"Email","subscribe",array(
					"subscriber" => $this->subscriber
				));
				$this->setError(!$result);
			}
			if($this->isError()){
				Output::returnJsonError($this->getFirstError());
			} else {
				Output::returnJsonSuccess();
			}
			return true;
		}
		else 
		{
			return false;
		}
	}
}