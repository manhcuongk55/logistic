<?php
class SWebUser extends CWebUser {
	protected $permissionConfig = "permissions";
	protected $models = array();
	protected $userDisabled = false;
	protected $modelClass = "User";

	protected function getPermission(){
		return $this->getState("permission");
	}

	public function can($permissionName) {
		return Son::load("Permission")->can($permissionName,$this->permissionConfig,$this->getPermission());
	}

	public function getModel($className,$criteria=array()){
		$id = $this->id;
		return ArrayHelper::get($this->models,$className,function() use($className,$id,$criteria){
			return $className::model()->findByPk($id,$criteria);
		},true);
	}

	public function disableUser(){
		$this->id = null;
		$this->userDisabled = true;
	}

	public function getIsGuest()
	{
	    if($this->userDisabled)
	    	return true;
	    return parent::getIsGuest();
	}

	public function getUser($criteria=array()){
		return $this->getModel($this->modelClass,$criteria);
	}

	public function getClass(){
		return $this->modelClass;
	}
}