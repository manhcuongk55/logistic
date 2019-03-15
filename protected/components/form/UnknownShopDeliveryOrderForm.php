<?php
class UnknownShopDeliveryOrderForm extends SForm {
	protected function getConfig(){
		$config = array(
			"inputs" => array(
				"__item" => array(
					"model" => "unknown_shop_delivery_order"
				),
                "delivery_order_code" => array(
                ),
                "total_weight" => array(
                    "type" => "number"
				),
                "total_volume" => array(
                    "type" => "number"
				),
                "description" => array(
                    "type" => "textarea"
				),
				"image" => array(
					"type" => "file_picker"
				),
				
			),
			"models" => array(
				"unknown_shop_delivery_order" => "UnknownShopDeliveryOrder",
			),
			"view" => "application.components.form.views.unknown_shop_delivery_order_form",
			"method" => "post",
			"ajaxSubmit" => true,
			"actionUrl" => Util::controller()->createUrl("/collaborator/unknown_shop_delivery_order_form"),
			"csrfProtect" => false,
			"uploadEnabled" => true,
			"uploadCheck" => true,
		);
		return $config;
	}

	protected function onHandleInput(){
		if(Input::isPost())
		{
			$valid = $this->readInputToModel();
			$this->setError(!$valid);
			if($valid){
				$result = $this->unknown_shop_delivery_order->save();
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