<?php
class SListHelper {
	var $advancedSearchTypes = array();

	public function advancedSearchRegisterType($name,$type,$do,$compareFunction=null,$defaultConfig=array(),$defaultHtmlAttributes=array()){
		if($compareFunction==null){
			$compareFunction = "match";
		}
		$this->advancedSearchTypes[$name] = array($type,$do,$compareFunction,$defaultConfig,$defaultHtmlAttributes);
	}

	public function advancedSearchDisplayInput($name,$inputName,$inputValue="",$htmlAttributes=array(),$inputConfig=null){
		list($type,$do,$compareFunction,$defaultConfig,$defaultHtmlAttributes) = ArrayHelper::get($this->advancedSearchTypes,$name,array(null,null,null,null,array()));
		if($type===null)
			return false;
		if(!$inputConfig)
			$inputConfig = array();
		if(!$defaultConfig)
			$defaultConfig = array();
		$inputConfig = array_replace_recursive($defaultConfig, $inputConfig);
		$htmlAttributes = array_replace_recursive($defaultHtmlAttributes, $htmlAttributes);
		//var_dump($inputConfig);
		$this->advancedSearchRenderHtmlAttributesFromConfig($inputConfig,$htmlAttributes);
		Son::load("SPlugin")->renderUnregisteredInput($inputName,$inputValue,$type,$do,$inputConfig,$htmlAttributes,$inputConfig);
		return true;
	}

	public function advancedSearchRenderHtmlAttributesFromConfig($config,&$htmlAttributes){
		if($triggerType=ArrayHelper::get($config,"triggerType","enter")){
			$htmlAttributes["list-input-advanced-search-onchange"] = $triggerType;
		}
	}

	public function advancedSearchCompare($criteria,$type,$name,$value,$options=null){
		list($type,$do,$compareFunction) = ArrayHelper::get($this->advancedSearchTypes,$type,array(null,null,"match"));
		if(is_string($compareFunction)){
			$this->advancedSearchDefaultFunction($compareFunction,$criteria,$name,$value,$options);
		} else {
			$compareFunction($criteria,$name,$value,$options=null);
		}
	}

	private function advancedSearchDefaultFunction($functionName,$criteria,$name,$value,$options=null){
		switch($functionName){
			case "match_total":
				$criteria->compare($name,$value,false);
				break;
			case "match_partial":
				$criteria->compare($name,$value,true);
				break;
			case "greater":
				//if($value==="" || $value===null)
					//return;
				$col = md5(str_replace(".", "_", $name));
				$criteria->addCondition("$name >= :" . $col . "_val");
				$criteria->params[":" . $col . "_val"] = $value;
				break;
			case "less":
				//if($value==="" || $value===null)
					//return;
				$col = md5(str_replace(".", "_", $name));
				$criteria->addCondition("$name <= :" . $col . "_val");
				$criteria->params[":" . $col . "_val"] = $value;
				break;
			case "range":
				$col = md5(str_replace(".", "_", $name));
				if(isset($value[0])){
					$criteria->addCondition("$name >= :" . $col . "_from");
					$criteria->params[":" . $col . "_from"] = $value[0];
				}
				if(isset($value[1])){
					$criteria->addCondition("$name <= :" . $col . "_to");
					$criteria->params[":" . $col . "_to"] = $value[1];
				}
				break;
			default: // match total
				$matchTotal = ArrayHelper::get($options,"match_total",true);
				$criteria->compare($name,$value,!$matchTotal);
				break;
		}
	}

}