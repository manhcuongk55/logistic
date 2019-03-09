<?php
class SListItem {
	var $model = array();
	var $list = null;
	var $openTag;
	var $itemIndex;

	public function __get($key){
		if(!isset($this->$key))
			return $this->model->$key;
		return parent::__get($key);
	}

	public function __construct($model=null,$list=null){
		if($model!=null){
			$this->model = $model;
		}
		if($list!=null){
			$this->list = $list;
		}
	}

	public function attr($attr){
		return $this->model->$attr;
	}

	public function primaryValue(){
		$primaryField = $this->list->getModel()->config["primaryField"];
		return $this->model->$primaryField;
	}

	public function renderAttr($attrName,$htmlAttrs=array()){
		$field = $this->list->config["fields"][$attrName];
		if(!$field["inlineEditEnabled"]){
			$this->renderDisplay($attrName,$htmlAttrs);
		} else {
			$htmlAttrs["inline-edit-attr"] = $attrName;
			switch ($field["inlineEditEnabled"]) {
				case "double_click":
					$htmlAttrsDisplay = $htmlAttrs;
					$htmlAttrsInput = $htmlAttrs;
					//
					$itemId = $this->id;
					$id = "$attrName-$itemId";
					$htmlAttrsDisplay["inline-edit"] = "";
					$htmlAttrsDisplay["data-inline-edit-type"] =  $field["inlineEditEnabled"];
					$htmlAttrsDisplay["inline-edit-id"] = $id;
					$this->renderDisplay($attrName,$htmlAttrsDisplay);
					//
					$htmlAttrsInput["hidden"] = "hidden";
					$htmlAttrsInput["inline-edit-input"] = "";
					$htmlAttrsInput["inline-edit-display-id"] = $id;
					$this->renderInput($attrName,$htmlAttrsInput);
					break;
				default: // show directly
					$htmlAttrs["inline-edit"] = "";
					$htmlAttrs["data-inline-edit-type"] =  $field["inlineEditEnabled"];
					$this->renderInput($attrName,$htmlAttrs);
					break;
			}
		}
	}

	public function renderDisplay($attrName,$htmlAttrs=array()){
		$field = $this->list->config["fields"][$attrName];
		$value = $this->model->$attrName;
		$type = ArrayHelper::get($field,"displayType");
		$config =  ArrayHelper::get($field,"displayConfig");
		$defaultHtmlAttrs = ArrayHelper::get($field,"displayHtmlAttributes",null);
		if($defaultHtmlAttrs!=null){
			$htmlAttrs = array_replace_recursive($defaultHtmlAttrs, $htmlAttrs);
		}
		if(is_callable($config))
			$config = $config($this->model);
		Son::load("SPlugin")->renderDisplay($value,$type,$config,$htmlAttrs);
	}

	public function renderInput($attrName,$htmlAttrs=array()){
		$field = $this->list->config["fields"][$attrName];
		$value = $this->model->$attrName;
		$type = ArrayHelper::get($field,"inputType");
		$config =  ArrayHelper::get($field,"inputConfig");
		if(is_callable($config))
			$config = $config($this->model);
		Son::load("SPlugin")->renderInput($attrName,$value,$type,$config,$htmlAttrs);
	}

	public function renderCheckbox($htmlAttrs=array()){
		$htmlAttrs["value"] = $this->primaryValue();
		$htmlAttrs["list-selected-items"] = "";
		echo CHtml::checkBox("ids",false,$htmlAttrs);
	}

	public function renderBegin($tag="div",$htmlAttrs=array()){
		$this->openTag = $tag;
		$htmlAttrs["list-item"] = $this->primaryValue();
		$htmlAttrs["item-data"] = "";
		foreach($this->list->config["fields"] as $fieldName => $field){
			$value = $this->model->$fieldName;
			//$value = htmlspecialchars(str_replace(array("\r\n","\n","\r","\n\r")," ",$value));
			$htmlAttrs["data-$fieldName"] = $value;
		}
		echo CHtml::openTag($tag,$htmlAttrs);
		if($itemSelectable = $this->list->getHtml()->config["itemSelectable"]){
			if($itemSelectable["type"]!="checkbox"){
				$this->renderCheckbox(array(
					"style" => "display: none"
				));
			}
		}
	}

	public function renderEnd(){
		echo CHtml::closeTag($this->openTag);
	}

	public function renderExtendedActionButton($content,$action,$config,$htmlAttributes=array()){
		$htmlAttributes["item-extended-action"] = $action;
		if($message = ArrayHelper::get($config,"message")){
			$htmlAttributes["item-message"] = $message;
		}
		$actionFunction = $this->list->config["actions"]["extendedAction"][$action];
		if(!is_callable($actionFunction)){
			$form = $this->list->getModel()->getExtendedActionForm($action,$actionFunction);
			$form->render();
			$htmlAttributes["item-form-modal"] = "#" . $form->viewParam("modalId");
		}
		echo CHtml::htmlButton($content,$htmlAttributes);
	}
}