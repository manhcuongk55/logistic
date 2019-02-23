<?php
class Permission {

	public $permissionConfigList = array();

	public function getPermissionConfig($configName){
		return ArrayHelper::get($this->permissionConfigList,$configName,function() use($configName){
			$arr = Util::param2($configName);
			$i = 0;
			foreach($arr as $key => $item){
				$arr[$key][] = $i++;
			}
			return $arr;
		},true);
	}

	public function can($permissionName,$permissionConfigName,$permissionValue){
		$config = $this->getPermissionConfig($permissionConfigName);
		$index = $config[$permissionName][1];
		return $permissionValue & pow(2,$index);
	}
}