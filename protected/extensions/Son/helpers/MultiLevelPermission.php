<?php
class MultiLevelPermission {
	private $_userLevelHierachier = array();
	private $_userLevelHierachierReverse = array();
	var $_permissions = array();

	/**
		* $_userLevelHierachierReverse is an array like array("user" => "mod", "mod" => "admin")
	*/
	private function calculateUserLevelHierachierReverse(){
		foreach($this->_userLevelHierachier as $userType => $children){
			foreach($children as $childrenUserType){
				$this->_userLevelHierachierReverse[$childrenUserType] = $userType;
			}
		}
	}

	/**
		* @param $config is an array like array( "admin" =>  array("mod"), "mod" => array("user") )
	*/
	public function setUserLevelHierachier($userLevelHierachier){
		$this->_userLevelHierachier = $userLevelHierachier;
		$this->calculateUserLevelHierachierReverse();
	}

	public function setPermission($userType,$permissionName,$canApplyForUpperLevel=true){
		$this->_permissions[$permissionName] = array();
		$this->_permissions[$permissionName][$userType] = true;
		if($canApplyForUpperLevel){
			$currentUserType = $userType;
			while(true){
				if(!isset($this->_userLevelHierachierReverse[$currentUserType])){
					// no more parent
					break;
				}
				$currentUserType = $this->_userLevelHierachierReverse[$currentUserType];
				$this->_permissions[$permissionName][$currentUserType] = true;
			}
		}
	}

	public function can($userType,$permissionName,$default=true){
		if(!isset($this->_permissions[$permissionName])){
			return $default;
		} else {
			$permissionSetting = $this->_permissions[$permissionName];
			return isset($permissionSetting[$userType]) && $permissionSetting[$userType];
		}
	}
}