<?php
class Input {
	/**
		* @param $defaultValueAndValidationRules Can be a single value for defaultValue case or an array with keys default, rules and labels
		* @param $errorWarningType Can be nothing, default, json, html, return
	*/
	static $postData = false;
	public static function getPostData(){
		if(self::$postData===false){
			$rawData = file_get_contents('php://input');
			if(!$rawData){
				$postData = $_POST;
			} else {
				parse_str($rawData, $postData);
			}
			self::$postData = $postData;
		}
		return self::$postData;
	}
	
	public static function getInput($arr,$name,$defaultValueAndValidationRules=null,$errorWarningType="nothing"){
		$hasInput = isset($arr[$name]);
		$defaultValue = null;
		$rules = null;
		$labels = null;
		if(!is_array($defaultValueAndValidationRules)){
			$defaultValue = $defaultValueAndValidationRules;
		} else {
			$defaultValue = ArrayHelper::get($defaultValueAndValidationRules,"default",null);
			$rules = ArrayHelper::get($defaultValueAndValidationRules,"rules",null);
			$labels = ArrayHelper::get($defaultValueAndValidationRules,"labels",null);
			if($labels===null)
				$labels = ArrayHelper::get($defaultValueAndValidationRules,"label",null);
		}
		if(!$hasInput && $defaultValue!==null){
			// no input => input = default value => always valid
			if($errorWarningType=="return"){
				return array($defaultValue,false,null,null);
			}
			return $defaultValue;
		}

		$value = ArrayHelper::get($arr,$name,$defaultValue);
		if($rules==null){
			if($errorWarningType=="return"){
				return array($value,null,null,null);
			} elseif($errorWarningType=="nothing"){
				return $value;
			}
		}
		if($rules){
			list($value,$hasError,$firstErrorMessage,$errors) = SValidator::validateSingle($name,$value,$rules,$labels);
		} else {
			$hasError = false;
			$firstErrorMessage = null;
			$errors = null;
		}
		if($errorWarningType=="return"){
			return array($value,$hasError,$firstErrorMessage,$errors);
		}
		if(!$hasError){
			return $value;
		} else {
			switch($errorWarningType){
				case "default":
					return $defaultValue;
				case "json":
					Output::returnJsonError($firstErrorMessage,400,null,$errors);
					return null;
				case "html":
					Output::showError($firstErrorMessage);
					return null;
				default:
					throw new Exception("Invalid Error warning type", 1);
					break;
			}
		}
		return null;
	}

	public static function getInputMultiple($arr,$dictNameWithDefaultValueAndValidationRulesArray,$errorWarningType="nothing",$multipleError=false){
		$globalHasError = false;
		$globalErrors = array();
		$globalFirstErrorMessage = null;
		foreach($dictNameWithDefaultValueAndValidationRulesArray as $key => $val){
			$name = null;
			$setting = null;
			if(is_numeric($key)){
				$name = $val;
				$setting = null;
			} else {
				$name = $key;
				$setting = $val;
			}
			if($errorWarningType=="nothing"){
				$arr[$name] = self::_getInput($arr,$name,$setting,"nothing");
			} else {
				list($value,$hasError,$firstErrorMessage,$errors) = self::_getInput($arr,$name,$setting,"return");
				$arr[$name] = $value;
				if($hasError){
					// when error => update global variable
					$globalHasError = true;
					if($globalFirstErrorMessage==null){
						$globalFirstErrorMessage = $firstErrorMessage;
					}
					$globalErrors[$name] = $errors;
					if(!$multipleError){
						break;
					}
				}
			}
		}
		if($errorWarningType=="return"){
			// if request return => return all error (multiple error mode)
			return array($arr,$globalHasError,$globalFirstErrorMessage,$globalErrors);
		}

		if(!$globalHasError){
			return array_values($arr);
		} else {
			switch($errorWarningType){
				case "default":
					return array_values($arr);
				case "json":
					Output::returnJsonError($globalFirstErrorMessage,400,null,$globalErrors);
					return null;
				case "html":
					Output::showError($globalFirstErrorMessage);
					return null;
				default:
					throw new Exception("Invalid Error warning type", 1);
					break;
			}
		}
	}

	public static function get($name,$defaultValueAndValidationRules=null,$errorWarningType="nothing"){
		return self::getInput($_GET,$name,$defaultValueAndValidationRules,$errorWarningType);
	}
	
	public static function post($name,$defaultValueAndValidationRules=null,$errorWarningType="nothing"){
		return self::getInput(self::getPostData(),$name,$defaultValueAndValidationRules,$errorWarningType);
	}

	public static function getMultiple($dictNameWithDefaultValueAndValidationRulesArray,$errorWarningType="nothing",$multipleError=false){
		return self::getInputMultiple($_GET,$dictNameWithDefaultValueAndValidationRulesArray,$errorWarningType,$multipleError);
	}

	public static function postMultiple($dictNameWithDefaultValueAndValidationRulesArray,$errorWarningType="nothing",$multipleError=false){
		return self::getInputMultiple($_POST,$dictNameWithDefaultValueAndValidationRulesArray,$errorWarningType,$multipleError);
	}
	
	public static function isAjax(){
		return Yii::app()->request->isAjaxRequest;
	}

	public static function isPost(){
		return count($_POST) > 0;
	}

	public static function getIpAddress(){
		$ip = null;
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		    $ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
		    $ip = $_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}
}