<?php
class SPlugin {

	var $inputPlugins = array();
	var $displayPlugins = array();

	public function renderUnregisteredInput($inputName,$inputValue,$type,$do,$inputConfig=array(),$htmlAttributes=array(),$defaultConfig=null,$defaultHtmlAttributes=array()){
		if($defaultConfig!==null){
			if($inputConfig!==null){
				$inputConfig = array_replace_recursive($defaultConfig, $inputConfig);
			} else {
				$inputConfig = $defaultConfig;
			}
		}
		$htmlAttributes = array_replace_recursive($defaultHtmlAttributes, $htmlAttributes);
		if(ArrayHelper::get($inputConfig,"multiple")){
			$inputName .= "[]";
		}
		switch($type){
			case "load_file":
				Util::controller()->renderPartial($do,array(
					"name" => $inputName,
					"value" => $inputValue,
					"config" => $inputConfig,
					"htmlAttributes" => $htmlAttributes,
				));
				break;
			case "function":
				$do($inputName,$inputValue,$inputConfig,$htmlAttributes);
				break;
			case "html_attr":
				$htmlAttributes = array_replace_recursive($htmlAttributes, $do);
				$inputType = ArrayHelper::get($inputConfig,"type","text");
				if($inputConfig){
					foreach($inputConfig as $key => $value){
						if(is_string($value) || is_numeric($value))
							$htmlAttributes["data-$key"] = $value;
					}
				}
				$this->renderDefaultInput($inputName,$inputValue,$inputType,$inputConfig,$htmlAttributes);
				break;
			default:
				$this->renderDefaultInput($inputName,$inputValue,$type,$inputConfig,$htmlAttributes);
				break;
		}
	}

	public function renderInput($inputName,$inputValue="",$inputType=null,$inputConfig=null,$htmlAttributes=array()){
		if($inputType==null){
			$inputType = "text";
		}
		list($type,$do,$defaultConfig,$extensionDependency,$defaultHtmlAttributes) = ArrayHelper::get($this->inputPlugins,$inputType,array($inputType,null,null,null,array()));
		$this->renderUnregisteredInput($inputName,$inputValue,$type,$do,$inputConfig,$htmlAttributes,$defaultConfig,$defaultHtmlAttributes);
		if($extensionDependency!==null){
			foreach($extensionDependency as $extensionName){
				Son::load("SAsset")->addExtension($extensionName);
			}
		}
	}

	public function renderDefaultInput($name,$value,$type,$config,$htmlAttributes){
		switch($type){
			case "textarea":
				echo CHtml::textArea($name,$value,$htmlAttributes);
				break;
			case "checkbox":
				echo CHtml::checkBox($name,$value,$htmlAttributes);
				break;
			case "checkboxlist":
				$data = $config["data"];
				if(is_callable($data))
					$data = $data();
				echo CHtml::checkBoxList($name,$value,$data,$htmlAttributes);
				break;
			case "radio":
				echo CHtml::radioButton($name,$value==$htmlAttributes["value"],$htmlAttributes);
				break;
			case "radioList":
				$data = $config["data"];
				if(is_callable($data))
					$data = $data();
				echo CHtml::radioButtonList($name,$value,$data,$htmlAttributes);
				break;
			case "select":
				$data = $config["data"];
				if(is_callable($data))
					$data = $data();
				echo CHtml::dropDownList($name,$value,$data,$htmlAttributes);
				break;
			case "file":
				echo CHtml::fileField($name,$value,$htmlAttributes);
				break;
			case "password":
				echo CHtml::passwordField($name,$value,$htmlAttributes);
				break;
			case "hidden":
					echo CHtml::hiddenField($name,$value,$htmlAttributes);
					break;
			case "number":
					echo CHtml::numberField($name,$value,$htmlAttributes);
					break;
			default: // text
				echo CHtml::textField($name,$value,$htmlAttributes);
				break;
		}
	}

	public function renderDisplay($displayValue,$displayType=null,$displayConfig=null,$htmlAttributes=array()){
		if($displayType==null){
			$displayType = "text";
		}
		list($type,$do,$defaultConfig,$extensionDependency,$defaultHtmlAttributes) = ArrayHelper::get($this->displayPlugins,$displayType,array($displayType,null,null,null,array()));
		if($defaultConfig!==null){
			if($displayConfig!==null){
				$displayConfig = array_replace_recursive($defaultConfig, $displayConfig);
			} else {
				$displayConfig = $defaultConfig;
			}
		}
		$htmlAttributes = array_replace_recursive($defaultHtmlAttributes, $htmlAttributes);
		switch($type){
			case "file":
				Util::controller()->renderPartial($do,array(
					"value" => $displayValue,
					"config" => $displayConfig,
					"htmlAttributes" => $htmlAttributes,
				));
				break;
			case "function":
				$do($displayValue,$displayConfig,$htmlAttributes);
				break;
			case "html_attr":
				$htmlAttributes = array_replace_recursive($htmlAttributes, $do);
				$tag = ArrayHelper::get($displayConfig,"tag","div");
				$willIncludeContent = ArrayHelper::get($displayConfig,"willIncludeContent",true);
				$content = "";
				if($willIncludeContent)
					$content = $displayValue;
				echo CHtml::tag($tag,$htmlAttributes,$title["text"],$content);
				break;
			default:
				$this->renderDefaultDisplay($displayValue,$displayType,$displayConfig,$htmlAttributes);
				break;
		}
		if($extensionDependency!==null){
			foreach($extensionDependency as $extensionName){
				Son::load("SAsset")->addExtension($extensionName);
			}
		}
	}

	public function renderDefaultDisplay($displayValue,$displayType,$displayConfig,$htmlAttributes=array()){
		switch($displayType){
			case "image":
				echo CHtml::image($displayValue,ArrayHelper::get($displayConfig,"alt",""),$htmlAttributes);
				break;
			case "raw":
				echo $displayValue;
				break;
			default: // span
				echo CHtml::tag("span",$htmlAttributes,$displayValue);
				break;
		}
	}

	/**
		* @param $do = string, $do is file path
		* @param $do = function, $do is the function
		* @param $do = array, $do is array key value of the attr
		* @param $do is null, $do is default
	*/
	public function registerInputPlugin($name,$type,$do,$defaultConfig=null,$extensionDependency=null,$defaultHtmlAttributes=array()){
		$this->inputPlugins[$name] = array($type,$do,$defaultConfig,$extensionDependency,$defaultHtmlAttributes);
	}

	public function registerDisplayPlugin($name,$type,$do,$defaultConfig=null,$extensionDependency=null,$defaultHtmlAttributes=array()){
		$this->displayPlugins[$name] = array($type,$do,$defaultConfig,$extensionDependency,$defaultHtmlAttributes);
	}

}