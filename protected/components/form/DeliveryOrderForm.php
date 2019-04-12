<?php
class DeliveryOrderForm extends SForm {
	protected function getConfig(){
		$config = array(
			"inputs" => array(
				"__item" => array(
					"model" => "delivery_order"
				),
                "delivery_order_code" => array(
					"model" => "shop_delivery_order"
                ),
                "product_name" => array(
                    "type" => "text"
				),
                "description" => array(
                    "type" => "textarea"
				),
				"num_block" => array(
					"type" => "number",
					"model" => "shop_delivery_order"
				),
				"image" => array(
					"type" => "file_picker"
				),
				"image_url" => array(
					"type" => "text"
				),
				
			),
			"models" => array(
				"delivery_order" => "DeliveryOrder",
				"shop_delivery_order" => "ShopDeliveryOrder",
			),
			"view" => "application.components.form.views.delivery_order_form",
			"method" => "post",
			"ajaxSubmit" => true,
			"actionUrl" => Util::controller()->createUrl("/user/delivery_order_form"),
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
				$this->delivery_order->user_id = Yii::app()->user->id;
                $this->delivery_order->status = DeliveryOrder::STATUS_SHIPPING;
				$result = $this->delivery_order->save();
                $this->setError(!$result);
                if($result){
					$this->shop_delivery_order->order_id = $this->delivery_order->id;
					$this->shop_delivery_order->type = ShopDeliveryOrder::TYPE_DELIVERY_ORDER;
					$this->shop_delivery_order->image_url = $this->delivery_order->getTheImage();
					$this->shop_delivery_order->user_id = $this->delivery_order->user_id;
					$result = $this->shop_delivery_order->save();
					$this->setError(!$result);
                }
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