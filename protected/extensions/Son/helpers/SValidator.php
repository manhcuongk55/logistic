<?php
class SValidator extends CModel {
    private $_data = array();
    private $_props = array();
    private $_labels = array();
    private $_rules = array();

    public function setData($data){
    	$this->_data = $data;
    }

    public function setProps($props){
    	$this->_props = $props;
    }

    public function setRules($rules){
    	$this->_rules = $rules;
    }

    public function setLabels($labels){
    	$this->_labels = $labels;
    }

	public function __get($name) {
        return in_array($name, $this->_props) ? $this->_data[$name] : parent::__get($name);
    }

    public function __set($name,$value) {
        if(in_array($name, $this->_props)){
        	$this->_data[$name] = $value;
        } else {
        	parent::__set($name,$value);
        }
    }

	// override functions

    public function rules(){
    	return $this->_rules;
    }

    public function attributeLabels(){
    	if($this->_labels==null)
    		return parent::attributeLabels();
    	return $this->_labels;
    }

    public function attributeNames(){
    	return $this->_props;
    }

	/**
		* @return list($inputValue,$hasError,$errorMessage,$errors)
	*/
	public static function validateArray($arr,$props,$rules,$labels=null){
		$dummy = new SValidator();
		$dummy->setData($arr);
		$dummy->setProps($props);
		$dummy->setRules($rules);
		if($labels!=null){
			$dummy->setLabels($labels);
		}
		$dummy->validate();
		$firstErrorMessage = null;
		$hasError = $dummy->hasErrors();
		if($hasError){
			$firstErrorMessage = ActiveRecordHelper::getFirstError($dummy);
		}
		return array($dummy->getAttributes(),$hasError,$firstErrorMessage,$dummy->getErrors());
	}

	public static function validateSingle($name,$value,$rules,$labels=null){
		$arrData = array();
		$arrProps = array();
		$arrRules = array();
		$arrLabels = null;
		//
		$arrData[$name] = $value;
		$arrProps[] = $name;
		foreach($rules as $rule){
			array_unshift($rule,$name);
			/*if(!isset($rule["allowEmpty"]))
				$rule["allowEmpty"] = false;*/
			$arrRules[] = $rule;
		}
		if($labels!=null){
			$arrLabels = array();
			$arrLabels[$name] = $labels;
		}
		list($arrData,$hasError,$firstErrorMessage,$arrErrors) = self::validateArray($arrData,$arrProps,$arrRules,$arrLabels);
		$errors = null;
		if($hasError)
			$errors = $arrErrors[$name];
		return array($arrData[$name],$hasError,$firstErrorMessage,$errors);
	}
}