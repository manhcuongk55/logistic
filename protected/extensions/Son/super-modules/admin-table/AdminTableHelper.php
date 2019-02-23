<?php
class AdminTableHelper {
	var $registeredActionButtons = array();

	public $defaultButtonClass = "btn btn-sm btn-default";

	public function registerActionButton($name,$type,$do,$defaultConfig=array(),$defaultHtmlAttributes=array()){
		$this->registeredActionButtons[$name] = array($type,$do,$defaultConfig,$defaultHtmlAttributes);
	}

	public function renderActionButton($item,$name,$config=array(),$htmlAttributes=array()){
		//var_dump($this->registeredActionButtons);die();
		list($type,$do,$defaultConfig,$defaultHtmlAttributes) = ArrayHelper::get($this->registeredActionButtons,$name,array(null,null,null,null,array()));
		if($type===null)
			return false;
		if(!$config){
			$config = array();
		} elseif(is_callable($config)){
			$config = $config($item);
		}
		if(!$defaultConfig)
			$defaultConfig = array();
		$config = array_replace_recursive($defaultConfig, $config);

		if(ArrayHelper::get($config,"disabled")){
			return;
		}

		$htmlAttributes = array_replace_recursive($defaultHtmlAttributes, $htmlAttributes);
		if($title = ArrayHelper::get($config,"title")){
			$htmlAttributes["title"] = $title;
			$htmlAttributes["rel"] = "tooltip";
		}
		switch($type){
			case "file":
				Util::controller()->renderPartial($do,array(
					"item" => $item,
					"config" => $config,
					"htmlAttributes" => $htmlAttributes,
				));
				break;
			default: // function:
				$do($item,$config,$htmlAttributes);
				break;
		}
	}
}