<?php
class SModelHelper {
	var $modelDynamicProperties = array();

	public function addProperty($className,$property){
		ArrayHelper::get($this->modelDynamicProperties,$className,array(),true);
		$this->modelDynamicProperties[$className][$property] = true;
	}

	public function hasProperty($className,$property){
		$classProperties = ArrayHelper::get($this->modelDynamicProperties,$className);
		if(!$classProperties)
			return false;
		return ArrayHelper::get($classProperties,$property);
	}
}